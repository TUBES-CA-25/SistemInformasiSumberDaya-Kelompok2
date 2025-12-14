<div class="admin-header">
    <h1>👨‍🎓 Data Alumni Asisten</h1>
    <a href="/SistemManagementSumberDaya/public/admin-alumni-form.php" class="btn btn-add">+ Tambah Alumni</a>
</div>

<div class="card" style="padding: 0;">
    <div style="overflow-x: auto;">
        <table class="crud-table" style="margin: 0;">
            <thead>
                <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <th style="width: 50px; padding: 15px;">No</th>
                    <th style="width: 80px; padding: 15px;">Foto</th>
                    <th style="padding: 15px;">Nama Alumni</th>
                    <th style="width: 100px; padding: 15px;">Angkatan</th>
                    <th style="padding: 15px;">Pekerjaan Sekarang</th>
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

.alumni-info {
    display: flex;
    gap: 12px;
    align-items: center;
}

.alumni-info img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #667eea;
}

.alumni-details strong {
    display: block;
    font-size: 14px;
    margin-bottom: 4px;
}

.alumni-details small {
    color: #999;
    font-size: 12px;
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
                        <td style="font-weight: 600; color: #667eea;">${index + 1}</td>
                        <td>
                            <img src="${item.foto || 'https://placehold.co/50x50'}" 
                                 style="width:50px; height:50px; border-radius:50%; object-fit:cover; border: 2px solid #667eea;">
                        </td>
                        <td>
                            <div class="alumni-details">
                                <strong>${item.nama}</strong>
                                <small>${item.divisi || 'Asisten'}</small>
                            </div>
                        </td>
                        <td style="text-align: center; font-weight: 600; color: #667eea;">${item.angkatan || '—'}</td>
                        <td><span style="color: #666; font-size: 13px;">${item.pekerjaan || '—'}</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="/SistemManagementSumberDaya/public/admin-alumni-form.php?id=${item.id}" 
                                   class="btn-edit">✏️ Edit</a>
                                <button onclick="hapusAlumni(${item.id})" 
                                        class="btn-delete" 
                                        style="cursor: pointer;">🗑️ Hapus</button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 30px; color: #999;">Belum ada data alumni.</td></tr>';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        document.getElementById('tableBody').innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 30px; color: red;">⚠️ Error: Gagal memuat data</td></tr>';
    });
}

function hapusAlumni(id) {
    if(confirm('Apakah Anda yakin ingin menghapus data alumni ini?')) {
        fetch('/SistemManagementSumberDaya/public/api.php/alumni/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success' || data.code === 200) {
                alert('✓ Data berhasil dihapus');
                loadAlumni();
            } else {
                alert('✗ Gagal menghapus: ' + (data.message || 'Error'));
            }
        })
        .catch(err => {
            alert('✗ Error: ' + err.message);
            console.error(err);
        });
    }
}
</script>