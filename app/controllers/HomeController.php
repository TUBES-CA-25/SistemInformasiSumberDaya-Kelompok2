<?php

/**
 * HomeController - Halaman Utama Portal Laboratorium
 * 
 * Menangani:
 * - Tampilan home/beranda dengan daftar kepala lab dan laboran
 * - Data enrichment: foto, nama, jabatan dari tabel manajemen
 * - Photo processing dengan fallback UI Avatars
 * - Halaman aplikasi/apps showcase
 * - Pemisahan data kepala lab vs laboran untuk display
 * 
 * Models:
 * - ManajemenModel: Fetch data kepala dan staff laboratorium
 */

require_once __DIR__ . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/ManajemenModel.php';

class HomeController extends Controller {
    // =========================================================================
    // BAGIAN 1: PROPERTI
    // =========================================================================
    
    /** @var ManajemenModel Model untuk data manajemen/kepala/laboran */
    private $model;

    
    // =========================================================================
    // BAGIAN 2: KONSTRUKTOR
    // =========================================================================
    
    /**
     * Inisialisasi HomeController dengan ManajemenModel
     */
    public function __construct() {
        $this->model = new ManajemenModel();
    }

    
    // =========================================================================
    // BAGIAN 3: RUTE PUBLIK
    // =========================================================================
    
    /**
     * Index - Tampilkan halaman beranda/home portal
     * 
     * Menampilkan halaman utama dengan daftar kepala laboratorium dan laboran.
     * Data diambil dari tabel manajemen dan dikelompokkan berdasarkan jabatan.
     * 
     * Flow:
     * 1. Fetch semua data manajemen dari database
     * 2. Loop setiap row dan enrich dengan foto URL
     * 3. Pisahkan data ke dua kategori: kepala_lab dan laboran
     * 4. Tentukan foto: UI Avatars fallback atau foto lokal
     * 5. Pass ke view home/index dengan data terstruktur
     * 
     * Photo Processing Priority:
     * - Cek file lokal di public/assets/uploads/
     * - Fallback ke UI Avatars dengan nama person
     * 
     * Data Structure untuk View:
     * {
     *   "judul": "Beranda - Portal Laboratorium",
     *   "kepala_lab": [...array dengan jabatan Kepala...],
     *   "laboran": [...array dengan jabatan selain Kepala...]
     * }
     * 
     * @return void Menampilkan view home/index
     */
    public function index() {
        // LANGKAH 1: Fetch semua data manajemen dari database
        $rawData = $this->model->getAll();

        // Initialize array untuk pembagian kategori
        $kepalLabList = [];
        $laboranList = [];

        // LANGKAH 2: Loop dan enrich data setiap row
        if (!empty($rawData)) {
            foreach ($rawData as $row) {
                // LANGKAH 3: Tentukan foto URL dengan priority
                $fotoDb = $row['foto'] ?? '';
                $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');
                
                // Default: UI Avatars placeholder dengan nama person
                $namaEnc = urlencode($row['nama'] ?? 'User');
                $row['foto_url'] = "https://ui-avatars.com/api/?name={$namaEnc}&background=eff6ff&color=2563eb&size=256&bold=true";
                
                // Cek jika ada file foto lokal
                if (!empty($fotoDb)) {
                    $filePath = ROOT_PROJECT . '/public/assets/uploads/' . $fotoDb;
                    if (file_exists($filePath)) {
                        $row['foto_url'] = $baseUrl . '/assets/uploads/' . $fotoDb;
                    }
                }

                // LANGKAH 4: Pisahkan ke kategori berdasarkan jabatan
                // Menggunakan stripos agar case-insensitive ('kepala', 'Kepala', 'KEPALA' semua cocok)
                if (stripos($row['jabatan'] ?? '', 'Kepala') !== false) {
                    $kepalLabList[] = $row;
                } else {
                    $laboranList[] = $row;
                }
            }
        }

        // LANGKAH 5: Prepare data untuk view
        $data = [
            'judul' => 'Beranda - Portal Laboratorium',
            'kepala_lab' => $kepalLabList,
            'laboran' => $laboranList
        ];

        // LANGKAH 6: Render view dengan data
        $this->view('home/index', $data);
    }

    /**
     * Apps - Tampilkan halaman showcase aplikasi/fitur
     * 
     * Halaman yang menampilkan daftar aplikasi dan fitur yang tersedia
     * di portal laboratorium dengan deskripsi dan link masing-masing.
     * 
     * @return void Menampilkan view home/apps
     */
    public function apps() {
        // LANGKAH 1: Prepare data untuk view
        $data = [
            'judul' => 'IC-Labs Apps - Aplikasi Portal Laboratorium'
        ];
        
        // LANGKAH 2: Render view dengan data
        $this->view('home/apps', $data);
    }
}
?>