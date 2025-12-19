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
            <label>Lokasi Lab</label>
            <input type="text" id="lokasi" name="lokasi" placeholder="Contoh: Gedung Fikom Lt. 2 (Ruang 204)">
            <small class="form-text text-muted">Lokasi fisik laboratorium di kampus.</small>
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

            <div class="form-group">
                <label>Jumlah Kursi</label>
                <input type="number" id="jumlahKursi" name="jumlahKursi" placeholder="0" min="0">
            </div>
        </div>

        <div class="form-group">
            <label>Upload Gambar (Opsional)</label>
            <div class="file-upload-wrapper">
                <input type="file" id="gambar" name="gambar" accept="image/*">
                <div id="preview-container" style="margin-top: 10px; display: none;">
                    <img id="preview-image" src="" alt="Preview" style="max-width: 200px; border-radius: 4px; border: 1px solid #ddd;">
                </div>
            </div>
            <small class="form-text text-muted">Format: JPG, PNG (Max 2MB). Biarkan kosong jika tidak ingin mengubah gambar saat edit.</small>
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
    const route = new URLSearchParams(window.location.search).get('route') || '';
    const matches = route.match(/admin\/laboratorium\/(\d+)\/edit/);
    
    if (matches && matches[1]) {
        const id = matches[1];
        document.getElementById('formTitle').textContent = 'Edit Laboratorium';
        document.getElementById('idLaboratorium').value = id;
        loadData(id);
    }

    // Image preview
    document.getElementById('gambar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('preview-container').style.display = 'block';
            }
            reader.readAsDataURL(file);
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
            document.getElementById('lokasi').value = data.lokasi || '';
            document.getElementById('deskripsi').value = data.deskripsi || '';
            document.getElementById('jumlahPc').value = data.jumlahPc || '';
            document.getElementById('jumlahKursi').value = data.jumlahKursi || '';
            
            // Hardware specs
            document.getElementById('processor').value = data.processor || '';
            document.getElementById('ram').value = data.ram || '';
            document.getElementById('storage').value = data.storage || '';
            document.getElementById('gpu').value = data.gpu || '';
            document.getElementById('monitor').value = data.monitor || '';
            
            // Software & Facilities
            document.getElementById('software').value = data.software || '';
            document.getElementById('fasilitas').value = data.fasilitas || '';
            
            // Set selected asisten if exists
            if (data.idKordinatorAsisten) {
                setTimeout(() => {
                    document.getElementById('idKordinatorAsisten').value = data.idKordinatorAsisten;
                }, 500);
            }
            
            if (data.gambar) {
                const imagePath = data.gambar.includes('http') ? data.gambar : '/SistemInformasiSumberDaya-Kelompok2/public/assets/uploads/' + data.gambar;
                document.getElementById('preview-image').src = imagePath;
                document.getElementById('preview-container').style.display = 'block';
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