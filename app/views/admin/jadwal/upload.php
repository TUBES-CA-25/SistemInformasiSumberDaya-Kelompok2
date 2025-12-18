<div class="admin-header">
    <h1>Upload Jadwal Praktikum (Excel)</h1>
    <a href="javascript:void(0)" onclick="navigate('admin/jadwal')" class="btn" style="background: #95a5a6;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <h3><i class="fas fa-info-circle"></i> Panduan Upload</h3>
    <ol style="line-height: 2;">
        <li>Download template Excel terlebih dahulu</li>
        <li>Isi data jadwal sesuai format template</li>
        <li>Simpan file dan upload kembali ke sistem</li>
        <li>Sistem akan memvalidasi dan menyimpan data otomatis</li>
    </ol>
</div>

<div class="card">
    <h3><i class="fas fa-download"></i> Download Template</h3>
    <p style="color: #666; margin-bottom: 20px;">Download template Excel untuk memudahkan input data jadwal praktikum.</p>
    <a href="<?php echo API_URL; ?>/jadwal-praktikum/template" class="btn btn-primary" style="background: #27ae60;">
        <i class="fas fa-file-excel"></i> Download Template Excel
    </a>
</div>

<div class="card">
    <h3><i class="fas fa-upload"></i> Upload File Excel</h3>
    <form id="uploadForm" enctype="multipart/form-data">
        <div class="form-group">
            <label>Pilih File Excel (.xlsx)</label>
            <input type="file" name="excel_file" id="fileInput" accept=".xlsx,.xls" required 
                   style="padding: 10px; border: 2px dashed #ddd; border-radius: 8px; width: 100%; cursor: pointer;">
            <small style="color: #666; display: block; margin-top: 5px;">
                Format: .xlsx atau .xls | Maksimal 5MB
            </small>
        </div>
        
        <div id="fileInfo" style="display: none; padding: 15px; background: #e8f5e9; border-radius: 8px; margin-bottom: 20px;">
            <p style="margin: 0; color: #27ae60;">
                <i class="fas fa-check-circle"></i> 
                File dipilih: <strong id="fileName"></strong>
            </p>
        </div>
        
        <button type="submit" class="btn btn-success" style="background: #3498db; padding: 12px 30px;">
            <i class="fas fa-cloud-upload-alt"></i> Upload & Proses
        </button>
    </form>
    
    <div id="uploadProgress" style="display: none; margin-top: 20px;">
        <div style="background: #f0f0f0; border-radius: 10px; overflow: hidden; height: 30px;">
            <div id="progressBar" style="background: linear-gradient(90deg, #3498db, #2ecc71); height: 100%; width: 0%; transition: width 0.3s; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                0%
            </div>
        </div>
        <p id="progressText" style="text-align: center; margin-top: 10px; color: #666;"></p>
    </div>
    
    <div id="uploadResult" style="margin-top: 20px;"></div>
</div>

<script>
const fileInput = document.getElementById('fileInput');
const fileInfo = document.getElementById('fileInfo');
const fileName = document.getElementById('fileName');
const uploadForm = document.getElementById('uploadForm');
const uploadProgress = document.getElementById('uploadProgress');
const progressBar = document.getElementById('progressBar');
const progressText = document.getElementById('progressText');
const uploadResult = document.getElementById('uploadResult');

fileInput.addEventListener('change', function(e) {
    if (this.files.length > 0) {
        const file = this.files[0];
        fileName.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
        fileInfo.style.display = 'block';
        uploadResult.innerHTML = '';
    }
});

uploadForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const file = fileInput.files[0];
    if (!file) {
        alert('Pilih file terlebih dahulu!');
        return;
    }
    
    // Validate file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file terlalu besar! Maksimal 5MB');
        return;
    }
    
    // Validate file type
    const validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];
    if (!validTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls)$/i)) {
        alert('Format file tidak valid! Gunakan .xlsx atau .xls');
        return;
    }
    
    const formData = new FormData();
    formData.append('excel_file', file);
    
    uploadProgress.style.display = 'block';
    progressBar.style.width = '0%';
    progressBar.textContent = '0%';
    progressText.textContent = 'Mengunggah file...';
    uploadResult.innerHTML = '';
    
    try {
        // Simulate progress
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += 10;
            if (progress <= 90) {
                progressBar.style.width = progress + '%';
                progressBar.textContent = progress + '%';
            }
        }, 200);
        
        const response = await fetch(API_URL + '/jadwal-praktikum/upload', {
            method: 'POST',
            body: formData
        });
        
        clearInterval(progressInterval);
        progressBar.style.width = '100%';
        progressBar.textContent = '100%';
        progressText.textContent = 'Upload selesai!';
        
        const result = await response.json();
        
        if (result.status === 'success' || response.ok) {
            uploadResult.innerHTML = `
                <div style="padding: 20px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; color: #155724;">
                    <h4 style="margin: 0 0 10px 0;"><i class="fas fa-check-circle"></i> Upload Berhasil!</h4>
                    <p style="margin: 0;">Data jadwal praktikum berhasil diupload dan disimpan ke database.</p>
                    ${result.data ? `<p style="margin: 5px 0 0 0;">Total data: <strong>${result.data.success_count || result.data.imported || 0}</strong> jadwal</p>` : ''}
                </div>
            `;
            
            // Reset form
            uploadForm.reset();
            fileInfo.style.display = 'none';
            
            // Redirect after 2 seconds
            setTimeout(() => {
                navigate('admin/jadwal');
            }, 2000);
        } else {
            // Show detailed errors if available
            let errorDetails = '';
            if (result.data && result.data.errors && result.data.errors.length > 0) {
                errorDetails = '<ul style="margin: 10px 0 0 20px; text-align: left;">';
                result.data.errors.slice(0, 10).forEach(err => {
                    errorDetails += `<li>${err}</li>`;
                });
                if (result.data.errors.length > 10) {
                    errorDetails += `<li><em>... dan ${result.data.errors.length - 10} error lainnya</em></li>`;
                }
                errorDetails += '</ul>';
            }
            
            throw new Error(result.message || 'Upload gagal', errorDetails);
        }
    } catch (error) {
        console.error('Upload error:', error);
        console.error('Error result:', result);
        
        let errorMessage = error.message || 'Terjadi kesalahan saat mengupload file.';
        let errorDetails = '';
        
        // Try to get detailed errors from result
        if (typeof result !== 'undefined' && result.data && result.data.errors) {
            errorDetails = '<div style="margin-top: 15px; max-height: 200px; overflow-y: auto; background: #fff; padding: 10px; border-radius: 4px;"><strong>Detail Error:</strong><ul style="margin: 5px 0 0 20px;">';
            result.data.errors.slice(0, 10).forEach(err => {
                errorDetails += `<li>${err}</li>`;
            });
            if (result.data.errors.length > 10) {
                errorDetails += `<li><em>... dan ${result.data.errors.length - 10} error lainnya</em></li>`;
            }
            errorDetails += '</ul></div>';
        }
        
        uploadResult.innerHTML = `
            <div style="padding: 20px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 8px; color: #721c24;">
                <h4 style="margin: 0 0 10px 0;"><i class="fas fa-exclamation-triangle"></i> Upload Gagal!</h4>
                <p style="margin: 0;">${errorMessage}</p>
                ${errorDetails}
                <p style="margin: 10px 0 0 0;"><small>Pastikan format file sesuai template dan data terisi dengan benar.</small></p>
            </div>
        `;
        progressText.textContent = 'Upload gagal!';
    }
});

function navigate(route) {
    if (window.location.port === '8000') {
        window.location.href = '/index.php?route=' + route;
    } else {
        window.location.href = '/' + route;
    }
}
</script>