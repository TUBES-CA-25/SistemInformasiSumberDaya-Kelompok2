<?php

/**
 * DetailSumberDayaService - Pengolah Data Detail Sumber Daya
 * * Bertanggung jawab atas transformasi data mentah dari database menjadi
 * format yang siap dikonsumsi oleh View Detail, baik untuk Asisten maupun Manajemen.
 * * @package App\Services
 */
require_once ROOT_PROJECT . '/app/models/ManajemenModel.php';
require_once ROOT_PROJECT . '/app/models/AsistenModel.php';

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

        $bioRaw = $asisten['bio'] ?? '';
        $rawItems = [];
        if (!empty($bioRaw)) {
            $decoded = json_decode($bioRaw, true);
            if (is_array($decoded)) {
                $rawItems = $decoded;
            } else {
                $rawItems = explode(',', $bioRaw);
            }
        }

        $matkulList = [];
        $invalidDays = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu', 'hari'];
        $invalidKeys = ['dosen', 'hari', 'jam', 'waktu', 'ruang', 'ruangan', 'lab', 'laboratorium', 'kelas', 'sks', 'belum ditetapkan'];

        foreach ($rawItems as $item) {
            $clean = trim((string)$item);
            // Bersihkan prefix asisten seperti "Asisten 2 ", "Asisten ", "Asprak 1 ", dsb.
            $clean = preg_replace('/^(asisten\s*(?:[12]|satu|dua)?|asprak\s*(?:[12]|satu|dua)?|co-?asisten)\s+/i', '', $clean);
            $clean = trim($clean);

            if (empty($clean) || strlen($clean) < 2) continue;
            $lower = strtolower($clean);

            // Filter hari dan kata kunci non-MK
            if (in_array($lower, $invalidDays) || in_array($lower, $invalidKeys)) continue;
            // Filter format jam
            if (preg_match('/^\d{1,2}[:.]\d{2}/', $clean)) continue;
            // Filter nama dosen bergelar
            if (preg_match('/,\s*(S\.Kom|M\.Kom|M\.T|S\.T|M\.Cs|MTA|M\.Si|M\.Eng|Dr\.|Ir\.|Prof\.)/i', $clean) ||
                preg_match('/^(Dr\.|Ir\.|Prof\.)\s+/i', $clean)) continue;

            if (!in_array($clean, $matkulList)) {
                $matkulList[] = $clean;
            }
        }

        $formattedBio = !empty($matkulList) ? implode(', ', $matkulList) : (!empty($bioRaw) ? $bioRaw : "Belum ada mata kuliah yang dicantumkan.");

        return [
            'nama'        => $asisten['nama'] ?? 'Tanpa Nama',
            'jabatan'     => $info['jabatan'],
            'kategori'    => $info['kategori'],
            'sub_info'    => $asisten['jurusan'] ?? 'Teknik Informatika',
            'sub_icon'    => 'ri-graduation-cap-line',
            'foto_url'    => $this->resolvePhoto($asisten['foto'] ?? '', $asisten['nama']),
            'foto_pos_x'  => $asisten['foto_pos_x'] ?? 50,
            'foto_pos_y'  => $asisten['foto_pos_y'] ?? 50,
            'email'       => $asisten['email'] ?? '-',
            'bio'         => $formattedBio,
            'matkul'      => $matkulList,
            'skills'      => $this->parseSkills($asisten['skills'] ?? ''),
            'badge_style' => $info['badge'],
            'back_link'   => '/asisten'
        ];
    }

    /**
     * Mengambil detail alumni dengan pengolahan role & skills.
     */
    public function getFormattedAlumni(int $id): ?array {
        require_once ROOT_PROJECT . '/app/models/AlumniModel.php';
        $alumniModel = new AlumniModel();
        $alumni = $alumniModel->getById($id, 'id');
        if (!$alumni) {
            return $this->getFormattedAsisten($id);
        }

        // Parsing Mata Kuliah yang Pernah Diajar
        $matkulRaw = $alumni['mata_kuliah'] ?? '';
        if (empty($matkulRaw) && !empty($alumni['kesan_pesan'])) {
            $matkulRaw = $alumni['kesan_pesan'];
        }

        $rawItems = [];
        if (!empty($matkulRaw)) {
            $decoded = json_decode($matkulRaw, true);
            if (is_array($decoded)) {
                $rawItems = $decoded;
            } else {
                $cleaned = str_replace(['[', ']', '"', "'", '\\'], '', $matkulRaw);
                $rawItems = explode(',', $cleaned);
            }
        }

        $matkulList = [];
        foreach ($rawItems as $item) {
            $clean = trim((string)$item);
            $clean = preg_replace('/^(asisten\s*(?:[12]|satu|dua)?|asprak\s*(?:[12]|satu|dua)?|co-?asisten)\s+/i', '', $clean);
            $clean = trim($clean);
            if (empty($clean) || strlen($clean) < 2) continue;
            if (!in_array($clean, $matkulList)) {
                $matkulList[] = $clean;
            }
        }

        $formattedBio = !empty($matkulList) ? implode(', ', $matkulList) : "Belum ada mata kuliah yang dicantumkan.";

        return [
            'nama'        => $alumni['nama'] ?? 'Tanpa Nama',
            'jabatan'     => trim(str_ireplace(['alumni asisten', 'asisten lab', 'asisten', 'alumni'], '', $alumni['divisi'] ?? '')),
            'kategori'    => '',
            'sub_info'    => !empty($alumni['jurusan']) ? $alumni['jurusan'] : (!empty($alumni['angkatan']) ? 'Angkatan ' . $alumni['angkatan'] : 'Teknik Informatika'),
            'sub_icon'    => 'ri-graduation-cap-line',
            'foto_url'    => Helper::processPhotoUrl($alumni['foto'] ?? '', $alumni['nama']),
            'foto_pos_x'  => $alumni['foto_pos_x'] ?? 50,
            'foto_pos_y'  => $alumni['foto_pos_y'] ?? 50,
            'email'       => $alumni['email'] ?? '-',
            'bio'         => $formattedBio,
            'matkul'      => $matkulList,
            'skills'      => $this->parseSkills($alumni['keahlian'] ?? ($alumni['skills'] ?? '')),
            'badge_style' => 'badge-alumni',
            'back_link'   => '/alumni'
        ];
    }

    /**
     * Mengambil detail manajemen dengan logika bio override.
     */
    public function getFormattedManajemen(int $id): ?array {
        $row = $this->manajemenModel->getById($id);
        if (!$row) return null;

        $isKepala = stripos(($row['jabatan'] ?? ''), 'Kepala') !== false;

        $hasNidn = (!empty($row['nidn']) && $row['nidn'] != '-');

        return [
            'nama'        => $row['nama'] ?? 'Tanpa Nama',
            'jabatan'     => $row['jabatan'] ?? '-',
            'kategori'    => $isKepala ? 'Pimpinan' : 'Staff Laboratorium',
            'sub_info'    => $hasNidn ? 'NIDN: ' . $row['nidn'] : 'Fakultas Ilmu Komputer',
            'sub_icon'    => $hasNidn ? 'ri-award-line' : 'ri-bank-line',
            'foto_url'    => $this->resolvePhoto($row['foto'] ?? '', $row['nama']),
            'foto_pos_x'  => $row['foto_pos_x'] ?? 50,
            'foto_pos_y'  => $row['foto_pos_y'] ?? 50,
            'email'       => $row['email'] ?? '-',
            'bio'         => $this->resolveManajemenBio($row),
            'skills'      => [],
            'badge_style' => $isKepala ? 'badge-kepala' : 'badge-staff',
            'back_link'   => '/atasan'
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