<?php

/**
 * ImageOptimizer - Kompresi & Resize Gambar saat Upload
 * 
 * Menggunakan PHP GD Library untuk mengoptimalkan ukuran file gambar
 * agar halaman web tidak lag saat scroll karena memuat gambar berukuran besar.
 * 
 * @package App\Helpers
 */
class ImageOptimizer
{
    /** @var int Lebar maksimum gambar dalam piksel */
    private static $maxWidth = 800;

    /** @var int Tinggi maksimum gambar dalam piksel */
    private static $maxHeight = 800;

    /** @var int Kualitas kompresi JPEG (0-100, semakin tinggi semakin baik) */
    private static $jpegQuality = 80;

    /** @var int Level kompresi PNG (0-9, semakin tinggi semakin kecil) */
    private static $pngCompression = 6;

    /**
     * Mengoptimalkan gambar setelah diupload.
     * Melakukan resize jika dimensi melebihi batas dan mengkompresi kualitas.
     *
     * @param string $filePath Path absolut ke file gambar yang akan dioptimalkan
     * @param int $maxWidth Lebar maksimum (default 800px)
     * @param int $maxHeight Tinggi maksimum (default 800px)
     * @param bool $cropSquare Apakah gambar harus dicrop menjadi 1:1
     * @return bool True jika berhasil dioptimalkan
     */
    public static function optimize(string $filePath, int $maxWidth = 0, int $maxHeight = 0, bool $cropSquare = false): bool
    {
        if (!file_exists($filePath) || !is_file($filePath)) {
            return false;
        }

        // Gunakan parameter custom atau default
        $maxW = $maxWidth > 0 ? $maxWidth : self::$maxWidth;
        $maxH = $maxHeight > 0 ? $maxHeight : self::$maxHeight;

        // Deteksi tipe gambar
        $imageInfo = @getimagesize($filePath);
        if ($imageInfo === false) {
            return false; // Bukan file gambar yang valid
        }

        $mime = $imageInfo['mime'];
        $origWidth = $imageInfo[0];
        $origHeight = $imageInfo[1];

        // Buat resource gambar dari file
        $sourceImage = @self::createImageFromFile($filePath, $mime);
        if ($sourceImage === null) {
            return false;
        }

        // Koreksi orientasi jika terputar berdasarkan EXIF
        $sourceImage = self::autoRotateImage($sourceImage, $filePath);
        $origWidth = imagesx($sourceImage);
        $origHeight = imagesy($sourceImage);

        // Skip jika gambar sudah kecil (di bawah 200KB, dimensi kecil, dan tidak meminta crop)
        $fileSize = filesize($filePath);
        if (!$cropSquare && $fileSize < 200 * 1024 && $origWidth <= $maxW && $origHeight <= $maxH) {
            @imagedestroy($sourceImage);
            return true; // Sudah optimal
        }

        // Estimasi kebutuhan memori: width * height * 4 bytes (RGBA) * 2 (source + destination)
        // Tambah margin 20% untuk overhead GD
        $estimatedMemory = $origWidth * $origHeight * 4 * 2 * 1.2;
        $memoryLimit = self::getMemoryLimitBytes();
        $currentUsage = memory_get_usage(true);

        if (($currentUsage + $estimatedMemory) > $memoryLimit) {
            @imagedestroy($sourceImage);
            return false;
        }

        if ($cropSquare) {
            // Tentukan ukuran target square (misalnya 400x400)
            $newWidth = $maxW > 0 && $maxW === $maxH ? $maxW : 400;
            $newHeight = $newWidth;

            // Cari sisi terpendek
            $size = min($origWidth, $origHeight);

            // Tentukan titik awal crop
            $srcX = 0;
            $srcY = 0;

            if ($origWidth > $origHeight) {
                // Lanskap: Potong tengah horizontal
                $srcX = (int) round(($origWidth - $origHeight) / 2);
            } else if ($origHeight > $origWidth) {
                // Potret: Geser crop sedikit ke atas (20% dari sisa tinggi) agar fokus ke area wajah
                $srcY = (int) round(($origHeight - $origWidth) * 0.20);
            }

            // Buat canvas baru 1:1
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

            // Pertahankan transparansi
            if ($mime === 'image/png' || $mime === 'image/webp') {
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                $transparent = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
                imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            // Lakukan crop dan resize sekaligus
            imagecopyresampled(
                $resizedImage, $sourceImage,
                0, 0, $srcX, $srcY,
                $newWidth, $newHeight,
                $size, $size
            );
        } else {
            // Hitung dimensi baru dengan menjaga rasio aspek
            $ratio = min($maxW / $origWidth, $maxH / $origHeight, 1.0);
            $newWidth = (int) round($origWidth * $ratio);
            $newHeight = (int) round($origHeight * $ratio);

            // Buat canvas baru
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

            // Pertahankan transparansi untuk PNG dan WebP
            if ($mime === 'image/png' || $mime === 'image/webp') {
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                $transparent = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
                imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            // Resize gambar dengan kualitas tinggi (resampling)
            imagecopyresampled(
                $resizedImage, $sourceImage,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $origWidth, $origHeight
            );
        }

        // Simpan gambar yang sudah dioptimalkan (menimpa file asli)
        $result = self::saveImage($resizedImage, $filePath, $mime);

        // Bersihkan memori
        @imagedestroy($sourceImage);
        @imagedestroy($resizedImage);

        return $result;
    }

    /**
     * Mengkonversi file gambar (JPEG/PNG/GIF) menjadi format WebP.
     *
     * File asal dihapus setelah konversi berhasil agar tidak menumpuk file duplikat.
     * SVG atau format lain yang tidak didukung GD akan dilewati (return false) dan
     * file asal dibiarkan apa adanya.
     *
     * @param string $filePath Path absolut file gambar sumber (idealnya sudah dioptimalkan lewat optimize())
     * @param int $quality Kualitas kompresi WebP (0-100)
     * @return string|false Path absolut file .webp baru jika berhasil, false jika dilewati/gagal
     */
    public static function convertToWebp(string $filePath, int $quality = 80)
    {
        if (!file_exists($filePath) || !is_file($filePath)) {
            return false;
        }

        if (!function_exists('imagewebp')) {
            return false; // GD di server tidak mendukung WebP
        }

        $imageInfo = @getimagesize($filePath);
        if ($imageInfo === false) {
            return false;
        }

        $mime = $imageInfo['mime'];

        // Sudah WebP, tidak perlu dikonversi ulang
        if ($mime === 'image/webp') {
            return false;
        }

        $image = self::createImageFromFile($filePath, $mime);
        if ($image === null) {
            return false; // Format tidak didukung GD (misal SVG)
        }

        // Pertahankan transparansi (PNG/GIF)
        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        $webpPath = preg_replace('/\.[^.\/]+$/', '.webp', $filePath);
        $success = imagewebp($image, $webpPath, $quality);
        @imagedestroy($image);

        if (!$success || !file_exists($webpPath)) {
            return false;
        }

        // Hapus file asli agar tidak ada file duplikat (jpg lama + webp baru)
        if ($webpPath !== $filePath) {
            @unlink($filePath);
        }

        return $webpPath;
    }

    /**
     * Memperbaiki orientasi gambar JPEG berdasarkan metadata EXIF.
     * 
     * @param resource $image Resource GD Image
     * @param string $filePath Path ke file gambar asli
     * @return resource Resource GD Image yang sudah dirotasi jika perlu
     */
    private static function autoRotateImage($image, string $filePath)
    {
        if (!function_exists('exif_read_data')) {
            return $image;
        }

        // EXIF hanya didukung oleh JPEG/TIFF
        $mimeType = @mime_content_type($filePath);
        if ($mimeType !== 'image/jpeg' && $mimeType !== 'image/jpg') {
            return $image;
        }

        $exif = @exif_read_data($filePath);
        if (empty($exif) || empty($exif['Orientation'])) {
            return $image;
        }

        $orientation = $exif['Orientation'];

        switch ($orientation) {
            case 3: // 180 degrees
                $rotated = imagerotate($image, 180, 0);
                if ($rotated !== false) {
                    @imagedestroy($image);
                    $image = $rotated;
                }
                break;
            case 6: // 90 degrees CW (rotate 270 CCW in imagerotate)
                $rotated = imagerotate($image, -90, 0);
                if ($rotated !== false) {
                    @imagedestroy($image);
                    $image = $rotated;
                }
                break;
            case 8: // 270 degrees CW (rotate 90 CCW in imagerotate)
                $rotated = imagerotate($image, 90, 0);
                if ($rotated !== false) {
                    @imagedestroy($image);
                    $image = $rotated;
                }
                break;
        }

        return $image;
    }

    /**
     * Membuat resource GD Image dari file berdasarkan tipe MIME.
     *
     * @param string $filePath Path ke file gambar
     * @param string $mime Tipe MIME gambar
     * @return \GdImage|null Resource GD Image atau null jika gagal
     */
    private static function createImageFromFile(string $filePath, string $mime)
    {
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                return @imagecreatefromjpeg($filePath);
            case 'image/png':
                return @imagecreatefrompng($filePath);
            case 'image/gif':
                return @imagecreatefromgif($filePath);
            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {
                    return @imagecreatefromwebp($filePath);
                }
                return null;
            default:
                return null; // SVG dan format lain tidak didukung GD
        }
    }

    /**
     * Menyimpan resource GD Image ke file.
     *
     * @param \GdImage $image Resource GD Image
     * @param string $filePath Path tujuan penyimpanan
     * @param string $mime Tipe MIME untuk menentukan format output
     * @return bool True jika berhasil disimpan
     */
    private static function saveImage($image, string $filePath, string $mime): bool
    {
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                return imagejpeg($image, $filePath, self::$jpegQuality);
            case 'image/png':
                return imagepng($image, $filePath, self::$pngCompression);
            case 'image/gif':
                return imagegif($image, $filePath);
            case 'image/webp':
                if (function_exists('imagewebp')) {
                    return imagewebp($image, $filePath, self::$jpegQuality);
                }
                return false;
            default:
                return false;
        }
    }

    /**
     * Mengambil batas memori PHP dalam bytes.
     *
     * @return int Batas memori dalam bytes
     */
    private static function getMemoryLimitBytes(): int
    {
        $limit = ini_get('memory_limit');
        if ($limit == -1) {
            return PHP_INT_MAX; // Unlimited
        }

        $unit = strtolower(substr($limit, -1));
        $value = (int) $limit;

        switch ($unit) {
            case 'g': $value *= 1024;
            case 'm': $value *= 1024;
            case 'k': $value *= 1024;
        }

        return $value;
    }
}
