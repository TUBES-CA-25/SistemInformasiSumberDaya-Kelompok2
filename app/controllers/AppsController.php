<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/AppsModel.php';

class AppsController extends Controller {

    /** @var AppsModel */
    private $model;

    public function __construct() {
        $this->model = new AppsModel();
    }

    /**
     * Helper pembersih buffer sebelum merespons JSON API
     */
    private function cleanBuffers(): void {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
    }

    /**
     * Tampilan Admin: Kelola IC-Labs Apps
     */
    public function adminIndex(): void {
        $data = [
            'judul' => 'Manajemen IC-Labs Apps'
        ];
        $this->view('admin/apps/index', $data);
    }

    // ==========================================
    // API ENDPOINTS
    // ==========================================

    /**
     * API: Ambil semua data aplikasi
     */
    public function apiIndex(): void {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        $apps = $this->model->getAllOrdered();
        $activeCount = count(array_filter($apps, function($a) {
            return (int)$a['is_active'] === 1;
        }));

        echo json_encode([
            'status' => 'success',
            'data'   => $apps,
            'meta'   => [
                'total'    => count($apps),
                'active'   => $activeCount,
                'inactive' => count($apps) - $activeCount
            ]
        ]);
        exit;
    }

    /**
     * API: Ambil satu detail aplikasi
     */
    public function apiShow($params = []): void {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        $id = $params['id'] ?? $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID aplikasi wajib disertakan.']);
            exit;
        }

        $app = $this->model->getById($id);
        if (!$app) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Aplikasi tidak ditemukan.']);
            exit;
        }

        echo json_encode(['status' => 'success', 'data' => $app]);
        exit;
    }

    /**
     * API: Tambah aplikasi baru
     */
    public function apiStore(): void {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        $input = $_POST;
        if (empty($input)) {
            $input = json_decode(file_get_contents('php://input'), true) ?? [];
        }

        $judul     = trim($input['judul'] ?? '');
        $deskripsi = trim($input['deskripsi'] ?? '');
        $ikon      = trim($input['ikon'] ?? 'ri-apps-line');
        $warna     = trim($input['warna'] ?? 'color-blue');
        $url       = trim($input['url'] ?? '#');
        $target    = trim($input['target'] ?? '_blank');
        $urutan    = isset($input['urutan']) ? (int)$input['urutan'] : 0;
        $isActive  = isset($input['is_active']) ? (int)$input['is_active'] : 1;

        if (empty($judul) || empty($deskripsi)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Judul dan deskripsi aplikasi wajib diisi.']);
            exit;
        }

        if (empty($ikon)) $ikon = 'ri-apps-line';
        if (empty($warna)) $warna = 'color-blue';
        if (empty($url)) $url = '#';

        // Auto target: jika URL eksternal (http/https), buka tab baru
        if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
            $target = '_blank';
        }

        $inserted = $this->model->insert([
            'judul'     => $judul,
            'deskripsi' => $deskripsi,
            'ikon'      => $ikon,
            'warna'     => $warna,
            'url'       => $url,
            'target'    => $target,
            'urutan'    => $urutan,
            'is_active' => $isActive
        ]);

        if ($inserted) {
            echo json_encode([
                'status'  => 'success',
                'message' => 'Aplikasi berhasil ditambahkan ke IC-Labs Apps.',
                'id'      => $this->model->getLastInsertId()
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan aplikasi baru.']);
        }
        exit;
    }

    /**
     * API: Update aplikasi
     */
    public function apiUpdate($params = []): void {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        $id = $params['id'] ?? $_POST['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID aplikasi wajib disertakan.']);
            exit;
        }

        $existing = $this->model->getById($id);
        if (!$existing) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Aplikasi tidak ditemukan.']);
            exit;
        }

        $input = $_POST;
        if (empty($input)) {
            $input = json_decode(file_get_contents('php://input'), true) ?? [];
        }

        $judul     = trim($input['judul'] ?? $existing['judul']);
        $deskripsi = trim($input['deskripsi'] ?? $existing['deskripsi']);
        $ikon      = trim($input['ikon'] ?? $existing['ikon']);
        $warna     = trim($input['warna'] ?? $existing['warna']);
        $url       = trim($input['url'] ?? $existing['url']);
        $target    = trim($input['target'] ?? $existing['target']);
        $urutan    = isset($input['urutan']) ? (int)$input['urutan'] : (int)$existing['urutan'];
        $isActive  = isset($input['is_active']) ? (int)$input['is_active'] : (int)$existing['is_active'];

        if (empty($judul) || empty($deskripsi)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Judul dan deskripsi aplikasi wajib diisi.']);
            exit;
        }

        if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
            $target = '_blank';
        }

        $updated = $this->model->update($id, [
            'judul'     => $judul,
            'deskripsi' => $deskripsi,
            'ikon'      => $ikon,
            'warna'     => $warna,
            'url'       => $url,
            'target'    => $target,
            'urutan'    => $urutan,
            'is_active' => $isActive
        ]);

        if ($updated) {
            echo json_encode(['status' => 'success', 'message' => 'Data aplikasi berhasil diperbarui.']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui aplikasi.']);
        }
        exit;
    }

    /**
     * API: Toggle status aktif/nonaktif aplikasi
     */
    public function apiToggle($params = []): void {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        $id = $params['id'] ?? $_POST['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID aplikasi wajib disertakan.']);
            exit;
        }

        $toggled = $this->model->toggleStatus((int)$id);
        if ($toggled) {
            $updated = $this->model->getById($id);
            $statusLabel = ((int)$updated['is_active'] === 1) ? 'diaktifkan' : 'dinonaktifkan';
            echo json_encode([
                'status'    => 'success',
                'message'   => "Aplikasi \"{$updated['judul']}\" berhasil {$statusLabel}.",
                'is_active' => (int)$updated['is_active']
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengubah status aplikasi.']);
        }
        exit;
    }

    /**
     * API: Hapus aplikasi
     */
    public function apiDelete($params = []): void {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        $id = $params['id'] ?? $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID aplikasi wajib disertakan.']);
            exit;
        }

        $deleted = $this->model->delete($id);
        if ($deleted) {
            echo json_encode(['status' => 'success', 'message' => 'Aplikasi berhasil dihapus.']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus aplikasi.']);
        }
        exit;
    }
}
