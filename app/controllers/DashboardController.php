<?php
require_once CONTROLLER_PATH . '/Controller.php';

require_once __DIR__ . '/../models/AsistenModel.php';
require_once __DIR__ . '/../models/LaboratoriumModel.php';
require_once __DIR__ . '/../models/AlumniModel.php';
require_once __DIR__ . '/../models/MatakuliahModel.php';
require_once __DIR__ . '/../models/JadwalPraktikumModel.php';

class DashboardController extends Controller {
    
    /**
     * Admin dashboard page
     */
    public function index($params = []) {
        try {
            $asistenModel = new AsistenModel();
            $labModel = new LaboratoriumModel();
            $alumniModel = new AlumniModel();
            $mkModel = new MatakuliahModel();
            $jadwalModel = new JadwalPraktikumModel();

            $stats = [
                'asisten' => $asistenModel->countAll(),
                'laboratorium' => $labModel->countAll(), 
                'alumni' => $alumniModel->countAll(),
                'matakuliah' => $mkModel->countAll(),
                'jadwal' => $jadwalModel->countAll()
            ];

            $this->view('admin/index', ['stats' => $stats]);
        } catch (Exception $e) {
            $this->view('admin/index', ['stats' => [], 'error' => $e->getMessage()]);
        }
    }

    /**
     * API stats endpoint
     */
    public function stats() {
        try {
            $asistenModel = new AsistenModel();
            $labModel = new LaboratoriumModel();
            $alumniModel = new AlumniModel();
            $mkModel = new MatakuliahModel();
            $jadwalModel = new JadwalPraktikumModel();

            $stats = [
                'asisten' => $asistenModel->countAll(),
                'laboratorium' => $labModel->countAll(),
                'alumni' => $alumniModel->countAll(),
                'matakuliah' => $mkModel->countAll(),
                'jadwal' => $jadwalModel->countAll()
            ];

            $this->success($stats, 'Dashboard stats retrieved successfully');
        } catch (Exception $e) {
            $this->error('Failed to retrieve stats: ' . $e->getMessage(), null, 500);
        }
    }
}
?>
