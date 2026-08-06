<?php
/**
 * Image Optimization Script
 * Compresses large images for better LCP performance.
 * Run once: php optimize_images.php
 */

$imagesDir = __DIR__ . '/public/images/';

$targets = [
    // [source, target, max_width, quality]
    ['gedung-fikom-siang.webp', 'gedung-fikom-siang.webp', 1400, 72],
    ['gedung-fikom-malam.webp', 'gedung-fikom-malam.webp', 1400, 72],
    ['Pusat-Kompetensi.jpg', 'Pusat-Kompetensi.webp', 800, 75],
    ['RisetDanInovasi.png', 'RisetDanInovasi.webp', 800, 75],
    ['Infrastruktur-Modern.jpg', 'Infrastruktur-Modern.webp', 800, 75],
    ['logo-iclabs.png', 'logo-iclabs.webp', 400, 80],
    ['navbar-icon.png', 'navbar-icon.webp', 96, 80],
];

foreach ($targets as [$source, $target, $maxWidth, $quality]) {
    $sourcePath = $imagesDir . $source;
    $targetPath = $imagesDir . $target;
    
    if (!file_exists($sourcePath)) {
        echo "SKIP: $source not found\n";
        continue;
    }
    
    $originalSize = filesize($sourcePath);
    $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
    
    // Create image from source
    switch ($ext) {
        case 'webp': $img = @imagecreatefromwebp($sourcePath); break;
        case 'jpg': case 'jpeg': $img = @imagecreatefromjpeg($sourcePath); break;
        case 'png': $img = @imagecreatefrompng($sourcePath); break;
        default: echo "SKIP: $source (unsupported format)\n"; continue 2;
    }
    
    if (!$img) {
        echo "ERROR: Could not read $source\n";
        continue;
    }
    
    $w = imagesx($img);
    $h = imagesy($img);
    
    // Resize if larger than max width
    if ($w > $maxWidth) {
        $newW = $maxWidth;
        $newH = (int)($h * ($maxWidth / $w));
        $resized = imagecreatetruecolor($newW, $newH);
        
        // Preserve transparency for PNG
        if ($ext === 'png') {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
        }
        
        imagecopyresampled($resized, $img, 0, 0, 0, 0, $newW, $newH, $w, $h);
        imagedestroy($img);
        $img = $resized;
        echo "RESIZE: $source ({$w}x{$h} -> {$newW}x{$newH})\n";
    }
    
    // Backup original if overwriting
    if ($source === $target) {
        $backupPath = $imagesDir . pathinfo($source, PATHINFO_FILENAME) . '_original.' . $ext;
        if (!file_exists($backupPath)) {
            copy($sourcePath, $backupPath);
            echo "BACKUP: $source -> " . basename($backupPath) . "\n";
        }
    }
    
    // Save as WebP
    imagewebp($img, $targetPath, $quality);
    imagedestroy($img);
    
    $newSize = filesize($targetPath);
    $savings = round((1 - $newSize / $originalSize) * 100);
    
    echo "OK: $source (" . round($originalSize/1024) . "KB) -> $target (" . round($newSize/1024) . "KB) [-{$savings}%]\n";
}

echo "\nDone! All images optimized.\n";
