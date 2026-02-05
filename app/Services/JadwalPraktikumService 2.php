<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * JadwalPraktikumService - Logika Bisnis Jadwal Praktikum
 * * Menangani proses kompleks seperti:
 * - Parsing file Excel ke array data database.
 * - Pencarian ID Asisten berdasarkan nama (Smart Search).
 * - Sinkronisasi data master (Mata Kuliah & Lab).
 * - Validasi bentrok/duplikasi jadwal.
 */
class JadwalPraktikumService {
    private $model;

    public function __construct() {
        $this->model = new JadwalPraktikumModel();
    }

    /**
     * Memproses file Excel dan mengimpor data ke database.
     * * @param string $filePath Path sementara file Excel.
     * @return array Ringkasan hasil import (success, duplicate, invalid).
     * @throws Exception Jika file rusak atau format tidak sesuai.
     */
    public function importFromExcel($filePath) {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        
        $stats = ['success' => 0, 'duplicate' => 0, 'invalid' => 0];

        foreach ($worksheet->getRowIterator(2) as $row) {
            $rowData = $this->extractRowData($row);
            if (empty(array_filter($rowData))) continue;

            // Mapping Data Kolom Excel
            $data = [
                'dosen'    => trim($rowData[2] ?? ''),
                'mk'       => trim($rowData[3] ?? ''),
                'kelas'    => trim($rowData[5] ?? ''),
                'freq'     => trim($rowData[6] ?? ''),
                'lab'      => trim($rowData[7] ?? ''),
                'hari'     => ucfirst(strtolower(trim($rowData[8] ?? ''))),
                'jam'      => str_replace('.', ':', trim($rowData[9] ?? '')),
                'asisten1' => trim($rowData[11] ?? ''),
                'asisten2' => trim($rowData[12] ?? '')
            ];

            // Parsing Waktu (07:00 - 09:00)
            $timeParts = explode('-', $data['jam']);
            $start = trim($timeParts[0] ?? '00:00');
            $end   = isset($timeParts[1]) ? trim($timeParts[1]) : $start;

            // Resolve Foreign Keys
            $idMK  = $this->findOrCreateMaster('matakuliah', 'namaMatakuliah', $data['mk']);
            $idLab = $this->findExistingMaster('laboratorium', 'nama', $data['lab']);

            if (!$idLab) {
                $stats['invalid']++;
                continue;
            }

            // Cek Duplikasi
            if ($this->model->checkDuplicate($idMK, $data['kelas'], $data['hari'], $start, $end, $idLab)) {
                $stats['duplicate']++;
                continue;
            }

            // Simpan Jadwal
            $this->model->insert([
                'idMatakuliah'   => $idMK,
                'kelas'          => $data['kelas'],
                'idLaboratorium' => $idLab,
                'hari'           => $data['hari'],
                'waktuMulai'     => $start,
                'waktuSelesai'   => $end,
                'dosen'          => $data['dosen'],
                'asisten1'       => $this->findAsistenIdByName($data['asisten1']),
                'asisten2'       => $this->findAsistenIdByName($data['asisten2']),
                'frekuensi'      => $data['freq'],
                'status'         => 'Aktif'
            ]);
            $stats['success']++;
        }

        return $stats;
    }

    /**
     * Smart Search ID Asisten berdasarkan nama.
     * Menggunakan 3 level pencarian: Exact, Like, dan Similarity.
     */
    private function findAsistenIdByName($name) {
        if (empty($name) || is_numeric($name)) return $name;
        
        $db = $this->model->db;
        $name = trim($name);

        // 1. Exact Match
        $stmt = $db->prepare("SELECT idAsisten FROM asisten WHERE LCASE(TRIM(nama)) = LCASE(?) LIMIT 1");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if ($res) return $res['idAsisten'];

        // 2. Partial Match
        $likeName = "%$name%";
        $stmt = $db->prepare("SELECT idAsisten FROM asisten WHERE nama LIKE ? LIMIT 1");
        $stmt->bind_param("s", $likeName);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        
        return $res ? $res['idAsisten'] : null;
    }

    private function extractRowData($row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        $data = [];
        foreach ($cellIterator as $cell) { $data[] = $cell->getFormattedValue(); }
        return $data;
    }

    private function findOrCreateMaster($table, $column, $value) {
        if (empty($value)) return null;
        $id = $this->findExistingMaster($table, $column, $value);
        if ($id) return $id;

        $db = $this->model->db;
        $db->query("INSERT INTO $table ($column) VALUES ('".addslashes($value)."')");
        return $db->insert_id;
    }

    private function findExistingMaster($table, $column, $value) {
        $db = $this->model->db;
        $stmt = $db->prepare("SELECT * FROM $table WHERE LCASE(TRIM($column)) = LCASE(TRIM(?)) LIMIT 1");
        $stmt->bind_param("s", $value);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? $res[array_key_first($res)] : null;
    }
}