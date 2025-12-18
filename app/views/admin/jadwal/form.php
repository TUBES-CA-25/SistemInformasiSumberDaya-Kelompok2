<div class="admin-header">
    <h1>Formulir Jadwal Praktikum</h1>
    <a href="javascript:void(0)" onclick="navigate('admin/jadwal')" class="btn" style="background: #95a5a6;">← Kembali</a>
</div>

<div class="card" style="max-width: 800px;">
    <form id="jadwalForm">
        <input type="hidden" id="idJadwal">

        <div class="form-group">
            <label>Mata Kuliah <span style="color:red">*</span></label>
            <select id="idMatakuliah" required>
                <option value="">-- Pilih Mata Kuliah --</option>
            </select>
        </div>

        <div class="form-group">
            <label>Laboratorium / Ruangan <span style="color:red">*</span></label>
            <select id="idLaboratorium" required>
                <option value="">-- Pilih Ruangan Lab --</option>
            </select>
        </div>
        
        <div class="form-group" style="display:flex; gap:20px;">
            <div style="flex:1;">
                <label>Hari</label>
                <select id="hari">
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                </select>
            </div>
            <div style="flex:1;">
                <label>Kelas</label>
                <input type="text" id="kelas" placeholder="Contoh: TI-A atau 130202A">
            </div>
        </div>

        <div class="form-group" style="display:flex; gap:20px;">
            <div style="flex:1;">
                <label>Waktu Mulai</label>
                <input type="time" id="waktuMulai" value="08:00">
            </div>
            <div style="flex:1;">
                <label>Waktu Selesai</label>
                <input type="time" id="waktuSelesai" value="10:00">
            </div>
        </div>
        
        <div class="form-group">
            <label>Status Jadwal</label>
            <select id="status">
                <option value="Aktif">Aktif</option>
                <option value="Diundur">Diundur</option>
                <option value="Batal">Batal</option>
            </select>
        </div>

        <button type="submit" class="btn btn-add" id="btnSave">Simpan Jadwal</button>
    </form>
    <div id="message" style="margin-top: 15px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', loadDependencies);

function loadDependencies() {
    // 1. Ambil data Mata Kuliah
    fetch(API_URL + '/matakuliah').then(res => res.json()).then(data => {
        if(data.status === 'success' || data.code === 200) {
            const select = document.getElementById('idMatakuliah');
            if (data.data && data.data.length > 0) {
                data.data.forEach(mk => {
                    const option = document.createElement('option');
                    option.value = mk.idMatakuliah;
                    option.textContent = `${mk.namaMatakuliah} (${mk.kodeMatakuliah})`;
                    select.appendChild(option);
                });
            }
        }
    }).catch(err => console.error('Error loading matakuliah:', err));

    // 2. Ambil data Laboratorium
    fetch(API_URL + '/laboratorium').then(res => res.json()).then(data => {
        if(data.status === 'success' || data.code === 200) {
            const select = document.getElementById('idLaboratorium');
            if (data.data && data.data.length > 0) {
                data.data.forEach(lab => {
                    const option = document.createElement('option');
                    option.value = lab.idLaboratorium;
                    option.textContent = lab.nama;
                    select.appendChild(option);
                });
            }
        }
    }).catch(err => console.error('Error loading laboratorium:', err));
}

// 3. Handle Simpan Data
document.getElementById('jadwalForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = {
        idMatakuliah: document.getElementById('idMatakuliah').value,
        idLaboratorium: document.getElementById('idLaboratorium').value,
        hari: document.getElementById('hari').value,
        waktuMulai: document.getElementById('waktuMulai').value,
        waktuSelesai: document.getElementById('waktuSelesai').value,
        kelas: document.getElementById('kelas').value,
        status: document.getElementById('status').value
    };

    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    btn.disabled = true;
    btn.innerText = 'Menyimpan...';

    fetch(API_URL + '/jadwalpraktikum', { 
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' || data.code === 201) { 
            msg.innerHTML = '<span style="color:green">✓ Berhasil disimpan! Mengalihkan...</span>';
            setTimeout(() => { navigate('admin/jadwal'); }, 1500);
        } else {
            msg.innerHTML = '<span style="color:red">✗ Gagal: ' + (data.message || 'Error server') + '</span>';
            btn.disabled = false;
            btn.innerText = 'Simpan Jadwal';
        }
    })
    .catch(err => {
        console.error(err);
        msg.innerHTML = '<span style="color:red">✗ Koneksi Error.</span>';
        btn.disabled = false;
        btn.innerText = 'Simpan Jadwal';
    });
});

function navigate(route) {
    if (window.location.port === '8000') {
        window.location.href = '/index.php?route=' + route;
    } else {
        window.location.href = '/' + route;
    }
}
</script>