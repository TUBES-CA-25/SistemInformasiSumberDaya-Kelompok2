<div class="admin-header">
    <h1>Manajemen Jadwal Praktikum</h1>
    <div class="btn-group">
        <a href="<?php echo BASE_URL; ?>/public/admin-jadwal-upload.php" class="btn btn-upload" style="background: #17a2b8; color: white; margin-right: 10px;">
            <i class="bi bi-upload"></i> Upload Excel
        </a>
        <a href="<?php echo BASE_URL; ?>/public/admin-jadwal-form.php" class="btn btn-add">+ Tambah Jadwal Baru</a>
    </div>
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
    fetch(API_URL + '/jadwal') // Perbaiki routing yang benar
    .then(res => res.json())
    .then(response => {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if((response.status === 'success' || response.code === 200) && response.data && response.data.length > 0) {
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
                            <a href="<?php echo BASE_URL; ?>/public/admin-jadwal-form.php?id=${item.idJadwal}" class="btn btn-edit">Edit</a>
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
        fetch(API_URL + '/jadwal/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success' || data.code === 200) {
                alert('Berhasil dihapus');
                loadJadwal();
            } else {
                alert('Gagal: ' + (data.message || 'Error'));
            }
        })
        .catch(err => alert('Error: ' + err.message));
    }
};
</script>