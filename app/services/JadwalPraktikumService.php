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

            // Resolve Foreign Keys
            $idMK  = $this->findOrCreateMatakuliah($kodeMK, $mk, $sks);
            $idLab = $this->findExistingLab($lab);

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

        // Jika tidak ditemukan baris header yang jelas, gunakan default fallback template
        if ($bestScore < 3 || empty($bestMapping)) {
            return [
                'headerRowIndex'    => 1,
                'firstDataRowIndex' => 2,
                'columnMap' => [
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
                ],
                'isFallback' => true
            ];
        }

        return [
            'headerRowIndex'    => $bestRow,
            'firstDataRowIndex' => $bestRow + 1,
            'columnMap'         => $bestMapping,
            'isFallback'        => false
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
     * Cari atau buat entitas Matakuliah.
     * Mengizinkan kode MK duplikat agar beberapa kelas/jadwal berbeda 
     * dapat berbagi kode mata kuliah yang sama tanpa error.
     */
    private function findOrCreateMatakuliah($kodeMK, $namaMK, $sks = null) {
        if (empty($namaMK) && empty($kodeMK)) return null;

        $db = $this->model->db;

        // 1. Cari berdasarkan nama matakuliah (prioritaskan pencocokan nama agar mata kuliah sama digunakan kembali)
        if (!empty($namaMK)) {
            $stmt = $db->prepare("SELECT idMatakuliah FROM matakuliah WHERE LCASE(TRIM(namaMatakuliah)) = LCASE(TRIM(?)) LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("s", $namaMK);
                $stmt->execute();
                $res = $stmt->get_result()->fetch_assoc();
                if ($res) return (int)$res['idMatakuliah'];
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

        // 3. Jika belum terdaftar, buat record matakuliah baru (kode boleh duplikat)
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
     * Cari atau buat master dosen secara otomatis jika belum ada.
     */
    private function findOrCreateDosen($name) {
        if (empty($name)) return null;
        if (is_numeric($name)) return (int)$name;
        
        $name = trim($name);
        
        // Lewati teks kata kunci header atau kode kelas
        if (preg_match('/^(dosen|no|kelas|[a-z][0-9](,[a-z][0-9])*)$/i', $name)) {
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
}