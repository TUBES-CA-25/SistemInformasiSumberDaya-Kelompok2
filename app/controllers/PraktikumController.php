<?php
require_once __DIR__ . '/Controller.php';

// Load Model-Model yang dibutuhkan
require_once __DIR__ . '/../models/PeraturanLabModel.php';
require_once __DIR__ . '/../models/SanksiLabModel.php';
// require_once __DIR__ . '/../models/JadwalModel.php'; // (Jika nanti ada fitur Jadwal)

class PraktikumController extends Controller {
    private $peraturanModel;
    private $sanksiModel;

    public function __construct() {
        // Inisialisasi Model
        $this->peraturanModel = new \PeraturanLabModel();
        $this->sanksiModel = new \SanksiLabModel();
    }

    // ==========================================================
    // HALAMAN TATA TERTIB & SANKSI
    // ==========================================================
    public function tatatertib() {
        // 1. Ambil Data Mentah dari Database
        // (Pastikan kedua Model ini punya method getAll(), nanti kita buat di langkah selanjutnya)
        $rules_raw = $this->peraturanModel->getAll();
        $sanksi_raw = $this->sanksiModel->getAll();
        
        // Base URL untuk aset gambar
        $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');

        // 2. Proses Data Peraturan (Cek Fisik Gambar)
        $rules_data = [];
        if (!empty($rules_raw)) {
            foreach ($rules_raw as $row) {
                $imgDb = $row['gambar'] ?? '';
                $row['img_url'] = ''; // Default kosong (nanti View tampilkan ikon)

                if (!empty($imgDb)) {
                    $path = ROOT_PROJECT . '/public/assets/uploads/' . $imgDb;
                    // Cek apakah file benar-benar ada
                    if (file_exists($path)) {
                        $row['img_url'] = $baseUrl . '/assets/uploads/' . $imgDb;
                    }
                }
                $rules_data[] = $row;
            }
        }

        // 3. Proses Data Sanksi (Cek Fisik Gambar)
        $sanksi_data = [];
        if (!empty($sanksi_raw)) {
            foreach ($sanksi_raw as $row) {
                $imgDb = $row['gambar'] ?? '';
                $row['img_url'] = ''; 

                if (!empty($imgDb)) {
                    $path = ROOT_PROJECT . '/public/assets/uploads/' . $imgDb;
                    if (file_exists($path)) {
                        $row['img_url'] = $baseUrl . '/assets/uploads/' . $imgDb;
                    }
                }
                $sanksi_data[] = $row;
            }
        }

        // 4. Siapkan Data untuk View
        $data = [
            'judul'  => 'Tata Tertib & Sanksi - Laboratorium FIKOM',
            'rules'  => $rules_data,
            'sanksi' => $sanksi_data
        ];

        // 5. Load View
        // Pastikan file view ada di folder: app/views/praktikum/tatatertib.php
        $this->view('praktikum/tatatertib', $data);
    }

    // ==========================================================
    // HALAMAN LAIN (PLACEHOLDER)
    // ==========================================================
    
    // public function jadwal() {
    //     // Nanti diisi logika untuk jadwal
    //     $this->view('praktikum/jadwal');
    // }

    // public function modul() {
    //     // Nanti diisi logika untuk modul
    //     $this->view('praktikum/modul');
    // }
}
?>