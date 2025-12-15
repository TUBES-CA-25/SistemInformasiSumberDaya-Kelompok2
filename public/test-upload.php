<?php
// Simple test untuk verify FormData dan $_FILES

header('Content-Type: application/json');

error_log('=== TEST UPLOAD ===');
error_log('REQUEST METHOD: ' . $_SERVER['REQUEST_METHOD']);
error_log('CONTENT TYPE: ' . ($_SERVER['CONTENT_TYPE'] ?? 'NOT SET'));
error_log('POST: ' . json_encode($_POST));
error_log('FILES keys: ' . json_encode(array_keys($_FILES)));

if (isset($_FILES['gambar'])) {
    error_log('FILE GAMBAR DETECTED!');
    error_log('File info: ' . json_encode([
        'name' => $_FILES['gambar']['name'],
        'type' => $_FILES['gambar']['type'],
        'size' => $_FILES['gambar']['size'],
        'error' => $_FILES['gambar']['error'],
        'tmp_name' => $_FILES['gambar']['tmp_name'],
        'tmp_exists' => file_exists($_FILES['gambar']['tmp_name'])
    ]));
} else {
    error_log('FILE GAMBAR NOT FOUND');
}

echo json_encode([
    'status' => 'debug',
    'post' => $_POST,
    'files_keys' => array_keys($_FILES),
    'has_gambar' => isset($_FILES['gambar']),
    'method' => $_SERVER['REQUEST_METHOD'],
    'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'NOT SET'
]);
?>
