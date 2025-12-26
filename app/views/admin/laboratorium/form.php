<div class="admin-header">
    <h1 id="formTitle">Tambah Laboratorium</h1>
    <a href="javascript:void(0)" onclick="navigate('admin/laboratorium')" class="btn" style="background: #95a5a6;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card" style="max-width: 800px;">
    <form id="labForm" enctype="multipart/form-data">
        <input type="hidden" id="idLaboratorium" name="idLaboratorium">

        <div class="form-group">
            <label>Nama Laboratorium <span style="color:red">*</span></label>
            <input type="text" id="nama" name="nama" placeholder="Contoh: Lab Pemrograman" required>
        </div>

        <div class="form-group">
            <label>Koordinator Lab</label>
            <select id="idKordinatorAsisten" name="idKordinatorAsisten">
                <option value="">-- Pilih Asisten --</option>
            </select>
            <small class="form-text text-muted">Pilih asisten yang bertanggung jawab sebagai koordinator lab ini (Opsional).</small>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi fasilitas dan kegunaan laboratorium..."></textarea>
        </div>

        <!-- Hardware Specifications -->
        <div class="form-section">
            <h3 style="color: #2c3e50; margin: 20px 0 15px 0; font-size: 18px; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px;">
                <i class="fas fa-desktop"></i> Spesifikasi Hardware
            </h3>
            
            <div class="form-row">
                <div class="form-group" style="flex: 1;">
                    <label>Processor</label>
                    <input type="text" id="processor" name="processor" placeholder="Contoh: Intel Core i7 Gen 12">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>RAM</label>
                    <input type="text" id="ram" name="ram" placeholder="Contoh: 32 GB DDR4">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="flex: 1;">
                    <label>Storage</label>
                    <input type="text" id="storage" name="storage" placeholder="Contoh: SSD NVMe 1 TB">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>GPU</label>
                    <input type="text" id="gpu" name="gpu" placeholder="Contoh: NVIDIA RTX 3060">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="flex: 1;">
                    <label>Monitor</label>
                    <input type="text" id="monitor" name="monitor" placeholder="Contoh: 24 Inch IPS Display">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Jumlah PC</label>
                    <input type="number" id="jumlahPc" name="jumlahPc" placeholder="0" min="0">
                </div>
            </div>
        </div>

        <!-- Software & Facilities -->
        <div class="form-section">
            <h3 style="color: #2c3e50; margin: 20px 0 15px 0; font-size: 18px; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px;">
                <i class="fas fa-code"></i> Software & Fasilitas
            </h3>
            
            <div class="form-group">
                <label>Software Terinstall</label>
                <textarea id="software" name="software" rows="3" placeholder="Contoh: Visual Studio Code, XAMPP, Node.js, Python, Android Studio"></textarea>
                <small class="form-text text-muted">Pisahkan dengan koma atau baris baru.</small>
            </div>

            <div class="form-group">
                <label>Fasilitas Pendukung</label>
                <textarea id="fasilitas" name="fasilitas" rows="3" placeholder="Contoh: AC Central, Proyektor HD, WiFi 6, Whiteboard"></textarea>
                <small class="form-text text-muted">Pisahkan dengan koma atau baris baru.</small>
            </div>
        </div>

        <div class="form-group">
            <label>Upload Gambar (Opsional - Bisa Multiple)</label>
            <div class="file-upload-wrapper">
                <input type="file" id="gambar" name="gambar[]" accept="image/*" multiple>
                <div id="preview-container" style="margin-top: 10px; display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 10px;">
                    <!-- Preview gambar akan ditampilkan di sini -->
                </div>
            </div>
            <small class="form-text text-muted">Format: JPG, PNG (Max 2MB per file). Pilih satu atau lebih gambar. Biarkan kosong jika tidak ingin mengubah gambar saat edit.</small>
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 30px;">
            <a href="javascript:void(0)" onclick="navigate('admin/laboratorium')" class="btn" style="background: #95a5a6; min-width: 120px;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-add" id="btnSave" style="min-width: 140px;">
                <i class="fas fa-save"></i> Simpan Data
            </button>
        </div>
    </form>
    <div id="message" style="margin-top: 15px;"></div>
</div>

<style>
.form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 0;
}

.form-row .form-group {
    margin-bottom: 20px;
}

.form-section {
    margin-top: 10px;
    padding-top: 10px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 24px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 15px;
    text-decoration: none;
    transition: all 0.3s ease;
    background: #667eea;
    color: white;
    gap: 8px;
}

.btn:hover {
    background: #5568d3;
}

.btn-add {
    background: #27ae60;
    color: white;
}

.btn-add:hover {
    background: #229954;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
        gap: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadAsisten();
    
    // Parse ID from route parameter (admin/laboratorium/{id}/edit)
    const params = new URLSearchParams(window.location.search);
    let route = params.get('route') || window.location.pathname;
    
    const matches = route.match(/admin\/laboratorium\/(\d+)\/edit/);
    
    if (matches && matches[1]) {
        const id = matches[1];
        document.getElementById('formTitle').textContent = 'Edit Laboratorium';
        document.getElementById('idLaboratorium').value = id;
        loadData(id);
    }

    // Image preview untuk multiple files
    document.getElementById('gambar').addEventListener('change', function(e) {
        const files = e.target.files;
        const container = document.getElementById('preview-container');
        container.innerHTML = ''; // Clear previous previews
        
        if (files.length > 0) {
            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.style.cssText = 'width: 100%; height: 120px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; cursor: pointer;';
                    img.title = file.name;
                    container.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        }
    });
});

function loadAsisten() {
    fetch(API_URL + '/asisten')
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success' || response.code === 200) {
            const select = document.getElementById('idKordinatorAsisten');
            response.data.forEach(asisten => {
                const option = document.createElement('option');
                option.value = asisten.idAsisten;
                option.textContent = asisten.nama + ' (' + asisten.nim + ')';
                select.appendChild(option);
            });
            // Jika ada pending nilai kordinator (dari loadData sebelum opsi siap), set sekarang
            if (window.pendingKordinatorAsisten) {
                try {
                    select.value = window.pendingKordinatorAsisten;
                } catch (e) {
                    console.warn('Failed setting pending kordinator value', e);
                }
                // clear pending to avoid reuse
                window.pendingKordinatorAsisten = null;
            }
        }
    })
    .catch(err => console.error('Error loading asisten:', err));
}

function loadData(id) {
    fetch(API_URL + '/laboratorium/' + id)
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success' || response.code === 200) {
            const data = response.data;
            document.getElementById('nama').value = data.nama || '';
            document.getElementById('deskripsi').value = data.deskripsi || '';
            document.getElementById('jumlahPc').value = data.jumlahPc || '';
            
            // Hardware specs
            document.getElementById('processor').value = data.processor || '';
            document.getElementById('ram').value = data.ram || '';
            document.getElementById('storage').value = data.storage || '';
            document.getElementById('gpu').value = data.gpu || '';
            document.getElementById('monitor').value = data.monitor || '';
            
            // Software & Facilities
            document.getElementById('software').value = data.software || '';
            document.getElementById('fasilitas').value = data.fasilitas || '';
            
            // Set selected asisten if exists. If options not loaded yet, store pending value to be applied
            if (data.idKordinatorAsisten) {
                const select = document.getElementById('idKordinatorAsisten');
                // try immediate set
                if (select.options && select.options.length > 1) {
                    select.value = data.idKordinatorAsisten;
                } else {
                    // mark pending value; loadAsisten will apply it when options are ready
                    window.pendingKordinatorAsisten = data.idKordinatorAsisten;
                }
            }
            
            // Display existing images in preview (read-only gallery)
            if (data.images && data.images.length > 0) {
                const container = document.getElementById('preview-container');
                container.innerHTML = '';
                data.images.forEach(image => {
                    const imagePath = image.namaGambar.includes('http') ? image.namaGambar : ASSETS_URL + '/assets/uploads/' + image.namaGambar;
                    const img = document.createElement('img');
                    img.src = imagePath;
                    img.style.cssText = 'width: 100%; height: 120px; object-fit: cover; border-radius: 4px; border: 2px solid #3498db; cursor: pointer; opacity: 0.6;';
                    img.title = 'Existing image - ' + image.namaGambar;
                    container.appendChild(img);
                });
            } else if (data.gambar) {
                // Fallback: single image
                const imagePath = data.gambar.includes('http') ? data.gambar : ASSETS_URL + '/assets/uploads/' + data.gambar;
                const container = document.getElementById('preview-container');
                container.innerHTML = '';
                const img = document.createElement('img');
                img.src = imagePath;
                img.style.cssText = 'width: 100%; height: 120px; object-fit: cover; border-radius: 4px; border: 2px solid #3498db; cursor: pointer; opacity: 0.6;';
                img.title = 'Existing image - ' + data.gambar;
                container.appendChild(img);
            }
        } else {
            alert('Data tidak ditemukan');
            navigate('admin/laboratorium');
        }
    })
    .catch(err => console.error(err));
}

document.getElementById('labForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('idLaboratorium').value;
    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    const formData = new FormData(this);
    
    let url = API_URL + '/laboratorium';
    if (id) {
        url += '/' + id;
        formData.append('_method', 'PUT'); 
    }

    fetch(url, { 
        method: 'POST', 
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' || data.code === 200 || data.code === 201) { 
            msg.innerHTML = '<div style="padding: 10px; background: #d4edda; color: #155724; border-radius: 4px;">Berhasil disimpan! Redirecting...</div>';
            setTimeout(() => { navigate('admin/laboratorium'); }, 1000);
        } else {
            msg.innerHTML = '<div style="padding: 10px; background: #f8d7da; color: #721c24; border-radius: 4px;">Gagal: ' + (data.message || 'Terjadi kesalahan') + '</div>';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
        }
    })
    .catch(err => {
        console.error(err);
        msg.innerHTML = '<div style="padding: 10px; background: #f8d7da; color: #721c24; border-radius: 4px;">Koneksi Error.</div>';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
    });
});

</script>