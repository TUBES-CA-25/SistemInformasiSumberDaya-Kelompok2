<?php

/**
 * DetailSumberDayaController - Tampilkan Detail Sumber Daya (Asisten & Manajemen)
 * 
 * Menangani:
 * - Tampilan detail asisten laboratorium
 * - Tampilan detail staf manajemen/kepala laboratorium
 * - Dynamic content rendering berdasarkan type parameter
 * - Data enrichment: foto, jabatan, kategori, skills, bio
 * - Photo processing dengan fallback UI Avatars
 * 
 * Type Support:
 * - 'asisten': Detail asisten laboratorium dengan role tracking
 * - 'manajemen': Detail kepala/staf laboratorium dengan manual bio override
 * 
 * Models:
 * - AsistenModel: Fetch data asisten dengan role detection
 * - ManajemenModel: Fetch data manajemen/kepala
 */

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/AsistenModel.php';
require_once ROOT_PROJECT . '/app/models/ManajemenModel.php';
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class DetailSumberDayaController extends Controller {
    // =========================================================================
    // BAGIAN 1: PROPERTI
    // =========================================================================
    
    /** @var AsistenModel Model untuk fetch data asisten */
    private $asistenModel;
    
    /** @var ManajemenModel Model untuk fetch data manajemen/kepala */
    private $manajemenModel;

    
    // =========================================================================
    // BAGIAN 2: KONSTRUKTOR
    // =========================================================================
    
    /**
     * Inisialisasi DetailSumberDayaController dengan models
     */
    public function __construct() {
        $this->asistenModel = new AsistenModel();
        $this->manajemenModel = new ManajemenModel();
    }

    
    // =========================================================================
    // BAGIAN 3: RUTE PUBLIK
    // =========================================================================
    
    /**
     * Index - Tampilkan detail sumber daya berdasarkan type
     * 
     * Entry point utama untuk menampilkan detail page.
     * 
     * Parameter:
     * - id: ID sumber daya (wajib ada)
     * - type: Tipe sumber daya ('asisten' atau 'manajemen', default: 'asisten')
     * 
     * Flow:
     * 1. Ambil parameter id dan type dari $params atau $_GET
     * 2. Validasi id tidak kosong (redirect jika kosong)
     * 3. Berdasarkan type, panggil method getter yang sesuai
     * 4. Validasi data ditemukan (redirect jika tidak ada)
     * 5. Kirim ke view dengan data yang sudah di-enrich
     * 
     * @param array $params Parameter dari router dengan keys 'id' dan 'type'
     * @return void Menampilkan view sumberdaya/detail atau redirect
     */
    public function index($params = []) {
        // Ambil parameter id dan type
        $id = $params['id'] ?? $_GET['id'] ?? null;
        $type = $params['type'] ?? $_GET['type'] ?? 'asisten'; // Default ke asisten

        // Validasi ID tidak kosong
        if (!$id) {
            $this->redirect('/asisten'); 
            return;
        }

        $dataDetail = null;

        // Pilih method getter berdasarkan type
        if ($type === 'manajemen') {
            $dataDetail = $this->getManajemenDetail($id);
        } else {
            $dataDetail = $this->getAsistenDetail($id);
        }

        // Validasi data ditemukan
        if (!$dataDetail) {
            $redirectTarget = ($type === 'manajemen') ? '/kepala' : '/asisten';
            $this->redirect($redirectTarget);
            return;
        }

        // Kirim ke view yang sama dengan data yang sudah di-enrich
        $this->view('sumberdaya/detail', ['dataDetail' => $dataDetail]);
    }

    
    // =========================================================================
    // BAGIAN 4: PRIVATE DETAIL GETTERS
    // =========================================================================
    
    /**
     * Get Asisten Detail - Ambil dan enrich data detail asisten
     * 
     * Proses:
     * 1. Fetch asisten dari database berdasarkan ID
     * 2. Tentukan jabatan berdasarkan isKoordinator dan statusAktif
     * 3. Parse skills dari JSON/serialized format
     * 4. Proses foto URL dengan fallback
     * 5. Return data terformat untuk view
     * 
     * Role Detection:
     * - isKoordinator = 1 → Koordinator Laboratorium (badge-coord)
     * - statusAktif contains 'calon' → Calon Asisten (badge-ca)
     * - Default → Asisten Praktikum (badge-asisten)
     * 
     * Skills Default:
     * - Jika skills kosong/invalid, gunakan default ['Teaching', 'Mentoring']
     * 
     * @param string $id ID asisten (idAsisten)
     * @return array|null Data detail asisten terformat atau null
     */
    private function getAsistenDetail($id) {
        // Fetch asisten dari database
        $asisten = $this->asistenModel->getById($id, 'idAsisten');
        
        if (!$asisten) {
            return null;
        }

        // LANGKAH 1: Tentukan jabatan dan kategori berdasarkan role
        $status = strtolower($asisten['statusAktif'] ?? '');
        $isCoord = $asisten['isKoordinator'] ?? 0;
        
        $jabatan = 'Asisten Praktikum';
        $kategori = 'Asisten Laboratorium';
        $badgeClass = 'badge-asisten';

        // Role detection logic
        if ($isCoord == 1) {
            $jabatan = 'Koordinator Laboratorium';
            $kategori = 'Koordinator';
            $badgeClass = 'badge-coord';
        } elseif ($status == 'ca' || strpos($status, 'calon') !== false) {
            $jabatan = 'Calon Asisten (CA)';
            $kategori = 'Calon Asisten';
            $badgeClass = 'badge-ca';
        }

        // LANGKAH 2: Parse skills dari berbagai format
        $skillsRaw = $asisten['skills'] ?? '[]';
        $skillsList = json_decode($skillsRaw, true);
        if (!is_array($skillsList)) {
            $skillsList = array_map('trim', explode(',', str_replace(['[', ']', '"'], '', $skillsRaw)));
        }
        $skillsList = array_filter($skillsList);
        // Gunakan default jika skills kosong
        if (empty($skillsList)) { 
            $skillsList = ['Teaching', 'Mentoring']; 
        }

        // LANGKAH 3: Return data terformat untuk view
        return [
            'nama'      => $asisten['nama'] ?? 'Tanpa Nama',
            'jabatan'   => $jabatan,
            'kategori'  => $kategori,
            'sub_info'  => $asisten['jurusan'] ?? 'Teknik Informatika',
            'sub_icon'  => 'ri-graduation-cap-line',
            'foto_url'  => $this->processPhotoUrl($asisten['foto'] ?? '', $asisten['nama']),
            'email'     => $asisten['email'] ?? '-',
            'bio'       => !empty($asisten['bio']) ? $asisten['bio'] : "Mahasiswa aktif dan asisten laboratorium.",
            'skills'    => $skillsList,
            'badge_style' => $badgeClass,
            'back_link' => PUBLIC_URL . '/asisten'
        ];
    }

    /**
     * Get Manajemen Detail - Ambil dan enrich data detail manajemen/kepala
     * 
     * Proses:
     * 1. Fetch manajemen dari database berdasarkan ID
     * 2. Deteksi adalah Kepala atau Staff biasa
     * 3. Apply manual bio override untuk nama-nama tertentu
     * 4. Proses foto URL dengan fallback
     * 5. Return data terformat untuk view
     * 
     * Manual Bio Override:
     * Jika field 'tentang' kosong, gunakan hardcoded bio berdasarkan nama:
     * - Abdul Rachman → Kepala Jaringan & Pemrograman
     * - Huzain Azis → Kepala Komputasi Dasar
     * - Herdianti → Kepala Laboratorium Riset
     * - Fatimah → Laboran Fakultas Ilmu Komputer
     * 
     * @param string $id ID manajemen (idManajemen)
     * @return array|null Data detail manajemen terformat atau null
     */
    private function getManajemenDetail($id) {
        // Fetch manajemen dari database
        $row = $this->manajemenModel->getById($id);
        
        if (!$row) {
            return null;
        }

        // LANGKAH 1: Deteksi kepala atau staff
        $isKepala = stripos(($row['jabatan'] ?? ''), 'Kepala') !== false;
        
        // LANGKAH 2: Apply manual bio override untuk nama tertentu
        $manualBio = !empty($row['tentang']) ? $row['tentang'] : "Staff/Pimpinan aktif di Laboratorium Fakultas Ilmu Komputer UMI.";
        
        if (empty($row['tentang'])) {
            $nama = $row['nama'] ?? '';
            // Hardcoded bio untuk kepala/staff tertentu
            if (stripos($nama, 'Abdul Rachman') !== false) {
                $manualBio = "Ir. Abdul Rachman Manga', S.Kom., M.T., MTA., MCF adalah Kepala Laboratorium Jaringan Dan Pemrograman. Beliau memiliki kepakaran mendalam di bidang infrastruktur jaringan enterprise.";
            } elseif (stripos($nama, 'Huzain Azis') !== false) {
                $manualBio = "Ir. Huzain Azis, S.Kom., M.Cs. MTA adalah Kepala Laboratorium Komputasi Dasar. Beliau aktif dalam penelitian bidang kecerdasan buatan dan komputasi.";
            } elseif (stripos($nama, 'Herdianti') !== false) {
                $manualBio = "Herdianti, S.Si., M.Eng., MTA. adalah Kepala Laboratorium Riset yang berfokus pada pengembangan penelitian mahasiswa dan inovasi teknologi.";
            } elseif (stripos($nama, 'Fatimah') !== false) {
                $manualBio = "Fatimah AR. Tuasamu, S.Kom., MTA, MOS adalah Laboran di Fakultas Ilmu Komputer Universitas Muslim Indonesia. Beliau bertanggung jawab atas pengelolaan operasional harian laboratorium.";
            }
        }

        // LANGKAH 3: Return data terformat untuk view
        return [
            'nama'      => $row['nama'] ?? 'Tanpa Nama',
            'jabatan'   => $row['jabatan'] ?? '-',
            'kategori'  => $isKepala ? 'Pimpinan' : 'Staff Laboratorium',
            'sub_info'  => !empty($row['nidn']) && $row['nidn'] != '-' ? 'NIDN: ' . $row['nidn'] : 'Fakultas Ilmu Komputer',
            'sub_icon'  => 'ri-id-card-line',
            'foto_url'  => $this->processPhotoUrl($row['foto'] ?? '', $row['nama']),
            'email'     => $row['email'] ?? '-', 
            'bio'       => $manualBio,
            'skills'    => [], // Manajemen tidak punya skills
            'badge_style' => $isKepala ? 'badge-kepala' : 'badge-staff',
            'back_link' => PUBLIC_URL . '/kepala'
        ];
    }

    
    // =========================================================================
    // BAGIAN 5: PRIVATE HELPER METHODS
    // =========================================================================
    
    /**
     * Process Photo URL - Proses URL foto dengan fallback ke UI Avatars
     * 
     * Priority:
     * 1. External URL (contains 'http')
     * 2. Local upload (public/assets/uploads/)
     * 3. Legacy image (public/images/asisten/)
     * 4. UI Avatars fallback dengan nama person
     * 
     * @param string $fotoName Nama file foto atau URL eksternal
     * @param string $nama Nama person untuk UI Avatars placeholder
     * @return string Complete image URL untuk display
     */
    private function processPhotoUrl($fotoName, $nama) {
        $namaEnc = urlencode($nama ?? 'User');
        // Default: UI Avatars placeholder
        $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=eff6ff&color=2563eb&size=256&bold=true";

        // Periksa jika ada foto yang di-set (bukan default UI Avatars)
        if (!empty($fotoName) && strpos($fotoName, 'ui-avatars') === false) {
            // Kasus 1: URL eksternal
            if (strpos($fotoName, 'http') !== false) {
                $imgUrl = $fotoName;
            } else {
                // Kasus 2: Local file paths
                $path1 = ROOT_PROJECT . '/public/assets/uploads/' . $fotoName;
                $path2 = ROOT_PROJECT . '/public/images/asisten/' . $fotoName;

                // Struktur uploads baru
                if (file_exists($path1)) {
                    $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');
                    $imgUrl = $baseUrl . '/assets/uploads/' . $fotoName;
                }
                // Struktur gambar legacy
                elseif (file_exists($path2)) {
                    $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');
                    $imgUrl = $baseUrl . '/images/asisten/' . $fotoName;
                }
            }
        }
        
        return $imgUrl;
    }
}