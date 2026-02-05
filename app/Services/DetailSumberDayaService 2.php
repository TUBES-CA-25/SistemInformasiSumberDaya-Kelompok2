<?php

/**
 * DetailSumberDayaService - Pengolah Data Detail Sumber Daya
 * * Bertanggung jawab atas transformasi data mentah dari database menjadi
 * format yang siap dikonsumsi oleh View Detail, baik untuk Asisten maupun Manajemen.
 * * @package App\Services
 */
class DetailSumberDayaService {
    private $asistenModel;
    private $manajemenModel;

    public function __construct() {
        $this->asistenModel = new AsistenModel();
        $this->manajemenModel = new ManajemenModel();
    }

    /**
     * Mengambil detail asisten dengan pengolahan role & skills.
     */
    public function getFormattedAsisten(int $id): ?array {
        $asisten = $this->asistenModel->getById($id, 'idAsisten');
        if (!$asisten) return null;

        $status = strtolower($asisten['statusAktif'] ?? '');
        $isCoord = $asisten['isKoordinator'] ?? 0;

        // Default Values
        $info = [
            'jabatan' => 'Asisten Praktikum',
            'kategori' => 'Asisten Laboratorium',
            'badge' => 'badge-asisten'
        ];

        // Role Detection Logic
        if ($isCoord == 1) {
            $info = ['jabatan' => 'Koordinator Laboratorium', 'kategori' => 'Koordinator', 'badge' => 'badge-coord'];
        } elseif (strpos($status, 'calon') !== false || $status == 'ca') {
            $info = ['jabatan' => 'Calon Asisten (CA)', 'kategori' => 'Calon Asisten', 'badge' => 'badge-ca'];
        }

        return [
            'nama'        => $asisten['nama'] ?? 'Tanpa Nama',
            'jabatan'     => $info['jabatan'],
            'kategori'    => $info['kategori'],
            'sub_info'    => $asisten['jurusan'] ?? 'Teknik Informatika',
            'sub_icon'    => 'ri-graduation-cap-line',
            'foto_url'    => $this->resolvePhoto($asisten['foto'] ?? '', $asisten['nama']),
            'email'       => $asisten['email'] ?? '-',
            'bio'         => $asisten['bio'] ?: "Mahasiswa aktif dan asisten laboratorium.",
            'skills'      => $this->parseSkills($asisten['skills'] ?? ''),
            'badge_style' => $info['badge'],
            'back_link'   => '/asisten'
        ];
    }

    /**
     * Mengambil detail manajemen dengan logika bio override.
     */
    public function getFormattedManajemen(int $id): ?array {
        $row = $this->manajemenModel->getById($id);
        if (!$row) return null;

        $isKepala = stripos(($row['jabatan'] ?? ''), 'Kepala') !== false;

        return [
            'nama'        => $row['nama'] ?? 'Tanpa Nama',
            'jabatan'     => $row['jabatan'] ?? '-',
            'kategori'    => $isKepala ? 'Pimpinan' : 'Staff Laboratorium',
            'sub_info'    => (!empty($row['nidn']) && $row['nidn'] != '-') ? 'NIDN: ' . $row['nidn'] : 'Fakultas Ilmu Komputer',
            'sub_icon'    => 'ri-id-card-line',
            'foto_url'    => $this->resolvePhoto($row['foto'] ?? '', $row['nama']),
            'email'       => $row['email'] ?? '-',
            'bio'         => $this->resolveManajemenBio($row),
            'skills'      => [],
            'badge_style' => $isKepala ? 'badge-kepala' : 'badge-staff',
            'back_link'   => '/kepala'
        ];
    }

    /**
     * Logika internal pemilihan Bio (Database vs Hardcoded Override)
     */
    private function resolveManajemenBio(array $row): string {
        if (!empty($row['tentang'])) return $row['tentang'];

        $overrides = [
            'Abdul Rachman' => "Ir. Abdul Rachman Manga', S.Kom., M.T. adalah Kepala Laboratorium Jaringan Dan Pemrograman.",
            'Huzain Azis'   => "Ir. Huzain Azis, S.Kom., M.Cs. adalah Kepala Laboratorium Komputasi Dasar.",
            'Herdianti'    => "Herdianti, S.Si., M.Eng. adalah Kepala Laboratorium Riset.",
            'Fatimah'      => "Fatimah AR. Tuasamu, S.Kom. adalah Laboran di Fakultas Ilmu Komputer UMI."
        ];

        foreach ($overrides as $name => $bio) {
            if (stripos($row['nama'], $name) !== false) return $bio;
        }

        return "Staff/Pimpinan aktif di Laboratorium Fakultas Ilmu Komputer UMI.";
    }

    /**
     * Helper: Resolving Photo URL dengan fallback UI-Avatars
     */
    private function resolvePhoto(?string $foto, string $nama): string {
        if (empty($foto) || strpos($foto, 'ui-avatars') !== false) {
            return "https://ui-avatars.com/api/?name=" . urlencode($nama) . "&background=eff6ff&color=2563eb&size=256&bold=true";
        }

        if (strpos($foto, 'http') === 0) return $foto;

        $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : '';
        $checkPaths = ['/assets/uploads/', '/images/asisten/'];

        foreach ($checkPaths as $path) {
            if (file_exists(ROOT_PROJECT . '/public' . $path . $foto)) {
                return $baseUrl . $path . $foto;
            }
        }

        return $baseUrl . '/assets/uploads/' . $foto;
    }

    /**
     * Helper: Parsing Skills dari JSON atau Comma-separated string
     */
    private function parseSkills(?string $raw): array {
        if (empty($raw)) return ['Teaching', 'Mentoring'];
        
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) return array_filter($decoded);

        $cleaned = str_replace(['[', ']', '"'], '', $raw);
        $items = array_filter(array_map('trim', explode(',', $cleaned)));

        return !empty($items) ? $items : ['Teaching', 'Mentoring'];
    }
}