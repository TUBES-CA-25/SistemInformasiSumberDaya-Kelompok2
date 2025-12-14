<div class="admin-header">
    <h1>Formulir Laboratorium</h1>
    <a href="/SistemManagementSumberDaya/public/admin-laboratorium.php" class="btn" style="background: #95a5a6;">← Kembali</a>
</div>

<div class="card" style="max-width: 600px;">
    <form id="labForm" enctype="multipart/form-data">
        <input type="hidden" id="idLaboratorium">

        <div class="form-group">
            <label>Nama Laboratorium <span style="color:red">*</span></label>
            <input type="text" id="nama" placeholder="Contoh: Lab Pemrograman" required>
        </div>

        <div class="form-group">
            <label>Koordinator Asisten</label>
            <select id="idKordinatorAsisten">
                <option value="">-- Pilih Asisten --</option>
            </select>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea id="deskripsi" rows="3" placeholder="Deskripsi laboratorium..."></textarea>
        </div>

        <div class="form-group">
            <label>Upload Gambar Lab</label>
            <input type="file" id="gambar" name="gambar" accept="image/*">
        </div>

        <div class="form-group" style="display:flex; gap:20px;">
            <div style="flex:1;">
                <label>Jumlah PC</label>
                <input type="number" id="jumlahPc" placeholder="30" min="0">
            </div>
            <div style="flex:1;">
                <label>Jumlah Kursi</label>
                <input type="number" id="jumlahKursi" placeholder="40" min="0">
            </div>
        </div>

        <button type="submit" class="btn btn-add" id="btnSave">Simpan Data</button>
    </form>
    <div id="message" style="margin-top: 15px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', loadAsisten);

function loadAsisten() {
    fetch('/SistemManagementSumberDaya/public/api.php/asisten').then(res => res.json()).then(data => {
        if(data.status === 'success') {
            const select = document.getElementById('idKordinatorAsisten');
            data.data.forEach(ast => {
                const option = document.createElement('option');
                option.value = ast.idAsisten;
                option.textContent = ast.nama;
                select.appendChild(option);
            });
        }
    });
}

document.getElementById('labForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    btn.disabled = true;
    btn.innerText = 'Menyimpan...';

    const formData = new FormData(this);
    formData.append('nama', document.getElementById('nama').value);
    formData.append('idKordinatorAsisten', document.getElementById('idKordinatorAsisten').value);
    formData.append('deskripsi', document.getElementById('deskripsi').value);
    formData.append('jumlahPc', document.getElementById('jumlahPc').value);
    formData.append('jumlahKursi', document.getElementById('jumlahKursi').value);

    fetch('/SistemManagementSumberDaya/public/api.php/laboratorium', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' || data.code === 201) {
            msg.innerHTML = '<span style="color:green">✓ Berhasil disimpan! Mengalihkan...</span>';
            setTimeout(() => { window.location.href = '/SistemManagementSumberDaya/public/admin-laboratorium.php'; }, 1500);
        } else {
            msg.innerHTML = '<span style="color:red">✗ Gagal: ' + (data.message || 'Error tidak diketahui') + '</span>';
            btn.disabled = false;
            btn.innerText = 'Simpan Data';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        msg.innerHTML = '<span style="color:red">✗ Koneksi Error.</span>';
        btn.disabled = false;
        btn.innerText = 'Simpan Data';
    });
});
</script>