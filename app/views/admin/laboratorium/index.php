<div class="admin-header">
    <h1>Data Mata Kuliah</h1>
    <a href="/admin-matakuliah-form.php" class="btn btn-add">+ Tambah Matakuliah</a>
</div>

<div class="card">
    <table class="crud-table">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Kode MK</th>
                <th>Nama Mata Kuliah</th>
                <th>Semester</th>
                <th>SKS</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <tr><td colspan="6" style="text-align:center;">Memuat data...</td></tr>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', loadMatakuliah);

function loadMatakuliah() {
    fetch('/api/matakuliah') // Sesuai Controller index()
    .then(res => res.json())
    .then(response => {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if(response.status === 'success' && response.data.length > 0) {
            response.data.forEach((item, index) => {
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><span style="background:#e8f6f3; color:#1abc9c; padding:3px 8px; border-radius:4px; font-weight:bold;">${item.kodeMatakuliah}</span></td>
                        <td>${item.namaMatakuliah}</td>
                        <td>${item.semester || '-'}</td>
                        <td>${item.sksKuliah || '-'}</td>
                        <td>
                            <a href="/admin-matakuliah-form.php?id=${item.idMatakuliah}" class="btn btn-edit">Edit</a>
                            <button onclick="hapusMatakuliah(${item.idMatakuliah})" class="btn btn-delete">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Belum ada data mata kuliah.</td></tr>';
        }
    })
    .catch(err => console.error(err));
}

function hapusMatakuliah(id) {
    if(confirm('Yakin hapus mata kuliah ini?')) {
        fetch('/api/matakuliah/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                alert('Berhasil dihapus');
                loadMatakuliah();
            } else {
                alert('Gagal: ' + (data.message || 'Error'));
            }
        });
    }
}
</script>