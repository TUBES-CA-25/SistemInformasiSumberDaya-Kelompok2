<?php
define('ROOT_PROJECT', dirname(__DIR__)); 
require_once ROOT_PROJECT . '/app/views/admin/templates/header.php';
?>

<style>
    .upload-area {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        background-color: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    .upload-area:hover {
        border-color: #28a745;
        background-color: #f1f8e9;
    }
    .upload-area.dragover {
        border-color: #28a745;
        background-color: #f1f8e9;
    }
    .file-info {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        margin-top: 10px;
    }
    .upload-icon {
        font-size: 3rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }
    .progress-container {
        display: none;
    }
    .result-container {
        display: none;
    }
</style>

<div class="admin-header">
    <h1>Upload Jadwal Praktikum (CSV)</h1>
    <div class="btn-group">
        <a href="<?php echo BASE_URL; ?>/public/admin-jadwal.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <a href="<?php echo BASE_URL; ?>/public/admin-jadwal-upload.php" class="btn btn-info">
            <i class="fas fa-file-excel"></i> Upload Excel
        </a>
        <button type="button" class="btn btn-success" onclick="downloadCsvTemplate()">
            <i class="fas fa-download"></i> Download Template CSV
        </button>
    </div>
</div>

<div class="alert alert-warning">
    <h5><i class="fas fa-exclamation-triangle"></i> Mengapa menggunakan CSV?</h5>
    <p class="mb-0">Ekstensi ZIP PHP tidak tersedia di server, sehingga file Excel tidak dapat diproses. CSV adalah alternatif yang tidak membutuhkan ekstensi tambahan.</p>
</div>

<div class="card">
    <div class="card-header">
        <h3>Upload File CSV</h3>
        <p class="text-muted mb-0">Upload file jadwal praktikum dalam format CSV (Comma Separated Values)</p>
    </div>
    <div class="card-body">
        <form id="uploadForm" enctype="multipart/form-data">
            <div class="upload-area" id="uploadArea">
                <i class="fas fa-file-csv upload-icon text-success"></i>
                <p class="mb-2"><strong>Klik untuk memilih file CSV atau drag & drop file di sini</strong></p>
                <p class="text-muted small">File yang didukung: .csv | Maksimal 5MB</p>
                <input type="file" id="fileInput" name="csv_file" accept=".csv" style="display: none;">
            </div>
            
            <div id="fileInfo" class="file-info" style="display: none;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-csv text-success me-2" style="font-size: 1.5rem;"></i>
                    <div class="flex-grow-1">
                        <strong id="fileName"></strong>
                        <div class="text-muted small" id="fileSize"></div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFile()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="progress-container mt-3" id="progressContainer">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         role="progressbar" style="width: 0%"></div>
                </div>
                <div class="text-center mt-2">
                    <span id="progressText">Mengunggah...</span>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success" id="uploadBtn" disabled>
                    <i class="fas fa-upload"></i> Upload CSV
                </button>
                <button type="button" class="btn btn-secondary" onclick="clearFile()">
                    <i class="fas fa-redo"></i> Reset
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Results Card -->
<div class="result-container mt-4" id="resultContainer">
    <div class="card">
        <div class="card-header">
            <h3>Hasil Upload</h3>
        </div>
        <div class="card-body" id="resultContent">
            <!-- Results will be populated here -->
        </div>
    </div>
</div>

<!-- Instructions Card -->
<div class="card mt-4">
    <div class="card-header">
        <h3>Petunjuk Upload CSV</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Langkah-langkah:</h5>
                <ol>
                    <li>Download template CSV menggunakan tombol di atas</li>
                    <li>Buka file template dengan Excel atau editor text</li>
                    <li>Isi data sesuai format yang tersedia</li>
                    <li>Simpan file sebagai CSV (Comma Separated Values)</li>
                    <li>Upload file CSV yang sudah diisi</li>
                </ol>
            </div>
            <div class="col-md-6">
                <h5>Format Data:</h5>
                <ul>
                    <li><strong>Header pertama:</strong> Mata Kuliah, Laboratorium, Hari, Waktu Mulai, Waktu Selesai, Kelas, Status</li>
                    <li><strong>Encoding:</strong> UTF-8 (untuk karakter khusus)</li>
                    <li><strong>Separator:</strong> Koma (,)</li>
                    <li><strong>Ukuran maksimal:</strong> 5MB</li>
                </ul>
            </div>
        </div>
        
        <div class="alert alert-info mt-3">
            <h6><i class="fas fa-info-circle"></i> Tips Penting:</h6>
            <ul class="mb-0">
                <li>Pastikan nama mata kuliah dan laboratorium sesuai dengan data yang sudah ada</li>
                <li>Gunakan format waktu 24 jam (contoh: 08:00, 14:30)</li>
                <li>Jika terjadi error, periksa <a href="<?php echo BASE_URL; ?>/public/data-referensi.php" target="_blank">data referensi</a></li>
                <li>Jika Excel tersedia, gunakan fitur "Save As" → "CSV UTF-8"</li>
            </ul>
        </div>
    </div>
</div>

<script>
    // Global variables
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const fileInfo = document.getElementById('fileInfo');
    const uploadBtn = document.getElementById('uploadBtn');
    const uploadForm = document.getElementById('uploadForm');
    const progressContainer = document.getElementById('progressContainer');
    const resultContainer = document.getElementById('resultContainer');

    // Drag and drop events
    uploadArea.addEventListener('click', () => fileInput.click());
    
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect();
        }
    });

    // File input change
    fileInput.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        const file = fileInput.files[0];
        if (file) {
            // Validate file type
            const validTypes = ['text/csv', 'application/csv', 'application/vnd.ms-excel'];
            const validExtensions = ['.csv'];
            
            if (!validTypes.includes(file.type) && !validExtensions.some(ext => file.name.toLowerCase().endsWith(ext))) {
                alert('File harus berformat CSV (.csv)');
                clearFile();
                return;
            }

            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file maksimal 5MB');
                clearFile();
                return;
            }

            // Show file info
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = formatFileSize(file.size);
            fileInfo.style.display = 'block';
            uploadArea.style.display = 'none';
            uploadBtn.disabled = false;
        }
    }

    function clearFile() {
        fileInput.value = '';
        fileInfo.style.display = 'none';
        uploadArea.style.display = 'block';
        uploadBtn.disabled = true;
        progressContainer.style.display = 'none';
        resultContainer.style.display = 'none';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Form submission
    uploadForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if (!fileInput.files[0]) {
            alert('Pilih file CSV terlebih dahulu');
            return;
        }

        const formData = new FormData();
        formData.append('csv_file', fileInput.files[0]);

        try {
            progressContainer.style.display = 'block';
            uploadBtn.disabled = true;

            const response = await fetch(API_URL + '/jadwal-praktikum/upload-csv', {
                method: 'POST',
                body: formData
            });

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.log('Non-JSON response:', text);
                throw new Error('Server returned non-JSON response. Check console for details.');
            }

            const result = await response.json();
            progressContainer.style.display = 'none';
            uploadBtn.disabled = false;

            displayResult(result, response.ok);

        } catch (error) {
            progressContainer.style.display = 'none';
            uploadBtn.disabled = false;
            
            displayResult({
                message: 'Terjadi kesalahan saat mengupload file CSV',
                error: error.message
            }, false);
        }
    });

    function displayResult(result, isSuccess) {
        resultContainer.style.display = 'block';
        const resultContent = document.getElementById('resultContent');

        if (isSuccess) {
            resultContent.innerHTML = `
                <div class="alert alert-success">
                    <h5><i class="fas fa-check-circle"></i> Upload CSV Berhasil!</h5>
                    <p class="mb-0">${result.message}</p>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3 text-center">
                        <div class="h4 text-success">${result.data.success_count}</div>
                        <div class="text-muted">Berhasil</div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="h4 text-warning">${result.data.skip_count}</div>
                        <div class="text-muted">Dilewati</div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="h4 text-danger">${result.data.error_count}</div>
                        <div class="text-muted">Error</div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="h4 text-info">${result.data.total_processed}</div>
                        <div class="text-muted">Total</div>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="${BASE_URL}/public/admin-jadwal.php" class="btn btn-primary">
                        <i class="fas fa-list"></i> Lihat Daftar Jadwal
                    </a>
                </div>

                ${result.data.errors && result.data.errors.length > 0 ? `
                    <div class="mt-3">
                        <h6>Detail Error:</h6>
                        <div class="error-list">
                            <ul class="list-unstyled">
                                ${result.data.errors.map(error => `<li class="text-danger"><i class="fas fa-exclamation-triangle"></i> ${error}</li>`).join('')}
                            </ul>
                        </div>
                    </div>
                ` : ''}
            `;
        } else {
            resultContent.innerHTML = `
                <div class="alert alert-danger">
                    <h5><i class="fas fa-times-circle"></i> Upload CSV Gagal!</h5>
                    <p class="mb-0">${result.message || 'Terjadi kesalahan yang tidak diketahui'}</p>
                    ${result.error ? `<small class="text-muted d-block mt-2">Detail: ${result.error}</small>` : ''}
                </div>
                
                ${result.data && result.data.errors ? `
                <div class="mt-3">
                    <h6>Detail Error:</h6>
                    <div class="error-list">
                        <ul class="list-unstyled">
                            ${result.data.errors.map(error => `<li class="text-danger"><i class="fas fa-exclamation-triangle"></i> ${error}</li>`).join('')}
                        </ul>
                    </div>
                </div>
                ` : ''}
                
                <div class="mt-3">
                    <a href="${BASE_URL}/public/data-referensi.php" class="btn btn-info" target="_blank">
                        <i class="fas fa-info-circle"></i> Lihat Data Referensi
                    </a>
                    <button type="button" class="btn btn-success" onclick="downloadCsvTemplate()">
                        <i class="fas fa-download"></i> Download Template CSV
                    </button>
                </div>
            `;
        }

        // Scroll to results
        resultContainer.scrollIntoView({ behavior: 'smooth' });
    }

    function downloadCsvTemplate() {
        const url = API_URL + '/jadwal-praktikum/csv-template';
        
        // Buat link download sementara
        const link = document.createElement('a');
        link.href = url;
        link.download = 'template_jadwal_praktikum.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>

<?php require_once ROOT_PROJECT . '/app/views/admin/templates/footer.php'; ?>