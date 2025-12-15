<div class="admin-header">
    <h1>Manajemen Peraturan Lab</h1>
    <a href="/SistemInformasiSumberDaya-Kelompok2/public/admin-peraturan-form.php" class="btn btn-add">+ Tambah Peraturan Baru</a>
</div>

<div class="card">
    <table class="crud-table">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Nama Peraturan</th>
                <th>Deskripsi</th>
                <th>Tanggal Upload</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <tr><td colspan="5" style="text-align:center;">Memuat data...</td></tr>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', loadPeraturan);

    function loadPeraturan() {
        const apiUrl = '/SistemInformasiSumberDaya-Kelompok2/public/api.php/tata-tertib';
        fetch(apiUrl)
            .then(res => res.json())
            .then(response => {
                const tableBody = document.getElementById('tableBody');
                tableBody.innerHTML = '';

                if (response.status === 'success' && response.data.length > 0) {
                    response.data.forEach((item, index) => {
                        const row = document.createElement('tr');
                        const deskripsi = item.uraFile ? item.uraFile.substring(0, 60) + '...' : '-';
                        const tanggal = new Date(item.tanggalUpload).toLocaleDateString('id-ID');

                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${escapeHtml(item.namaFile)}</td>
                            <td>${escapeHtml(deskripsi)}</td>
                            <td>${tanggal}</td>
                            <td>
                                <a href="/SistemInformasiSumberDaya-Kelompok2/public/admin-peraturan-form.php?id=${item.idTataTerib}" class="btn-sm btn-edit">Edit</a>
                                <button class="btn-sm btn-delete" onclick="deletePeraturan(${item.idTataTerib})">Hapus</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    tableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;">Belum ada data peraturan</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('tableBody').innerHTML = '<tr><td colspan="5" style="text-align:center;">Gagal memuat data</td></tr>';
            });
    }

    function deletePeraturan(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus peraturan ini?')) return;

        const apiUrl = `/SistemInformasiSumberDaya-Kelompok2/public/api.php/tata-tertib/${id}`;
        fetch(apiUrl, {
            method: 'DELETE'
        })
            .then(res => res.json())
            .then(response => {
                if (response.status === 'success') {
                    alert('Peraturan berhasil dihapus!');
                    loadPeraturan();
                } else {
                    alert('Gagal menghapus peraturan: ' + response.message);
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
