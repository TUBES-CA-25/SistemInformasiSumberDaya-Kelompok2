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