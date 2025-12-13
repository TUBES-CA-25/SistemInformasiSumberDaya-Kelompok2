<div class="admin-header">
    <h1>Manajemen Jadwal Praktikum</h1>
    <a href="/admin-jadwal-form.php" class="btn btn-add">+ Tambah Jadwal Baru</a>
</div>

<div class="card">
    <table class="crud-table">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Mata Kuliah</th>
                <th>Laboratorium</th>
                <th>Hari & Waktu</th>
                <th>Kelas</th>
                <th>Status</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <tr><td colspan="7" style="text-align:center;">Memuat data...</td></tr>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', loadJadwal);

function loadJadwal() {
    fetch('/api/jadwalpraktikum') // Sesuai Controller index()
    .then(res => res.json())
    .then(response => {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if(response.status === 'success' && response.data.length > 0) {
            response.data.forEach((item, index) => {
                // Catatan: Item di bawah ini mengasumsikan Model / API 
                // teman Anda sudah melakukan JOIN untuk menampilkan nama (namaMatakuliah, namaLab).
                const statusBadge = item.status === 'Aktif' 
                    ? '<span style="color:green; font-weight:bold;">Aktif</span>' 
                    : '<span style="color:red;">Diundur</span>';

                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.namaMatakuliah || 'MK tidak ditemukan'} (${item.kodeMatakuliah || '-'})</td>
                        <td>${item.namaLab || 'Lab tidak ditemukan'}</td>
                        <td>
                            <strong>${item.hari}</strong><br>
                            <small>${item.waktuMulai} - ${item.waktuSelesai}</small>
                        </td>
                        <td>${item.kelas || '-'}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <a href="/admin-jadwal-form.php?id=${item.idJadwal}" class="btn btn-edit">Edit</a>
                            <button onclick="hapusJadwal(${item.idJadwal})" class="btn btn-delete">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Belum ada jadwal praktikum.</td></tr>';
        }
    })
    .catch(err => console.error(err));
}

function hapusJadwal(id) {
    if(confirm('Yakin hapus jadwal ini?')) {
        fetch('/api/jadwalpraktikum/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                alert('Berhasil dihapus');
                loadJadwal();
            } else {
                alert('Gagal: ' + (data.message || 'Error'));
            }
        });
    }
}
</script>