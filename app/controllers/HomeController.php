<?php
require_once __DIR__ . '/Controller.php';
// Panggil Model yang baru kita update
require_once ROOT_PROJECT . '/app/models/ManajemenModel.php'; 

class HomeController extends Controller {
    private $model;

    public function __construct() {
        // Instansiasi ManajemenModel
        $this->model = new \ManajemenModel(); 
    }

    public function index() {
        // 1. AMBIL DATA DARI MODEL (Lebih Bersih & Aman)
        $raw_data = $this->model->getAll();

        $kepala_lab_list = [];
        $laboran_list = [];

        // 2. Filter Data & Siapkan Foto
        if (!empty($raw_data)) {
            foreach ($raw_data as $row) {
                // Logic Foto
                $fotoDb = $row['foto'] ?? '';
                $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');
                
                // Default Avatar
                $row['foto_url'] = "https://ui-avatars.com/api/?name=" . urlencode($row['nama']) . "&background=eff6ff&color=2563eb";
                
                // Cek File Foto Asli
                if (!empty($fotoDb)) {
                    $path = ROOT_PROJECT . '/public/assets/uploads/' . $fotoDb;
                    if (file_exists($path)) {
                        $row['foto_url'] = $baseUrl . '/assets/uploads/' . $fotoDb;
                    }
                }

                // Pisahkan Kepala Lab vs Laboran
                // Menggunakan stripos agar 'kepala', 'Kepala', 'KEPALA' semua terbaca
                if (stripos($row['jabatan'] ?? '', 'Kepala') !== false) {
                    $kepala_lab_list[] = $row;
                } else {
                    $laboran_list[] = $row;
                }
            }
        }

        // 3. Kirim Data ke View
        $data = [
            'judul' => 'Beranda - Portal Laboratorium',
            'kepala_lab' => $kepala_lab_list,
            'laboran' => $laboran_list
        ];

        $this->view('home/index', $data);
    }

    // [BARU] Method untuk Halaman Apps
    public function apps() {
        $data['judul'] = 'IC-Labs Apps'; // Judul halaman
        // Memanggil view yang ada di app/views/home/apps.php
        $this->view('home/apps', $data); 
    }
    
}
?>