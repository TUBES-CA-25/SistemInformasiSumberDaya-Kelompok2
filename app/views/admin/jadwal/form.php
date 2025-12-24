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
let jadwalId = null;
let mkLoaded = false;
let labLoaded = false;

document.addEventListener('DOMContentLoaded', function() {
    // Flexible ID detection
    const routeParam = new URLSearchParams(window.location.search).get('route');
    const qs = new URLSearchParams(window.location.search);
    const explicitId = qs.get('id');
    let id = null;

    if (routeParam) {
        const m = routeParam.match(/admin\/jadwal\/(\d+)\/edit/);
        if (m && m[1]) id = m[1];
    }

    if (!id) {
        const m2 = window.location.pathname.match(/admin\/jadwal\/(\d+)\/edit/);
        if (m2 && m2[1]) id = m2[1];
    }

    if (!id && explicitId) id = explicitId;

    if (id) {
        jadwalId = id;
        document.querySelector('.admin-header h1').textContent = 'Edit Jadwal Praktikum';
        document.getElementById('idJadwal').value = jadwalId;
    }

    loadDependencies();
});

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
            mkLoaded = true;
            if (jadwalId && mkLoaded && labLoaded) loadJadwalAfterLabs();
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
            labLoaded = true;
            if (jadwalId && mkLoaded && labLoaded) loadJadwalAfterLabs();
        }
    }).catch(err => console.error('Error loading laboratorium:', err));
}

function loadJadwalAfterLabs() {
    fetch(API_URL + '/jadwal/' + jadwalId)
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success' || response.code === 200) {
            const data = response.data;
            document.getElementById('idMatakuliah').value = data.idMatakuliah || '';
            document.getElementById('idLaboratorium').value = data.idLaboratorium || '';
            document.getElementById('hari').value = data.hari || 'Senin';
            document.getElementById('kelas').value = data.kelas || '';
            document.getElementById('waktuMulai').value = data.waktuMulai ? data.waktuMulai.substring(0, 5) : '08:00';
            document.getElementById('waktuSelesai').value = data.waktuSelesai ? data.waktuSelesai.substring(0, 5) : '10:00';
            document.getElementById('status').value = data.status || 'Aktif';
        } else {
            alert('Data tidak ditemukan');
            navigate('admin/jadwal');
        }
    })
    .catch(err => {
        console.error('Error loading jadwal:', err);
        alert('Gagal memuat data jadwal');
    });
}

// 3. Handle Simpan Data
document.getElementById('jadwalForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('idJadwal').value;
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

    const url = id ? (API_URL + '/jadwal/' + id) : (API_URL + '/jadwal');
    const method = id ? 'PUT' : 'POST';

    fetch(url, { 
        method: method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' || data.code === 201 || data.code === 200) { 
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

</script>