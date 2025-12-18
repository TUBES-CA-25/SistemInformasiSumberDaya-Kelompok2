<style>
/* Custom Button Styles */
.btn {
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white !important;
}

.btn-success {
    background-color: #27ae60;
}
.btn-success:hover {
    background-color: #219150;
    transform: translateY(-2px);
}

.btn-secondary {
    background-color: #95a5a6;
}
.btn-secondary:hover {
    background-color: #7f8c8d;
    transform: translateY(-2px);
}

.btn-success:disabled {
    background-color: #95a5a6;
    cursor: not-allowed;
    transform: none;
}
</style>

<div class="admin-header">
    <h1><i class="fas fa-user-graduate"></i> Formulir Alumni</h1>
    <a href="<?php echo BASE_URL; ?>/public/admin-alumni.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card" style="max-width: 800px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <form id="alumniForm" enctype="multipart/form-data">
        <input type="hidden" id="id">

        <div class="form-group">
            <label><i class="fas fa-user"></i> Nama Lengkap <span style="color:red">*</span></label>
            <input type="text" id="nama" name="nama" placeholder="Nama Alumni" required>
        </div>

        <div class="form-group" style="display:flex; gap:20px;">
            <div style="flex:1;">
                <label><i class="fas fa-calendar-alt"></i> Tahun Angkatan <span style="color:red">*</span></label>
                <input type="number" id="angkatan" name="angkatan" placeholder="2020" min="2000" max="2030" required>
            </div>
            <div style="flex:1;">
                <label><i class="fas fa-sitemap"></i> Divisi / Posisi</label>
                <input type="text" id="divisi" name="divisi" placeholder="Contoh: Asisten Lab">
            </div>
        </div>

        <div class="form-group">
            <label><i class="fas fa-briefcase"></i> Pekerjaan Saat Ini</label>
            <input type="text" id="pekerjaan" name="pekerjaan" placeholder="Contoh: Software Engineer">
        </div>

        <div class="form-group">
            <label><i class="fas fa-building"></i> Perusahaan</label>
            <input type="text" id="perusahaan" name="perusahaan" placeholder="Contoh: PT Teknologi Indonesia">
        </div>

        <div class="form-group">
            <label><i class="fas fa-graduation-cap"></i> Tahun Lulus</label>
            <input type="text" id="tahun_lulus" name="tahun_lulus" placeholder="2023">
        </div>

        <div class="form-group">
            <label><i class="fas fa-camera"></i> Upload Foto</label>
            <input type="file" id="foto" name="foto" accept="image/*">
            <small style="color: #777;">Format: JPG, PNG. Max 2MB</small>
        </div>

        <div class="form-group">
            <label><i class="fas fa-code"></i> Keahlian</label>
            <textarea id="keahlian" name="keahlian" rows="2" placeholder="Contoh: PHP, Laravel, React, MySQL"></textarea>
        </div>

        <div class="form-group">
            <label><i class="fas fa-envelope"></i> Email</label>
            <input type="email" id="email" name="email" placeholder="email@example.com">
        </div>

        <div class="form-group">
            <label><i class="fab fa-linkedin"></i> LinkedIn</label>
            <input type="url" id="linkedin" name="linkedin" placeholder="https://linkedin.com/in/...">
        </div>

        <div class="form-group">
            <label><i class="fas fa-globe"></i> Portfolio</label>
            <input type="url" id="portfolio" name="portfolio" placeholder="https://portfolio.com">
        </div>

        <div class="form-group">
            <label><i class="fas fa-comment-alt"></i> Kesan & Pesan</label>
            <textarea id="kesan_pesan" name="kesan_pesan" rows="4" placeholder="Tulis pengalaman selama menjadi asisten..."></textarea>
        </div>

        <button type="submit" class="btn btn-success" id="btnSave" style="width: 100%; padding: 12px; font-size: 16px;">
            <i class="fas fa-save"></i> Simpan Data
        </button>
    </form>
    <div id="message" style="margin-top: 15px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    if (id) {
        loadAlumniData(id);
    }
});

function loadAlumniData(id) {
    fetch(API_URL + '/alumni/' + id)
    .then(res => res.json())
    .then(response => {
        if ((response.status === 'success' || response.code === 200) && response.data) {
            const data = response.data;
            document.getElementById('id').value = data.id;
            document.getElementById('nama').value = data.nama;
            document.getElementById('angkatan').value = data.angkatan;
            document.getElementById('divisi').value = data.divisi || '';
            document.getElementById('pekerjaan').value = data.pekerjaan || '';
            document.getElementById('perusahaan').value = data.perusahaan || '';
            document.getElementById('tahun_lulus').value = data.tahun_lulus || '';
            document.getElementById('keahlian').value = data.keahlian || '';
            document.getElementById('email').value = data.email || '';
            document.getElementById('linkedin').value = data.linkedin || '';
            document.getElementById('portfolio').value = data.portfolio || '';
            document.getElementById('kesan_pesan').value = data.kesan_pesan || '';
            
            document.querySelector('.admin-header h1').innerHTML = '<i class="fas fa-user-edit"></i> Edit Alumni';
            document.getElementById('btnSave').innerHTML = '<i class="fas fa-save"></i> Update Data';
        }
    })
    .catch(err => console.error('Error loading alumni:', err));
}

document.getElementById('alumniForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    const formData = new FormData(this);

    fetch(API_URL + '/alumni', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log('Response:', data);
        if (data.status === 'success' || data.code === 201) {
            msg.innerHTML = '<div style="padding: 10px; background: #d4edda; color: #155724; border-radius: 4px; border: 1px solid #c3e6cb;"><i class="fas fa-check-circle"></i> Berhasil disimpan! Mengalihkan...</div>';
            setTimeout(() => { window.location.href = BASE_URL + '/public/admin-alumni.php'; }, 1500);
        } else {
            msg.innerHTML = '<div style="padding: 10px; background: #f8d7da; color: #721c24; border-radius: 4px; border: 1px solid #f5c6cb;"><i class="fas fa-exclamation-circle"></i> Gagal: ' + (data.message || 'Error tidak diketahui') + '</div>';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        msg.innerHTML = '<div style="padding: 10px; background: #f8d7da; color: #721c24; border-radius: 4px; border: 1px solid #f5c6cb;"><i class="fas fa-wifi"></i> Gagal koneksi ke server</div>';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
    });
});
</script>