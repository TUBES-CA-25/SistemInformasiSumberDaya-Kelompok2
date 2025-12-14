<div class="admin-header">
    <h1>📖 Data Mata Kuliah</h1>
    <a href="/SistemManagementSumberDaya/public/admin-matakuliah-form.php" class="btn btn-add">+ Tambah Matakuliah</a>
</div>

<div class="card" style="padding: 0;">
    <div style="overflow-x: auto;">
        <table class="crud-table" style="margin: 0;">
            <thead>
                <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <th style="width: 50px; padding: 15px;">No</th>
                    <th style="width: 120px; padding: 15px;">Kode MK</th>
                    <th style="padding: 15px;">Nama Mata Kuliah</th>
                    <th style="width: 100px; padding: 15px; text-align: center;">Semester</th>
                    <th style="width: 80px; padding: 15px; text-align: center;">SKS</th>
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

.kode-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 13px;
    display: inline-block;
    letter-spacing: 0.5px;
}

.semester-badge {
    background-color: #ffeaa7;
    color: #d63031;
    padding: 6px 10px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 13px;
    display: inline-block;
    min-width: 40px;
    text-align: center;
}

.sks-badge {
    background-color: #dfe6e9;
    color: #2d3436;
    padding: 6px 10px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 13px;
    display: inline-block;
    min-width: 40px;
    text-align: center;
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
document.addEventListener('DOMContentLoaded', loadMatakuliah);

function loadMatakuliah() {
    fetch('/SistemManagementSumberDaya/public/api.php/matakuliah')
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
                            <span class="kode-badge">${item.kodeMatakuliah}</span>
                        </td>
                        <td>
                            <strong style="color: #333; font-size: 14px;">${item.namaMatakuliah}</strong>
                        </td>
                        <td style="text-align: center;">
                            <span class="semester-badge">${item.semester || '—'}</span>
                        </td>
                        <td style="text-align: center;">
                            <span class="sks-badge">${item.sksKuliah || '—'}</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="/SistemManagementSumberDaya/public/admin-matakuliah-form.php?id=${item.idMatakuliah}" 
                                   class="btn-edit">✏️ Edit</a>
                                <button onclick="hapusMatakuliah(${item.idMatakuliah})" 
                                        class="btn-delete" 
                                        style="cursor: pointer;">🗑️ Hapus</button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 30px; color: #999;">Belum ada data mata kuliah.</td></tr>';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        document.getElementById('tableBody').innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 30px; color: red;">⚠️ Error: Gagal memuat data</td></tr>';
    });
}

function hapusMatakuliah(id) {
    if(confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?')) {
        fetch('/SistemManagementSumberDaya/public/api.php/matakuliah/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success' || data.code === 200) {
                alert('✓ Data berhasil dihapus');
                loadMatakuliah();
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