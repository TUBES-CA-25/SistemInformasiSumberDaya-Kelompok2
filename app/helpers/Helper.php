<?php
/**
 * Helper Class & Functions
 */

class Helper {
    /**
     * Membuat slug dari string (contoh: "Budi Santoso" -> "budi-santoso")
     */
    public static function slugify($text) {
        // ganti non letter atau digit dengan -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }
        // hapus karakter yang tidak diinginkan
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     * Menghasilkan nama file yang unik dan rapi berdasarkan kategori dan nama subjek
     */
    public static function generateFilename($category, $subjectName, $extension) {
        $cleanName = self::slugify($subjectName);
        $timestamp = time();
        $random = rand(100, 999);
        $ext = strtolower($extension);
        return "{$category}_{$cleanName}_{$timestamp}_{$random}.{$ext}";
    }

    /**
     * [BARU] Proses URL Foto (Static Method)
     * Digunakan untuk menangani URL foto dari Asisten, Manajemen, dan Detail agar tidak error.
     */
    public static function processPhotoUrl($fotoName, $nama) {
        $namaEnc = urlencode($nama ?? 'User');
        
        // Default Avatar (UI Avatars)
        $imgUrl = "https://ui-avatars.com/api/?name={$namaEnc}&background=eff6ff&color=2563eb&size=256&bold=true";

        if (!empty($fotoName) && strpos($fotoName, 'ui-avatars') === false) {
            if (strpos($fotoName, 'http') !== false) {
                // Jika URL eksternal
                $imgUrl = $fotoName;
            } else {
                // Tentukan Base URL (Fallback jika konstanta belum ada)
                $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : (defined('ASSETS_URL') ? ASSETS_URL : '');

                // Cek Folder Uploads (Prioritas Utama - Struktur Baru)
                $path1 = ROOT_PROJECT . '/public/assets/uploads/' . $fotoName;
                
                // Cek Folder Images Manajemen (Legacy)
                $path2 = ROOT_PROJECT . '/public/images/manajemen/' . $fotoName;
                
                // Cek Folder Images Asisten (Legacy)
                $path3 = ROOT_PROJECT . '/public/images/asisten/' . $fotoName;

                if (file_exists($path1)) {
                    $imgUrl = $baseUrl . '/assets/uploads/' . $fotoName;
                } elseif (file_exists($path2)) {
                    $imgUrl = $baseUrl . '/images/manajemen/' . $fotoName;
                } elseif (file_exists($path3)) {
                    $imgUrl = $baseUrl . '/images/asisten/' . $fotoName;
                }
            }
        }
        return $imgUrl;
    }
}

/**
 * Redirect to a specific URL
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

/**
 * Print debug information
 */
function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die;
}

/**
 * Return JSON response
 */
function json_response($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}

/**
 * Sanitize input
 */
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

/**
 * Check if POST request
 */
function is_post() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Check if GET request
 */
function is_get() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}
?>