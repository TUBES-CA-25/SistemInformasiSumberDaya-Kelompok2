<div class="admin-header">
    <h1>Data Asisten Laboratorium</h1>
    <a href="/SistemInformasiSumberDaya-Kelompok2/public/admin-asisten-form.php" class="btn btn-add">+ Tambah Asisten Baru</a>
</div>

<div class="card">
    <table class="crud-table">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th style="width: 100px;">Foto</th>
                <th>Nama Lengkap</th>
                <th>Jurusan</th> <th>Status</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <tr>
                <td colspan="6" style="text-align:center;">Sedang memuat data...</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
// Saat halaman dibuka, ambil data dari API
document.addEventListener('DOMContentLoaded', function() {
    loadAsisten();
});

function loadAsisten() {
    fetch('/SistemInformasiSumberDaya-Kelompok2/public/api.php/asisten') // Memanggil method index() di Controller
    .then(response => response.json())
    .then(res => {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = ''; // Bersihkan loading

        if(res.status === 'success' && res.data.length > 0) {
            res.data.forEach((item, index) => {
                // Tentukan label status
                const statusBadge = item.statusAktif == 1 
                    ? '<span style="color:green; font-weight:bold;">Aktif</span>' 
                    : '<span style="color:red;">Non-Aktif</span>';

                // Render Baris Tabel
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><img src="${item.foto || 'https://placehold.co/50x50'}" style="width:50px; height:50px; border-radius:50%; object-fit:cover;"></td>
                        <td>${item.nama}</td>
                        <td>${item.jurusan || '-'}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <a href="#" class="btn btn-edit">Edit</a>
                            <button onclick="hapusAsisten(${item.idAsisten})" class="btn btn-delete">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Belum ada data asisten.</td></tr>';
        }
    })
    .catch(err => console.error(err));
}

// Fungsi Hapus (Menghubungkan ke method delete() di Controller)
function hapusAsisten(id) {
    if(confirm('Yakin ingin menghapus data ini?')) {
        fetch('/SistemInformasiSumberDaya-Kelompok2/public/api.php/asisten/' + id, { method: 'DELETE' })
        .then(response => response.json())
        .then(res => {
            if(res.status === 'success') {
                alert('Data berhasil dihapus');
                loadAsisten(); // Reload tabel
            } else {
                alert('Gagal menghapus');
            }
        });
    }
}
</script>