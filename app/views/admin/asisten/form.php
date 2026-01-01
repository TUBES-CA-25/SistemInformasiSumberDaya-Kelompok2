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
    <h1><i class="fas fa-user-edit"></i> Formulir Asisten</h1>
    <a href="javascript:void(0)" onclick="navigate('admin/asisten')" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card" style="max-width: 800px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <form id="asistenForm" enctype="multipart/form-data">
        
        <input type="hidden" id="idAsisten" name="idAsisten">

        <div class="form-group">
            <label><i class="fas fa-user"></i> Nama Lengkap <span style="color:red">*</span></label>
            <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap..." required>
        </div>

        <div class="form-group">
            <label><i class="fas fa-envelope"></i> Email <span style="color:red">*</span></label>
            <input type="email" id="email" name="email" placeholder="email@umi.ac.id" required>
        </div>

            <div class="form-group">
                <label><i class="fab fa-linkedin"></i> LinkedIn</label>
                <input type="url" id="linkedin" name="linkedin" placeholder="https://linkedin.com/in/username">
                <small style="color: grey;">Opsional. Masukkan URL profil LinkedIn.</small>
            </div>

        <div class="form-group">
            <label><i class="fas fa-graduation-cap"></i> Jurusan</label> 
            <select id="jurusan" name="jurusan" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <option value="Teknik Informatika">Teknik Informatika</option>
                <option value="Sistem Informasi">Sistem Informasi</option>
            </select>
        </div>

        <!-- Jabatan, Kategori, Laboratorium, Spesialisasi removed as per request -->

        <div class="form-group">
            <label><i class="fas fa-tools"></i> Keahlian / Spesialisasi</label> 
            <input type="text" id="skills" name="skills" placeholder="Pisahkan dengan koma. Contoh: PHP, MySQL, Networking">
            <small style="color: grey;">Masukkan beberapa keahlian dipisahkan dengan koma.</small>
        </div>

        <div class="form-group">
            <label><i class="fas fa-info-circle"></i> Bio / Tentang Saya</label> 
            <textarea id="bio" name="bio" rows="4" placeholder="Deskripsi singkat tentang asisten..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
        </div>

        <div class="form-group">
            <label><i class="fas fa-camera"></i> Upload Foto</label>
            <input type="file" id="foto" name="foto" accept="image/*">
            <small id="fotoInfo" style="color: #666; display: none; margin-top: 5px;">
                <i class="fas fa-info-circle"></i> Foto saat ini: <span id="fotoCurrent" style="font-weight: bold;"></span>
            </small>
        </div>

        <div class="form-group">
            <label><i class="fas fa-id-badge"></i> Status Asisten</label>
            <select id="statusAktif" name="statusAktif" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <option value="Asisten">Asisten</option>
                <option value="CA">CA</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success" id="btnSave" style="width: 100%; padding: 12px; font-size: 16px;">
            <i class="fas fa-save"></i> Simpan Data
        </button>
    </form>
    
    <div id="message" style="margin-top: 15px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Robust ID detection: check `route` query, pathname, or explicit `id` query
    const routeParam = new URLSearchParams(window.location.search).get('route');
    const qs = new URLSearchParams(window.location.search);
    const explicitId = qs.get('id');
    let id = null;

    if (routeParam) {
        const m = routeParam.match(/admin\/asisten\/(\d+)\/edit/);
        if (m && m[1]) id = m[1];
    }

    if (!id) {
        const m2 = window.location.pathname.match(/admin\/asisten\/(\d+)\/edit/);
        if (m2 && m2[1]) id = m2[1];
    }

    if (!id && explicitId) id = explicitId;

    if (id) loadAsistenData(id);
});

function loadAsistenData(id) {
    fetch(API_URL + '/asisten/' + id)
    .then(response => response.json())
    .then(data => {
        if ((data.status === 'success' || data.code === 200) && data.data) {
            const asisten = data.data;
            document.getElementById('idAsisten').value = asisten.idAsisten;
            document.getElementById('nama').value = asisten.nama || '';
            document.getElementById('email').value = asisten.email || '';
            document.getElementById('jurusan').value = asisten.jurusan || 'Teknik Informatika';
            // Removed: jabatan, kategori, lab, spesialisasi
            document.getElementById('bio').value = asisten.bio || '';
            
            // Handle skills (expecting JSON string or array)
            let skillsStr = '';
            if (asisten.skills) {
                try {
                    let skills = typeof asisten.skills === 'string' ? JSON.parse(asisten.skills) : asisten.skills;
                    if (Array.isArray(skills)) {
                        skillsStr = skills.join(', ');
                    } else if (typeof skills === 'string') {
                         skillsStr = skills;
                    }
                } catch (e) {
                    skillsStr = asisten.skills; // Fallback plain text
                }
            }
            document.getElementById('skills').value = skillsStr;

            let status = asisten.statusAktif;
            // Map legacy boolean values to new string values
            if (status == '1') status = 'Asisten';
            if (status == '0') status = 'Tidak Aktif';
            document.getElementById('statusAktif').value = status || 'Asisten';
            
            if (asisten.foto) {
                document.getElementById('fotoInfo').style.display = 'block';
                document.getElementById('fotoCurrent').innerText = asisten.foto;
            }
            
            document.querySelector('.admin-header h1').innerHTML = '<i class="fas fa-user-edit"></i> Edit Asisten: ' + asisten.nama;
            document.getElementById('btnSave').innerHTML = '<i class="fas fa-save"></i> Update Data';
        }
    })
    .catch(error => {
        console.error('Error loading asisten:', error);
    });
}

document.getElementById('asistenForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    btn.disabled = true;
    
    const idAsisten = document.getElementById('idAsisten').value;
    const isEdit = idAsisten ? true : false;
    
    btn.innerHTML = isEdit ? '<i class="fas fa-spinner fa-spin"></i> Mengupdate...' : '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    // Pakai FormData agar bisa upload file
    const form = document.getElementById('asistenForm');
    const formData = new FormData(form);
    // Field sudah otomatis masuk karena ada attribute name di HTML

    const url = isEdit ? (API_URL + '/asisten/' + idAsisten) : (API_URL + '/asisten');
    const method = 'POST'; // Always POST untuk FormData dengan file

    fetch(url, {
        method: method,
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.status === 'success' || data.code === 201 || data.code === 200) {
                msg.innerHTML = '<span style="color:green"><i class="fas fa-check-circle"></i> ' + (isEdit ? 'Data berhasil diupdate! ' : 'Berhasil disimpan! ') + 'Mengalihkan...</span>';
                setTimeout(() => { navigate('admin/asisten'); }, 1000);
            } else {
                msg.innerHTML = '<span style="color:red"><i class="fas fa-exclamation-circle"></i> Gagal: ' + (data.message || 'Error validasi') + '</span>';
                btn.disabled = false;
                btn.innerHTML = isEdit ? '<i class="fas fa-save"></i> Update Data' : '<i class="fas fa-save"></i> Simpan Data';
            }
        } catch (e) {
            console.error('Parse error:', text);
            msg.innerHTML = '<span style="color:red">Terjadi kesalahan: ' + text + '</span>';
            btn.disabled = false;
            btn.innerHTML = isEdit ? '<i class="fas fa-save"></i> Update Data' : '<i class="fas fa-save"></i> Simpan Data';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        msg.innerHTML = '<span style="color:red">Terjadi kesalahan koneksi.</span>';
        btn.disabled = false;
        btn.innerText = isEdit ? 'Update Data' : 'Simpan Data';
    });
});

</script>