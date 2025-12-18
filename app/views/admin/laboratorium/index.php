<div class="admin-header">
    <h1><i class="fas fa-desktop"></i> Data Laboratorium / Fasilitas</h1>
    <a href="<?php echo BASE_URL; ?>/public/admin-laboratorium-form.php" class="btn btn-success">
        <i class="fas fa-plus"></i> Tambah Laboratorium
    </a>
</div>

<div class="card" style="padding: 0;">
    <div style="overflow-x: auto;">
        <table class="crud-table" style="margin: 0;">
            <thead>
                <tr style="background: #34495e; color: white;">
                    <th style="width: 50px; padding: 15px; text-align: center;">No</th>
                    <th style="width: 100px; padding: 15px; text-align: center;">Gambar</th>
                    <th style="padding: 15px;">Nama Laboratorium</th>
                    <th style="padding: 15px;">Deskripsi</th>
                    <th style="width: 130px; padding: 15px; text-align: center;">Kapasitas</th>
                    <th style="width: 150px; padding: 15px; text-align: center;">Aksi</th>
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
/* Custom Button Styles */
.btn {
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white !important;
}

.btn-success {
    background-color: #27ae60;
}
.btn-success:hover {
    background-color: #219150;
    transform: translateY(-2px);
}

.crud-table tbody tr {
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s ease;
}

.crud-table tbody tr:hover {
    background-color: #f8f9fa;
}

.crud-table tbody td {
    padding: 12px 15px;
    vertical-align: middle;
    color: #555;
}

.crud-table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.lab-image {
    width: 80px;
    height: 60px;
    border-radius: 4px;
    object-fit: cover;
    border: 1px solid #ddd;
    display: block;
    margin: 0 auto;
}

.lab-info strong {
    display: block;
    font-size: 15px;
    margin-bottom: 4px;
    color: #2c3e50;
}

.lab-capacity {
    display: flex;
    gap: 10px;
    font-size: 13px;
    justify-content: center;
}

.capacity-item {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #666;
    background: #f8f9fa;
    padding: 4px 8px;
    border-radius: 4px;
    border: 1px solid #eee;
}

.capacity-item strong {
    color: #2980b9;
    font-size: 14px;
    margin: 0;
}

.action-buttons {
    display: flex;
    gap: 5px;
    justify-content: center;
}

.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    color: white;
    text-decoration: none;
}

.btn-edit {
    background-color: #f39c12;
}

.btn-edit:hover {
    background-color: #e67e22;
}

.btn-delete {
    background-color: #e74c3c;
}

.btn-delete:hover {
    background-color: #c0392b;
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
    fetch(API_URL + '/laboratorium')
    .then(res => res.json())
    .then(response => {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if((response.status === 'success' || response.code === 200) && response.data && response.data.length > 0) {
            response.data.forEach((item, index) => {
                const fotoUrl = item.gambar 
                    ? (item.gambar.includes('http') ? item.gambar : BASE_URL + '/storage/uploads/' + item.gambar) 
                    : 'https://placehold.co/80x60?text=No+Image';

                const row = `
                    <tr>
                        <td style="text-align: center; font-weight: 600; color: #7f8c8d;">${index + 1}</td>
                        <td>
                            <img src="${fotoUrl}" class="lab-image" alt="Lab">
                        </td>
                        <td>
                            <div class="lab-info">
                                <strong>${item.nama}</strong>
                            </div>
                        </td>
                        <td>
                            <small style="color: #666; line-height: 1.6;">
                                ${item.deskripsi ? (item.deskripsi.length > 100 ? item.deskripsi.substring(0, 100) + '...' : item.deskripsi) : '—'}
                            </small>
                        </td>
                        <td>
                            <div class="lab-capacity">
                                <div class="capacity-item" title="Jumlah PC">
                                    <i class="fas fa-desktop"></i> <strong>${item.jumlahPc || 0}</strong>
                                </div>
                                <div class="capacity-item" title="Jumlah Kursi">
                                    <i class="fas fa-chair"></i> <strong>${item.jumlahKursi || 0}</strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/public/admin-laboratorium-form.php?id=${item.idLaboratorium}" 
                                   class="btn-action btn-edit" title="Edit">
                                   <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="hapusLaboratorium(${item.idLaboratorium})" 
                                        class="btn-action btn-delete" 
                                        title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 40px; color: #999;"><i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>Belum ada data laboratorium.</td></tr>';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        document.getElementById('tableBody').innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 30px; color: #e74c3c;"><i class="fas fa-exclamation-triangle"></i> Gagal memuat data</td></tr>';
    });
}

function hapusLaboratorium(id) {
    if(confirm('Apakah Anda yakin ingin menghapus laboratorium ini?')) {
        fetch(API_URL + '/laboratorium/' + id, { method: 'DELETE' })
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