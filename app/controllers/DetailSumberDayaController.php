<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/AsistenModel.php';
require_once ROOT_PROJECT . '/app/models/ManajemenModel.php'; // Pastikan model ini ada
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class DetailSumberDayaController extends Controller {
    private $asistenModel;
    private $manajemenModel;

    public function __construct() {
        $this->asistenModel = new AsistenModel();
        $this->manajemenModel = new ManajemenModel();
    }

    /**
     * Main Entry Point
     * URL: /index.php?page=detail&type=asisten&id=1
     */
    public function index($params = []) {
        $id = $params['id'] ?? $_GET['id'] ?? null;
        $type = $params['type'] ?? $_GET['type'] ?? 'asisten'; // Default ke asisten

        if (!$id) {
            // Redirect default jika ID tidak ada
            $this->redirect('/asisten'); 
            return;
        }

        $dataDetail = null;

        // Pilih logika berdasarkan tipe
        if ($type === 'manajemen') {
            $dataDetail = $this->getManajemenDetail($id);
        } else {
            $dataDetail = $this->getAsistenDetail($id);
        }

        // Jika data tidak ditemukan setelah dicari
        if (!$dataDetail) {
            // Bisa diarahkan ke 404 atau kembali ke list
            $redirectTarget = ($type === 'manajemen') ? '/kepala' : '/asisten';
            $this->redirect($redirectTarget);
            return;
        }

        // Kirim ke View yang sama
        $this->view('sumberdaya/detail', ['dataDetail' => $dataDetail]);
    }

    /**
     * LOGIC KHUSUS ASISTEN (Private)
     */
    private function getAsistenDetail($id) {
        $asisten = $this->asistenModel->getById($id, 'idAsisten');
        
        if (!$asisten) return null;

        // 1. Tentukan Status & Jabatan
        $status = strtolower($asisten['statusAktif'] ?? '');
        $isCoord = $asisten['isKoordinator'] ?? 0;
        
        $jabatan = 'Asisten Praktikum';
        $kategori = 'Asisten Laboratorium';
        $badgeClass = 'badge-asisten';

        if ($isCoord == 1) {
            $jabatan = 'Koordinator Laboratorium';
            $kategori = 'Koordinator';
            $badgeClass = 'badge-coord';
        } elseif ($status == 'ca' || strpos($status, 'calon') !== false) {
            $jabatan = 'Calon Asisten (CA)';
            $kategori = 'Calon Asisten';
            $badgeClass = 'badge-ca';
        }

        // 2. Parsing Skills
        $skillsRaw = $asisten['skills'] ?? '[]';
        $skillsList = json_decode($skillsRaw, true);
        if (!is_array($skillsList)) {
            $skillsList = array_map('trim', explode(',', str_replace(['[', ']', '"'], '', $skillsRaw)));
        }
        $skillsList = array_filter($skillsList);
        if (empty($skillsList)) { $skillsList = ["-"]; }

        // 3. Return Format Standar View
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
     * LOGIC KHUSUS MANAJEMEN/KEPALA (Private)
     */
    private function getManajemenDetail($id) {
        $row = $this->manajemenModel->getById($id);
        
        if (!$row) return null;

        $isKepala = stripos(($row['jabatan'] ?? ''), 'Kepala') !== false;
        
        // 1. Logic Fallback Bio (Manual Override untuk nama tertentu)
        $manualBio = !empty($row['tentang']) ? $row['tentang'] : "Staff/Pimpinan aktif di Laboratorium Fakultas Ilmu Komputer UMI.";
        
        if (empty($row['tentang'])) {
            $nama = $row['nama'] ?? '';
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

        // 2. Return Format Standar View
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
            'back_link' => PUBLIC_URL . '/kepala' // Link balik ke halaman kepala/manajemen
        ];
    }

    /**
     * Helper Lokal untuk URL Foto
     */
    private function processPhotoUrl($fotoName, $nama) {
        $namaEnc = urlencode($nama ?? 'User');
        $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=eff6ff&color=2563eb&size=256&bold=true";

        if (!empty($fotoName) && strpos($fotoName, 'ui-avatars') === false) {
            if (strpos($fotoName, 'http') !== false) {
                $imgUrl = $fotoName;
            } else {
                $path1 = ROOT_PROJECT . '/public/assets/uploads/' . $fotoName;
                $path2 = ROOT_PROJECT . '/public/images/asisten/' . $fotoName;

                if (file_exists($path1)) {
                    $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');
                    $imgUrl = $baseUrl . '/assets/uploads/' . $fotoName;
                } elseif (file_exists($path2)) {
                    $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');
                    $imgUrl = $baseUrl . '/images/asisten/' . $fotoName;
                }
            }
        }
        return $imgUrl;
    }
}
?>