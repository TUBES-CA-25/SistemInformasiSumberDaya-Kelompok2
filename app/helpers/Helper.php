<?php
/**
 * Helper Class & Functions
 * Menyediakan utilitas umum untuk manipulasi string, file, dan pengolahan data.
 */

class Helper {
    /**
     * [BARU] Menghasilkan inisial dari nama (maksimal 2 karakter)
     * Contoh: "Budi Santoso" -> "BS", "Andi" -> "AN"
     */
    public static function getInitials($name) {
        $name = trim($name ?? '');
        if (empty($name)) return '??';

        $parts = explode(' ', $name);
        $initials = '';
        
        foreach ($parts as $part) {
            if (!empty($part) && ctype_alpha($part[0])) {
                $initials .= strtoupper($part[0]);
                if (strlen($initials) >= 2) break;
            }
        }
        
        // Jika hanya 1 kata, ambil 2 huruf pertama (Andi -> AN)
        if (strlen($initials) === 1 && strlen($name) > 1) {
            $initials = strtoupper(substr($name, 0, 2));
        }

        return !empty($initials) ? $initials : '??';
    }

    /**
     * [BARU] Membatasi panjang teks untuk ringkasan (short description)
     */
    public static function limitText($text, $limit = 150) {
        $text = strip_tags($text ?? ''); // Bersihkan tag HTML agar tidak rusak saat dipotong
        if (strlen($text) > $limit) {
            return substr($text, 0, $limit) . '...';
        }
        return $text;
    }

    /**
     * Membuat slug dari string (contoh: "Budi Santoso" -> "budi-santoso")
     */
    public static function slugify($text) {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return empty($text) ? 'n-a' : $text;
    }

    /**
     * Menghasilkan nama file yang unik berdasarkan kategori dan nama subjek
     */
    public static function generateFilename($category, $subjectName, $extension) {
        $cleanName = self::slugify($subjectName);
        $timestamp = time();
        $random = rand(100, 999);
        $ext = strtolower($extension);
        return "{$category}_{$cleanName}_{$timestamp}_{$random}.{$ext}";
    }

    /**
     * Membatasi nilai posisi foto (persentase) ke rentang 0-100.
     * Dipakai untuk sanitasi input foto_pos_x/foto_pos_y sebelum disimpan ke DB.
     */
    public static function clampPercent($value, $default = 50) {
        if ($value === null || $value === '') return $default;
        if (!is_numeric($value)) return $default;
        return max(0, min(100, (float) $value));
    }

    /**
     * Membuat atribut CSS object-position dari data foto_pos_x/foto_pos_y sebuah baris data.
     * Fallback ke 50% 50% (tengah) jika data belum ada (misal record lama sebelum kolom ditambahkan).
     */
    public static function objectPosStyle($row) {
        $x = self::clampPercent($row['foto_pos_x'] ?? null);
        $y = self::clampPercent($row['foto_pos_y'] ?? null);
        return 'object-position: ' . $x . '% ' . $y . '%;';
    }

    /**
     * Proses URL Foto dengan Fallback UI Avatars
     */
    public static function processPhotoUrl($fotoName, $nama) {
        $namaEnc = urlencode($nama ?? 'User');
        $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=eff6ff&color=2563eb&size=256&bold=true";

        if (!empty($fotoName) && strpos($fotoName, 'ui-avatars') === false) {
            if (strpos($fotoName, 'http') === 0) {
                $imgUrl = $fotoName;
            } else {
                $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');
                
                // Daftar path pencarian (Urutan: Upload baru -> Legacy Manajemen -> Legacy Asisten)
                $checkPaths = [
                    '/public/assets/uploads/' => $baseUrl . '/assets/uploads/',
                    '/public/images/manajemen/' => $baseUrl . '/images/manajemen/',
                    '/public/images/asisten/' => $baseUrl . '/images/asisten/'
                ];

                foreach ($checkPaths as $physicalPath => $webPath) {
                    if (file_exists(ROOT_PROJECT . $physicalPath . $fotoName)) {
                        return $webPath . $fotoName;
                    }
                }
            }
        }
        return $imgUrl;
    }

    /**
     * Meng-encode integer ID menjadi string hash unik.
     */
    public static function encodeId($id, $salt = 'SistemInformasiLab2026') {
        if ($id === null || $id === '') return '';
        if (!is_numeric($id) || (int)$id <= 0) return (string)$id;
        
        $num = (int)$id;
        $mask = hexdec(substr(md5($salt), 0, 7));
        $obscured = $num ^ $mask;
        $code = base_convert((string)$obscured, 10, 36);
        $check = substr(md5($salt . $code), 0, 2);
        return $code . $check;
    }

    /**
     * Meng-decode string hash kembali menjadi integer ID asli.
     * Mendukung fallback jika $hash berupa numeric ID murni.
     */
    public static function decodeId($hash, $salt = 'SistemInformasiLab2026') {
        if ($hash === null || $hash === '') return null;
        if (is_numeric($hash)) return (int)$hash;

        $hash = (string)$hash;
        if (strlen($hash) <= 2) return null;

        $check = substr($hash, -2);
        $code = substr($hash, 0, -2);
        
        if (substr(md5($salt . $code), 0, 2) !== $check) {
            return null;
        }

        $obscured = base_convert($code, 36, 10);
        $mask = hexdec(substr(md5($salt), 0, 7));
        $num = (int)$obscured ^ $mask;
        
        return $num > 0 ? $num : null;
    }

    /**
     * Asset Versioning Helper (Cache-Busting)
     * Mengembalikan URL publik dengan timestamp filemtime ?v=timestamp.
     * Contoh: Helper::asset('/css/home.css') -> "http://.../css/home.css?v=172208000"
     */
    public static function asset($relativePath) {
        $cleanPath = '/' . ltrim($relativePath, '/');
        $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : '';
        $fullPath = defined('ROOT_PROJECT') ? (ROOT_PROJECT . '/public' . $cleanPath) : '';
        
        $v = (!empty($fullPath) && file_exists($fullPath)) ? filemtime($fullPath) : '1.0';
        return $baseUrl . $cleanPath . '?v=' . $v;
    }
}

/**
 * --- GLOBAL HELPER FUNCTIONS ---
 * (Memanggil static methods dari class Helper agar tetap simpel di View/Controller)
 */

function redirect($url) {
    if (strpos($url, 'http') !== 0 && defined('BASE_URL')) {
        $url = BASE_URL . '/' . ltrim($url, '/');
    }
    header("Location: " . $url);
    exit;
}

function dd($data) {
    echo '<style>body{background:#1a1a1a;color:#00ff00;padding:20px;font-family:monospace;}</style>';
    echo '<h2>DEBUG DATA</h2><hr>';
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die;
}

function sanitize($data) {
    return htmlspecialchars(trim($data ?? ''));
}

function asset($path) {
    return Helper::asset($path);
}