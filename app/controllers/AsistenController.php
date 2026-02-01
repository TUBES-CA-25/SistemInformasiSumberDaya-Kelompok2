<?php

/**
 * AsistenController - Kelola Data Asisten & Tampilan
 * 
 * Menangani:
 * - Daftar dan detail asisten publik
 * - Operasi CRUD asisten untuk admin
 * - Upload dan proses foto dengan fallback UI Avatars
 * - Pengelompokan asisten (Koordinator, Asisten, Calon Asisten, Alumni)
 * - Endpoint API untuk pengambilan data asisten
 * - Penugasan dan pengelolaan koordinator
 * 
 * Tabel Database: asisten
 * Kunci Utama: idAsisten
 * Direktori Foto: public/assets/uploads/asisten/
 */

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/AsistenModel.php';
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class AsistenController extends Controller {
    // =========================================================================
    // BAGIAN 1: PROPERTI
    // =========================================================================
    
    /** @var AsistenModel Instance model untuk operasi data asisten */
    private $model;

    
    // =========================================================================
    // BAGIAN 2: KONSTRUKTOR
    // =========================================================================
    
    /**
     * Inisialisasi AsistenController dengan AsistenModel
     */
    public function __construct() {
        $this->model = new AsistenModel();
    }

    
    // =========================================================================
    // BAGIAN 3: RUTE PUBLIK
    // =========================================================================
    
    /**
     * Indeks Publik - Tampilkan semua asisten dikelompokkan berdasarkan kategori
     * 
     * Mengambil semua data asisten dan mengorganisirnya berdasarkan:
     * - Koordinator (isKoordinator = 1)
     * - Asisten Praktikum (status default)
     * - Calon Asisten (CA atau statusAktif mengandung 'calon')
     * - Alumni (statusAktif = 'alumni')
     * 
     * Setiap record asisten mencakup URL foto yang diproses dengan fallback ke UI Avatars
     * 
     * @param array $params Parameter rute (tidak digunakan)
     * @return void Menampilkan view sumberdaya/asisten
     */
    public function index($params = []) {
        // Ambil semua record asisten
        $all_data = $this->model->getAll();
        
        // Inisialisasi kontainer pengelompokan
        $koordinator_list = [];
        $asisten_list = [];
        $ca_list = [];
        $alumni_list = [];

        // Proses dan kategorisasi setiap record asisten
        if (!empty($all_data)) {
            foreach ($all_data as $row) {
                // Proses URL foto dengan logika fallback
                $row['foto_url'] = $this->processPhotoUrl($row);
                
                // Normalisasi dan ekstrak status dan flag koordinator
                $status = strtolower($row['statusAktif'] ?? '');
                $isCoord = $row['isKoordinator'] ?? 0;

                // Kategorisasi berdasarkan status dan flag koordinator
                if ($isCoord == 1) {
                    $koordinator_list[] = $row;
                } elseif ($status == 'ca' || strpos($status, 'calon') !== false) {
                    $ca_list[] = $row;
                } elseif ($status == 'alumni') {
                    $alumni_list[] = $row;
                } else {
                    // Default: Asisten Praktikum
                    $asisten_list[] = $row;
                }
            }
        }

        // Siapkan data view dengan kategori yang terorganisir
        $data = [
            'judul'       => 'Asisten Laboratorium',
            'koordinator' => $koordinator_list,
            'asisten'     => $asisten_list,
            'ca'          => $ca_list,
            'alumni'      => $alumni_list
        ];

        $this->view('sumberdaya/asisten', $data);
    }

    /**
     * Detail Publik - Tampilkan informasi rinci untuk satu asisten
     * 
     * Mengambil data asisten berdasarkan ID dan memproses skills dari format JSON.
     * Memproses URL foto dengan fallback ke UI Avatars.
     * Redirect ke /asisten jika asisten tidak ditemukan.
     * 
     * @param array $params Parameter rute dengan kunci 'id'
     * @return void Menampilkan view sumberdaya/detail atau redirect
     */
    public function detail($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/asisten');
            return;
        }

        // Ambil asisten berdasarkan ID
        $asisten = $this->model->getById($id);
        if (!$asisten) {
            $this->redirect('/asisten');
            return;
        }

        // Proses URL foto
        $asisten['foto_url'] = $this->processPhotoUrl($asisten);
        
        // Bersihkan array skills dari format JSON/serialized
        $skillsRaw = $asisten['skills'] ?? '[]';
        $skillsList = json_decode($skillsRaw, true);
        if (!is_array($skillsList)) {
            $skillsList = array_map('trim', explode(',', str_replace(['[', ']', '"'], '', $skillsRaw)));
        }
        $asisten['skills_list'] = array_filter($skillsList);

        $this->view('sumberdaya/detail', ['id' => $id, 'asisten' => $asisten]);
    }

    
    // =========================================================================
    // BAGIAN 4: RUTE ADMIN (Operasi CRUD)
    // =========================================================================
    
    /**
     * Indeks Admin - Tampilkan tabel manajemen asisten
     * 
     * Menampilkan semua record asisten dalam tabel admin untuk operasi CRUD.
     * 
     * @param array $params Parameter rute (tidak digunakan)
     * @return void Menampilkan view admin/asisten/index
     */
    public function adminIndex($params = []) {
        $data = $this->model->getAllForAdmin();
        $this->view('admin/asisten/index', ['asisten' => $data]);
    }

    /**
     * Form Buat - Tampilkan form untuk entri asisten baru
     * 
     * Menampilkan form kosong untuk menambah record asisten baru.
     * 
     * @param array $params Parameter rute (tidak digunakan)
     * @return void Menampilkan view admin/asisten/form
     */
    public function create($params = []) {
        $this->view('admin/asisten/form', ['asisten' => null, 'action' => 'create']);
    }

    /**
     * Form Edit - Tampilkan form untuk edit asisten yang ada
     * 
     * Memuat data asisten dan isi form dengan nilai yang ada.
     * Memproses skills dari JSON ke format array untuk tampilan form.
     * Redirect ke indeks admin jika asisten tidak ditemukan.
     * 
     * @param array $params Parameter rute dengan kunci 'id'
     * @return void Menampilkan view admin/asisten/form atau redirect
     */
    public function edit($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/asisten');
            return;
        }
        
        $asisten = $this->model->getById($id);
        if (!$asisten) {
            $this->setFlash('error', 'Data asisten tidak ditemukan');
            $this->redirect('/admin/asisten');
            return;
        }
        
        // Dekode skills untuk editing form
        $asisten['skills_array'] = $this->cleanSkillsArray($asisten['skills'] ?? '[]');
        
        $this->view('admin/asisten/form', ['asisten' => $asisten, 'action' => 'edit']);
    }

    /**
     * Simpan - Buat record asisten baru
     * 
     * Validasi field wajib (nama, email)
     * Normalisasi skills dari string/array ke format JSON
     * Tangani upload foto dengan validasi (jpg, jpeg, png, gif)
     * Generate nama file unik via Helper
     * Kembalikan respons JSON dengan status
     * 
     * @return void Kembalikan JSON dengan respons sukses/error
     */
    public function store() {
        try {
            // Kumpulkan dan sanitasi input
            $input = [
                'nama' => $_POST['nama'] ?? '',
                'email' => $_POST['email'] ?? '',
                'jurusan' => $_POST['jurusan'] ?? '',
                'bio' => $_POST['bio'] ?? '',
                'skills' => isset($_POST['skills']) ? $_POST['skills'] : '[]',
                'statusAktif' => $_POST['statusAktif'] ?? 'Asisten',
                'isKoordinator' => $_POST['isKoordinator'] ?? '0'
            ];

            // Validasi field wajib
            if (empty($input['nama']) || empty($input['email'])) {
                $this->error('Field Nama dan Email wajib diisi', null, 400);
                return;
            }

            // Normalisasi skills ke format JSON
            $input['skills'] = $this->normalizeSkillsJson($input['skills']);

            // Tangani upload foto jika disediakan
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->handlePhotoUpload('asisten', $input['nama']);
                if ($uploadResult['status'] !== 'success') {
                    $this->error($uploadResult['message'], null, $uploadResult['code']);
                    return;
                }
                $input['foto'] = $uploadResult['filename'];
            }

            // Masukkan record ke database
            $result = $this->model->insert($input);
            if ($result) {
                $this->success(['id' => $this->model->getLastInsertId()], 'Asisten berhasil ditambahkan', 201);
            } else {
                $this->error('Gagal menambahkan asisten', null, 500);
            }
        } catch (Exception $e) {
            error_log('Asisten store error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Perbarui - Modifikasi record asisten yang ada
     * 
     * Validasi asisten ada sebelum update
     * Validasi field wajib
     * Normalisasi skills ke format JSON
     * Tangani upload foto jika foto baru disediakan (hapus foto lama)
     * Perbarui record database
     * 
     * @param array $params Parameter rute dengan kunci 'id'
     * @return void Kembalikan JSON dengan respons sukses/error
     */
    public function update($params) {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                $this->error('ID asisten tidak ditemukan', null, 400);
                return;
            }

            // Ambil record yang ada
            $asisten = $this->model->getById($id);
            if (!$asisten) {
                $this->error('Asisten tidak ditemukan', null, 404);
                return;
            }

            // Kumpulkan dan sanitasi input
            $input = [
                'nama' => $_POST['nama'] ?? '',
                'email' => $_POST['email'] ?? '',
                'jurusan' => $_POST['jurusan'] ?? '',
                'bio' => $_POST['bio'] ?? '',
                'skills' => isset($_POST['skills']) ? $_POST['skills'] : '[]',
                'statusAktif' => $_POST['statusAktif'] ?? 'Asisten',
                'isKoordinator' => $_POST['isKoordinator'] ?? '0'
            ];

            // Validasi field wajib
            if (empty($input['nama']) || empty($input['email'])) {
                $this->error('Field Nama dan Email wajib diisi', null, 400);
                return;
            }

            // Normalisasi skills ke format JSON
            $input['skills'] = $this->normalizeSkillsJson($input['skills']);

            // Tangani upload foto jika disediakan
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                // Hapus foto lama jika ada
                if (!empty($asisten['foto'])) {
                    $this->deletePhotoFile($asisten['foto']);
                }
                
                // Upload foto baru
                $uploadResult = $this->handlePhotoUpload('asisten', $input['nama']);
                if ($uploadResult['status'] !== 'success') {
                    $this->error($uploadResult['message'], null, $uploadResult['code']);
                    return;
                }
                $input['foto'] = $uploadResult['filename'];
            }

            // Perbarui record di database
            $result = $this->model->update($id, $input, 'idAsisten');
            if ($result) {
                $this->success(null, 'Asisten berhasil diupdate', 200);
            } else {
                $this->error('Gagal mengupdate asisten', null, 500);
            }
        } catch (Exception $e) {
            error_log('Asisten update error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Hapus - Hapus record asisten dan foto terkait
     * 
     * Validasi asisten ada sebelum penghapusan
     * Hapus file foto terkait dari filesystem
     * Hapus record dari database
     * 
     * @param array $params Parameter rute dengan kunci 'id'
     * @return void Kembalikan JSON dengan respons sukses/error
     */
    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID tidak ditemukan', null, 400);
            return;
        }

        // Ambil record asisten
        $asisten = $this->model->getById($id);
        if (!$asisten) {
            $this->error('Asisten tidak ditemukan', null, 404);
            return;
        }

        // Hapus file foto yang terkait
        if (!empty($asisten['foto'])) {
            $this->deletePhotoFile($asisten['foto']);
        }

        // Hapus record database
        $result = $this->model->delete($id, 'idAsisten');
        if ($result) {
            $this->success(null, 'Asisten berhasil dihapus', 200);
        } else {
            $this->error('Gagal menghapus asisten', null, 500);
        }
    }

    /**
     * Pilih Koordinator - Tampilkan antarmuka pemilihan koordinator
     * 
     * Daftar semua asisten untuk penugasan koordinator.
     * 
     * @param array $params Parameter rute (tidak digunakan)
     * @return void Menampilkan view admin/asisten/pilih-koordinator
     */
    public function pilihKoordinator($params = []) {
        $data = $this->model->getAllForAdmin();
        $this->view('admin/asisten/pilih-koordinator', ['asisten' => $data]);
    }

    /**
     * Atur Koordinator - Berikan peran koordinator
     * 
     * Reset semua flag koordinator dan atur asisten yang ditentukan sebagai koordinator.
     * Hanya satu koordinator yang dapat aktif pada satu waktu.
     * 
     * @param array $params Parameter rute dengan kunci 'id'
     * @return void Kembalikan JSON dengan respons sukses/error
     */
    public function setKoordinator($params) {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                $this->error('ID tidak ditemukan', null, 400);
                return;
            }

            // Reset semua koordinator dan atur yang baru
            $this->model->resetAllKoordinator();
            $result = $this->model->update($id, ['isKoordinator' => 1], 'idAsisten');
            
            if ($result) {
                $this->success(null, 'Koordinator berhasil diperbarui', 200);
            } else {
                $this->error('Gagal mengupdate koordinator', null, 500);
            }
        } catch (Exception $e) {
            error_log('Set Koordinator error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    
    // =========================================================================
    // BAGIAN 5: RUTE API
    // =========================================================================
    
    /**
     * Indeks API - Kembalikan semua asisten sebagai JSON
     * 
     * Kembalikan daftar record asisten berpaginasi atau lengkap dalam format JSON.
     * 
     * @return void Kembalikan JSON dengan array data asisten
     */
    public function apiIndex() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Asisten retrieved successfully');
    }

    /**
     * Tampilkan API - Kembalikan satu asisten sebagai JSON
     * 
     * Kembalikan record asisten rinci berdasarkan ID dalam format JSON.
     * Termasuk URL foto yang diproses.
     * Kembalikan 404 jika asisten tidak ditemukan.
     * 
     * @param array $params Parameter rute dengan kunci 'id'
     * @return void Kembalikan JSON dengan data asisten atau error
     */
    public function apiShow($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID asisten tidak ditemukan', null, 400);
            return;
        }

        $data = $this->model->getById($id);
        if (!$data) {
            $this->error('Asisten tidak ditemukan', null, 404);
            return;
        }
        
        // Proses URL foto dengan fallback
        $data['foto_url'] = $this->processPhotoUrl($data);
        $this->success($data, 'Asisten berhasil diambil');
    }

    
    // =========================================================================
    // BAGIAN 6: METODE PEMBANTU PRIVAT
    // =========================================================================
    
    /**
     * Proses URL Foto - Kembalikan URL gambar yang tepat untuk asisten
     * 
     * Menentukan prioritas sumber gambar:
     * 1. URL Eksternal (berisi 'http')
     * 2. Upload lokal (public/assets/uploads/asisten/)
     * 3. Gambar legacy (public/images/asisten/)
     * 4. Fallback UI Avatars (https://ui-avatars.com/api/)
     * 
     * @param array $row Record asisten dengan kunci 'foto' dan 'nama'
     * @return string URL gambar lengkap untuk tampilan
     */
    private function processPhotoUrl($row) {
        $fotoName = $row['foto'] ?? '';
        $namaEnc = urlencode($row['nama'] ?? 'User');
        
        // Default: UI Avatars dengan placeholder nama
        $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=eff6ff&color=2563eb&size=256&bold=true";

        // Periksa foto non-default
        if (!empty($fotoName) && strpos($fotoName, 'ui-avatars') === false) {
            // Kasus 1: URL eksternal (berisi http/https)
            if (strpos($fotoName, 'http') !== false) {
                $imgUrl = $fotoName;
            } else {
                // Kasus 2: Path file lokal
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

    /**
     * Bersihkan Array Skills - Konversi skills serialized/JSON ke format array
     * 
     * Menangani multiple format input:
     * - Array JSON: ["skill1","skill2"]
     * - Serialized dengan bracket: ["skill1","skill2"]
     * - String terpisah koma: skill1, skill2
     * 
     * Kembalikan array bersih dari string skill yang dipotong, filter nilai kosong.
     * 
     * @param string $skillsRaw Data skills mentah dari database atau form
     * @return array Array string skill yang bersih
     */
    private function cleanSkillsArray($skillsRaw) {
        $skillsArr = json_decode($skillsRaw, true);
        if (!is_array($skillsArr)) {
            $skillsArr = array_map('trim', explode(',', str_replace(['[', ']', '"'], '', $skillsRaw)));
        }
        return $skillsArr;
    }

    /**
     * Normalisasi Skills JSON - Konversi skills ke format JSON untuk penyimpanan database
     * 
     * Menangani multiple format input:
     * - String: "skill1, skill2"
     * - Array: ['skill1', 'skill2']
     * - JSON: ["skill1","skill2"]
     * 
     * Kembalikan array yang dikodekan JSON aman untuk penyimpanan database.
     * 
     * @param mixed $skills Data skills dari input form
     * @return string Array skills yang dikodekan JSON
     */
    private function normalizeSkillsJson($skills) {
        if (!empty($skills) && !is_array($skills) && (is_string($skills) && $skills[0] !== '[')) {
            // Konversi string terpisah koma ke array
            $skillsArray = array_map('trim', explode(',', $skills));
            return json_encode($skillsArray);
        } elseif (is_array($skills)) {
            return json_encode($skills);
        }
        return $skills;
    }

    /**
     * Tangani Upload Foto - Proses dan simpan file foto yang diupload
     * 
     * Validasi:
     * - Ekstensi file (hanya jpg, jpeg, png, gif)
     * - Buat direktori upload jika diperlukan
     * - Generate nama file unik via Helper
     * - Pindahkan file ke public/assets/uploads/{folderName}/
     * 
     * Kembalikan array status dengan:
     * - status: 'success' atau 'error'
     * - filename: path relatif ke uploads/ (misal 'asisten/file_xxx.jpg')
     * - message: pesan error jika gagal
     * - code: kode status HTTP
     * 
     * @param string $folderName Subfolder dalam uploads/ (misal 'asisten')
     * @param string $identifier Nama untuk generasi filename (biasanya nama asisten)
     * @return array Array status dengan hasil upload
     */
    private function handlePhotoUpload($folderName, $identifier) {
        $subFolder = $folderName . '/';
        $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/' . $subFolder;
        
        // Buat direktori jika tidak ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Validasi ekstensi file
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($ext, $allowedExts)) {
            return [
                'status' => 'error',
                'message' => 'Format file tidak didukung. Gunakan: jpg, jpeg, png, gif',
                'code' => 400
            ];
        }
        
        // Generate nama file unik dan pindahkan file
        $filename = Helper::generateFilename($folderName, $identifier, $ext);
        $destination = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $destination)) {
            return [
                'status' => 'success',
                'filename' => $subFolder . $filename
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Gagal mengupload file. Periksa permission folder uploads',
                'code' => 500
            ];
        }
    }

    /**
     * Hapus File Foto - Hapus foto dari filesystem
     * 
     * Dengan aman menghapus file foto dari direktori public/assets/uploads/.
     * Gagal senyap jika file tidak ada (tidak throw exception).
     * 
     * @param string $filePath Path relatif ke file (misal 'asisten/photo_xxx.jpg')
     * @return void
     */
    private function deletePhotoFile($filePath) {
        $fullPath = dirname(__DIR__, 2) . '/public/assets/uploads/' . $filePath;
        if (file_exists($fullPath) && is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}
?>