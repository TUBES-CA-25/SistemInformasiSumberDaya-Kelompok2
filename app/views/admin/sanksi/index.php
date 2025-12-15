<div class="admin-header">
    <h1>Manajemen Sanksi Lab</h1>
    <a href="/SistemInformasiSumberDaya-Kelompok2/public/admin-sanksi-form.php" class="btn btn-add">+ Tambah Sanksi Baru</a>
</div>

<div class="card">
    <table class="crud-table">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Judul Sanksi</th>
                <th>Deskripsi</th>
                <th>Tanggal Buat</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <tr><td colspan="5" style="text-align:center;">Memuat data...</td></tr>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', loadSanksi);

    function loadSanksi() {
        const apiUrl = '/SistemInformasiSumberDaya-Kelompok2/public/api.php/sanksi-lab';
        fetch(apiUrl)
            .then(res => res.json())
            .then(response => {
                const tableBody = document.getElementById('tableBody');
                tableBody.innerHTML = '';

                if (response.status === 'success' && response.data.length > 0) {
                    response.data.forEach((item, index) => {
                        const row = document.createElement('tr');
                        const deskripsi = item.deskripsi ? item.deskripsi.substring(0, 60) + '...' : '-';
                        const tanggal = new Date(item.created_at).toLocaleDateString('id-ID');

                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${escapeHtml(item.judul)}</td>
                            <td>${escapeHtml(deskripsi)}</td>
                            <td>${tanggal}</td>
                            <td>
                                <a href="/SistemInformasiSumberDaya-Kelompok2/public/admin-sanksi-form.php?id=${item.id}" class="btn-sm btn-edit">Edit</a>
                                <button class="btn-sm btn-delete" onclick="deleteSanksi(${item.id})">Hapus</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    tableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;">Belum ada data sanksi</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('tableBody').innerHTML = '<tr><td colspan="5" style="text-align:center;">Gagal memuat data</td></tr>';
            });
    }

    function deleteSanksi(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus sanksi ini?')) return;

        const apiUrl = `/SistemInformasiSumberDaya-Kelompok2/public/api.php/sanksi-lab/${id}`;
        fetch(apiUrl, {
            method: 'DELETE'
        })
            .then(res => res.json())
            .then(response => {
                if (response.status === 'success') {
                    alert('Sanksi berhasil dihapus!');
                    loadSanksi();
                } else {
                    alert('Gagal menghapus sanksi: ' + response.message);
                }
            })
            .catch(error => alert('Error: ' + error));
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
