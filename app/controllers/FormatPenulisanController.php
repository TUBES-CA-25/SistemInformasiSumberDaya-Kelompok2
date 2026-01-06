<?php
require_once ROOT_PROJECT . '/app/models/FormatPenulisanModel.php';

class FormatPenulisanController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new FormatPenulisanModel();
    }

    public function index() {
        // Membersihkan buffer agar JSON/HTML bersih
        while (ob_get_level()) { ob_end_clean(); }
        
        $data = $this->model->getAllFormat();
        $this->view('praktikum/format_penulisan', ['formats' => $data]);
    }
}