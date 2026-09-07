<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

/**
 * JadwalUpkService - Logika Bisnis Jadwal UPK
 * 
 * Menangani proses import data file (Excel/CSV) dengan Smart Column Auto-Detection:
 * - Bebas dari template kaku: otomatis mendeteksi baris header dan memetakan kolom berdasarkan nama.
 * - Kolom dapat diletakkan dalam urutan acak atau format bervariasi.
 * - Mampu mengurai tanggal bahasa Indonesia (misal: "Senin, 20 Juni 2025", "20/06/2025", serial number Excel).
 * - Mampu mengurai format jam (misal: "08:00 - 10:00", "08.00 - 10.00", atau kolom mulai & selesai terpisah).
 * - Normalisasi otomatis nama prodi (TI -> Teknik Informatika, SI -> Sistem Informasi).
 */
class JadwalUpkService {
    private $model;

    /**
     * Pola regex / sinonim untuk mendeteksi nama kolom secara otomatis.
     */
    private static $fieldPatterns = [
        'jam_mulai' => [
            '/^(jam[\s\-_]*mulai|waktu[\s\-_]*mulai|mulai|start|starttime|darijam)$/i',
        ],
        'jam_selesai' => [
            '/^(jam[\s\-_]*selesai|waktu[\s\-_]*selesai|selesai|end|endtime|sampaijam)$/i',
        ],
        'tanggal' => [
            '/^(hari[\/\s\-_]*tanggal|hari[\/\s\-_]*tgl|tanggal|tgl|date|jadwal[\s\-_]*tanggal|hari[\s\-_]*dan[\s\-_]*tanggal)$/i',
            '/^(tanggal.*upk|tgl.*ujian|tanggal.*ujian|hari.*tanggal)$/i',
        ],
        'hari' => [
            '/^(hari|day)$/i',
        ],
        'jam' => [
            '/^(jam|waktu|pukul|time|jam[\s\-_]*upk|jam[\s\-_]*ujian|waktu[\s\-_]*ujian|sesi|jadwal[\s\-_]*jam)$/i',
            '/^(jam.*ujian|waktu.*ujian)$/i',
        ],
        'mata_kuliah' => [
            '/^(mata[\s\-_]*kuliah|matakuliah|matkul|namamk|nama[\s\-_]*mata[\s\-_]*kuliah|mk|course|coursename|subject|mata[\s\-_]*ajaran|nama[\s\-_]*matkul)$/i',
            '/^(nama[\s\-_]*mata[\s\-_]*kuliah|nama[\s\-_]*mk)$/i',
        ],
        'kode_mk' => [
            '/^(kode|kodemk|kodematakuliah|kodematkul|kdmk|kd|coursecode|code)$/i',
            '/^(kode.*mk|kode.*mat.*kuliah)$/i',
        ],
        'dosen' => [
            '/^(dosen|namadosen|dosen[\s\-_]*pengampu|pengampu|pengajar|lecturer|instruktur|dosen[\s\-_]*pembina|pembina)$/i',
            '/^(dosen.*pengampu|nama.*dosen)$/i',
        ],
        'ruangan' => [
            '/^(ruangan|ruang|laboratorium|lab|tempat|room|lokasi|namalab|ruanglab|ruang[\s\-_]*ujian|nama[\s\-_]*ruangan)$/i',
            '/^(ruang.*lab|nama.*lab|ruang.*ujian)$/i',
        ],
        'kelas_freq' => [
            '/^(kelas[\/\s\-_]*freq|kls[\/\s\-_]*freq|kelas[\/\s\-_]*frekuensi|kls[\/\s\-_]*frekuensi)$/i',
        ],
        'kelas' => [
            '/^(kelas|kls|class|kelompok|group|rombel)$/i',
        ],
        'prodi' => [
            '/^(prodi|program[\s\-_]*studi|jurusan|departemen|studi)$/i',
        ],
        'frekuensi' => [
            '/^(frekuensi|frekwensi|freq|frek|gelombang|pertemuan)$/i',
        ]
    ];

    public function __construct($model = null) {
        $this->model = $model;
    }

    /**
     * Memproses file Excel (.xlsx / .xls) menggunakan deteksi kolom cerdas.
     * 
     * @param string $filePath Path file sementara.
     * @return array Data yang telah dinormalisasi dan siap disimpan.
     * @throws Exception Jika file kosong atau kolom wajib tidak ditemukan.
     */
    public function parseExcel(string $filePath): array {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();

        // 1. Deteksi Baris Header & Pemetaan Kolom Otomatis
        $detection = $this->detectHeaders($worksheet);
        $colMap = $detection['columnMap'];
        $startRow = $detection['firstDataRowIndex'];

        // Validasi kolom esensial: harus memiliki setidaknya Mata Kuliah, Kode MK, atau Ruangan
        if (!isset($colMap['mata_kuliah']) && !isset($colMap['kode_mk']) && !isset($colMap['ruangan'])) {
            throw new Exception("Kolom Mata Kuliah atau Ruangan tidak dapat dideteksi dalam file Excel. Pastikan tabel memiliki nama kolom.");
        }

        $highestRow = $worksheet->getHighestRow();
        $dataImport = [];

        for ($rowNum = $startRow; $rowNum <= $highestRow; $rowNum++) {
            $row = $worksheet->getRowIterator($rowNum, $rowNum)->current();
            if (!$row) continue;

            $rowData = $this->extractRowData($row);
            $parsed = $this->parseRow($rowData, $colMap, $rowNum);
            if ($parsed !== null) {
                $dataImport[] = $parsed;
            }
        }

        if (empty($dataImport)) {
            throw new Exception("File Excel tidak mengandung jadwal UPK yang valid.");
        }

        return $dataImport;
    }

    /**
     * Memproses file CSV menggunakan deteksi kolom cerdas.
     * 
     * @param string $filePath Path file CSV.
     * @return array Data yang telah dinormalisasi dan siap disimpan.
     * @throws Exception Jika file kosong atau format CSV rusak.
     */
    public function parseCSV(string $filePath): array {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new Exception("File CSV tidak dapat dibaca atau tidak ditemukan.");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (empty($lines)) {
            throw new Exception("File CSV kosong.");
        }

        // Auto-detect delimiter
        $delimiters = [',', ';', "\t", '|'];
        $bestDelimiter = ',';
        $maxCount = 0;
        $sampleLine = $lines[0];
        foreach ($delimiters as $delim) {
            $count = substr_count($sampleLine, $delim);
            if ($count > $maxCount) {
                $maxCount = $count;
                $bestDelimiter = $delim;
            }
        }

        $rows = [];
        $handle = fopen($filePath, 'r');
        while (($row = fgetcsv($handle, 0, $bestDelimiter, '"', '\\')) !== false) {
            $rows[] = $row;
        }
        fclose($handle);

        if (empty($rows)) {
            throw new Exception("File CSV tidak memiliki baris data.");
        }

        $detection = $this->detectHeadersFromArray($rows);
        $colMap = $detection['columnMap'];
        $startRow = $detection['firstDataRowIndex'];

        $dataImport = [];
        for ($i = $startRow; $i < count($rows); $i++) {
            $parsed = $this->parseRow($rows[$i], $colMap, $i + 1);
            if ($parsed !== null) {
                $dataImport[] = $parsed;
            }
        }

        if (empty($dataImport)) {
            throw new Exception("File CSV tidak mengandung jadwal UPK yang valid.");
        }

        return $dataImport;
    }

    /**
     * Parsing baris tunggal berdasarkan peta kolom yang terdeteksi.
     */
    private function parseRow(array $rowData, array $colMap, int $rowNum): ?array {
        // Lewati jika seluruh baris kosong
        if (empty(array_filter($rowData, fn($v) => trim((string)$v) !== ''))) {
            return null;
        }

        $getVal = function($field, $default = '') use ($colMap, $rowData) {
            if (!isset($colMap[$field])) return $default;
            $idx = $colMap[$field];
            return isset($rowData[$idx]) ? trim((string)$rowData[$idx]) : $default;
        };

        $mk      = $getVal('mata_kuliah');
        $kodeMK  = $getVal('kode_mk');
        $dosen   = $getVal('dosen');
        $ruangan = $getVal('ruangan');
        $prodi   = $this->normalizeProdi($getVal('prodi'));
        $kelas   = strtoupper($getVal('kelas'));
        $freq    = $getVal('frekuensi');

        // Jika ada kolom gabungan kelas_freq (misal: "A/1" atau "B-2")
        if (isset($colMap['kelas_freq'])) {
            $kf = $getVal('kelas_freq');
            if (!empty($kf)) {
                $parts = preg_split('/\s*[\/\-]\s*/', $kf);
                $kelas = strtoupper(trim($parts[0] ?? ''));
                if (isset($parts[1]) && empty($freq)) {
                    $freq = trim($parts[1]);
                }
            }
        }

        // Jika kelas memuat pola "A/1" atau "A / 2"
        if (strpos($kelas, '/') !== false) {
            $parts = explode('/', $kelas);
            $kelas = strtoupper(trim($parts[0]));
            if (empty($freq) && isset($parts[1])) {
                $freq = trim($parts[1]);
            }
        }

        // Abaikan baris ringkasan / total / catatan
        if (preg_match('/^(total|jumlah|keterangan|catatan|rekapitulasi)$/i', $mk)) {
            return null;
        }

        // Jika nama MK kosong tapi ada kode MK, gunakan kode MK
        if (empty($mk) && !empty($kodeMK)) {
            $mk = $kodeMK;
        }

        // Lewati baris jika data utama kosong sama sekali
        if (empty($mk) && empty($ruangan) && empty($dosen)) {
            return null;
        }

        // Parsing Tanggal
        $rawTanggal = $getVal('tanggal');
        if (empty($rawTanggal) && isset($colMap['hari'])) {
            $rawTanggal = $getVal('hari');
        }
        $tanggal = $this->parseDate($rawTanggal);

        // Parsing Jam / Waktu
        if (isset($colMap['jam_mulai']) && isset($colMap['jam_selesai'])) {
            $jam = $this->parseTimeRange($getVal('jam_mulai', '08:00'), $getVal('jam_selesai', '10:00'));
        } else {
            $jam = $this->parseTimeRange($getVal('jam', '08:00 - 10:00'));
        }

        // Bersihkan frekuensi jika kosong atau tanda strip
        if ($freq === '-' || $freq === '0' || empty($freq)) {
            $freq = '';
        }

        return [
            'prodi'       => $prodi,
            'tanggal'     => $tanggal,
            'jam'         => $jam,
            'mata_kuliah' => $mk,
            'dosen'       => $dosen,
            'frekuensi'   => $freq,
            'kelas'       => $kelas,
            'ruangan'     => $ruangan
        ];
    }

    /**
     * Memindai baris teratas (1 - 15) untuk mendeteksi baris header dan memetakan kolom secara otomatis.
     */
    public function detectHeaders($worksheet, int $maxScanRows = 15): array {
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

            // Berikan bobot lebih untuk kolom-kolom penentu UPK
            if (isset($mapping['mata_kuliah'])) $score += 3;
            if (isset($mapping['dosen']))       $score += 2;
            if (isset($mapping['tanggal']))     $score += 3;
            if (isset($mapping['ruangan']))     $score += 2;
            if (isset($mapping['jam']) || (isset($mapping['jam_mulai']) && isset($mapping['jam_selesai']))) $score += 2;

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestRow = $rowIdx;
                $bestMapping = $mapping;
            }
        }

        // Fallback default jika tidak ada header yang jelas
        if ($bestScore < 3 || empty($bestMapping)) {
            return [
                'headerRowIndex'    => 1,
                'firstDataRowIndex' => 2,
                'columnMap' => [
                    'prodi'       => 1,
                    'tanggal'     => 2,
                    'jam'         => 3,
                    'mata_kuliah' => 4,
                    'dosen'       => 5,
                    'frekuensi'   => 6,
                    'kelas'       => 7,
                    'ruangan'     => 8
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
     * Memindai baris array untuk CSV guna mendeteksi baris header dan memetakan kolom.
     */
    public function detectHeadersFromArray(array $rows, int $maxScanRows = 15): array {
        $bestRow = null;
        $bestScore = 0;
        $bestMapping = [];

        $totalRows = min(count($rows), $maxScanRows);

        for ($rowIdx = 0; $rowIdx < $totalRows; $rowIdx++) {
            $headers = $rows[$rowIdx];
            if (!is_array($headers)) continue;

            $mapping = $this->mapColumns($headers);
            $score = count($mapping);

            if (isset($mapping['mata_kuliah'])) $score += 3;
            if (isset($mapping['dosen']))       $score += 2;
            if (isset($mapping['tanggal']))     $score += 3;
            if (isset($mapping['ruangan']))     $score += 2;
            if (isset($mapping['jam']) || (isset($mapping['jam_mulai']) && isset($mapping['jam_selesai']))) $score += 2;

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestRow = $rowIdx;
                $bestMapping = $mapping;
            }
        }

        if ($bestScore < 3 || empty($bestMapping)) {
            return [
                'headerRowIndex'    => 0,
                'firstDataRowIndex' => 1,
                'columnMap' => [
                    'prodi'       => 1,
                    'tanggal'     => 2,
                    'jam'         => 3,
                    'mata_kuliah' => 4,
                    'dosen'       => 5,
                    'frekuensi'   => 6,
                    'kelas'       => 7,
                    'ruangan'     => 8
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
     */
    private function mapColumns(array $headers): array {
        $mapping = [];
        $usedCols = [];

        // Urutan prioritas agar field yang lebih spesifik dipetakan lebih awal
        $priorityOrder = [
            'jam_mulai',
            'jam_selesai',
            'tanggal',
            'mata_kuliah',
            'kode_mk',
            'dosen',
            'ruangan',
            'jam',
            'kelas_freq',
            'kelas',
            'prodi',
            'frekuensi'
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
     * Normalisasi string header untuk pencocokan regex.
     */
    private function normalizeHeader($str): string {
        $str = trim((string)$str);
        // Hapus karakter kontrol dan non-breaking space
        $str = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $str);
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $str));
    }

    /**
     * Ekstrak isi sel per baris menjadi array nilai teks terformat.
     * Mengonversi cell Date Excel secara otomatis ke format Y-m-d.
     */
    private function extractRowData($row): array {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        $data = [];
        foreach ($cellIterator as $cell) {
            $val = null;
            if (ExcelDate::isDateTime($cell)) {
                try {
                    $dt = ExcelDate::excelToDateTimeObject($cell->getValue());
                    $val = $dt->format('Y-m-d');
                } catch (\Throwable $e) {
                    $val = $cell->getFormattedValue();
                }
            } else {
                $val = $cell->getFormattedValue();
            }
            $data[] = $val;
        }
        return $data;
    }

    /**
     * Parsing tanggal yang mendukung berbagai format (Y-m-d, d/m/Y, teks bulan Indonesia, Excel serial date).
     */
    public function parseDate($val): string {
        if ($val === null || $val === '') {
            return date('Y-m-d');
        }

        if ($val instanceof \DateTimeInterface) {
            return $val->format('Y-m-d');
        }

        $str = trim((string)$val);
        if (empty($str)) {
            return date('Y-m-d');
        }

        // Jika berupa angka serial date Excel
        if (is_numeric($str) && (float)$str > 20000 && (float)$str < 80000) {
            try {
                return ExcelDate::excelToDateTimeObject((float)$str)->format('Y-m-d');
            } catch (\Throwable $e) {}
        }

        // Hapus nama hari di depan (misal: "Senin, ", "Selasa - ", "Rabu / ")
        $cleanStr = preg_replace('/^(senin|selasa|rabu|kamis|jumat|jum\'at|sabtu|minggu|monday|tuesday|wednesday|thursday|friday|saturday|sunday)[\s,.\/\-_]+/i', '', $str);
        $cleanStr = trim($cleanStr);

        // Kamus bulan Indonesia ke Inggris untuk strtotime
        $bulanMap = [
            'januari' => 'January', 'jan' => 'Jan',
            'februari' => 'February', 'feb' => 'Feb',
            'maret' => 'March', 'mar' => 'Mar',
            'april' => 'April', 'apr' => 'Apr',
            'mei' => 'May',
            'juni' => 'June', 'jun' => 'Jun',
            'juli' => 'July', 'jul' => 'Jul',
            'agustus' => 'August', 'agt' => 'Aug', 'ags' => 'Aug',
            'september' => 'September', 'sep' => 'Sep', 'sept' => 'Sep',
            'oktober' => 'October', 'okt' => 'Oct',
            'november' => 'November', 'nov' => 'Nov',
            'desember' => 'December', 'des' => 'Dec'
        ];

        $searchMonth = strtolower($cleanStr);
        foreach ($bulanMap as $idBulan => $enBulan) {
            $pattern = '/\b' . preg_quote($idBulan, '/') . '\b/i';
            if (preg_match($pattern, $searchMonth)) {
                $cleanStr = preg_replace($pattern, $enBulan, $cleanStr);
                break;
            }
        }

        // Format d/m/Y atau d-m-Y
        if (preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})$/', $cleanStr, $m)) {
            $d = (int)$m[1];
            $mth = (int)$m[2];
            $y = (int)$m[3];
            if ($mth <= 12 && $d <= 31) {
                return sprintf('%04d-%02d-%02d', $y, $mth, $d);
            }
        }

        $ts = strtotime($cleanStr);
        if ($ts && $ts > 0) {
            return date('Y-m-d', $ts);
        }

        return date('Y-m-d');
    }

    /**
     * Parsing rentang waktu seperti "08:00 - 10:00", "08.00 - 10.00", "08:00 s/d 10:00".
     */
    public function parseTimeRange($val, $valEnd = null): string {
        if (!empty($valEnd)) {
            $start = $this->normalizeTimeStr((string)$val);
            $end = $this->normalizeTimeStr((string)$valEnd);
            return "$start - $end";
        }

        $str = trim((string)$val);
        if (empty($str)) return '08:00 - 10:00';

        $str = preg_replace('/(\d{1,2})\.(\d{2})/', '$1:$2', $str);

        // Pola rentang waktu: 08:00 - 10:00, 08:00 s/d 10:00, 08:00 sampai 10:00
        if (preg_match('/(\d{1,2}:\d{2})\s*(?:-|–|—|s\/d|s\.d|sampai|to)\s*(\d{1,2}:\d{2})/i', $str, $matches)) {
            $start = $this->normalizeTimeStr($matches[1]);
            $end   = $this->normalizeTimeStr($matches[2]);
            return "$start - $end";
        }

        // Jika hanya 1 waktu tunggal
        if (preg_match('/(\d{1,2}:\d{2})/', $str, $matches)) {
            return $this->normalizeTimeStr($matches[1]);
        }

        return $str;
    }

    /**
     * Normalisasi format jam ke HH:mm (misal: "8:00" -> "08:00").
     */
    private function normalizeTimeStr(string $time): string {
        $time = trim($time);
        $time = preg_replace('/(\d{1,2})\.(\d{2})/', '$1:$2', $time);
        if (preg_match('/^(\d{1,2}):(\d{2})/', $time, $m)) {
            return sprintf('%02d:%02d', (int)$m[1], (int)$m[2]);
        }
        return !empty($time) ? $time : '08:00';
    }

    /**
     * Normalisasi nama Program Studi.
     */
    private function normalizeProdi($raw): string {
        $str = trim((string)$raw);
        if (empty($str)) return 'Teknik Informatika';
        $upper = strtoupper($str);
        if ($upper === 'TI' || stripos($upper, 'INFORMATIKA') !== false) {
            return 'Teknik Informatika';
        }
        if ($upper === 'SI' || stripos($upper, 'SISTEM INFORMASI') !== false) {
            return 'Sistem Informasi';
        }
        return $str;
    }

    /**
     * Mengelola impor CSV melalui Model (kompatibilitas).
     */
    public function importCSV(string $filePath): bool {
        $data = $this->parseCSV($filePath);
        return $this->model->importData($data);
    }
}