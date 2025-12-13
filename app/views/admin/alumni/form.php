<div class="admin-header">
    <h1>Formulir Alumni</h1>
    <a href="/admin-alumni.php" class="btn" style="background: #95a5a6;">← Kembali</a>
</div>

<div class="card" style="max-width: 800px;">
    <form id="alumniForm">
        <input type="hidden" id="idAlumni">

        <div class="form-group">
            <label>Nama Lengkap <span style="color:red">*</span></label>
            <input type="text" id="nama" placeholder="Nama Alumni" required>
        </div>

        <div class="form-group" style="display:flex; gap:20px;">
            <div style="flex:1;">
                <label>Tahun Angkatan</label>
                <input type="number" id="tahunAngkatan" placeholder="2020" min="2000" max="2030">
            </div>
            <div style="flex:1;">
                <label>Jabatan Terakhir di Lab</label>
                <select id="jabatanTerakhir">
                    <option value="Anggota">Anggota Asisten</option>
                    <option value="Koordinator">Koordinator Lab</option>
                    <option value="Sekretaris">Sekretaris</option>
                    <option value="Bendahara">Bendahara</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Pekerjaan Saat Ini</label>
            <input type="text" id="pekerjaan" placeholder="Contoh: Software Engineer di Tokopedia">
        </div>

        <div class="form-group">
            <label>Link Foto (URL)</label>
            <input type="text" id="foto" placeholder="https://...">
        </div>

        <div class="form-group">
            <label>Testimoni / Kesan Pesan</label>
            <textarea id="testimoni" rows="4" placeholder="Tulis pengalaman selama menjadi asisten..."></textarea>
        </div>

        <button type="submit" class="btn btn-add" id="btnSave">Simpan Data</button>
    </form>
    <div id="message" style="margin-top: 15px;"></div>
</div>

<script>
document.getElementById('alumniForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = {
        nama: document.getElementById('nama').value,
        tahunAngkatan: document.getElementById('tahunAngkatan').value,
        jabatanTerakhir: document.getElementById('jabatanTerakhir').value,
        pekerjaan: document.getElementById('pekerjaan').value,
        foto: document.getElementById('foto').value,
        testimoni: document.getElementById('testimoni').value
    };

    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    btn.disabled = true;
    btn.innerText = 'Menyimpan...';

    fetch('/api/alumni', { 
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' || data.code === 201) { 
            msg.innerHTML = '<span style="color:green">Berhasil disimpan! Redirecting...</span>';
            setTimeout(() => { window.location.href = '/admin-alumni.php'; }, 1000);
        } else {
            msg.innerHTML = '<span style="color:red">Gagal: ' + (data.message || 'Error') + '</span>';
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