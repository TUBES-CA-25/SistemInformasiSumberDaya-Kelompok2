<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/SanksiLabModel.php';
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class SanksiController extends Controller {
    private $model;
    public function __construct() {
        $this->model = new \SanksiLabModel();
    }

    public function apiIndex() {
        $data = $this->model->getAll();
        $this->success($data, 'Data sanksi lab retrieved successfully');
    }

    public function apiShow($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID tidak ditemukan', null, 400);
            return;
        }
        $data = $this->model->getById($id);
        if (!$data) {
            $this->error('Data tidak ditemukan', null, 404);
            return;
        }
        $this->success($data, 'Sanksi lab retrieved successfully');
    }

    // Legacy methods
    public function index() {
        return $this->apiIndex();
    }

    public function show($params) {
        return $this->apiShow($params);
    }

    public function store() {
        try {
            // Log request data
            error_log('SANKSI STORE - REQUEST METHOD: ' . $_SERVER['REQUEST_METHOD']);
            error_log('SANKSI STORE - POST: ' . json_encode($_POST));
            error_log('SANKSI STORE - FILES: ' . json_encode(array_keys($_FILES ?? [])));
            
            // Check if this is actually an update (PUT override)
            if (isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
                $this->error('ID harus diberikan di URL untuk update', null, 400);
            }
            
            // Filter only database fields, ignore 'tipe' and 'id' (used for routing only)
            $input = [
                'judul' => $_POST['judul'] ?? '',
                'deskripsi' => $_POST['deskripsi'] ?? '',
                'display_format' => $_POST['display_format'] ?? 'list'
            ];
            
            error_log('SANKSI STORE - INPUT: ' . json_encode($input));
            
            // Validasi field wajib
            if (empty($input['judul']) || empty($input['deskripsi'])) {
                $this->error('Field judul dan deskripsi wajib diisi', null, 400);
            }
            
            // Set default display_format jika kosong
            if (empty($input['display_format'])) {
                $input['display_format'] = 'list';
            }
            

            
            error_log('SANKSI STORE - FINAL INPUT: ' . json_encode($input));
            $result = $this->model->insert($input);
            error_log('SANKSI STORE - INSERT RESULT: ' . ($result ? 'SUCCESS' : 'FAILED'));
            
            if ($result) {
                $this->success([], 'Sanksi lab created', 201);
            }
            
            $this->error('Failed to create sanksi lab', null, 500);
        } catch (Exception $e) {
            error_log('SANKSI STORE - EXCEPTION: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID tidak ditemukan', null, 400);
        
        // Ambil data lama dulu
        $oldData = $this->model->getById($id);
        if (!$oldData) $this->error('Data tidak ditemukan', null, 404);
        
        // Ambil input data dari $_POST (FormData akan tersimpan di sini untuk POST)
        $input = $_POST ?? [];
        
        // Debug logging
        error_log('SANKSI UPDATE - ID: ' . $id . ', POST keys: ' . json_encode(array_keys($input)) . ', FILES: ' . json_encode(array_keys($_FILES ?? [])));
        error_log('SANKSI UPDATE - POST values: ' . json_encode($input));
        
        // Set default values dari old data jika tidak ada input baru
        $judul = !empty($input['judul']) ? trim($input['judul']) : $oldData['judul'];
        $deskripsi = !empty($input['deskripsi']) ? trim($input['deskripsi']) : $oldData['deskripsi'];
        $urutan = !empty($input['urutan']) ? intval($input['urutan']) : ($oldData['urutan'] ?? 0);
        $displayFormat = !empty($input['display_format']) ? $input['display_format'] : ($oldData['display_format'] ?? 'list');
        
        // Validasi field wajib
        if (empty($judul) || empty($deskripsi)) {
            $this->error('Field judul dan deskripsi wajib diisi', null, 400);
        }
        
        $input = [
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'urutan' => $urutan,
            'display_format' => $displayFormat
        ];
        
        error_log('SANKSI UPDATE - Final input: ' . json_encode($input));
        
        // Optional: handle file upload for gambar

        
        error_log('SANKSI UPDATE - About to update with: ' . json_encode($input));
        $result = $this->model->update($id, $input);
        error_log('SANKSI UPDATE - Result: ' . ($result ? 'SUCCESS' : 'FAILED'));
        
        if ($result) $this->success([], 'Sanksi lab updated');
        $this->error('Failed to update sanksi lab', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) $this->error('ID tidak ditemukan', null, 400);
        $result = $this->model->delete($id);
        if ($result) $this->success([], 'Sanksi lab deleted');
        $this->error('Failed to delete sanksi lab', null, 500);
    }
}
?>
