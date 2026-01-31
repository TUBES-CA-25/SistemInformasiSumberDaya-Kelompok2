<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/LaboratoriumModel.php';
require_once __DIR__ . '/../models/AsistenModel.php';
require_once __DIR__ . '/../models/LaboratoriumGambarModel.php';
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class LaboratoriumController extends Controller {
    private $model;
    private $asistenModel;
    private $gambarModel;

    public function __construct() {
        $this->model = new \LaboratoriumModel();
        $this->asistenModel = new \AsistenModel();
        $this->gambarModel = new \LaboratoriumGambarModel();
    }

    // API methods (TIDAK DIUBAH)
    public function apiIndex() {
        $data = $this->model->getAll();
        foreach ($data as &$lab) {
            $lab['images'] = $this->gambarModel->getByLaboratorium($lab['idLaboratorium']);
        }
        $this->success($data, 'Data Laboratorium retrieved successfully');
    }

    public function apiShow($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID laboratorium tidak ditemukan', null, 400);
            return;
        }
        $data = $this->model->getById($id, 'idLaboratorium');
        if (!$data) {
            $this->error('Laboratorium tidak ditemukan', null, 404);
            return;
        }
        $data['images'] = $this->gambarModel->getByLaboratorium($id);
        $this->success($data, 'Laboratorium retrieved successfully');
    }

    // Admin view methods (TIDAK DIUBAH)
    public function adminIndex($params = []) {
        $data = $this->model->getAll();
        $this->view('admin/laboratorium/index', ['laboratorium' => $data]);
    }

    /**
     * [FIXED] Method Detail
     * Menambahkan pengecekan $_GET['id'] agar kompatibel dengan routing ?page=...&id=...
     */
    public function detail($params = []) {
        // PERBAIKAN DI SINI:
        // Cek ID dari $params (jika lewat path) ATAU dari $_GET (jika lewat query string)
        $id = $params['id'] ?? $_GET['id'] ?? null;
        
        if (!$id) {
            // Jika ID tidak ada, kembalikan ke list dengan format URL yang benar
            $this->redirect('index.php?page=laboratorium'); 
            return;
        }
        
        // 1. Ambil Detail Laboratorium
        $lab = $this->model->getById($id, 'idLaboratorium');
        
        if (!$lab) {
            // Jika lab tidak ketemu di DB
            $this->redirect('index.php?page=laboratorium');
            return;
        }

        // 2. Ambil Galeri Gambar
        $images = $this->gambarModel->getByLaboratorium($id);
        
        $gallery = [];
        $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');

        if (!empty($lab['gambar'])) {
            $pathUtama = ROOT_PROJECT . '/public/assets/uploads/' . $lab['gambar'];
            if (file_exists($pathUtama)) {
                $gallery[] = [
                    'src' => $baseUrl . '/assets/uploads/' . $lab['gambar'],
                    'desc' => 'Foto Utama'
                ];
            }
        }

        if (!empty($images)) {
            foreach ($images as $img) {
                if ($img['namaGambar'] !== $lab['gambar']) {
                    $pathImg = ROOT_PROJECT . '/public/assets/uploads/' . $img['namaGambar'];
                    if (file_exists($pathImg)) {
                        $gallery[] = [
                            'src' => $baseUrl . '/assets/uploads/' . $img['namaGambar'],
                            'desc' => $img['deskripsiGambar'] ?? ''
                        ];
                    }
                }
            }
        }

        // 3. Ambil Data Koordinator
        $coordName = 'Koordinator Lab';
        $coordEmail = null;
        $coordPhoto = null;
        $initials = 'KL';

        if (!empty($lab['idKordinatorAsisten'])) {
            $asisten = $this->asistenModel->getById($lab['idKordinatorAsisten'], 'idAsisten'); 
            
            if ($asisten) {
                $coordName = $asisten['nama'];
                $coordEmail = $asisten['email'];
                
                if (!empty($asisten['foto'])) {
                    $pathFoto = ROOT_PROJECT . '/public/assets/uploads/' . $asisten['foto'];
                    if (file_exists($pathFoto)) {
                        $coordPhoto = $baseUrl . '/assets/uploads/' . $asisten['foto'];
                    }
                }
            }
        }

        // Initials
        $parts = explode(' ', $coordName);
        $initials = '';
        foreach ($parts as $part) {
            if (!empty($part) && ctype_alpha($part[0])) {
                $initials .= strtoupper($part[0]);
                if (strlen($initials) >= 2) break;
            }
        }

        // 4. Data Hardware dll
        $hardwareData = [
            'Processor' => $lab['processor'] ?? '',
            'RAM'       => $lab['ram'] ?? '',
            'Storage'   => $lab['storage'] ?? '',
            'GPU'       => $lab['gpu'] ?? '',
            'Monitor'   => $lab['monitor'] ?? '',
            'Jumlah PC' => !empty($lab['jumlahPc']) ? $lab['jumlahPc'] . ' Unit' : null,
        ];
        $hardwareData = array_filter($hardwareData, fn($value) => !empty($value));

        $softwareList = !empty($lab['software']) ? array_map('trim', explode(',', $lab['software'])) : [];
        $pendukungList = !empty($lab['fasilitas_pendukung']) ? array_map('trim', explode(',', $lab['fasilitas_pendukung'])) : [];

        // 5. Link Kembali (Disesuaikan dengan format query string)
        $backLink = 'index.php?page=laboratorium';
        $jenis = isset($lab['jenis']) ? strtolower($lab['jenis']) : '';
        if (strpos($jenis, 'riset') !== false || strpos($jenis, 'research') !== false) {
            $backLink = 'index.php?page=riset';
        }

        // 6. Kirim Data
        $data = [
            'judul' => 'Detail ' . ($lab['nama'] ?? 'Fasilitas'),
            'laboratorium' => $lab,
            'gallery' => $gallery,
            'hardware' => $hardwareData,
            'software' => $softwareList,
            'pendukung' => $pendukungList,
            'back_link' => $backLink,
            'koordinator' => [
                'nama' => $coordName,
                'email' => $coordEmail,
                'foto' => $coordPhoto,
                'initials' => $initials
            ]
        ];
        
        $this->view('fasilitas/detail', $data);
    }

    public function create($params = []) {
        $this->view('admin/laboratorium/form', ['laboratorium' => null, 'action' => 'create']);
    }

    public function edit($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/laboratorium');
            return;
        }
        $lab = $this->model->getById($id, 'idLaboratorium');
        if (!$lab) {
            $this->setFlash('error', 'Data laboratorium tidak ditemukan');
            $this->redirect('/admin/laboratorium');
            return;
        }
        $this->view('admin/laboratorium/form', ['laboratorium' => $lab, 'action' => 'edit']);
    }

    // --- WEB VIEW METHODS (PUBLIC) ---
    public function index() {
        $raw_data = $this->model->getAll();
        
        $lab_list = [];
        if (!empty($raw_data)) {
            foreach ($raw_data as $row) {
                $jenisDb = trim($row['jenis'] ?? '');
                if (stripos($jenisDb, 'Lab') !== false) {
                    
                    // --- [FIX] LOGIC PENYELAMAT GAMBAR ---
                    $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');
                    $imgSrc = null;

                    // Cek 1: Apakah ada gambar utama di database?
                    if (!empty($row['gambar'])) {
                        $imgSrc = $baseUrl . '/assets/uploads/' . $row['gambar'];
                    }

                    // Cek 2: Jika gambar utama KOSONG (misal baru dihapus), CARI DARI GALERI!
                    if (empty($imgSrc)) {
                        // Panggil model gambar untuk cari foto apapun milik lab ini
                        $fotoCadangan = $this->gambarModel->getByLaboratorium($row['idLaboratorium']);
                        
                        // Jika ketemu foto lain, ambil yang pertama
                        if (!empty($fotoCadangan) && isset($fotoCadangan[0]['namaGambar'])) {
                            $imgSrc = $baseUrl . '/assets/uploads/' . $fotoCadangan[0]['namaGambar'];
                        }
                    }
                    // ------------------------------------

                    $descRaw = $row['deskripsi'] ?? '';
                    $shortDesc = strlen($descRaw) > 150 ? substr($descRaw, 0, 150) . '...' : $descRaw;

                    $row['img_src'] = $imgSrc;
                    $row['short_desc'] = $shortDesc;
                    
                    $lab_list[] = $row;
                }
            }
        }

        $data = [
            'judul' => 'Fasilitas Laboratorium',
            'laboratorium' => $lab_list 
        ];

        $this->view('fasilitas/laboratorium', $data);
    }

    public function riset() {
        $raw_data = $this->model->getAll();
        
        $riset_list = [];
        if (!empty($raw_data)) {
            foreach ($raw_data as $row) {
                $jenisDb = trim($row['jenis'] ?? '');
                if (strcasecmp($jenisDb, 'Riset') === 0 || stripos($jenisDb, 'Riset') !== false) {
                    
                    // --- [FIX] LOGIC PENYELAMAT GAMBAR ---
                    $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');
                    $imgSrc = null;

                    // Cek 1: Apakah ada gambar utama?
                    if (!empty($row['gambar'])) {
                        $imgSrc = $baseUrl . '/assets/uploads/' . $row['gambar'];
                    }

                    // Cek 2: Jika KOSONG, cari "adiknya" di galeri
                    if (empty($imgSrc)) {
                        $fotoCadangan = $this->gambarModel->getByLaboratorium($row['idLaboratorium']);
                        
                        if (!empty($fotoCadangan) && isset($fotoCadangan[0]['namaGambar'])) {
                            $imgSrc = $baseUrl . '/assets/uploads/' . $fotoCadangan[0]['namaGambar'];
                        }
                    }
                    // ------------------------------------

                    $descRaw = $row['deskripsi'] ?? '';
                    $shortDesc = strlen($descRaw) > 120 ? substr($descRaw, 0, 120) . '...' : $descRaw;

                    $n = strtolower($row['nama'] ?? '');
                    $style = ['bg' => '#f8fafc', 'icon' => 'ri-flask-line', 'color' => '#64748b', 'badge_bg' => '#f1f5f9', 'badge_text' => '#475569'];

                    // (Bagian styling icon tetap sama...)
                    if (strpos($n, 'ai') !== false || strpos($n, 'intelligence') !== false) {
                        $style = ['bg' => '#eff6ff', 'icon' => 'ri-brain-line', 'color' => '#2563eb', 'badge_bg' => '#dbeafe', 'badge_text' => '#1e40af'];
                    } elseif (strpos($n, 'iot') !== false || strpos($n, 'network') !== false || strpos($n, 'jaringan') !== false) {
                        $style = ['bg' => '#f0fdf4', 'icon' => 'ri-wifi-line', 'color' => '#16a34a', 'badge_bg' => '#dcfce7', 'badge_text' => '#16a34a'];
                    } elseif (strpos($n, 'mobile') !== false || strpos($n, 'app') !== false) {
                        $style = ['bg' => '#fff7ed', 'icon' => 'ri-smartphone-line', 'color' => '#ea580c', 'badge_bg' => '#ffedd5', 'badge_text' => '#9a3412'];
                    }

                    $row['img_src_final'] = $imgSrc;
                    $row['deskripsi_final'] = $shortDesc;
                    $row['style_final'] = $style;
                    
                    $riset_list[] = $row;
                }
            }
        }

        $data = [
            'judul' => 'Pusat Riset & Inovasi',
            'riset' => $riset_list 
        ];

        $this->view('fasilitas/riset', $data);
    }

    public function denah() {
        $data['judul'] = 'Denah Lokasi & Tata Letak';
        $this->view('fasilitas/denah', $data);
    }

    public function show($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID laboratorium tidak ditemukan', null, 400);
        }
        $data = $this->model->getById($id, 'idLaboratorium');
        if (!$data) {
            $this->error('Laboratorium tidak ditemukan', null, 404);
        }
        $this->success($data, 'Laboratorium retrieved successfully');
    }

    // Store, Update, Delete methods (TIDAK DIUBAH - Tetap sama seperti sebelumnya)
    public function store() {
        try {
            $input = [
                'nama' => $_POST['nama'] ?? '',
                'jenis' => $_POST['jenis'] ?? 'Laboratorium',
                'deskripsi' => $_POST['deskripsi'] ?? '',
                'jumlahPc' => $_POST['jumlahPc'] ?? 0,
                'kapasitas' => $_POST['kapasitas'] ?? 0,
                'processor' => $_POST['processor'] ?? '',
                'ram' => $_POST['ram'] ?? '',
                'storage' => $_POST['storage'] ?? '',
                'gpu' => $_POST['gpu'] ?? '',
                'monitor' => $_POST['monitor'] ?? '',
                'software' => $_POST['software'] ?? '',
                'fasilitas_pendukung' => $_POST['fasilitas'] ?? '',
                'idKordinatorAsisten' => !empty($_POST['idKordinatorAsisten']) ? $_POST['idKordinatorAsisten'] : null
            ];
            
            if (empty($input['nama'])) {
                $this->error('Nama laboratorium wajib diisi', null, 400);
                return;
            }
            
            $uploadedImages = [];
            if (isset($_FILES['gambar']) && is_array($_FILES['gambar']['name'])) {
                $subFolder = 'laboratorium/';
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/' . $subFolder;
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileCount = count($_FILES['gambar']['name']);
                for ($i = 0; $i < $fileCount; $i++) {
                    if ($_FILES['gambar']['error'][$i] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($_FILES['gambar']['name'][$i], PATHINFO_EXTENSION));
                        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                        
                        if (!in_array($ext, $allowedExts)) {
                            $this->error('Format file tidak didukung pada file ' . $_FILES['gambar']['name'][$i], null, 400);
                            return;
                        }
                        
                        $filename = Helper::generateFilename('lab', $input['nama'], $ext);
                        $target = $uploadDir . $filename;
                        
                        if (move_uploaded_file($_FILES['gambar']['tmp_name'][$i], $target)) {
                            $uploadedImages[] = [
                                'filename' => $subFolder . $filename,
                                'description' => $_POST['gambar_desc'][$i] ?? null
                            ];
                        }
                    }
                }
                if (!empty($uploadedImages)) {
                    $input['gambar'] = $uploadedImages[0]['filename'];
                }
            }
            
            $result = $this->model->insert($input);
            
            if ($result) {
                $lastId = $this->model->getLastInsertId();
                if (!empty($uploadedImages)) {
                    foreach ($uploadedImages as $index => $image) {
                        $isUtama = ($index === 0) ? 1 : 0;
                        $this->gambarModel->insertImage($lastId, $image['filename'], $isUtama, $index);
                    }
                }
                $this->success(['id' => $lastId], 'Laboratorium berhasil ditambahkan', 201);
            } else {
                $this->error('Gagal menambahkan laboratorium', null, 500);
            }
        } catch (Exception $e) {
            error_log('Laboratorium store error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function update($params) {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                $this->error('ID laboratorium tidak ditemukan', null, 400);
                return;
            }

            $existing = $this->model->getById($id, 'idLaboratorium');
            if (!$existing) {
                $this->error('Laboratorium tidak ditemukan', null, 404);
                return;
            }

            $input = [
                'nama' => $_POST['nama'] ?? $existing['nama'],
                'jenis' => $_POST['jenis'] ?? ($existing['jenis'] ?? 'Laboratorium'),
                'deskripsi' => $_POST['deskripsi'] ?? '',
                'jumlahPc' => $_POST['jumlahPc'] ?? 0,
                'kapasitas' => $_POST['kapasitas'] ?? 0,
                'processor' => $_POST['processor'] ?? '',
                'ram' => $_POST['ram'] ?? '',
                'storage' => $_POST['storage'] ?? '',
                'gpu' => $_POST['gpu'] ?? '',
                'monitor' => $_POST['monitor'] ?? '',
                'software' => $_POST['software'] ?? '',
                'fasilitas_pendukung' => $_POST['fasilitas'] ?? '',
                'idKordinatorAsisten' => !empty($_POST['idKordinatorAsisten']) ? $_POST['idKordinatorAsisten'] : null
            ];
            
            if (empty($input['nama'])) {
                $this->error('Nama laboratorium wajib diisi', null, 400);
                return;
            }
            
            $uploadedImages = [];
            if (isset($_FILES['gambar']) && is_array($_FILES['gambar']['name'])) {
                $subFolder = 'laboratorium/';
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/' . $subFolder;
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileCount = count($_FILES['gambar']['name']);
                for ($i = 0; $i < $fileCount; $i++) {
                    if ($_FILES['gambar']['error'][$i] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($_FILES['gambar']['name'][$i], PATHINFO_EXTENSION));
                        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                        if (!in_array($ext, $allowedExts)) {
                            $this->error('Format file tidak didukung', null, 400);
                            return;
                        }
                        $filename = Helper::generateFilename('lab', $input['nama'], $ext);
                        $target = $uploadDir . $filename;
                        if (move_uploaded_file($_FILES['gambar']['tmp_name'][$i], $target)) {
                            $uploadedImages[] = [
                                'filename' => $subFolder . $filename,
                                'description' => $_POST['gambar_desc'][$i] ?? null
                            ];
                        }
                    }
                }
                if (!empty($uploadedImages)) {
                    $input['gambar'] = $uploadedImages[0]['filename'];
                }
            }
            
            $result = $this->model->update($id, $input, 'idLaboratorium');
            
            if ($result) {
                if (!empty($uploadedImages)) {
                    foreach ($uploadedImages as $index => $image) {
                        $isUtama = ($index === 0) ? 1 : 0;
                        $this->gambarModel->insertImage($id, $image['filename'], $isUtama, $index);
                    }
                }
                $this->success([], 'Laboratorium berhasil diupdate', 200);
            } else {
                $this->error('Gagal mengupdate laboratorium', null, 500);
            }
        } catch (Exception $e) {
            error_log('Laboratorium update error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID laboratorium tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idLaboratorium');
        if (!$existing) {
            $this->error('Laboratorium tidak ditemukan', null, 404);
        }

        $db = new Database();
        $mysqli = method_exists($db, 'getConnection') ? $db->getConnection() : $db->getPdo();
        
        if (is_object($mysqli) && get_class($mysqli) === 'mysqli') {
            $stmt = $mysqli->prepare("SELECT COUNT(*) as count FROM jadwalpraktikum WHERE idLaboratorium = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $jadwalCount = $result['count'] ?? 0;
        } else {
            $stmt = $mysqli->prepare("SELECT COUNT(*) as count FROM jadwalpraktikum WHERE idLaboratorium = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $jadwalCount = $result['count'] ?? 0;
        }

        if ($jadwalCount > 0) {
            $this->error("Tidak bisa dihapus karena digunakan dalam jadwal praktikum.", null, 400);
            return;
        }

        $result = $this->model->delete($id, 'idLaboratorium');
        if ($result) {
            $this->success([], 'Laboratorium deleted successfully');
        }
        $this->error('Failed to delete laboratorium', null, 500);
    }

public function deleteImage($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID gambar tidak ditemukan', null, 400);
            return;
        }

        try {
            // 1. Ambil data gambar yang mau dihapus
            $gambarToDelete = $this->gambarModel->getById($id, 'idGambar');
            if (!$gambarToDelete) {
                $this->error('Data gambar tidak ditemukan', null, 404);
                return;
            }

            $labId = $gambarToDelete['idLaboratorium'];
            $filename = $gambarToDelete['namaGambar'];
            
            // 2. Hapus File Fisik
            $rootUploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/';
            $filePath = $rootUploadDir . $filename;
            
            if (file_exists($filePath)) {
                @unlink($filePath);
            }

            // 3. Hapus Record dari Tabel Galeri
            $result = $this->gambarModel->deleteImage($id);

            if ($result) {
                // --- [LOGIC BARU: AUTO-REPLACE THUMBNAIL] ---
                
                // Ambil data Laboratorium Induk
                $labInduk = $this->model->getById($labId, 'idLaboratorium');

                // Cek: Apakah foto yang barusan dihapus adalah Foto Utama (Thumbnail)?
                if ($labInduk && $labInduk['gambar'] === $filename) {
                    
                    // Cari "Adik-adiknya" (Foto sisa di galeri)
                    $sisaFoto = $this->gambarModel->getByLaboratorium($labId);
                    
                    if (!empty($sisaFoto)) {
                        // AMBIL FOTO PERTAMA DARI SISA YANG ADA
                        $fotoPengganti = $sisaFoto[0]['namaGambar'];
                        
                        // Update Tabel Induk dengan foto baru
                        $this->model->update($labId, ['gambar' => $fotoPengganti], 'idLaboratorium');
                        
                        // Opsional: Set foto pengganti jadi "isUtama = 1" di tabel gambar
                        // $this->gambarModel->update($sisaFoto[0]['idGambar'], ['isUtama' => 1]);
                        
                    } else {
                        // Jika tidak ada sisa foto sama sekali, kosongkan thumbnail
                        $this->model->update($labId, ['gambar' => null], 'idLaboratorium');
                    }
                }
                // ---------------------------------------------

                $this->success(['id' => $id], 'Gambar berhasil dihapus dan thumbnail diperbarui');
            } else {
                $this->error('Gagal menghapus record gambar', null, 500);
            }
        } catch (Exception $e) {
            $this->error('Terjadi kesalahan: ' . $e->getMessage(), null, 500);
        }
    }
}