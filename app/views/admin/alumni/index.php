<div class="admin-header">
    <h1>Data Alumni Asisten</h1>
    <a href="/SistemManagementSumberDaya/public/admin-alumni-form.php" class="btn btn-add">+ Tambah Alumni</a>
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
    fetch('/SistemManagementSumberDaya/public/api.php/alumni') 
    .then(res => res.json())
    .then(response => {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if((response.status === 'success' || response.code === 200) && response.data && response.data.length > 0) {
            response.data.forEach((item, index) => {
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><img src="${item.foto || 'https://placehold.co/50x50'}" style="width:50px; height:50px; border-radius:50%; object-fit:cover;"></td>
                        <td>
                            <strong>${item.nama}</strong><br>
                            <small style="color:#777;">${item.divisi || 'Asisten'}</small>
                        </td>
                        <td>${item.angkatan || '-'}</td>
                        <td>${item.pekerjaan || '-'}</td>
                        <td>
                            <a href="/SistemManagementSumberDaya/public/admin-alumni-form.php?id=${item.id}" class="btn btn-edit">Edit</a>
                            <button onclick="hapusAlumni(${item.id})" class="btn btn-delete">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Belum ada data alumni.</td></tr>';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        document.getElementById('tableBody').innerHTML = '<tr><td colspan="6" style="text-align:center; color:red;">Error: Gagal memuat data</td></tr>';
    });
}

function hapusAlumni(id) {
    if(confirm('Yakin hapus data alumni ini?')) {
        fetch('/SistemManagementSumberDaya/public/api.php/alumni/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success' || data.code === 200) {
                alert('Data berhasil dihapus');
                loadAlumni();
            } else {
                alert('Gagal menghapus: ' + (data.message || 'Error'));
            }
        })
        .catch(err => alert('Error: ' + err.message));
    }
}
</script>