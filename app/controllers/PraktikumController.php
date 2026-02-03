<?php

/**
 * PraktikumController
 * * Mengelola halaman-halaman yang berkaitan dengan informasi praktikum,
 * khususnya penyajian data Tata Tertib dan Sanksi Laboratorium.
 * * @package App\Controllers
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/PeraturanLabModel.php';
require_once __DIR__ . '/../models/SanksiLabModel.php';

class PraktikumController extends Controller {
    
    /** @var PeraturanLabModel Instance model peraturan */
    private $peraturanModel;

    /** @var SanksiLabModel Instance model sanksi */
    private $sanksiModel;

    /**
     * Konstruktor: Inisialisasi dependensi model yang dibutuhkan.
     */
    public function __construct() {
        $this->peraturanModel = new \PeraturanLabModel();
        $this->sanksiModel = new \SanksiLabModel();
    }

    /**
     * Menampilkan Halaman Tata Tertib & Sanksi.
     * * Mengambil data dari database, memvalidasi keberadaan file gambar secara fisik,
     * dan mengirimkannya ke view terkait.
     * * @return void
     */
    public function tatatertib(): void {
        // 1. Ambil data mentah dari masing-masing model
        $rules_raw  = $this->peraturanModel->getAll();
        $sanksi_raw = $this->sanksiModel->getAll();
        
        // 2. Proses validasi gambar untuk setiap baris data
        // Menggunakan array_map untuk kode yang lebih bersih (cleaner loop)
        $rules_data  = $this->processMediaUrls($rules_raw);
        $sanksi_data = $this->processMediaUrls($sanksi_raw);

        // 3. Siapkan paket data untuk dikirim ke view
        $data = [
            'judul'  => 'Tata Tertib & Sanksi - Laboratorium FIKOM',
            'rules'  => $rules_data,
            'sanksi' => $sanksi_data
        ];

        // 4. Render tampilan
        $this->view('praktikum/tatatertib', $data);
    }

    /**
     * Helper: Memproses URL Media/Gambar.
     * * Melakukan pengecekan fisik file di server. Jika file ada, akan dibuatkan URL lengkap.
     * Jika tidak ada, field img_url akan dikosongkan agar view bisa memberikan fallback (ikon).
     * * 
     * * @param array $dataset Array data hasil fetch dari database.
     * @return array Data yang telah diperkaya dengan properti 'img_url'.
     */
    private function processMediaUrls(array $dataset): array {
        if (empty($dataset)) {
            return [];
        }

        $baseUrl = defined('PUBLIC_URL') ? rtrim(PUBLIC_URL, '/') : '';

        foreach ($dataset as &$row) {
            $imgName = $row['gambar'] ?? '';
            $row['img_url'] = ''; // Default jika gambar tidak ada

            if (!empty($imgName)) {
                $physicalPath = ROOT_PROJECT . '/public/assets/uploads/' . $imgName;
                
                if (file_exists($physicalPath)) {
                    $row['img_url'] = $baseUrl . '/assets/uploads/' . $imgName;
                }
            }
        }

        return $dataset;
    }
}