<div class="admin-header">
    <h1>Formulir Alumni</h1>
    <a href="<?php echo BASE_URL; ?>/public/admin-alumni.php" class="btn" style="background: #95a5a6;">← Kembali</a>
</div>

<div class="card" style="max-width: 800px;">
    <form id="alumniForm" enctype="multipart/form-data">
        <input type="hidden" id="id">

        <div class="form-group">
            <label>Nama Lengkap <span style="color:red">*</span></label>
            <input type="text" id="nama" name="nama" placeholder="Nama Alumni" required>
        </div>

        <div class="form-group" style="display:flex; gap:20px;">
            <div style="flex:1;">
                <label>Tahun Angkatan <span style="color:red">*</span></label>
                <input type="number" id="angkatan" name="angkatan" placeholder="2020" min="2000" max="2030" required>
            </div>
            <div style="flex:1;">
                <label>Divisi / Posisi</label>
                <input type="text" id="divisi" name="divisi" placeholder="Contoh: Asisten Lab">
            </div>
        </div>

        <div class="form-group">
            <label>Pekerjaan Saat Ini</label>
            <input type="text" id="pekerjaan" name="pekerjaan" placeholder="Contoh: Software Engineer">
        </div>

        <div class="form-group">
            <label>Perusahaan</label>
            <input type="text" id="perusahaan" name="perusahaan" placeholder="Contoh: PT Teknologi Indonesia">
        </div>

        <div class="form-group">
            <label>Tahun Lulus</label>
            <input type="text" id="tahun_lulus" name="tahun_lulus" placeholder="2023">
        </div>

        <div class="form-group">
            <label>Upload Foto</label>
            <input type="file" id="foto" name="foto" accept="image/*">
            <small style="color: #777;">Format: JPG, PNG. Max 2MB</small>
        </div>

        <div class="form-group">
            <label>Keahlian</label>
            <textarea id="keahlian" name="keahlian" rows="2" placeholder="Contoh: PHP, Laravel, React, MySQL"></textarea>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" id="email" name="email" placeholder="email@example.com">
        </div>

        <div class="form-group">
            <label>LinkedIn</label>
            <input type="url" id="linkedin" name="linkedin" placeholder="https://linkedin.com/in/...">
        </div>

        <div class="form-group">
            <label>Portfolio</label>
            <input type="url" id="portfolio" name="portfolio" placeholder="https://portfolio.com">
        </div>

        <div class="form-group">
            <label>Kesan & Pesan</label>
            <textarea id="kesan_pesan" name="kesan_pesan" rows="4" placeholder="Tulis pengalaman selama menjadi asisten..."></textarea>
        </div>

        <button type="submit" class="btn btn-add" id="btnSave">Simpan Data</button>
    </form>
    <div id="message" style="margin-top: 15px;"></div>
</div>

<script>
document.getElementById('alumniForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    btn.disabled = true;
    btn.innerText = 'Menyimpan...';

    const formData = new FormData(this);

    fetch(API_URL + '/alumni', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log('Response:', data);
        if (data.status === 'success' || data.code === 201) {
            msg.innerHTML = '<span style="color:green">✓ Berhasil disimpan! Mengalihkan...</span>';
            setTimeout(() => { window.location.href = BASE_URL + '/public/admin-alumni.php'; }, 1500);
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