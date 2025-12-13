<div class="admin-header">
    <h1>Data Alumni Asisten</h1>
    <a href="/admin-alumni-form.php" class="btn btn-add">+ Tambah Alumni</a>
</div>

<div class="card">
    <table class="crud-table">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th style="width: 80px;">Foto</th>
                <th>Nama Alumni</th>
                <th>Angkatan</th>
                <th>Pekerjaan Sekarang</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <tr><td colspan="6" style="text-align:center;">Memuat data...</td></tr>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', loadAlumni);

function loadAlumni() {
    // Asumsi endpoint API teman Anda adalah /api/alumni
    fetch('/api/alumni') 
    .then(res => res.json())
    .then(response => {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if(response.status === 'success' && response.data.length > 0) {
            response.data.forEach((item, index) => {
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><img src="${item.foto || 'https://placehold.co/50x50'}" style="width:50px; height:50px; border-radius:50%; object-fit:cover;"></td>
                        <td>
                            <strong>${item.nama}</strong><br>
                            <small style="color:#777;">Ex-${item.jabatanTerakhir || 'Asisten'}</small>
                        </td>
                        <td>${item.tahunAngkatan || '-'}</td>
                        <td>${item.pekerjaan || '-'}</td>
                        <td>
                            <a href="/admin-alumni-form.php?id=${item.idAlumni}" class="btn btn-edit">Edit</a>
                            <button onclick="hapusAlumni(${item.idAlumni})" class="btn btn-delete">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Belum ada data alumni.</td></tr>';
        }
    })
    .catch(err => console.error(err));
}

function hapusAlumni(id) {
    if(confirm('Yakin hapus data alumni ini?')) {
        fetch('/api/alumni/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                alert('Data berhasil dihapus');
                loadAlumni();
            } else {
                alert('Gagal menghapus');
            }
        });
    }
}
</script>