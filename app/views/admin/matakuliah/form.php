<div class="admin-header">
    <h1>Formulir Mata Kuliah</h1>
    <a href="/SistemManagementSumberDaya/public/admin-matakuliah.php" class="btn" style="background: #95a5a6;">← Kembali</a>
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
                <input type="number" id="sksKuliah" placeholder="2" min="1" max="6">
            </div>
        </div>

        <button type="submit" class="btn btn-add" id="btnSave">Simpan Data</button>
    </form>
    <div id="message" style="margin-top: 15px;"></div>
</div>

<script>
document.getElementById('mkForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Data JSON sesuai nama kolom di Database & Controller Store
    const formData = {
        kodeMatakuliah: document.getElementById('kodeMatakuliah').value,
        namaMatakuliah: document.getElementById('namaMatakuliah').value,
        semester: document.getElementById('semester').value,
        sksKuliah: document.getElementById('sksKuliah').value
    };

    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    btn.disabled = true;
    btn.innerText = 'Menyimpan...';

    // Kirim ke Controller store()
    fetch('/api/matakuliah', { 
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' || data.code === 201) { 
            msg.innerHTML = '<span style="color:green">Berhasil disimpan! Redirecting...</span>';
            setTimeout(() => { window.location.href = '/admin-matakuliah.php'; }, 1000);
        } else {
            msg.innerHTML = '<span style="color:red">Gagal: ' + (data.message || 'Kode MK mungkin sudah ada') + '</span>';
            btn.disabled = false;
            btn.innerText = 'Simpan Data';
        }
    })
    .catch(err => {
        console.error(err);
        msg.innerHTML = '<span style="color:red">Koneksi Error.</span>';
        btn.disabled = false;
        btn.innerText = 'Simpan Data';
    });
});
</script>