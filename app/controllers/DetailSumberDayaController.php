<?php

/**
 * DetailSumberDayaController - Orchestrator Tampilan Detail
 * * Fokus pada penanganan Request (ID, Type) dan Response (View).
 * Semua logika pengolahan data didelegasikan ke DetailSumberDayaService.
 * * @package App\Controllers
 */

require_once ROOT_PROJECT . '/app/models/AsistenModel.php';
require_once ROOT_PROJECT . '/app/services/DetailSumberDayaService.php';

class DetailSumberDayaController extends Controller {
    
    private $service;

    public function __construct() {
        $this->service = new DetailSumberDayaService();
    }

    /**
     * Main Entry Point untuk Halaman Detail
     * URL: /detail?id=X&type=asisten|manajemen
     */
    public function index(array $params = []): void {
        $id = $params['id'] ?? $_GET['id'] ?? null;
        $type = $params['type'] ?? $_GET['type'] ?? 'asisten';

        if (!$id) {
            $this->redirect('/asisten');
            return;
        }

        // Delegasikan pengambilan data ke Service berdasarkan Type
        if ($type === 'manajemen') {
            $dataDetail = $this->service->getFormattedManajemen((int)$id);
        } elseif ($type === 'alumni') {
            $dataDetail = $this->service->getFormattedAlumni((int)$id);
        } else {
            $dataDetail = $this->service->getFormattedAsisten((int)$id);
        }

        // Jika data tidak ditemukan di Service
        if (!$dataDetail) {
            $this->redirect($type === 'manajemen' ? '/atasan' : ($type === 'alumni' ? '/alumni' : '/asisten'));
            return;
        }

        $this->view('sumberdaya/detail', [
            'dataDetail' => $dataDetail,
            'judul' => 'Detail ' . ($type === 'manajemen' ? 'Staff' : ($type === 'alumni' ? 'Alumni' : 'Asisten')) . ' - ' . $dataDetail['nama']
        ]);
    }

    /**
     * API Endpoint: Mengambil data detail terformat dalam format JSON
     * URL: /api/sumberdaya/detail/{id}?type=asisten|manajemen|alumni
     */
    public function apiDetail(array $params = []): void {
        if (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json');

        $id = $params['id'] ?? $_GET['id'] ?? null;
        $type = $_GET['type'] ?? 'asisten';

        if (!$id) {
            echo json_encode([
                'status' => false,
                'message' => 'ID tidak ditemukan'
            ]);
            exit;
        }

        if ($type === 'manajemen') {
            $dataDetail = $this->service->getFormattedManajemen((int)$id);
        } elseif ($type === 'alumni') {
            $dataDetail = $this->service->getFormattedAlumni((int)$id);
        } else {
            $dataDetail = $this->service->getFormattedAsisten((int)$id);
        }

        if (!$dataDetail) {
            echo json_encode([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
            exit;
        }

        echo json_encode([
            'status' => true,
            'data' => $dataDetail
        ]);
        exit;
    }
}