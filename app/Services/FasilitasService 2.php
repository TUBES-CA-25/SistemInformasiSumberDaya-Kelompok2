<?php

require_once ROOT_PROJECT . '/app/models/FasilitasModel.php';
require_once ROOT_PROJECT . '/app/models/LaboratoriumGambarModel.php';
require_once ROOT_PROJECT . '/app/models/AsistenModel.php';
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

/**
 * FasilitasService - Pusat Logika Bisnis Fasilitas & Laboratorium
 * Menangani pengolahan hardware, galeri gambar, dan data koordinator.
 */
class FasilitasService {
    private $model;
    private $gambarModel;
    private $asistenModel;

    public function __construct() {
        $this->model = new FasilitasModel();
        $this->gambarModel = new LaboratoriumGambarModel();
        $this->asistenModel = new AsistenModel();
    }

    /**
     * Daftar semua fasilitas dengan thumbnail otomatis
     */
    public function getFasilitasWithThumbnails() {
        $data = $this->model->getAll();
        foreach ($data as &$row) {
            $row['img_src'] = $this->resolveThumbnail($row);
            $row['short_desc'] = Helper::limitText($row['deskripsi'] ?? '', 150);
        }
        return $data;
    }

    /**
     * Format data lengkap untuk halaman Detail
     */
    public function getFullDetail($id) {
        $lab = $this->model->getById($id, 'idLaboratorium');
        if (!$lab) return null;

        return [
            'lab'         => $lab,
            'gallery'     => $this->resolveGallery($lab),
            'koordinator' => $this->resolveKoordinator($lab['idKordinatorAsisten'] ?? null),
            'hardware'    => $this->formatHardware($lab),
            'software'    => $this->parseList($lab['software'] ?? ''),
            'pendukung'   => $this->parseList($lab['fasilitas_pendukung'] ?? '')
        ];
    }

    public function resolveThumbnail($lab) {
        $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : '';
        if (!empty($lab['gambar'])) return $baseUrl . '/assets/uploads/' . $lab['gambar'];
        
        $gallery = $this->gambarModel->getByLaboratorium($lab['idLaboratorium']);
        return !empty($gallery) ? $baseUrl . '/assets/uploads/' . $gallery[0]['namaGambar'] : null;
    }

    private function formatHardware($lab) {
        return array_filter([
            'Processor' => $lab['processor'] ?? '',
            'RAM'       => $lab['ram'] ?? '',
            'Storage'   => $lab['storage'] ?? '',
            'GPU'       => $lab['gpu'] ?? '',
            'Monitor'   => $lab['monitor'] ?? '',
            'PC'        => !empty($lab['jumlahPc']) ? $lab['jumlahPc'] . ' Unit' : ''
        ]);
    }

    private function resolveKoordinator($id) {
        if (!$id) return ['nama' => 'Staff Laboratorium', 'initials' => 'SL'];
        $asisten = $this->asistenModel->getById($id, 'idAsisten');
        return $asisten ? [
            'nama' => $asisten['nama'],
            'email' => $asisten['email'],
            'foto' => !empty($asisten['foto']) ? PUBLIC_URL . '/assets/uploads/' . $asisten['foto'] : null,
            'initials' => Helper::getInitials($asisten['nama'])
        ] : ['nama' => 'Staff Laboratorium', 'initials' => 'SL'];
    }

    private function resolveGallery($lab) {
        $images = $this->gambarModel->getByLaboratorium($lab['idLaboratorium']);
        $gallery = [];
        foreach ($images as $img) {
            $gallery[] = [
                'src' => PUBLIC_URL . '/assets/uploads/' . $img['namaGambar'],
                'desc' => $img['deskripsiGambar'] ?? ''
            ];
        }
        return $gallery;
    }

    private function parseList($str) {
        return !empty($str) ? array_map('trim', explode(',', $str)) : [];
    }

    public function deleteImageWithLogic($idGambar) {
        $gambar = $this->gambarModel->getById($idGambar, 'idGambar');
        if (!$gambar) throw new Exception("Data gambar tidak ditemukan.");

        $labId = $gambar['idLaboratorium'];
        $filename = $gambar['namaGambar'];

        $filePath = ROOT_PROJECT . '/public/assets/uploads/' . $filename;
        if (file_exists($filePath)) @unlink($filePath);

        $this->gambarModel->deleteImage($idGambar);

        $lab = $this->model->getById($labId, 'idLaboratorium');
        if ($lab && $lab['gambar'] === $filename) {
            $sisa = $this->gambarModel->getByLaboratorium($labId);
            $pengganti = !empty($sisa) ? $sisa[0]['namaGambar'] : null;
            $this->model->update($labId, ['gambar' => $pengganti], 'idLaboratorium');
        }
    }
}