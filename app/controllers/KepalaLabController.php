<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/ManajemenModel.php';

/**
 * KepalaLabController
 */
class KepalaLabController extends Controller {
    private $model;
    
    public function __construct() {
        $this->model = new ManajemenModel();
    }
    
    public function index($params = []) {

    // Ambil semua data manajemen via Model
    $all = $this->model->getAll();

    // Kirimkan ke view dalam $data
    $data = [
        'manajemen' => $all
    ];

    $this->view('sumberdaya/kepala', $data);
}

    
    /**
     * Detail profil pimpinan/laboran (legacy view, MVC route)
     */
    public function detail($params = []) {
        // Terima parameter {id} dari rute dan teruskan ke view legacy
        if (!empty($params['id'])) {
            $_GET['id'] = $params['id']; // menjaga kompatibilitas view legacy
        }
        $this->view('sumberdaya/detail');
    }
    
    /**
     * API endpoint untuk mendapatkan semua manajemen lab
     */
    public function apiIndex($params = []) {
        try {
            $data = $this->model->getAll();
            $this->response([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (Exception $e) {
            $this->response([
                'status' => 'error',
                'message' => 'Gagal mengambil data manajemen'
            ], 500);
        }
    }
}
?>