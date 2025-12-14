<div class="admin-header">
    <h1>📚 Data Asisten Laboratorium</h1>
    <a href="/SistemManagementSumberDaya/public/admin-asisten-form.php" class="btn btn-add">+ Tambah Asisten Baru</a>
</div>

<div class="card" style="padding: 0;">
    <div style="overflow-x: auto;">
        <table class="crud-table" style="margin: 0;">
            <thead>
                <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <th style="width: 50px; padding: 15px;">No</th>
                    <th style="width: 100px; padding: 15px;">Foto</th>
                    <th style="padding: 15px;">Nama Lengkap</th>
                    <th style="padding: 15px;">Jurusan</th>
                    <th style="width: 100px; padding: 15px;">Status</th>
                    <th style="width: 150px; padding: 15px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr>
                    <td colspan="6" style="text-align:center; padding: 30px; color: #999;">
                        <div style="animation: spin 1s linear infinite; display: inline-block;">⟳</div> Sedang memuat data...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>
.crud-table tbody tr {
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.3s ease;
}

.crud-table tbody tr:hover {
    background-color: #f8f9ff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.crud-table tbody td {
    padding: 12px 15px;
    vertical-align: middle;
}

.crud-table tbody td img {
    display: block;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 13px;
    display: inline-block;
}

.status-aktif {
    background-color: #d4edda;
    color: #155724;
}

.status-nonaktif {
    background-color: #f8d7da;
    color: #721c24;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-edit, .btn-delete {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-edit {
    background-color: #17a2b8;
    color: white;
}

.btn-edit:hover {
    background-color: #138496;
    transform: translateY(-2px);
}

.btn-delete {
    background-color: #dc3545;
    color: white;
}

.btn-delete:hover {
    background-color: #c82333;
    transform: translateY(-2px);
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<script>
// Saat halaman dibuka, ambil data dari API
document.addEventListener('DOMContentLoaded', function() {
    loadAsisten();
});

function loadAsisten() {
    fetch('/SistemManagementSumberDaya/public/api.php/asisten')
    .then(response => response.json())
    .then(res => {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if((res.status === 'success' || res.code === 200) && res.data && res.data.length > 0) {
            res.data.forEach((item, index) => {
                const statusBadge = item.statusAktif == 1 
                    ? '<span class="status-badge status-aktif">✓ Aktif</span>' 
                    : '<span class="status-badge status-nonaktif">✗ Non-Aktif</span>';

                const row = `
                    <tr>
                        <td style="font-weight: 600; color: #667eea;">${index + 1}</td>
                        <td>
                            <img src="${item.foto || 'https://placehold.co/50x50'}" 
                                 style="width:50px; height:50px; border-radius:50%; object-fit:cover; border: 2px solid #667eea;">
                        </td>
                        <td><strong>${item.nama}</strong></td>
                        <td><span style="color: #666; font-size: 13px;">${item.jurusan || '—'}</span></td>
                        <td>${statusBadge}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="/SistemManagementSumberDaya/public/admin-asisten-form.php?id=${item.idAsisten}" 
                                   class="btn-edit">✏️ Edit</a>
                                <button onclick="hapusAsisten(${item.idAsisten})" 
                                        class="btn-delete" 
                                        style="cursor: pointer;">🗑️ Hapus</button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 30px; color: #999;">Belum ada data asisten.</td></tr>';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        document.getElementById('tableBody').innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 30px; color: red;">⚠️ Error: Gagal memuat data</td></tr>';
    });
}

function hapusAsisten(id) {
    if(confirm('Apakah Anda yakin ingin menghapus data asisten ini?')) {
        fetch('/SistemManagementSumberDaya/public/api.php/asisten/' + id, { method: 'DELETE' })
        .then(response => response.json())
        .then(res => {
            if(res.status === 'success' || res.code === 200) {
                alert('✓ Data berhasil dihapus');
                loadAsisten();
            } else {
                alert('✗ Gagal menghapus: ' + (res.message || 'Error'));
            }
        })
        .catch(err => {
            alert('✗ Error: ' + err.message);
            console.error(err);
        });
    }
}
</script>