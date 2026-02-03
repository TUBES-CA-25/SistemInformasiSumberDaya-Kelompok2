<?php

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/services/DashboardService.php';

// Model tetap di-load di sini atau di Service (tergantung sistem autoload Anda)
require_once ROOT_PROJECT . '/app/models/AsistenModel.php';
require_once ROOT_PROJECT . '/app/models/FasilitasModel.php';
require_once ROOT_PROJECT . '/app/models/AlumniModel.php';
require_once ROOT_PROJECT . '/app/config/Database.php';

class DashboardController extends Controller {
    private $service;

    public function __construct() {
        $this->service = new DashboardService();
    }

    /**
     * Menampilkan halaman Dashboard
     */
    public function index() {
        $this->view('admin/dashboard/index');
    }

    /**
     * API Endpoint untuk data statistik
     */
    public function stats() {
        try {
            $data = $this->service->getDashboardStats();
            $this->success($data, 'Statistik dashboard berhasil diambil');
        } catch (Exception $e) {
            error_log('Dashboard stats error: ' . $e->getMessage());
            $this->error('Gagal mengambil statistik: ' . $e->getMessage(), null, 500);
        }
    }
}