<div class="admin-header">
    <h1>Manajemen Kepala Laboratorium</h1>
    <a href="javascript:void(0)" onclick="navigate('admin/manajemen/create')" class="btn btn-primary">+ Tambah Kepala Lab</a>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Foto</th>
                <th style="width: 30%;">Nama</th>
                <th style="width: 30%;">Jabatan</th>
                <th style="width: 20%;">Aksi</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <tr>
                <td colspan="5" style="text-align: center; padding: 30px; color: #999;">
                    <span class="spinner"></span> Loading...
                </td>
            </tr>
        </tbody>
    </table>
</div>

<style>
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #ecf0f1;
}

.admin-header h1 {
    margin: 0;
    font-size: 28px;
    color: #2c3e50;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5568d3;
    transform: translateY(-2px);
}

.btn-edit {
    background: #f39c12;
    color: white;
    padding: 6px 12px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-size: 12px;
    text-decoration: none;
    display: inline-block;
    margin-right: 5px;
}

.btn-edit:hover {
    background: #e67e22;
}

.btn-delete {
    background: #e74c3c;
    color: white;
    padding: 6px 12px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-size: 12px;
}

.btn-delete:hover {
    background: #c82333;
    transform: translateY(-2px);
}

.table-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table thead {
    background: #34495e;
    color: white;
}

.admin-table th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
}

.admin-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #ecf0f1;
}

.admin-table tbody tr:hover {
    background: #f8f9fa;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-aktif {
    background: #d4edda;
    color: #155724;
}

.status-nonaktif {
    background: #f8d7da;
    color: #721c24;
}

.action-buttons {
    display: flex;
    gap: 5px;
}

.spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadManajemen();
});

function loadManajemen() {
    fetch(API_URL + '/manajemen')
    .then(response => response.json())
    .then(res => {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if((res.status === 'success' || res.code === 200) && res.data && res.data.length > 0) {
            res.data.forEach((item, index) => {
                const row = `
                    <tr>
                        <td style="font-weight: 600; color: #667eea;">${index + 1}</td>
                        <td>
                            <img src="${item.foto ? (item.foto.includes('http') ? item.foto : '/SistemInformasiSumberDaya-Kelompok2/storage/uploads/' + item.foto) : 'https://placehold.co/50x50'}" 
                                 style="width:50px; height:50px; border-radius:50%; object-fit:cover; border: 2px solid #667eea;">
                        </td>
                        <td><strong>${item.nama}</strong></td>
                        <td><span style="color: #666; font-size: 13px;">${item.jabatan || '—'}</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="javascript:void(0)" onclick="navigate('admin/manajemen/' + item.idManajemen + '/edit')" 
                                   class="btn-edit">✏️ Edit</a>
                                <button onclick="hapusManajemen(${item.idManajemen})" 
                                        class="btn-delete" 
                                        style="cursor: pointer;">🗑️ Hapus</button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px; color: #999;">Tidak ada data</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('tableBody').innerHTML = '<tr><td colspan="5" style="text-align: center; color: red;">Error loading data</td></tr>';
    });
}

function hapusManajemen(id) {
    if (confirm('Yakin hapus data ini?')) {
        fetch(API_URL + '/manajemen/' + id, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' || data.code === 200) {
                alert('Data berhasil dihapus');
                loadManajemen();
            } else {
                alert('Gagal hapus data: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

function navigate(route) {
    if (window.location.port === '8000') {
        window.location.href = '/index.php?route=' + route;
    } else {
        window.location.href = '/' + route;
    }
}
</script>
