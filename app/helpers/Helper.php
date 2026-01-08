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
