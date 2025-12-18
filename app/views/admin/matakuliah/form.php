<div class="admin-header">
    <h1 id="formTitle">Formulir Mata Kuliah</h1>
    <a href="javascript:void(0)" onclick="navigate('admin/matakuliah')" class="btn" style="background: #95a5a6;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card" style="max-width: 600px;">
    <form id="mkForm">
        <input type="hidden" id="idMatakuliah">

        <div class="form-group">
            <label>Kode Mata Kuliah <span style="color:red">*</span></label>
            <input type="text" id="kodeMatakuliah" placeholder="Contoh: TIF-123" required>
        </div>

        <div class="form-group">
            <label>Nama Mata Kuliah <span style="color:red">*</span></label>
            <input type="text" id="namaMatakuliah" placeholder="Contoh: Algoritma Pemrograman" required>
        </div>

        <div class="form-group" style="display:flex; gap:20px;">
            <div style="flex:1;">
                <label>Semester</label>
                <select id="semester">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                </select>
            </div>
            <div style="flex:1;">
                <label>Jumlah SKS</label>
                <input type="number" id="sksKuliah" placeholder="2" min="1" max="6" required>
            </div>
        </div>

        <button type="submit" class="btn btn-add" id="btnSave">
            <i class="fas fa-save"></i> Simpan Data
        </button>
    </form>
    <div id="message" style="margin-top: 15px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    if (id) {
        document.getElementById('formTitle').textContent = 'Edit Mata Kuliah';
        document.getElementById('idMatakuliah').value = id;
        loadData(id);
    }
});

function loadData(id) {
    fetch(API_URL + '/matakuliah/' + id)
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success' || response.code === 200) {
            const data = response.data;
            document.getElementById('kodeMatakuliah').value = data.kodeMatakuliah;
            document.getElementById('namaMatakuliah').value = data.namaMatakuliah;
            document.getElementById('semester').value = data.semester;
            document.getElementById('sksKuliah').value = data.sksKuliah;
        } else {
            alert('Data tidak ditemukan');
            navigate('admin/matakuliah');
        }
    })
    .catch(err => console.error(err));
}

document.getElementById('mkForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('idMatakuliah').value;
    const formData = {
        kodeMatakuliah: document.getElementById('kodeMatakuliah').value,
        namaMatakuliah: document.getElementById('namaMatakuliah').value,
        semester: document.getElementById('semester').value,
        sksKuliah: document.getElementById('sksKuliah').value
    };

    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    const url = id ? (API_URL + '/matakuliah/' + id) : (API_URL + '/matakuliah');
    const method = id ? 'PUT' : 'POST';

    fetch(url, { 
        method: method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' || data.code === 200 || data.code === 201) { 
            msg.innerHTML = '<div style="padding: 10px; background: #d4edda; color: #155724; border-radius: 4px;">Berhasil disimpan! Redirecting...</div>';
            setTimeout(() => { navigate('admin/matakuliah'); }, 1000);
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

function navigate(route) {
    if (window.location.port === '8000') {
        window.location.href = '/index.php?route=' + route;
    } else {
        window.location.href = '/' + route;
    }
}
</script>