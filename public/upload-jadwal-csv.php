<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Jadwal CSV - Alternatif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .alert { padding: 15px; border-radius: 5px; margin: 10px 0; }
        .alert-warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
        .alert-info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
        .upload-area { border: 2px dashed #ccc; padding: 30px; text-align: center; border-radius: 10px; }
        .btn { background: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 5px; text-decoration: none; display: inline-block; margin: 5px; }
        .btn-success { background: #28a745; }
        .btn-warning { background: #ffc107; color: #000; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload Jadwal Praktikum (CSV) - Solusi Alternatif</h1>
        
        <div class="alert alert-warning">
            <h5>⚠️ Mengapa menggunakan CSV?</h5>
            <p>Ekstensi ZIP PHP tidak tersedia di server Anda, sehingga file Excel tidak dapat diproses. 
            CSV adalah alternatif yang tidak membutuhkan ekstensi tambahan.</p>
        </div>

        <div class="alert alert-info">
            <h5>📋 Cara menggunakan:</h5>
            <ol>
                <li>Download template CSV</li>
                <li>Buka dengan Excel atau editor text</li>
                <li>Isi data sesuai format</li>
                <li>Simpan sebagai CSV</li>
                <li>Upload file CSV</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Upload File CSV</h5>
                        <a href="api.php/jadwal-praktikum/csv-template" class="btn btn-success">
                            📥 Download Template CSV
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="uploadForm" enctype="multipart/form-data">
                            <div class="upload-area">
                                <p>📄 Pilih file CSV untuk upload jadwal praktikum</p>
                                <input type="file" id="fileInput" name="csv_file" accept=".csv" required>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn">Upload CSV</button>
                                <a href="fix-zip-extension.php" class="btn btn-warning">
                                    🔧 Perbaiki Ekstensi ZIP
                                </a>
                            </div>
                        </form>

                        <div id="result" style="display: none; margin-top: 20px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Format CSV Required</h5>
                    </div>
                    <div class="card-body">
                        <small>
                            <strong>Kolom yang diperlukan (urutan harus sama):</strong>
                            <ol>
                                <li>Mata Kuliah</li>
                                <li>Laboratorium</li>
                                <li>Hari</li>
                                <li>Waktu Mulai (HH:MM)</li>
                                <li>Waktu Selesai (HH:MM)</li>
                                <li>Kelas</li>
                                <li>Status</li>
                            </ol>
                            
                            <strong>Contoh:</strong>
                            <pre style="font-size: 11px;">
Mata Kuliah,Laboratorium,Hari,Waktu Mulai,Waktu Selesai,Kelas,Status
Pemrograman Web,Lab Komputer 1,Senin,08:00,10:00,A,Aktif
Basis Data,Lab Komputer 2,Selasa,10:00,12:00,B,Aktif
                            </pre>
                        </small>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Troubleshooting</h5>
                    </div>
                    <div class="card-body">
                        <small>
                            <strong>Masalah umum:</strong>
                            <ul>
                                <li>Pastikan encoding CSV adalah UTF-8</li>
                                <li>Gunakan koma (,) sebagai separator</li>
                                <li>Jangan ada baris kosong</li>
                                <li>Mata kuliah dan lab harus sudah ada di database</li>
                            </ul>
                            
                            <strong>Links:</strong><br>
                            <a href="data-referensi.php">📋 Lihat Data Mata Kuliah & Lab</a><br>
                            <a href="simple-upload-test.php">🧪 Test Upload</a><br>
                            <a href="fix-zip-extension.php">🔧 Fix ZIP Extension</a><br>
                            <a href="admin-jadwal-upload.php">📄 Upload Excel (jika ZIP fixed)</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            const fileInput = document.getElementById('fileInput');
            const resultDiv = document.getElementById('result');
            
            if (!fileInput.files[0]) {
                alert('Pilih file CSV terlebih dahulu');
                return;
            }
            
            formData.append('csv_file', fileInput.files[0]);
            
            resultDiv.style.display = 'block';
            resultDiv.innerHTML = '<div class="alert alert-info">Mengupload file...</div>';
            
            try {
                const response = await fetch('api.php/jadwal-praktikum/upload-csv', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    resultDiv.innerHTML = `
                        <div class="alert alert-success">
                            <h6>✅ Upload Berhasil!</h6>
                            <p>${result.message}</p>
                            <ul>
                                <li>Total diproses: ${result.data.total_processed}</li>
                                <li>Berhasil: ${result.data.success_count}</li>
                                <li>Error: ${result.data.error_count}</li>
                            </ul>
                            ${result.data.errors.length > 0 ? '<strong>Errors:</strong><ul>' + result.data.errors.map(e => '<li>' + e + '</li>').join('') + '</ul>' : ''}
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <h6>❌ Upload Gagal</h6>
                            <p>${result.message}</p>
                            ${result.data && result.data.errors ? '<ul>' + result.data.errors.map(e => '<li>' + e + '</li>').join('') + '</ul>' : ''}
                        </div>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <h6>❌ Error</h6>
                        <p>Terjadi kesalahan: ${error.message}</p>
                    </div>
                `;
            }
        });
    </script>
</body>
</html>