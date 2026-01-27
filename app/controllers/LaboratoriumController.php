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

    // Admin view methods (TIDAK DIUBAH - Agar Admin Tetap Jalan)
    public function adminIndex($params = []) {
        $data = $this->model->getAll();
        $this->view('admin/laboratorium/index', ['laboratorium' => $data]);
    }

    public function detail($params = []) {
        $id = $params['id'] ?? null;
        
        if (!$id) {
            $this->redirect('/laboratorium');
            return;
        }
        
        // 1. Ambil Detail Laboratorium (Ini sudah benar ada 'idLaboratorium')
        $lab = $this->model->getById($id, 'idLaboratorium');
        
        if (!$lab) {
            $this->view('fasilitas/detail', ['laboratorium' => null]);
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
            // --- PERBAIKAN DI SINI (TAMBAHKAN 'idAsisten') ---
            // Sebelumnya: $this->asistenModel->getById($lab['idKordinatorAsisten']); -> ERROR karena cari 'id'
            // Sekarang: Kita paksa cari 'idAsisten'
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

        // 5. Link Kembali
        $backLink = PUBLIC_URL . '/laboratorium';
        $jenis = isset($lab['jenis']) ? strtolower($lab['jenis']) : '';
        if (strpos($jenis, 'riset') !== false || strpos($jenis, 'research') !== false) {
            $backLink = PUBLIC_URL . '/riset';
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
    // [UPDATED] Menggunakan logika filter yang aman & handling gambar
    public function index() {
        // 1. Ambil SEMUA data mentah dari Model
        // Model boleh pakai MySQLi/PDO, Controller terima array
        $raw_data = $this->model->getAll();
        
        $lab_list = [];
        if (!empty($raw_data)) {
            foreach ($raw_data as $row) {
                // --- FILTER JENIS (ANTI SPASI & CASE INSENSITIVE) ---
                // Trim: Membuang spasi di depan/belakang database (jika ada)
                $jenisDb = trim($row['jenis'] ?? '');

                // Logic: Loloskan jika mengandung kata "Lab" (Contoh: "Laboratorium", "Lab Komputer")
                // stripos: Mencari string tanpa peduli huruf besar/kecil
                if (stripos($jenisDb, 'Lab') !== false) {
                    
                    // --- LOGIC GAMBAR ---
                    $imgSrc = null;
                    if (!empty($row['gambar'])) {
                        // Cek fisik file di server menggunakan ROOT_PROJECT
                        $filePath = ROOT_PROJECT . '/public/assets/uploads/' . $row['gambar'];
                        
                        if (file_exists($filePath)) {
                            // Tentukan Base URL (Gunakan PUBLIC_URL atau ASSETS_URL)
                            $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');
                            $imgSrc = $baseUrl . '/assets/uploads/' . $row['gambar'];
                        }
                    }

                    // --- LOGIC DESKRIPSI ---
                    $descRaw = $row['deskripsi'] ?? '';
                    $shortDesc = strlen($descRaw) > 150 ? substr($descRaw, 0, 150) . '...' : $descRaw;

                    // --- INJEKSI DATA KE VIEW ---
                    $row['img_src'] = $imgSrc;
                    $row['short_desc'] = $shortDesc;
                    
                    $lab_list[] = $row;
                }
            }
        }

        // 2. Kirim ke View dengan key 'laboratorium'
        $data = [
            'judul' => 'Fasilitas Laboratorium',
            'laboratorium' => $lab_list 
        ];

        // 3. Load View
        $this->view('fasilitas/laboratorium', $data);
    }

    // --- METHOD KHUSUS HALAMAN RISET ---
    public function riset() {
        // 1. Ambil Semua Data
        $raw_data = $this->model->getAll();
        
        $riset_list = [];
        if (!empty($raw_data)) {
            foreach ($raw_data as $row) {
                // 2. Filter Jenis: 'Riset' (Anti Spasi & Case Insensitive)
                $jenisDb = trim($row['jenis'] ?? '');
                
                if (strcasecmp($jenisDb, 'Riset') === 0 || stripos($jenisDb, 'Riset') !== false) {
                    
                    // --- LOGIC GAMBAR ---
                    $imgSrc = null;
                    if (!empty($row['gambar'])) {
                        $filePath = ROOT_PROJECT . '/public/assets/uploads/' . $row['gambar'];
                        if (file_exists($filePath)) {
                            $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');
                            $imgSrc = $baseUrl . '/assets/uploads/' . $row['gambar'];
                        }
                    }

                    // --- LOGIC DESKRIPSI ---
                    $descRaw = $row['deskripsi'] ?? '';
                    $shortDesc = strlen($descRaw) > 120 ? substr($descRaw, 0, 120) . '...' : $descRaw;

                    // --- LOGIC STYLE/WARNA (Dipindah dari View ke Controller) ---
                    $n = strtolower($row['nama'] ?? '');
                    $style = ['bg' => '#f8fafc', 'icon' => 'ri-flask-line', 'color' => '#64748b', 'badge_bg' => '#f1f5f9', 'badge_text' => '#475569']; // Default Abu

                    if (strpos($n, 'ai') !== false || strpos($n, 'intelligence') !== false) {
                        $style = ['bg' => '#eff6ff', 'icon' => 'ri-brain-line', 'color' => '#2563eb', 'badge_bg' => '#dbeafe', 'badge_text' => '#1e40af']; // Biru
                    } elseif (strpos($n, 'iot') !== false || strpos($n, 'network') !== false || strpos($n, 'jaringan') !== false) {
                        $style = ['bg' => '#f0fdf4', 'icon' => 'ri-wifi-line', 'color' => '#16a34a', 'badge_bg' => '#dcfce7', 'badge_text' => '#16a34a']; // Hijau
                    } elseif (strpos($n, 'mobile') !== false || strpos($n, 'app') !== false) {
                        $style = ['bg' => '#fff7ed', 'icon' => 'ri-smartphone-line', 'color' => '#ea580c', 'badge_bg' => '#ffedd5', 'badge_text' => '#9a3412']; // Orange
                    }

                    // Masukkan data matang ke array
                    $row['img_src_final'] = $imgSrc;
                    $row['deskripsi_final'] = $shortDesc;
                    $row['style_final'] = $style; // Style  dikirim dari sini
                    
                    $riset_list[] = $row;
                }
            }
        }

        // 3. Kirim ke View dengan key 'riset'
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

    // Store, Update, Delete methods (TIDAK DIUBAH)
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
                        $this->gambarModel->insertImage($lastId, $image['filename'], $image['description'], $isUtama, $index);
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
                        $this->gambarModel->insertImage($id, $image['filename'], $image['description'], $isUtama, $index);
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

        // Cek apakah lab masih digunakan di jadwal praktikum
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
            $gambar = $this->gambarModel->getById($id, 'idGambar');
            if (!$gambar) {
                $this->error('Data gambar tidak ditemukan', null, 404);
                return;
            }

            $filename = $gambar['namaGambar'];
            $rootUploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/';
            $filePath = $rootUploadDir . $filename;
            
            if (file_exists($filePath)) {
                @unlink($filePath);
            } else {
                $baseName = basename($filename);
                if (file_exists($rootUploadDir . $baseName)) {
                    @unlink($rootUploadDir . $baseName);
                }
            }

            $result = $this->gambarModel->deleteImage($id);
            if ($result) {
                $this->success(['id' => $id], 'Gambar berhasil dihapus');
            } else {
                $this->error('Gagal menghapus record gambar', null, 500);
            }
        } catch (Exception $e) {
            $this->error('Terjadi kesalahan: ' . $e->getMessage(), null, 500);
        }
    }
}