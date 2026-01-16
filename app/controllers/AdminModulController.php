<?php
class AdminModulController extends Controller {
    public function index() {
        $data['judul'] = 'Manajemen Modul Praktikum';
        $this->view('admin/templates/header');
        $this->view('admin/modul/index', $data);
        $this->view('admin/templates/footer');
    }
}