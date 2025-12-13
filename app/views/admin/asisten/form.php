<div class="admin-header">
    <h1>Formulir Asisten</h1>
    <a href="/admin-asisten.php" class="btn" style="background: #95a5a6;">← Kembali</a>
</div>

<div class="card" style="max-width: 800px;">
    <form id="asistenForm">
        
        <input type="hidden" id="idAsisten">

        <div class="form-group">
            <label>Nama Lengkap <span style="color:red">*</span></label>
            <input type="text" id="nama" placeholder="Masukkan nama lengkap..." required>
        </div>

        <div class="form-group">
            <label>Email <span style="color:red">*</span></label>
            <input type="email" id="email" placeholder="email@umi.ac.id" required>
        </div>

        <div class="form-group">
            <label>Jurusan</label> <input type="text" id="jurusan" placeholder="Contoh: Teknik Informatika">
        </div>

        <div class="form-group">
            <label>Link Foto (URL)</label> <input type="text" id="foto" placeholder="https://...">
        </div>

        <div class="form-group">
            <label>Status Aktif</label>
            <select id="statusAktif">
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-add" id="btnSave">Simpan Data</button>
    </form>
    
    <div id="message" style="margin-top: 15px;"></div>
</div>

<script>
document.getElementById('asistenForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // 1. Ambil data sesuai kolom DATABASE
    const formData = {
        nama: document.getElementById('nama').value,
        email: document.getElementById('email').value,
        jurusan: document.getElementById('jurusan').value,
        foto: document.getElementById('foto').value,
        statusAktif: document.getElementById('statusAktif').value
    };

    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    
    btn.disabled = true;
    btn.innerText = 'Menyimpan...';

    // 2. Kirim ke API (Sesuai Controller teman Anda)
    fetch('/api/asisten', { 
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        // Cek response sesuai format Controller: $this->success(...)
        if (data.status === 'success' || data.code === 201) { 
            msg.innerHTML = '<span style="color:green">Berhasil disimpan! Mengalihkan...</span>';
            setTimeout(() => { window.location.href = '/admin-asisten.php'; }, 1000);
        } else {
            // Menangkap pesan error dari Controller (misal: "Email sudah terdaftar")
            msg.innerHTML = '<span style="color:red">Gagal: ' + (data.message || 'Error validasi') + '</span>';
            btn.disabled = false;
            btn.innerText = 'Simpan Data';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        msg.innerHTML = '<span style="color:red">Terjadi kesalahan koneksi.</span>';
        btn.disabled = false;
        btn.innerText = 'Simpan Data';
    });
});
</script>