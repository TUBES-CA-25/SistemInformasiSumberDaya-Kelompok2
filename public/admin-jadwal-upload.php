<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Jadwal Praktikum - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .upload-area {
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            background-color: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .upload-area:hover {
            border-color: #007bff;
            background-color: #e3f2fd;
        }
        .upload-area.dragover {
            border-color: #007bff;
            background-color: #e3f2fd;
        }
        .file-info {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-top: 10px;
        }
        .progress-container {
            display: none;
        }
        .result-container {
            display: none;
        }
        .error-list {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block bg-light sidebar">
                <div class="sidebar-sticky pt-3">
                    <h5>Admin Panel</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="admin-dashboard.php">
                                <i class="bi bi-house"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="admin-jadwal.php">
                                <i class="bi bi-calendar"></i> Jadwal Praktikum
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin-jadwal-upload.php">
                                <i class="bi bi-upload"></i> Upload Jadwal
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Upload Jadwal Praktikum</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-outline-secondary" onclick="downloadTemplate()">
                            <i class="bi bi-download"></i> Download Template
                        </button>
                    </div>
                </div>

                <!-- Upload Form -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Upload File Excel</h5>
                            </div>
                            <div class="card-body">
                                <form id="uploadForm" enctype="multipart/form-data">
                                    <div class="upload-area" id="uploadArea">
                                        <i class="bi bi-cloud-upload fs-1 text-muted mb-3"></i>
                                        <p class="mb-2">Klik untuk memilih file atau drag & drop file Excel di sini</p>
                                        <p class="text-muted small">File yang didukung: .xlsx, .xls (Maksimal 5MB)</p>
                                        <input type="file" id="fileInput" name="excel_file" accept=".xlsx,.xls" style="display: none;">
                                    </div>
                                    
                                    <div id="fileInfo" class="file-info" style="display: none;">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-file-earmark-excel text-success me-2"></i>
                                            <div class="flex-grow-1">
                                                <strong id="fileName"></strong>
                                                <div class="text-muted small" id="fileSize"></div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFile()">
                                                <i class="bi bi-x"></i>
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
                                        <button type="submit" class="btn btn-primary" id="uploadBtn" disabled>
                                            <i class="bi bi-upload"></i> Upload Jadwal
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="clearFile()">
                                            <i class="bi bi-arrow-clockwise"></i> Reset
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Results -->
                        <div class="result-container mt-4" id="resultContainer">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Hasil Upload</h5>
                                </div>
                                <div class="card-body" id="resultContent">
                                    <!-- Results will be populated here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Petunjuk Upload</h5>
                            </div>
                            <div class="card-body">
                                <h6>Format File:</h6>
                                <ul class="small">
                                    <li>File harus berformat Excel (.xlsx atau .xls)</li>
                                    <li>Maksimal ukuran file 5MB</li>
                                    <li>Gunakan template yang disediakan</li>
                                </ul>

                                <h6 class="mt-3">Kolom yang Diperlukan:</h6>
                                <ol class="small">
                                    <li><strong>Mata Kuliah:</strong> Nama mata kuliah (harus sudah terdaftar)</li>
                                    <li><strong>Laboratorium:</strong> Nama laboratorium (harus sudah terdaftar)</li>
                                    <li><strong>Hari:</strong> Senin, Selasa, Rabu, dst.</li>
                                    <li><strong>Waktu Mulai:</strong> Format HH:MM (08:00)</li>
                                    <li><strong>Waktu Selesai:</strong> Format HH:MM (10:00)</li>
                                    <li><strong>Kelas:</strong> Kelas praktikum (A, B, C)</li>
                                    <li><strong>Status:</strong> Aktif atau Nonaktif</li>
                                </ol>

                                <h6 class="mt-3">Tips:</h6>
                                <ul class="small">
                                    <li>Download template terlebih dahulu</li>
                                    <li>Pastikan data mata kuliah dan laboratorium sudah ada di sistem</li>
                                    <li>Periksa format waktu dan hari</li>
                                    <li>Hapus contoh data sebelum mengupload</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const fileInfo = document.getElementById('fileInfo');
        const uploadBtn = document.getElementById('uploadBtn');
        const uploadForm = document.getElementById('uploadForm');
        const progressContainer = document.getElementById('progressContainer');
        const resultContainer = document.getElementById('resultContainer');

        // Click to select file
        uploadArea.addEventListener('click', () => {
            fileInput.click();
        });

        // Drag and drop events
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
                alert('Pilih file terlebih dahulu');
                return;
            }

            const formData = new FormData();
            formData.append('excel_file', fileInput.files[0]);

            // Show progress
            progressContainer.style.display = 'block';
            uploadBtn.disabled = true;

            try {
                const response = await fetch('api.php/jadwal-praktikum/upload', {
                    method: 'POST',
                    body: formData
                });

                // Log response for debugging
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    // Response is not JSON, probably HTML error page
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
                        <h6><i class="bi bi-check-circle"></i> Upload Berhasil!</h6>
                        <p class="mb-0">${result.message}</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 text-success">${result.data.success_count}</div>
                                <div class="text-muted small">Berhasil</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 text-warning">${result.data.skip_count}</div>
                                <div class="text-muted small">Dilewati</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 text-danger">${result.data.error_count}</div>
                                <div class="text-muted small">Error</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 text-info">${result.data.total_processed}</div>
                                <div class="text-muted small">Total</div>
                            </div>
                        </div>
                    </div>

                    ${result.data.errors && result.data.errors.length > 0 ? `
                    <div class="mt-3">
                        <h6>Detail Error:</h6>
                        <div class="error-list">
                            <ul class="list-unstyled">
                                ${result.data.errors.map(error => `<li class="text-danger small"><i class="bi bi-exclamation-triangle"></i> ${error}</li>`).join('')}
                            </ul>
                        </div>
                    </div>
                    ` : ''}
                `;
            } else {
                resultContent.innerHTML = `
                    <div class="alert alert-danger">
                        <h6><i class="bi bi-x-circle"></i> Upload Gagal!</h6>
                        <p class="mb-0">${result.message || 'Terjadi kesalahan yang tidak diketahui'}</p>
                    </div>
                    
                    ${result.data && result.data.errors ? `
                    <div class="mt-3">
                        <h6>Detail Error:</h6>
                        <div class="error-list">
                            <ul class="list-unstyled">
                                ${result.data.errors.map(error => `<li class="text-danger small"><i class="bi bi-exclamation-triangle"></i> ${error}</li>`).join('')}
                            </ul>
                        </div>
                    </div>
                    ` : ''}
                `;
            }

            // Scroll to results
            resultContainer.scrollIntoView({ behavior: 'smooth' });
        }

        function downloadTemplate() {
            window.location.href = 'api.php/jadwal-praktikum/template';
        }
    </script>
</body>
</html>