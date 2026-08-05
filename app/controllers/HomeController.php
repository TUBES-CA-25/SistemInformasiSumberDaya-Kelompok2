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
        // LANGKAH 1: Fetch & cache data manajemen dan showcase (TTL 1 jam / 3600s)
        $cachedData = Cache::remember('home_index_data', 3600, function() {
            $rawData = $this->model->getAll();
            $kepalLabList = [];
            $laboranList = [];

            if (!empty($rawData)) {
                foreach ($rawData as $row) {
                    $fotoDb = $row['foto'] ?? '';
                    $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');
                    
                    $namaEnc = urlencode($row['nama'] ?? 'User');
                    $row['foto_url'] = "https://ui-avatars.com/api/?name={$namaEnc}&background=eff6ff&color=2563eb&size=256&bold=true";
                    
                    if (!empty($fotoDb)) {
                        $filePath = ROOT_PROJECT . '/public/assets/uploads/' . $fotoDb;
                        if (file_exists($filePath)) {
                            $row['foto_url'] = $baseUrl . '/assets/uploads/' . $fotoDb;
                        }
                    }

                    if (stripos($row['jabatan'] ?? '', 'Kepala') !== false) {
                        $kepalLabList[] = $row;
                    } else {
                        $laboranList[] = $row;
                    }
                }
            }

            // Fetch Showcase Slides
            $showcaseList = [];
            try {
                require_once ROOT_PROJECT . '/app/models/ShowcaseModel.php';
                $showcaseModel = new ShowcaseModel();
                $rawShowcase = $showcaseModel->getAllOrdered();
                $baseUrl = defined('PUBLIC_URL') ? rtrim(PUBLIC_URL, '/') : '';
                foreach ($rawShowcase as $item) {
                    if (isset($item['is_active']) && (int)$item['is_active'] === 0) continue;
                    $imgName = $item['gambar'] ?? '';
                    $item['img_url'] = $baseUrl . '/images/Pusat-Kompetensi.jpg';
                    if (!empty($imgName)) {
                        $uploadPath = ROOT_PROJECT . '/public/assets/uploads/' . $imgName;
                        $imagesPath = ROOT_PROJECT . '/public/images/' . $imgName;
                        if (file_exists($uploadPath)) {
                            $item['img_url'] = $baseUrl . '/assets/uploads/' . $imgName;
                        } else if (file_exists($imagesPath)) {
                            $item['img_url'] = $baseUrl . '/images/' . $imgName;
                        }
                    }
                    $showcaseList[] = $item;
                }
            } catch (\Throwable $e) {}

            return [
                'kepala_lab' => $kepalLabList,
                'laboran' => $laboranList,
                'showcase_list' => $showcaseList
            ];
        });

        // LANGKAH 2: Prepare data untuk view
        $data = array_merge([
            'judul' => 'Beranda - Portal Laboratorium',
        ], $cachedData);

        // LANGKAH 3: Render view dengan data
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