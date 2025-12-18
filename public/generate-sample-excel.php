<?php
/**
 * Script untuk membuat contoh file Excel untuk testing upload jadwal
 */

require_once '../app/config/config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

try {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers
    $headers = [
        'A1' => 'Mata Kuliah',
        'B1' => 'Laboratorium', 
        'C1' => 'Hari',
        'D1' => 'Waktu Mulai',
        'E1' => 'Waktu Selesai',
        'F1' => 'Kelas',
        'G1' => 'Status'
    ];

    foreach ($headers as $cell => $header) {
        $sheet->setCellValue($cell, $header);
    }

    // Add sample data - pastikan data ini sesuai dengan yang ada di database
    $sampleData = [
        ['Pemrograman Web', 'Lab Komputer 1', 'Senin', '08:00', '10:00', 'A', 'Aktif'],
        ['Basis Data', 'Lab Komputer 2', 'Selasa', '10:00', '12:00', 'B', 'Aktif'],
        ['Jaringan Komputer', 'Lab Jaringan', 'Rabu', '13:00', '15:00', 'A', 'Aktif'],
        ['Sistem Operasi', 'Lab Komputer 1', 'Kamis', '08:00', '10:00', 'C', 'Aktif'],
        ['Algoritma', 'Lab Komputer 3', 'Jumat', '10:00', '12:00', 'B', 'Aktif']
    ];

    $row = 2;
    foreach ($sampleData as $data) {
        $col = 'A';
        foreach ($data as $value) {
            $sheet->setCellValue($col . $row, $value);
            $col++;
        }
        $row++;
    }

    // Style headers
    $headerRange = 'A1:G1';
    $sheet->getStyle($headerRange)->getFont()->setBold(true);
    $sheet->getStyle($headerRange)->getFill()
          ->setFillType(Fill::FILL_SOLID)
          ->getStartColor()->setARGB('FFCCCCCC');

    // Auto-size columns
    foreach (range('A', 'G') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Output file
    $writer = new Xlsx($spreadsheet);
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="contoh_jadwal_praktikum.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
    exit;

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>