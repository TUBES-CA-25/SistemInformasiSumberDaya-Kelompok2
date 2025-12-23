<div class="admin-header">
    <h1><i class="fas fa-users"></i> Data Asisten Laboratorium</h1>
    <div style="display: flex; gap: 10px;">
        <a href="javascript:void(0)" onclick="navigate('admin/asisten/koordinator')" class="btn btn-primary">
            <i class="fas fa-user-check"></i> Pilih Koordinator
        </a>
        <a href="javascript:void(0)" onclick="navigate('admin/asisten/create')" class="btn btn-success">
            <i class="fas fa-plus"></i> Tambah Asisten Baru
        </a>
    </div>
</div>

<div class="card" style="padding: 0;">
    <div style="overflow-x: auto;">
        <table class="crud-table" style="margin: 0;">
            <thead>
                <tr style="background: #34495e; color: white;">
                    <th style="width: 50px; padding: 15px; text-align: center;">No</th>
                    <th style="width: 80px; padding: 15px; text-align: center;">Foto</th>
                    <th style="padding: 15px;">Nama Lengkap</th>
                    <th style="padding: 15px;">Jurusan</th>
                    <th style="width: 150px; padding: 15px; text-align: center;">Status</th>
                    <th style="width: 80px; padding: 15px; text-align: center;">Aksi</th>
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
/* Custom Button Styles for Consistency */
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

.btn-primary {
    background-color: #3498db;
}
.btn-primary:hover {
    background-color: #2980b9;
    transform: translateY(-2px);
}

.btn-success {
    background-color: #27ae60;
}
.btn-success:hover {
    background-color: #219150;
    transform: translateY(-2px);
}

.btn-secondary {
    background-color: #95a5a6;
}
.btn-secondary:hover {
    background-color: #7f8c8d;
    transform: translateY(-2px);
}

/* Table Styles */
.crud-table tbody tr {
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s ease;
    cursor: pointer;
}

.crud-table tbody tr:hover {
    background-color: #f0f7ff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
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

.avatar-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e0e0e0;
    display: block;
    margin: 0 auto;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12px;
    display: inline-block;
}

.status-aktif {
    background-color: #e8f5e9;
    color: #27ae60;
    border: 1px solid #c8e6c9;
}

.status-nonaktif {
    background-color: #ffebee;
    color: #c0392b;
    border: 1px solid #ffcdd2;
}

.coord-badge {
    background-color: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: bold;
    border: 1px solid #bbdefb;
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

.row-clickable {
    position: relative;
}

.row-clickable td:not(:last-child) {
    cursor: pointer;
}

.btn-action {
    position: relative;
    z-index: 10;
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
    fetch(API_URL + '/asisten')
    .then(response => response.json())
    .then(res => {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if((res.status === 'success' || res.code === 200) && res.data && res.data.length > 0) {
            res.data.forEach((item, index) => {
                let statusBadge = '';

                // Jika Koordinator, tampilkan hanya badge Koordinator
                if (item.isKoordinator == 1) {
                    statusBadge = '<span class="coord-badge"><i class="fas fa-crown"></i> Koordinator</span>';
                } else {
                    // Normalisasi status
                    let statusText = item.statusAktif;
                    if (statusText == '1') statusText = 'Asisten';
                    if (statusText == '0') statusText = 'Tidak Aktif';
                    
                    let badgeClass = 'status-aktif';
                    let icon = 'check-circle';
                    
                    if (statusText === 'Tidak Aktif') {
                        badgeClass = 'status-nonaktif';
                        icon = 'times-circle';
                    } else if (statusText === 'CA') {
                        badgeClass = 'status-ca'; 
                        icon = 'user-graduate';
                    } else if (statusText === 'CCA') {
                        badgeClass = 'status-cca';
                        icon = 'user-tie';
                    }

                    statusBadge = `<span class="status-badge ${badgeClass}"><i class="fas fa-${icon}"></i> ${statusText}</span>`;
                }

                const fotoUrl = item.foto 
                    ? (item.foto.includes('http') ? item.foto : '/assets/uploads/' + item.foto) 
                    : 'https://placehold.co/50x50?text=Foto';

                const row = `
                    <tr class="row-clickable" onclick="editAsisten(${item.idAsisten}, event)">
                        <td style="text-align: center; font-weight: 600; color: #7f8c8d;">${index + 1}</td>
                        <td>
                            <img src="${fotoUrl}" class="avatar-img" alt="Foto">
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #2c3e50;">${item.nama}</div>
                            <div style="font-size: 12px; color: #7f8c8d;">${item.email || ''}</div>
                        </td>
                        <td><span style="color: #555; font-size: 13px;">${item.jurusan || '—'}</span></td>
                        <td style="text-align: center;">${statusBadge}</td>
                        <td style="text-align: center;">
                            <button onclick="hapusAsisten(${item.idAsisten}, event)" 
                                    class="btn-action btn-delete" 
                                    title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 40px; color: #999;"><i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>Belum ada data asisten.</td></tr>';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        document.getElementById('tableBody').innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 30px; color: #e74c3c;"><i class="fas fa-exclamation-triangle"></i> Gagal memuat data</td></tr>';
    });
}

function editAsisten(id, event) {
    // Jangan navigate jika yang diklik adalah tombol delete
    if (event.target.closest('.btn-delete')) {
        return;
    }
    navigate('admin/asisten/' + id + '/edit');
}

function hapusAsisten(id, event) {
    // Stop propagation agar tidak trigger row click
    event.stopPropagation();
    
    if(confirm('Apakah Anda yakin ingin menghapus data asisten ini?')) {
        fetch(API_URL + '/asisten/' + id, { method: 'DELETE' })
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