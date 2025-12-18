<?php
define('ROOT_PROJECT', dirname(__DIR__)); 
require_once ROOT_PROJECT . '/app/views/admin/templates/header.php';
?>

<style>
    /* Custom Styles for Upload Page to match Admin Theme */
    .upload-container {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-top: 20px;
    }
    
    .upload-main {
        flex: 2;
        min-width: 300px;
    }
    
    .upload-sidebar {
        flex: 1;
        min-width: 250px;
    }

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
    .upload-area:hover, .upload-area.dragover {
        border-color: #3498db;
        background-color: #eef7fb;
    }
    
    .file-info {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .upload-icon {
        font-size: 3rem;
        color: #7f8c8d;
        margin-bottom: 1rem;
    }

    /* Progress Bar */
    .progress-container {
        display: none;
        margin-top: 15px;
    }
    .progress {
        background-color: #e9ecef;
        border-radius: 0.25rem;
        height: 1rem;
        overflow: hidden;
    }
    .progress-bar {
        background-color: #3498db;
        height: 100%;
        transition: width 0.6s ease;
        width: 0%;
    }
    .progress-text {
        text-align: center;
        font-size: 0.85rem;
        color: #666;
        margin-top: 5px;
    }

    /* Alerts */
    .alert {
        padding: 15px;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
    .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
    .alert-info { color: #0c5460; background-color: #d1ecf1; border-color: #bee5eb; }

    /* Utilities */
    .text-muted { color: #6c757d; }
    .text-success { color: #28a745; }
    .text-warning { color: #856404; }
    .text-danger { color: #dc3545; }
    .text-info { color: #17a2b8; }
    .small { font-size: 0.875em; }
    .mb-0 { margin-bottom: 0; }
    .mt-3 { margin-top: 1rem; }
    .d-none { display: none; }
    
    /* Buttons override/addition */
    .btn-secondary { background-color: #6c757d; color: white; }
    .btn-info { background-color: #17a2b8; color: white; }
    .btn-primary { background-color: #007bff; color: white; }
    .btn-success { background-color: #28a745; color: white; }
    
    /* Result Grid */
    .result-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 15px;
        text-align: center;
    }
    .stat-item h4 { margin: 0; font-size: 1.5rem; }
    .stat-item span { font-size: 0.8rem; color: #666; }
</style>

<div class="admin-header">
    <h1>Upload Jadwal Praktikum</h1>
    <div class="btn-group">
        <a href="<?php echo BASE_URL; ?>/public/admin-jadwal.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button type="button" class="btn btn-info" onclick="downloadTemplate()">
            <i class="fas fa-download"></i> Template
        </button>
    </div>
</div>

<div class="upload-container">
    <!-- Main Upload Area -->
    <div class="upload-main">
        <div class="card">
            <h3>Upload File Excel</h3>
            <p class="text-muted">Upload file jadwal praktikum dalam format Excel (.xlsx, .xls)</p>
            
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="upload-area" id="uploadArea">
                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                    <p class="mb-2"><strong>Klik atau drag & drop file di sini</strong></p>
                    <p class="text-muted small">Format: .xlsx, .xls | Maks: 5MB</p>
                    <input type="file" id="fileInput" name="excel_file" accept=".xlsx,.xls" style="display: none;">
                </div>
                
                <div id="fileInfo" class="file-info" style="display: none;">
                    <div style="display: flex; align-items: center;">
                        <i class="fas fa-file-excel text-success" style="font-size: 1.5rem; margin-right: 10px;"></i>
                        <div>
                            <strong id="fileName">filename.xlsx</strong>
                            <div class="text-muted small" id="fileSize">0 KB</div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-delete" style="padding: 5px 10px;" onclick="clearFile()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="progress-container" id="progressContainer">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <div class="progress-text" id="progressText">Mengunggah...</div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-add" id="uploadBtn" disabled style="width: 100%;">
                        <i class="fas fa-upload"></i> Upload Jadwal
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Area -->
        <div class="card mt-3" id="resultContainer" style="display: none;">
            <h3>Hasil Upload</h3>
            <div id="resultContent"></div>
        </div>
    </div>

    <!-- Sidebar Instructions -->
    <div class="upload-sidebar">
        <div class="card">
            <h3>Petunjuk</h3>
            <div style="font-size: 0.9rem; color: #555;">
                <p><strong>Format File:</strong></p>
                <ul style="padding-left: 20px; margin-bottom: 15px;">
                    <li>Gunakan template yang disediakan.</li>
                    <li>Format file harus Excel (.xlsx / .xls).</li>
                </ul>

                <p><strong>Kolom Wajib:</strong></p>
                <ul style="padding-left: 20px; margin-bottom: 15px;">
                    <li>Mata Kuliah (Sesuai Data)</li>
                    <li>Laboratorium (Sesuai Data)</li>
                    <li>Hari, Waktu Mulai, Waktu Selesai</li>
                    <li>Kelas, Status</li>
                </ul>

                <div class="alert alert-info" style="font-size: 0.85rem;">
                    <i class="fas fa-info-circle"></i> <strong>Tips:</strong><br>
                    Pastikan nama Mata Kuliah dan Laboratorium persis sama dengan data di sistem.
                </div>
            </div>
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
            const validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                               'application/vnd.ms-excel'];
            const validExtensions = ['.xlsx', '.xls'];
            
            if (!validTypes.includes(file.type) && !validExtensions.some(ext => file.name.toLowerCase().endsWith(ext))) {
                alert('File harus berformat Excel (.xlsx atau .xls)');
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
            fileInfo.style.display = 'flex';
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
            alert('Pilih file terlebih dahulu');
            return;
        }

        const file = fileInput.files[0];
        const formData = new FormData();
        formData.append('excel_file', file);

        // Show progress
        progressContainer.style.display = 'block';
        uploadBtn.disabled = true;

        try {
            const response = await fetch(API_URL + '/jadwal-praktikum/upload', {
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
                message: 'Terjadi kesalahan saat mengupload file',
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
                    <h5><i class="fas fa-check-circle"></i> Upload Berhasil!</h5>
                    <p class="mb-0">${result.message}</p>
                </div>
                
                <div class="result-stats">
                    <div class="stat-item">
                        <h4 class="text-success">${result.data.success_count}</h4>
                        <span>Berhasil</span>
                    </div>
                    <div class="stat-item">
                        <h4 class="text-warning">${result.data.skip_count}</h4>
                        <span>Dilewati</span>
                    </div>
                    <div class="stat-item">
                        <h4 class="text-danger">${result.data.error_count}</h4>
                        <span>Error</span>
                    </div>
                    <div class="stat-item">
                        <h4 class="text-info">${result.data.total_processed}</h4>
                        <span>Total</span>
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
                        <ul style="padding-left: 20px;">
                            ${result.data.errors.map(error => `<li class="text-danger"><i class="fas fa-exclamation-triangle"></i> ${error}</li>`).join('')}
                        </ul>
                    </div>
                </div>
                ` : ''}
            `;
        } else {
            resultContent.innerHTML = `
                <div class="alert alert-danger">
                    <h5><i class="fas fa-times-circle"></i> Upload Gagal!</h5>
                    <p class="mb-0">${result.message || 'Terjadi kesalahan yang tidak diketahui'}</p>
                    ${result.error ? `<small class="text-muted d-block mt-2">Detail: ${result.error}</small>` : ''}
                </div>
                
                ${result.data && result.data.errors ? `
                <div class="mt-3">
                    <h6>Detail Error:</h6>
                    <div class="error-list">
                        <ul style="padding-left: 20px;">
                            ${result.data.errors.map(error => `<li class="text-danger"><i class="fas fa-exclamation-triangle"></i> ${error}</li>`).join('')}
                        </ul>
                    </div>
                </div>
                ` : ''}
                
                <div class="mt-3">
                    <a href="${BASE_URL}/public/data-referensi.php" class="btn btn-info" target="_blank">
                        <i class="fas fa-info-circle"></i> Lihat Data Referensi
                    </a>
                </div>
            `;
        }

        // Scroll to results
        resultContainer.scrollIntoView({ behavior: 'smooth' });
    }

    function downloadTemplate() {
        const url = API_URL + '/jadwal-praktikum/download-template';
        
        // Buat link download sementara
        const link = document.createElement('a');
        link.href = url;
        link.download = 'template_jadwal_praktikum.xlsx';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>

<?php require_once ROOT_PROJECT . '/app/views/admin/templates/footer.php'; ?>