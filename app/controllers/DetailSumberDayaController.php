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
        $dataDetail = ($type === 'manajemen') 
            ? $this->service->getFormattedManajemen((int)$id)
            : $this->service->getFormattedAsisten((int)$id);

        // Jika data tidak ditemukan di Service
        if (!$dataDetail) {
            $this->redirect($type === 'manajemen' ? '/kepala' : '/asisten');
            return;
        }

        $this->view('sumberdaya/detail', [
            'dataDetail' => $dataDetail,
            'judul' => 'Detail ' . ($type === 'manajemen' ? 'Staff' : 'Asisten') . ' - ' . $dataDetail['nama']
        ]);
    }
}