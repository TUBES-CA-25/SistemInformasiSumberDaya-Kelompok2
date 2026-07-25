<?php

/**
 * ShowcaseController
 * Controller untuk mengelola CRUD Slider Showcase Home di Panel Admin dan API.
 */

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/ShowcaseModel.php';
require_once ROOT_PROJECT . '/app/helpers/ImageOptimizer.php';

class ShowcaseController extends Controller 
{
    private $model;

    public function __construct() {
        $this->model = new \ShowcaseModel();
    }

    /**
     * Helper jsonResponse untuk mengirim response JSON
     */
    protected function jsonResponse($data, int $status = 200): void {
        $this->response($data, $status);
    }

    /**
     * Helper untuk mengoptimalkan dan mengonversi gambar ke WebP
     */
    private function processAndOptimizeImage(string $targetPath): string {
        if (!file_exists($targetPath)) {
            return basename($targetPath);
        }

        // 1. Optimasi dimensi & kualitas jika terlalu besar
        ImageOptimizer::optimize($targetPath, 1920, 1920, 85);

        // 2. Konversi ke WebP jika didukung server GD
        $webpPath = ImageOptimizer::convertToWebp($targetPath, 80);
        if ($webpPath && file_exists($webpPath)) {
            return basename($webpPath);
        }

        return basename($targetPath);
    }

    /**
     * Tampilkan halaman admin manajemen showcase
     */
    public function adminIndex(): void {
        $this->view('admin/showcase/index', [
            'judul' => 'Manajemen Slider Home Showcase - Admin IC-Labs'
        ]);
    }

    /**
     * API: Ambil semua item showcase untuk tabel admin dan home
     */
    public function apiIndex(): void {
        try {
            $raw = $this->model->getAllOrdered();
            $baseUrl = defined('PUBLIC_URL') ? rtrim(PUBLIC_URL, '/') : '';

            foreach ($raw as &$row) {
                // Image utama URL
                $imgName = $row['gambar'] ?? '';
                $row['img_url'] = $baseUrl . '/images/' . ($imgName ?: 'Pusat-Kompetensi.jpg');

                if (!empty($imgName)) {
                    $uploadPath = ROOT_PROJECT . '/public/assets/uploads/' . $imgName;
                    if (file_exists($uploadPath)) {
                        $row['img_url'] = $baseUrl . '/assets/uploads/' . $imgName;
                    }
                }

                // Galeri foto URLs
                $galeriArr = [];
                if (!empty($row['galeri_foto'])) {
                    $decoded = json_decode($row['galeri_foto'], true);
                    if (is_array($decoded)) {
                        foreach ($decoded as $gImg) {
                            if (empty($gImg)) continue;
                            $gUrl = $baseUrl . '/images/' . $gImg;
                            $gPath = ROOT_PROJECT . '/public/assets/uploads/' . $gImg;
                            if (file_exists($gPath)) {
                                $gUrl = $baseUrl . '/assets/uploads/' . $gImg;
                            }
                            $galeriArr[] = $gUrl;
                        }
                    }
                }

                // Fallback jika galeri kosong, masukkan gambar utama
                if (empty($galeriArr) && !empty($row['img_url'])) {
                    $galeriArr[] = $row['img_url'];
                }

                $row['galeri_urls'] = $galeriArr;
            }

            $this->jsonResponse(['status' => 'success', 'data' => $raw]);
        } catch (\Throwable $e) {
            error_log("SHOWCASE_API_INDEX_ERROR: " . $e->getMessage());
            $this->jsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Tambah item showcase baru
     */
    public function store(): void {
        try {
            $badge             = trim($_POST['badge_text'] ?? '');
            $judul             = trim($_POST['judul'] ?? '');
            $deskripsi         = trim($_POST['deskripsi'] ?? '');
            $deskripsi_lengkap = trim($_POST['deskripsi_lengkap'] ?? '');
            $link_url          = trim($_POST['link_url'] ?? '');
            $link_label        = trim($_POST['link_label'] ?? '');
            $urutan            = (int)($_POST['urutan'] ?? 0);
            $is_active         = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;

            if (empty($badge) || empty($judul) || empty($deskripsi)) {
                $this->jsonResponse(['status' => 'error', 'message' => 'Badge, Judul, dan Deskripsi wajib diisi!'], 400);
                return;
            }

            $targetDir = ROOT_PROJECT . '/public/assets/uploads/';
            if (!is_dir($targetDir)) {
                @mkdir($targetDir, 0755, true);
            }

            $gambar = '';
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['gambar']['tmp_name'];
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['gambar']['name']);
                $targetFile = $targetDir . $fileName;

                if (@move_uploaded_file($fileTmp, $targetFile)) {
                    $gambar = $this->processAndOptimizeImage($targetFile);
                }
            }

            // Upload Multiple Galeri Foto dengan kompresi WebP
            $galeri = [];
            if (!empty($gambar)) {
                $galeri[] = $gambar;
            }

            if (isset($_FILES['galeri_foto']) && is_array($_FILES['galeri_foto']['name'])) {
                foreach ($_FILES['galeri_foto']['name'] as $key => $name) {
                    if ($_FILES['galeri_foto']['error'][$key] === UPLOAD_ERR_OK) {
                        $tmpName = $_FILES['galeri_foto']['tmp_name'][$key];
                        $gName = time() . '_' . $key . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $name);
                        $gTargetFile = $targetDir . $gName;

                        if (@move_uploaded_file($tmpName, $gTargetFile)) {
                            $optimizedGName = $this->processAndOptimizeImage($gTargetFile);
                            $galeri[] = $optimizedGName;
                        }
                    }
                }
            }

            $res = $this->model->createItem([
                'badge_text'        => $badge,
                'judul'             => $judul,
                'deskripsi'         => $deskripsi,
                'deskripsi_lengkap' => $deskripsi_lengkap,
                'gambar'            => $gambar,
                'galeri_foto'       => $galeri,
                'link_url'          => $link_url,
                'link_label'        => $link_label,
                'urutan'            => $urutan,
                'is_active'         => $is_active
            ]);

            if ($res) {
                $this->jsonResponse(['status' => 'success', 'message' => 'Item showcase berhasil ditambahkan & dioptimasi ke WebP!']);
            } else {
                $this->jsonResponse(['status' => 'error', 'message' => 'Gagal menyimpan data ke database.'], 500);
            }
        } catch (\Throwable $e) {
            error_log("SHOWCASE_STORE_ERROR: " . $e->getMessage());
            $this->jsonResponse(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API: Update item showcase
     */
    public function update($params = null): void {
        try {
            $id = 0;
            if (is_array($params)) {
                $id = (int)($params['id'] ?? $_POST['id'] ?? 0);
            } else if (is_numeric($params)) {
                $id = (int)$params;
            } else {
                $id = (int)($_POST['id'] ?? 0);
            }

            if ($id <= 0) {
                $this->jsonResponse(['status' => 'error', 'message' => 'ID tidak valid!'], 400);
                return;
            }

            $badge             = trim($_POST['badge_text'] ?? '');
            $judul             = trim($_POST['judul'] ?? '');
            $deskripsi         = trim($_POST['deskripsi'] ?? '');
            $deskripsi_lengkap = trim($_POST['deskripsi_lengkap'] ?? '');
            $link_url          = trim($_POST['link_url'] ?? '');
            $link_label        = trim($_POST['link_label'] ?? '');
            $urutan            = (int)($_POST['urutan'] ?? 0);
            $is_active         = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;

            if (empty($badge) || empty($judul) || empty($deskripsi)) {
                $this->jsonResponse(['status' => 'error', 'message' => 'Badge, Judul, dan Deskripsi wajib diisi!'], 400);
                return;
            }

            $oldItem = $this->model->getById($id);
            $existingGaleri = [];
            if (!empty($oldItem['galeri_foto'])) {
                $decoded = json_decode($oldItem['galeri_foto'], true);
                if (is_array($decoded)) $existingGaleri = $decoded;
            }

            $targetDir = ROOT_PROJECT . '/public/assets/uploads/';
            if (!is_dir($targetDir)) {
                @mkdir($targetDir, 0755, true);
            }

            $data = [
                'badge_text'        => $badge,
                'judul'             => $judul,
                'deskripsi'         => $deskripsi,
                'deskripsi_lengkap' => $deskripsi_lengkap,
                'link_url'          => $link_url,
                'link_label'        => $link_label,
                'urutan'            => $urutan,
                'is_active'         => $is_active
            ];

            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['gambar']['tmp_name'];
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['gambar']['name']);
                $targetFile = $targetDir . $fileName;

                if (@move_uploaded_file($fileTmp, $targetFile)) {
                    $optimizedFileName = $this->processAndOptimizeImage($targetFile);
                    $data['gambar'] = $optimizedFileName;
                    if (!in_array($optimizedFileName, $existingGaleri)) {
                        array_unshift($existingGaleri, $optimizedFileName);
                    }
                }
            }

            // Append new gallery uploads if provided with WebP compression
            if (isset($_FILES['galeri_foto']) && is_array($_FILES['galeri_foto']['name'])) {
                foreach ($_FILES['galeri_foto']['name'] as $key => $name) {
                    if ($_FILES['galeri_foto']['error'][$key] === UPLOAD_ERR_OK) {
                        $tmpName = $_FILES['galeri_foto']['tmp_name'][$key];
                        $gName = time() . '_' . $key . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $name);
                        $gTargetFile = $targetDir . $gName;

                        if (@move_uploaded_file($tmpName, $gTargetFile)) {
                            $optimizedGName = $this->processAndOptimizeImage($gTargetFile);
                            $existingGaleri[] = $optimizedGName;
                        }
                    }
                }
            }

            $data['galeri_foto'] = $existingGaleri;

            $res = $this->model->updateItem($id, $data);

            if ($res) {
                $this->jsonResponse(['status' => 'success', 'message' => 'Item showcase berhasil diperbarui & dioptimasi ke WebP!']);
            } else {
                $this->jsonResponse(['status' => 'error', 'message' => 'Gagal memperbarui data.'], 500);
            }
        } catch (\Throwable $e) {
            error_log("SHOWCASE_UPDATE_ERROR: " . $e->getMessage());
            $this->jsonResponse(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API: Hapus item showcase
     */
    public function delete($params = null): void {
        try {
            $id = 0;
            if (is_array($params)) {
                $id = (int)($params['id'] ?? $_POST['id'] ?? 0);
            } else if (is_numeric($params)) {
                $id = (int)$params;
            } else {
                $id = (int)($_POST['id'] ?? 0);
            }

            if ($id <= 0) {
                $this->jsonResponse(['status' => 'error', 'message' => 'ID tidak valid!'], 400);
                return;
            }

            $res = $this->model->deleteItem($id);
            if ($res) {
                $this->jsonResponse(['status' => 'success', 'message' => 'Item showcase berhasil dihapus!']);
            } else {
                $this->jsonResponse(['status' => 'error', 'message' => 'Gagal menghapus item.'], 500);
            }
        } catch (\Throwable $e) {
            error_log("SHOWCASE_DELETE_ERROR: " . $e->getMessage());
            $this->jsonResponse(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
