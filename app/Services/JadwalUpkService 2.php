<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * JadwalUpkService - Logika Bisnis Jadwal UPK
 * * Menangani pengolahan data mentah dari file (Excel/CSV) sebelum disimpan ke database.
 * Memisahkan tanggung jawab pemrosesan data dari Controller.
 */
class JadwalUpkService {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    /**
     * Memproses file Excel (.xlsx / .xls) menjadi array data siap simpan.
     * * @param string $filePath Path file sementara.
     * @return array Data yang telah diformat.
     * @throws Exception Jika file kosong atau gagal dibaca.
     */
    public function parseExcel(string $filePath): array {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $dataImport = [];

        // Loop baris ke-2 (skip header)
        foreach ($worksheet->getRowIterator(2) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            
            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getFormattedValue();
            }

            // Lewati baris jika semua kolom kosong
            if (empty(array_filter($rowData))) continue;

            // Pemetaan kolom Excel ke struktur database
            $dataImport[] = [
                'prodi'       => trim($rowData[1] ?? ''),
                'tanggal'     => $this->formatDate(trim($rowData[2] ?? '')),
                'jam'         => trim($rowData[3] ?? ''),
                'mata_kuliah' => trim($rowData[4] ?? ''),
                'dosen'       => trim($rowData[5] ?? ''),
                'frekuensi'   => trim($rowData[6] ?? ''),
                'kelas'       => trim($rowData[7] ?? ''),
                'ruangan'     => trim($rowData[8] ?? '')
            ];
        }

        if (empty($dataImport)) {
            throw new Exception("File Excel tidak mengandung data yang valid.");
        }

        return $dataImport;
    }

    /**
     * Helper untuk memastikan format tanggal valid (Y-m-d).
     */
    private function formatDate(string $dateStr): string {
        $timestamp = strtotime($dateStr);
        return $timestamp ? date('Y-m-d', $timestamp) : date('Y-m-d');
    }

    /**
     * Mengelola impor CSV melalui Model.
     */
    public function importCSV(string $filePath): bool {
        return $this->model->importCSV($filePath);
    }
}