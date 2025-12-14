<div class="admin-header">
    <h1>Data Laboratorium / Fasilitas</h1>
    <a href="/SistemManagementSumberDaya/public/admin-laboratorium-form.php" class="btn btn-add">+ Tambah Laboratorium</a>
</div>

<div class="card">
    <table class="crud-table">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th style="width: 80px;">Gambar</th>
                <th>Nama Laboratorium</th>
                <th>Deskripsi</th>
                <th>PC / Kursi</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <tr><td colspan="6" style="text-align:center;">Memuat data...</td></tr>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', loadLaboratorium);

function loadLaboratorium() {
    fetch('/SistemManagementSumberDaya/public/api.php/laboratorium')
    .then(res => res.json())
    .then(response => {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if((response.status === 'success' || response.code === 200) && response.data && response.data.length > 0) {
            response.data.forEach((item, index) => {
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><img src="${item.gambar || 'https://placehold.co/60x60'}" style="width:60px; height:60px; border-radius:4px; object-fit:cover;"></td>
                        <td><strong>${item.nama}</strong></td>
                        <td>${item.deskripsi || '-'}</td>
                        <td>${item.jumlahPc || 0} PC / ${item.jumlahKursi || 0} Kursi</td>
                        <td>
                            <a href="/SistemManagementSumberDaya/public/admin-laboratorium-form.php?id=${item.idLaboratorium}" class="btn btn-edit">Edit</a>
                            <button onclick="hapusLaboratorium(${item.idLaboratorium})" class="btn btn-delete">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Belum ada data laboratorium.</td></tr>';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        document.getElementById('tableBody').innerHTML = '<tr><td colspan="6" style="text-align:center; color:red;">Error: Gagal memuat data</td></tr>';
    });
}

function hapusLaboratorium(id) {
    if(confirm('Yakin hapus laboratorium ini?')) {
        fetch('/SistemManagementSumberDaya/public/api.php/laboratorium/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success' || data.code === 200) {
                alert('Berhasil dihapus');
                loadLaboratorium();
            } else {
                alert('Gagal: ' + (data.message || 'Error'));
            }
        })
        .catch(err => alert('Error: ' + err.message));
    }
}
</script>