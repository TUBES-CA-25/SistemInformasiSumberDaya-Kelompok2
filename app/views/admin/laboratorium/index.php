<div class="admin-header">
    <h1>🏢 Data Laboratorium / Fasilitas</h1>
    <a href="/SistemManagementSumberDaya/public/admin-laboratorium-form.php" class="btn btn-add">+ Tambah Laboratorium</a>
</div>

<div class="card" style="padding: 0;">
    <div style="overflow-x: auto;">
        <table class="crud-table" style="margin: 0;">
            <thead>
                <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <th style="width: 50px; padding: 15px;">No</th>
                    <th style="width: 100px; padding: 15px;">Gambar</th>
                    <th style="padding: 15px;">Nama Laboratorium</th>
                    <th style="padding: 15px;">Deskripsi</th>
                    <th style="width: 130px; padding: 15px;">PC / Kursi</th>
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

.lab-image {
    width: 80px;
    height: 80px;
    border-radius: 6px;
    object-fit: cover;
    border: 2px solid #667eea;
    display: block;
}

.lab-info {
    display: block;
}

.lab-info strong {
    display: block;
    font-size: 14px;
    margin-bottom: 4px;
    color: #333;
}

.lab-capacity {
    display: flex;
    gap: 15px;
    font-size: 13px;
}

.capacity-item {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #666;
}

.capacity-item strong {
    color: #667eea;
    font-size: 16px;
    margin: 0;
}

.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-edit, .btn-delete {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
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
                        <td style="font-weight: 600; color: #667eea;">${index + 1}</td>
                        <td>
                            <img src="${item.gambar || 'https://placehold.co/80x80'}" 
                                 class="lab-image">
                        </td>
                        <td>
                            <div class="lab-info">
                                <strong>${item.nama}</strong>
                                <small style="color: #999;">${item.deskripsi ? item.deskripsi.substring(0, 50) + (item.deskripsi.length > 50 ? '...' : '') : '—'}</small>
                            </div>
                        </td>
                        <td>
                            <small style="color: #666; line-height: 1.6;">
                                ${item.deskripsi || '—'}
                            </small>
                        </td>
                        <td>
                            <div class="lab-capacity">
                                <div class="capacity-item">
                                    <strong>${item.jumlahPc || 0}</strong> <span>💻</span>
                                </div>
                                <div class="capacity-item">
                                    <strong>${item.jumlahKursi || 0}</strong> <span>🪑</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="/SistemManagementSumberDaya/public/admin-laboratorium-form.php?id=${item.idLaboratorium}" 
                                   class="btn-edit">✏️ Edit</a>
                                <button onclick="hapusLaboratorium(${item.idLaboratorium})" 
                                        class="btn-delete" 
                                        style="cursor: pointer;">🗑️ Hapus</button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 30px; color: #999;">Belum ada data laboratorium.</td></tr>';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        document.getElementById('tableBody').innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 30px; color: red;">⚠️ Error: Gagal memuat data</td></tr>';
    });
}

function hapusLaboratorium(id) {
    if(confirm('Apakah Anda yakin ingin menghapus laboratorium ini?')) {
        fetch('/SistemManagementSumberDaya/public/api.php/laboratorium/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success' || data.code === 200) {
                alert('✓ Data berhasil dihapus');
                loadLaboratorium();
            } else {
                alert('✗ Gagal: ' + (data.message || 'Error'));
            }
        })
        .catch(err => {
            alert('✗ Error: ' + err.message);
            console.error(err);
        });
    }
}
</script>