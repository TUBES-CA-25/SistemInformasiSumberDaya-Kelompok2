<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * JadwalPraktikumService - Logika Bisnis Jadwal Praktikum
 * 
 * Menangani proses kompleks seperti:
 * - Parsing file Excel/CSV dengan Smart Column Auto-Detection (bebas template & acak kolom).
 * - Pencarian ID Asisten berdasarkan nama (Smart Search).
 * - Sinkronisasi data master (Mata Kuliah & Lab fleksibel).
 * - Validasi bentrok/duplikasi jadwal.
 */
class JadwalPraktikumService {
    private $model;

    /**
     * Pola regex / sinonim untuk mendeteksi nama kolom secara otomatis.
     */
    private static $fieldPatterns = [
        'kode_mk' => [
            '/^(kode|kodemk|kodematakuliah|kodematkul|kdmk|kd|coursecode|code)$/i',
            '/^(kode.*mk|kode.*mat.*kuliah)$/i',
        ],
        'mk' => [
            '/^(matakuliah|matkul|namamatakuliah|namamk|mk|namamatkul|course|coursename|subject|mataajar|mataajaran)$/i',
            '/^(nama.*mata.*kuliah|nama.*mk)$/i',
        ],
        'dosen' => [
            '/^(dosen|namadosen|dosenpengampu|pengampu|pengajar|lecturer|instruktur)$/i',
            '/^(dosen.*pengampu|nama.*dosen)$/i',
        ],
        'sks' => [
            '/^(sks|bobotsks|jmlsks|credits|skskuliah|sksmk|jumlahsks)$/i',
        ],
        'kelas' => [
            '/^(kelas|kls|class|kelompok|group|rombel)$/i',
        ],
        'freq' => [
            '/^(freq|frekuensi|frekwensi|minggu|pertemuan)$/i',
        ],
        'lab' => [
            '/^(laboratorium|lab|ruang|ruangan|tempat|room|lokasi|namalab|ruanglab)$/i',
            '/^(ruang.*lab|nama.*lab)$/i',
        ],
        'hari' => [
            '/^(hari|day)$/i',
        ],
        'jam_mulai' => [
            '/^(jammulai|waktumulai|mulai|start|starttime|darijam)$/i',
        ],
        'jam_selesai' => [
            '/^(jamselesai|waktuselesai|selesai|end|endtime|sampaijam)$/i',
        ],
        'jam' => [
            '/^(jam|waktu|pukul|time|jampraktikum|waktupraktikum|jamkuliah|sesi|jadwal)$/i',
        ],
        'asisten1' => [
            '/^(nama)?(asisten|asprak|ast).*1/i',
            '/^(nama)?(asisten|asprak|ast).*i(?![0-9a-z])/i',
            '/^(nama)?(asisten|asprak|ast).*pertama/i',
            '/^(nama)?koordinatorasisten/i',
            '/^ca(?![0-9a-z])/i',
        ],
        'asisten2' => [
            '/^(nama)?(asisten|asprak|ast).*2/i',
            '/^(nama)?(asisten|asprak|ast).*ii(?![0-9a-z])/i',
            '/^(nama)?(asisten|asprak|ast).*kedua/i',
            '/^(coasisten|asistenpendamping)/i',
        ],
        'asisten_single' => [
            '/^(asisten|asprak|asistenpraktikum|namaasisten)$/i',
        ],
        'prodi' => [
            '/^(prodi|programstudi|jurusan|departemen)$/i',
        ]
    ];

    public function __construct() {
        $this->model = new JadwalPraktikumModel();
    }

    /**
     * Memproses file Excel/CSV dan mengimpor data ke database.
     * Menggunakan Smart Column Auto-Detection untuk mendeteksi kolom secara otomatis
     * tanpa mewajibkan urutan tertentu atau kesesuaian template yang kaku.
     * 
     * @param string $filePath Path file sementara Excel/CSV.
     * @return array Ringkasan hasil import (success, duplicate, invalid, errors).
     * @throws Exception Jika file rusak atau kolom utama tidak dapat dikenali.
     */
    public function importFromExcel($filePath) {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        
        $stats = [
            'success'   => 0,
            'duplicate' => 0,
            'invalid'   => 0,
            'errors'    => []
        ];

        // Deteksi Header & Pemetaan Kolom secara otomatis
        $detection = $this->detectHeaders($worksheet);
        $colMap = $detection['columnMap'];
        $startRow = $detection['firstDataRowIndex'];

        // Validasi kolom krusial: minimal kolom Mata Kuliah harus terdeteksi
        if (!isset($colMap['mk']) && !isset($colMap['kode_mk'])) {
            throw new Exception("Kolom Mata Kuliah tidak dapat dideteksi dalam file. Pastikan file memuat kolom untuk Mata Kuliah.");
        }

        $highestRow = $worksheet->getHighestRow();

        for ($rowNum = $startRow; $rowNum <= $highestRow; $rowNum++) {
            $row = $worksheet->getRowIterator($rowNum, $rowNum)->current();
            if (!$row) continue;

            $rowData = $this->extractRowData($row);
            // Lewati jika seluruh baris kosong
            if (empty(array_filter($rowData, fn($v) => trim((string)$v) !== ''))) {
                continue;
            }

            // Ambil data berdasarkan mapping kolom yang terdeteksi
            $getVal = function($field, $default = '') use ($colMap, $rowData) {
                if (!isset($colMap[$field])) return $default;
                $idx = $colMap[$field];
                return isset($rowData[$idx]) ? trim((string)$rowData[$idx]) : $default;
            };

            $mk    = $getVal('mk');
            $kodeMK = $getVal('kode_mk');
            $lab   = $getVal('lab');
            $hari  = ucfirst(strtolower($getVal('hari')));
            $dosen = $getVal('dosen');
            $kelas = strtoupper($getVal('kelas'));
            $sks   = !empty($getVal('sks')) ? (int)$getVal('sks') : null;
            $rawFreq = $getVal('freq', '');
            $freq  = (!empty($rawFreq) && trim((string)$rawFreq) !== '-' && trim((string)$rawFreq) !== '') ? trim((string)$rawFreq) : null;

            // Abaikan baris ringkasan/total jika ada
            if (preg_match('/^(total|jumlah|keterangan|catatan)$/i', $mk)) {
                continue;
            }

            // Jika nama MK kosong tapi kode ada, gunakan kode sebagai nama cadangan
            if (empty($mk) && !empty($kodeMK)) {
                $mk = $kodeMK;
            }

            // Lewati baris jika data dasar tidak ada
            if (empty($mk) && empty($lab) && empty($hari)) {
                continue;
            }

            // Parsing Waktu (Mendukung rentang waktu maupun kolom jam_mulai & jam_selesai terpisah)
            if (isset($colMap['jam_mulai']) && isset($colMap['jam_selesai'])) {
                $start = $this->normalizeTimeStr($getVal('jam_mulai', '00:00'));
                $end   = $this->normalizeTimeStr($getVal('jam_selesai', $start));
            } else {
                $jamRaw = $getVal('jam', '00:00');
                [$start, $end] = $this->parseTimeRange($jamRaw);
            }

            // Parsing Asisten (Mendukung asisten 1 & 2 terpisah, atau 1 kolom gabungan)
            $asisten1 = $getVal('asisten1');
            $asisten2 = $getVal('asisten2');
            if (empty($asisten1) && empty($asisten2) && isset($colMap['asisten_single'])) {
                $single = $getVal('asisten_single');
                $parts = preg_split('/\s*(?:,|\/|&|\bdan\b)\s*/i', $single);
                $asisten1 = trim($parts[0] ?? '');
                $asisten2 = trim($parts[1] ?? '');
            } elseif (!empty($asisten1) && empty($asisten2)) {
                $parts = preg_split('/\s*(?:,|\/|&|\bdan\b)\s*/i', $asisten1);
                if (count($parts) > 1) {
                    $asisten1 = trim($parts[0]);
                    $asisten2 = trim($parts[1]);
                }
            }

            // Resolve Foreign Keys & Prodi
            $idMK  = $this->findOrCreateMatakuliah($kodeMK, $mk, $sks);
            $idLab = $this->findExistingLab($lab);
            $prodi = $this->normalizeProdi($getVal('prodi'), $kodeMK, $mk);

            if (!$idLab) {
                $stats['invalid']++;
                $labNameDisplay = !empty($lab) ? "'$lab'" : "kosong";
                $stats['errors'][] = "Baris $rowNum: Laboratorium $labNameDisplay tidak dikenal di sistem.";
                continue;
            }

            // Cek Duplikasi Jadwal (Hanya bentrok jika MK, kelas, hari, jam, dan lab persis sama)
            if ($this->model->checkDuplicate($idMK, $kelas, $hari, $start, $end, $idLab)) {
                $stats['duplicate']++;
                continue;
            }

            // Simpan Jadwal Praktikum
            $this->model->insert([
                'idMatakuliah'   => $idMK,
                'prodi'          => $prodi,
                'kelas'          => $kelas,
                'idLaboratorium' => $idLab,
                'hari'           => $hari,
                'waktuMulai'     => $start,
                'waktuSelesai'   => $end,
                'idDosen'        => $this->findOrCreateDosen($dosen),
                'asisten1'       => $this->findAsistenIdByName($asisten1),
                'asisten2'       => $this->findAsistenIdByName($asisten2),
                'frekuensi'      => $freq,
                'status'         => 'Aktif'
            ]);
            $stats['success']++;
        }

        return $stats;
    }

    /**
     * Memindai baris teratas (1 - 10) untuk menemukan baris header tabel dan memetakan kolom.
     * 
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet
     * @param int $maxScanRows
     * @return array
     */
    private function detectHeaders($worksheet, int $maxScanRows = 10): array {
        $bestRow = null;
        $bestScore = 0;
        $bestMapping = [];

        $highestRow = min($worksheet->getHighestRow(), $maxScanRows);

        for ($rowIdx = 1; $rowIdx <= $highestRow; $rowIdx++) {
            $row = $worksheet->getRowIterator($rowIdx, $rowIdx)->current();
            if (!$row) continue;

            $cellIt = $row->getCellIterator();
            $cellIt->setIterateOnlyExistingCells(false);

            $headers = [];
            foreach ($cellIt as $cell) {
                $headers[] = $cell->getFormattedValue();
            }

            // Hitung skor kecocokan header
            $mapping = $this->mapColumns($headers);
            $score = count($mapping);

            // Berikan bobot lebih untuk kolom-kolom utama
            if (isset($mapping['mk'])) $score += 3;
            if (isset($mapping['lab'])) $score += 3;
            if (isset($mapping['hari'])) $score += 2;
            if (isset($mapping['jam']) || (isset($mapping['jam_mulai']) && isset($mapping['jam_selesai']))) $score += 2;

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestRow = $rowIdx;
                $bestMapping = $mapping;
            }
        }

        // Jalankan Smart Content Inspection untuk memvalidasi atau melengkapi pemetaan kolom dari sampel data nyata
        $firstDataRow = ($bestRow !== null) ? $bestRow + 1 : 2;
        $contentMap = $this->detectColumnsByContent($worksheet, $firstDataRow);

        // Gabungkan hasil deteksi header dengan deteksi berbasis isi sel
        if (empty($bestMapping) || $bestScore < 3) {
            $finalMapping = !empty($contentMap) ? $contentMap : [
                'kode_mk'  => 1,
                'dosen'    => 2,
                'mk'       => 3,
                'sks'      => 4,
                'kelas'    => 5,
                'freq'     => 6,
                'lab'      => 7,
                'hari'     => 8,
                'jam'      => 9,
                'prodi'    => 10,
                'asisten1' => 11,
                'asisten2' => 12
            ];
            $isFallback = empty($contentMap);
        } else {
            $finalMapping = $this->reconcileMappingWithContent($bestMapping, $contentMap);
            $isFallback = false;
        }

        return [
            'headerRowIndex'    => $bestRow ?? 1,
            'firstDataRowIndex' => $firstDataRow,
            'columnMap'         => $finalMapping,
            'isFallback'        => $isFallback
        ];
    }

    /**
     * Memetakan teks header ke identifier field sistem.
     * 
     * @param array $headers
     * @return array
     */
    private function mapColumns(array $headers): array {
        $mapping = [];
        $usedCols = [];

        // Urutan prioritas agar field spesifik terpetakan lebih dulu
        $priorityOrder = [
            'kode_mk',
            'jam_mulai',
            'jam_selesai',
            'asisten1',
            'asisten2',
            'mk',
            'lab',
            'dosen',
            'hari',
            'jam',
            'kelas',
            'sks',
            'freq',
            'asisten_single',
            'prodi'
        ];

        foreach ($priorityOrder as $field) {
            $patterns = self::$fieldPatterns[$field];

            foreach ($headers as $colIdx => $rawHeader) {
                if (in_array($colIdx, $usedCols)) continue;
                if (empty(trim((string)$rawHeader))) continue;

                $normalized = $this->normalizeHeader($rawHeader);

                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $normalized) || preg_match($pattern, trim((string)$rawHeader))) {
                        $mapping[$field] = $colIdx;
                        $usedCols[] = $colIdx;
                        break 2;
                    }
                }
            }
        }

        return $mapping;
    }

    /**
     * Deteksi kolom pintar berdasarkan isi data aktual sel (Heuristic Content Inspection).
     * Memeriksa sampel baris data nyata untuk memastikan kolom hari tidak tertukar dengan kode MK,
     * kolom jam tidak tertukar dengan dosen, dan kolom dosen tidak tertukar dengan mata kuliah.
     */
    private function detectColumnsByContent($worksheet, int $startRow = 2, int $sampleLimit = 15): array {
        $highestRow = min($worksheet->getHighestRow(), $startRow + $sampleLimit);
        if ($startRow > $highestRow) return [];

        $colSamples = [];
        for ($r = $startRow; $r <= $highestRow; $r++) {
            $row = $worksheet->getRowIterator($r, $r)->current();
            if (!$row) continue;
            $data = $this->extractRowData($row);
            foreach ($data as $colIdx => $val) {
                $valStr = trim((string)$val);
                if ($valStr !== '') {
                    $colSamples[$colIdx][] = $valStr;
                }
            }
        }

        if (empty($colSamples)) return [];

        $scores = [];
        $dayNames = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

        foreach ($colSamples as $colIdx => $values) {
            $total = count($values);
            if ($total === 0) continue;

            $dayCount = 0;
            $timeCount = 0;
            $dosenCount = 0;
            $labCount = 0;
            $classCount = 0;
            $sksCount = 0;
            $courseLikeCount = 0;

            foreach ($values as $v) {
                $vLower = strtolower($v);

                // Cek Hari
                if (in_array($vLower, $dayNames)) $dayCount++;

                // Cek Jam/Waktu
                if (preg_match('/^\d{1,2}[:.]\d{2}/', $v) || preg_match('/\d{1,2}[:.]\d{2}\s*(?:-|–|s\/d)\s*\d{1,2}[:.]\d{2}/i', $v)) {
                    $timeCount++;
                }

                // Cek Dosen (gelar akademik atau nama bergelar)
                if (preg_match('/,\s*(S\.Kom|M\.Kom|M\.T|S\.T|M\.Cs|MTA|M\.Si|M\.Eng|Dr\.|Ir\.|Prof\.)/i', $v) ||
                    preg_match('/^(Dr\.|Ir\.|Prof\.)\s+/i', $v)) {
                    $dosenCount++;
                }

                // Cek Lab
                if (preg_match('/^(lab|laboratorium|ruang)\s+/i', $v) || stripos($v, 'laboratorium') !== false) {
                    $labCount++;
                }

                // Cek Kelas (misal A1, B2, C, TI-A)
                if (preg_match('/^[A-Z][0-9]?$/i', $v) || preg_match('/^[A-Z]{2,4}-[A-Z0-9]$/i', $v)) {
                    $classCount++;
                }

                // Cek SKS (angka 1-6 atau "3 SKS")
                if (preg_match('/^[1-6](\s*sks)?$/i', $v)) {
                    $sksCount++;
                }

                // Cek kemiripan MK
                if (!$this->isInvalidCourseName($v) && strlen($v) >= 4) {
                    $courseLikeCount++;
                }
            }

            if ($dayCount / $total >= 0.4) $scores['hari'][$colIdx] = $dayCount / $total;
            if ($timeCount / $total >= 0.4) $scores['jam'][$colIdx] = $timeCount / $total;
            if ($dosenCount / $total >= 0.3) $scores['dosen'][$colIdx] = $dosenCount / $total;
            if ($labCount / $total >= 0.3) $scores['lab'][$colIdx] = $labCount / $total;
            if ($classCount / $total >= 0.4) $scores['kelas'][$colIdx] = $classCount / $total;
            if ($sksCount / $total >= 0.5) $scores['sks'][$colIdx] = $sksCount / $total;
            if ($courseLikeCount / $total >= 0.4 && $dosenCount === 0 && $dayCount === 0) {
                $scores['mk'][$colIdx] = $courseLikeCount / $total;
            }
        }

        $contentMap = [];
        $assignedCols = [];

        // Tetapkan kolom dengan prioritas paling spesifik
        $order = ['hari', 'jam', 'dosen', 'lab', 'kelas', 'sks', 'mk'];
        foreach ($order as $field) {
            if (isset($scores[$field])) {
                arsort($scores[$field]);
                foreach ($scores[$field] as $colIdx => $sc) {
                    if (!in_array($colIdx, $assignedCols)) {
                        $contentMap[$field] = $colIdx;
                        $assignedCols[] = $colIdx;
                        break;
                    }
                }
            }
        }

        return $contentMap;
    }

    /**
     * Rekonsiliasi antara pemetaan header dan pemetaan data nyata.
     * Mencegah kolom Hari/Jam tertukar menjadi Kode MK atau Dosen.
     */
    private function reconcileMappingWithContent(array $headerMap, array $contentMap): array {
        $final = $headerMap;

        // 1. Jika kolom yang dipetakan sebagai kode_mk atau mk ternyata isinya Hari, koreksi!
        if (isset($contentMap['hari'])) {
            $hariCol = $contentMap['hari'];
            if (isset($final['kode_mk']) && $final['kode_mk'] === $hariCol) unset($final['kode_mk']);
            if (isset($final['mk']) && $final['mk'] === $hariCol) unset($final['mk']);
            $final['hari'] = $hariCol;
        }

        // 2. Jika kolom yang dipetakan sebagai dosen ternyata isinya Jam/Waktu, koreksi!
        if (isset($contentMap['jam'])) {
            $jamCol = $contentMap['jam'];
            if (isset($final['dosen']) && $final['dosen'] === $jamCol) unset($final['dosen']);
            if (isset($final['mk']) && $final['mk'] === $jamCol) unset($final['mk']);
            $final['jam'] = $jamCol;
        }

        // 3. Jika kolom yang dipetakan sebagai mk ternyata isinya Dosen, pindahkan ke dosen!
        if (isset($contentMap['dosen'])) {
            $dosenCol = $contentMap['dosen'];
            if (isset($final['mk']) && $final['mk'] === $dosenCol) {
                unset($final['mk']);
            }
            $final['dosen'] = $dosenCol;
        }

        // 4. Jika kolom mk belum ada atau terhapus karena koreksi, gunakan kolom mk dari contentMap jika ada
        if (!isset($final['mk']) && isset($contentMap['mk'])) {
            $final['mk'] = $contentMap['mk'];
        }

        return $final;
    }

    /**
     * Normalisasi string header untuk pencocokan.
     */
    private function normalizeHeader($str): string {
        $str = trim((string)$str);
        // Hapus karakter kontrol dan non-breaking space
        $str = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $str);
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $str));
    }

    /**
     * Parsing rentang waktu seperti "08:00 - 10:30", "08.00 - 10.00", "08:00 s/d 10:00".
     */
    private function parseTimeRange(string $timeStr): array {
        $clean = trim($timeStr);
        $clean = preg_replace('/(\d{1,2})\.(\d{2})/', '$1:$2', $clean);
        $parts = preg_split('/\s*(?:-|–|—|s\/d|sd|sampai)\s*/i', $clean);

        $start = $this->normalizeTimeStr($parts[0] ?? '00:00');
        $end   = isset($parts[1]) && trim($parts[1]) !== '' ? $this->normalizeTimeStr($parts[1]) : $start;

        return [$start, $end];
    }

    /**
     * Normalisasi format jam ke HH:mm (e.g. "8:00" -> "08:00").
     */
    private function normalizeTimeStr(string $time): string {
        $time = trim($time);
        $time = preg_replace('/(\d{1,2})\.(\d{2})/', '$1:$2', $time);
        if (preg_match('/^(\d{1,2}):(\d{2})/', $time, $matches)) {
            return sprintf('%02d:%02d', (int)$matches[1], (int)$matches[2]);
        }
        return !empty($time) ? $time : '00:00';
    }

    private $labsCache = null;

    /**
     * Kamus alias/sinonim nama laboratorium (Indonesia & Inggris & variasi ejaan).
     */
    private static $labAliases = [
        'startup'          => 'startup',
        'start-up'         => 'startup',
        'start up'         => 'startup',
        'jarkom'           => 'computernetwork',
        'jaringan'         => 'computernetwork',
        'jaringankomputer' => 'computernetwork',
        'computernetwork'  => 'computernetwork',
        'visikomputer'     => 'computervision',
        'computervision'   => 'computervision',
        'sainsdata'        => 'datascience',
        'ilmudata'         => 'datascience',
        'datascience'      => 'datascience',
        'mikro'            => 'microcontroller',
        'mikrokontroler'   => 'microcontroller',
        'mikrokontroller'  => 'microcontroller',
        'microcontroller'  => 'microcontroller',
        'multimedia'       => 'multimedia',
        'mm'               => 'multimedia',
        'riset1'           => 'researchroom1',
        'ruangriset1'      => 'researchroom1',
        'research1'        => 'researchroom1',
        'researchroom1'    => 'researchroom1',
        'riset2'           => 'researchroom2',
        'ruangriset2'      => 'researchroom2',
        'research2'        => 'researchroom2',
        'researchroom2'    => 'researchroom2',
        'riset3'           => 'researchroom3',
        'ruangriset3'      => 'researchroom3',
        'research3'        => 'researchroom3',
        'researchroom3'    => 'researchroom3',
    ];

    /**
     * Smart Search ID Laboratorium berdasarkan nama dengan toleransi penamaan tinggi.
     * Mengabaikan spasi/tanda hubung (misal: "startup", "start-up", "Start Up", "Lab Startup" -> cocok ke "Start Up").
     * Mendukung alias sinonim Indonesia/Inggris (misal: "Jarkom" -> "Computer Network").
     */
    private function findExistingLab($name) {
        if (empty($name)) return null;
        if (is_numeric($name)) return (int)$name;

        $db = $this->model->db;
        $rawClean = trim($name);

        // 1. Coba pencocokan langsung via database (Exact & Strip Prefix)
        $stmt = $db->prepare("SELECT idLaboratorium FROM laboratorium WHERE LCASE(TRIM(nama)) = LCASE(TRIM(?)) LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $rawClean);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            if ($res) return (int)$res['idLaboratorium'];
        }

        $stripped = trim(preg_replace('/^(laboratorium|lab|ruang|ruangan)\s+/i', '', $rawClean));
        if (!empty($stripped) && $stripped !== $rawClean) {
            $stmt = $db->prepare("SELECT idLaboratorium FROM laboratorium WHERE LCASE(TRIM(nama)) = LCASE(TRIM(?)) LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("s", $stripped);
                $stmt->execute();
                $res = $stmt->get_result()->fetch_assoc();
                if ($res) return (int)$res['idLaboratorium'];
            }
        }

        // 2. In-memory Smart Matching dengan Normalisasi Karakter (Menghapus spasi, strip '-', simbol, dll.)
        if ($this->labsCache === null) {
            $this->labsCache = [];
            $res = $db->query("SELECT idLaboratorium, nama FROM laboratorium");
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $this->labsCache[] = [
                        'id'         => (int)$row['idLaboratorium'],
                        'nama'       => $row['nama'],
                        'normalized' => strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string)$row['nama']))
                    ];
                }
            }
        }

        $cleanNorm = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $stripped));
        if (empty($cleanNorm)) {
            $cleanNorm = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $rawClean));
        }

        // Cek apakah ada di kamus alias
        $targetNorm = self::$labAliases[$cleanNorm] ?? $cleanNorm;

        // a. Exact normalized match (misal: "startup" atau "start-up" cocok dengan "Start Up" -> "startup")
        foreach ($this->labsCache as $lab) {
            if ($lab['normalized'] === $targetNorm || $lab['normalized'] === $cleanNorm) {
                return $lab['id'];
            }
        }

        // b. Containment normalized match
        foreach ($this->labsCache as $lab) {
            if (strlen($cleanNorm) >= 3 && (str_contains($lab['normalized'], $cleanNorm) || str_contains($cleanNorm, $lab['normalized']))) {
                return $lab['id'];
            }
        }

        // c. Fuzzy similarity (Levenshtein / similar_text > 75%)
        $bestId = null;
        $bestScore = 0;
        foreach ($this->labsCache as $lab) {
            similar_text($cleanNorm, $lab['normalized'], $percent);
            if ($percent > 75 && $percent > $bestScore) {
                $bestScore = $percent;
                $bestId = $lab['id'];
            }
        }
        if ($bestId) {
            return $bestId;
        }

        // 3. Fallback SQL LIKE Search
        $like = '%' . $rawClean . '%';
        $stmt = $db->prepare("SELECT idLaboratorium FROM laboratorium WHERE nama LIKE ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $like);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            if ($res) return (int)$res['idLaboratorium'];
        }

        return null;
    }

    private $asistenCache = null;

    /**
     * Smart Search & Resolver ID Asisten berdasarkan nama.
     * Mengabaikan karakter tak terlihat (zero-width space), membersihkan gelar akademik,
     * serta mencocokkan secara tepat ke master asisten.
     * Jika asisten belum terdaftar di master, otomatis didaftarkan agar tidak bernilai 0/hilang.
     */
    private function findOrCreateAsisten($name) {
        if (empty($name)) return null;
        if (is_numeric($name)) return (int)$name;

        // 1. Bersihkan karakter non-breaking space / zero-width space
        $clean = preg_replace('/[\x{200B}-\x{200D}\x{2060}\x{FEFF}\x{00A0}]/u', '', (string)$name);
        $clean = trim($clean);
        if (empty($clean) || $clean === '-' || strtolower($clean) === 'kosong') return null;

        // 2. Hilangkan gelar untuk pencocokan jika ada (misal ", S.Kom")
        $baseName = trim(preg_replace('/,\s*(S\.Kom|M\.Kom|M\.T\.|S\.T\.|M\.Cs\.|B\.Sc\.|M\.Sc\.|MTA\.|S\.Pd\.|M\.Pd\.).*$/i', '', $clean));

        $db = $this->model->db;

        // 3. In-memory cache master asisten
        if ($this->asistenCache === null) {
            $this->asistenCache = [];
            $res = $db->query("SELECT idAsisten, nama FROM asisten");
            if ($res) {
                while ($r = $res->fetch_assoc()) {
                    $this->asistenCache[] = [
                        'id'   => (int)$r['idAsisten'],
                        'nama' => $r['nama'],
                        'norm' => strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string)$r['nama']))
                    ];
                }
            }
        }

        $normInput = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $clean));
        $normBase  = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $baseName));

        // a. Exact / Normalized Match
        foreach ($this->asistenCache as $a) {
            if ($a['norm'] === $normInput || $a['norm'] === $normBase) {
                return $a['id'];
            }
        }

        // b. Containment Match (panjang >= 6)
        foreach ($this->asistenCache as $a) {
            if (strlen($normBase) >= 6 && (str_contains($a['norm'], $normBase) || str_contains($normBase, $a['norm']))) {
                return $a['id'];
            }
        }

        // c. Fuzzy similarity (> 80%)
        $bestId = null;
        $bestScore = 0;
        foreach ($this->asistenCache as $a) {
            similar_text($normBase, $a['norm'], $perc);
            if ($perc > 80 && $perc > $bestScore) {
                $bestScore = $perc;
                $bestId = $a['id'];
            }
        }
        if ($bestId) return $bestId;

        // d. Buat asisten baru jika belum ada sama sekali
        $nameToInsert = !empty($baseName) ? $baseName : $clean;
        $escaped = $db->real_escape_string($nameToInsert);
        $db->query("INSERT INTO asisten (nama, statusAktif) VALUES ('$escaped', 'Asisten')");
        $newId = (int)$db->insert_id;

        $this->asistenCache[] = [
            'id'   => $newId,
            'nama' => $nameToInsert,
            'norm' => strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nameToInsert))
        ];

        return $newId;
    }

    private function findAsistenIdByName($name) {
        return $this->findOrCreateAsisten($name);
    }

    /**
     * Ekstrak isi sel per baris menjadi array nilai teks terformat.
     */
    private function extractRowData($row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        $data = [];
        foreach ($cellIterator as $cell) {
            $data[] = $cell->getFormattedValue();
        }
        return $data;
    }

    /**
     * Validasi pintar: cek apakah nama merupakan nama yang TIDAK valid untuk mata kuliah
     * (misal: nama hari, format jam, nama dosen bergelar, atau kata kunci header).
     */
    private function isInvalidCourseName(?string $name): bool {
        if (empty($name)) return true;
        $name = trim($name);
        $lower = strtolower($name);

        // 1. Cek Hari
        $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu', 'hari'];
        if (in_array($lower, $days)) return true;

        // 2. Cek Jam / Waktu
        if (preg_match('/^\d{1,2}[:.]\d{2}/', $name) || preg_match('/^(jam|waktu|pukul)\b/i', $name)) {
            return true;
        }

        // 3. Cek Gelar Dosen / Akademik
        if (preg_match('/,\s*(S\.Kom|M\.Kom|M\.T|S\.T|M\.Cs|MTA|M\.Si|M\.Eng|Dr\.|Ir\.|Prof\.)/i', $name) ||
            preg_match('/^(Dr\.|Ir\.|Prof\.)\s+/i', $name)) {
            return true;
        }

        // 4. Cek Kata Kunci Header / Metadata
        $keywords = ['dosen', 'hari', 'jam', 'waktu', 'ruang', 'ruangan', 'lab', 'laboratorium', 'kelas', 'sks', 'total', 'keterangan', 'asisten', 'no', 'nomor', 'belum ditetapkan', 'mata kuliah unik a'];
        if (in_array($lower, $keywords)) return true;

        // 5. Cek apakah nama ini cocok persis dengan nama dosen di database
        $db = $this->model->db;
        $escaped = $db->real_escape_string($name);
        $res = $db->query("SELECT idDosen FROM dosen WHERE LCASE(TRIM(nama)) = LCASE('$escaped') LIMIT 1");
        if ($res && $res->num_rows > 0) {
            return true;
        }

        return false;
    }

    /**
     * Validasi kode mata kuliah agar tidak menggunakan nama hari atau kata kunci.
     */
    private function isInvalidCourseCode(?string $code): bool {
        if (empty($code)) return true;
        $code = trim($code);
        $lower = strtolower($code);
        $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu', 'hari'];
        if (in_array($lower, $days)) return true;
        $keywords = ['dosen', 'hari', 'jam', 'waktu', 'ruang', 'ruangan', 'lab', 'laboratorium', 'kelas', 'sks', 'no', 'nomor'];
        if (in_array($lower, $keywords)) return true;
        if (preg_match('/^\d{1,2}[:.]\d{2}/', $code)) return true;
        return false;
    }

    /**
     * Validasi nama dosen agar tidak menggunakan jam/waktu, hari, atau lab.
     */
    private function isInvalidDosenName(?string $name): bool {
        if (empty($name)) return true;
        $name = trim($name);
        $lower = strtolower($name);

        // 1. Cek Jam / Waktu
        if (preg_match('/^\d{1,2}[:.]\d{2}/', $name) || preg_match('/^(jam|waktu|pukul)\b/i', $name)) {
            return true;
        }

        // 2. Cek Hari
        $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu', 'hari'];
        if (in_array($lower, $days)) return true;

        // 3. Cek Kata Kunci Header / Metadata
        $keywords = ['dosen', 'hari', 'jam', 'waktu', 'ruang', 'ruangan', 'lab', 'laboratorium', 'kelas', 'sks', 'total', 'keterangan', 'asisten', 'no', 'nomor', 'dosen test'];
        if (in_array($lower, $keywords)) return true;

        // 4. Cek apakah ini nama lab
        if (preg_match('/^(lab|laboratorium|ruang)\s+/i', $name)) {
            return true;
        }

        return false;
    }

    /**
     * Cari atau buat entitas Matakuliah dengan validasi ketat (Smart Protection).
     * Mencegah nama dosen, hari, atau jam masuk sebagai master mata kuliah.
     */
    private function findOrCreateMatakuliah($kodeMK, $namaMK, $sks = null) {
        $namaMK = trim((string)$namaMK);
        $kodeMK = trim((string)$kodeMK);

        // Validasi: jika namaMK invalid, coba lihat apakah kodeMK sebenarnya nama mata kuliah
        if ($this->isInvalidCourseName($namaMK)) {
            if (!empty($kodeMK) && !$this->isInvalidCourseName($kodeMK)) {
                $namaMK = $kodeMK;
                $kodeMK = '';
            } else {
                return null;
            }
        }

        if ($this->isInvalidCourseCode($kodeMK)) {
            $kodeMK = '';
        }

        if (empty($namaMK) && empty($kodeMK)) return null;

        $db = $this->model->db;

        // 1. Cari berdasarkan nama matakuliah (prioritaskan pencocokan nama agar mata kuliah sama digunakan kembali)
        if (!empty($namaMK)) {
            $stmt = $db->prepare("SELECT idMatakuliah, kodeMatakuliah, namaMatakuliah FROM matakuliah WHERE LCASE(TRIM(namaMatakuliah)) = LCASE(TRIM(?)) LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("s", $namaMK);
                $stmt->execute();
                $res = $stmt->get_result()->fetch_assoc();
                if ($res) {
                    $id = (int)$res['idMatakuliah'];
                    if (!empty($kodeMK) && !$this->isInvalidCourseCode($kodeMK) && $res['kodeMatakuliah'] !== $kodeMK) {
                        $upd = $db->prepare("UPDATE matakuliah SET kodeMatakuliah = ? WHERE idMatakuliah = ?");
                        if ($upd) {
                            $upd->bind_param("si", $kodeMK, $id);
                            $upd->execute();
                        }
                    }
                    return $id;
                }
            }

            // Coba pencocokan normalisasi nama (misal: "Algoritma Pemrograman" vs "Algoritma dan Pemrograman" / "Algoritma dan Pemrograman 1")
            $norm = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string)$namaMK));
            $norm = preg_replace('/(dan|1|i|ii)$/i', '', $norm);
            if (strlen($norm) >= 6) {
                $allRes = $db->query("SELECT idMatakuliah, kodeMatakuliah, namaMatakuliah FROM matakuliah");
                if ($allRes) {
                    while ($row = $allRes->fetch_assoc()) {
                        $dbNorm = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string)$row['namaMatakuliah']));
                        $dbNorm = preg_replace('/(dan|1|i|ii)$/i', '', $dbNorm);
                        if ($dbNorm === $norm || (strlen($norm) >= 8 && (str_contains($dbNorm, $norm) || str_contains($norm, $dbNorm)))) {
                            $id = (int)$row['idMatakuliah'];
                            if (!empty($kodeMK) && !$this->isInvalidCourseCode($kodeMK) && $row['kodeMatakuliah'] !== $kodeMK) {
                                $upd = $db->prepare("UPDATE matakuliah SET kodeMatakuliah = ? WHERE idMatakuliah = ?");
                                if ($upd) {
                                    $upd->bind_param("si", $kodeMK, $id);
                                    $upd->execute();
                                }
                            }
                            return $id;
                        }
                    }
                }
            }
        }

        // 2. Jika nama belum ada tapi kode ada, coba cari berdasarkan kode matakuliah
        if (!empty($kodeMK)) {
            $stmt = $db->prepare("SELECT idMatakuliah FROM matakuliah WHERE LCASE(TRIM(kodeMatakuliah)) = LCASE(TRIM(?)) LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("s", $kodeMK);
                $stmt->execute();
                $res = $stmt->get_result()->fetch_assoc();
                if ($res) return (int)$res['idMatakuliah'];
            }
        }

        // 3. Jika belum terdaftar dan lolos validasi, buat record matakuliah baru
        $cleanKode = !empty($kodeMK) ? trim($kodeMK) : 'MK-' . strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', (string)$namaMK), 0, 6));
        $cleanNama = !empty($namaMK) ? trim($namaMK) : $cleanKode;
        $cleanSks  = !empty($sks) ? (int)$sks : 3;

        $stmt = $db->prepare("INSERT INTO matakuliah (kodeMatakuliah, namaMatakuliah, sksKuliah) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssi", $cleanKode, $cleanNama, $cleanSks);
            $stmt->execute();
            return (int)$db->insert_id;
        }

        return null;
    }

    /**
     * Cari atau buat master dosen secara otomatis jika belum ada dengan validasi ketat.
     * Mencegah format waktu ("07:00-09:30") atau hari masuk sebagai master dosen.
     */
    private function findOrCreateDosen($name) {
        if (empty($name)) return null;
        if (is_numeric($name)) return (int)$name;
        
        $name = trim($name);
        
        // Lewati teks kata kunci header, kode kelas, atau data tidak valid
        if ($this->isInvalidDosenName($name)) {
            return null;
        }

        $db = $this->model->db;

        // 1. Exact Match
        $stmt = $db->prepare("SELECT idDosen FROM dosen WHERE LCASE(TRIM(nama)) = LCASE(?) LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            if ($res) return (int)$res['idDosen'];
        }

        // 2. Partial Search jika ada nama lengkap
        $stmt2 = $db->prepare("SELECT idDosen FROM dosen WHERE ? LIKE CONCAT('%', nama, '%') ORDER BY LENGTH(nama) DESC LIMIT 1");
        if ($stmt2) {
            $stmt2->bind_param("s", $name);
            $stmt2->execute();
            $res2 = $stmt2->get_result()->fetch_assoc();
            if ($res2) return (int)$res2['idDosen'];
        }

        // 3. Insert dosen baru jika belum ditemukan
        $escapedName = $db->real_escape_string($name);
        $db->query("INSERT INTO dosen (nama) VALUES ('$escapedName')");
        return (int)$db->insert_id;
    }

    /**
     * Normalisasi nama Program Studi (TI atau SI).
     * 
     * @param mixed $raw
     * @param string $kodeMK
     * @param string $namaMK
     * @return string 'TI' atau 'SI'
     */
    public function normalizeProdi($raw = '', $kodeMK = '', $namaMK = ''): string {
        $str = trim((string)$raw);
        $upper = strtoupper($str);
        
        if ($upper === 'SI' || stripos($upper, 'SISTEM INFORMASI') !== false) {
            return 'SI';
        }
        if ($upper === 'TI' || stripos($upper, 'INFORMATIKA') !== false) {
            return 'TI';
        }

        // Cek dari kode matakuliah
        $kode = strtoupper(trim((string)$kodeMK));
        if (str_starts_with($kode, '131') || str_starts_with($kode, 'SI')) {
            return 'SI';
        }
        if (str_starts_with($kode, '130') || str_starts_with($kode, 'TI')) {
            return 'TI';
        }

        // Default ke TI
        return 'TI';
    }
}