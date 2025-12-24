<div class="admin-header">
    <h1><i class="fas fa-user-graduate"></i> Data Alumni Asisten</h1>
    <a href="javascript:void(0)" onclick="navigate('admin/alumni/create')" class="btn btn-success">
        <i class="fas fa-plus"></i> Tambah Alumni
    </a>
</div>

<div class="card" style="padding: 0;">
    <div style="overflow-x: auto;">
        <table class="crud-table" style="margin: 0;">
            <thead>
                <tr style="background: #34495e; color: white;">
                    <th style="width: 50px; padding: 15px; text-align: center;">No</th>
                    <th style="width: 80px; padding: 15px; text-align: center;">Foto</th>
                    <th style="padding: 15px;">Nama Alumni</th>
                    <th style="width: 100px; padding: 15px; text-align: center;">Angkatan</th>
                    <th style="padding: 15px;">Pekerjaan Sekarang</th>
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
    cursor: pointer;
}

.crud-table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateX(2px);
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

.alumni-info {
    display: flex;
    gap: 12px;
    align-items: center;
}

.avatar-img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e0e0e0;
    display: block;
    margin: 0 auto;
}

.alumni-details strong {
    display: block;
    font-size: 14px;
    margin-bottom: 4px;
    color: #2c3e50;
}

.alumni-details small {
    color: #7f8c8d;
    font-size: 12px;
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
    fetch(API_URL + '/alumni') 
    .then(res => res.json())
    .then(response => {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if((response.status === 'success' || response.code === 200) && response.data && response.data.length > 0) {
            response.data.forEach((item, index) => {
                const fotoUrl = item.foto 
                    ? (item.foto.includes('http') ? item.foto : ASSETS_URL + '/assets/uploads/' + item.foto) 
                    : 'https://placehold.co/50x50?text=Foto';

                const row = `
                    <tr onclick="editAlumni(${item.id}, event)">
                        <td style="text-align: center; font-weight: 600; color: #7f8c8d;">${index + 1}</td>
                        <td>
                            <img src="${fotoUrl}" class="avatar-img" alt="Foto">
                        </td>
                        <td>
                            <div class="alumni-details">
                                <strong>${item.nama}</strong>
                                <small>${item.divisi || 'Asisten'}</small>
                            </div>
                        </td>
                        <td style="text-align: center; font-weight: 600; color: #555;">${item.angkatan || '—'}</td>
                        <td><span style="color: #555; font-size: 13px;">${item.pekerjaan || '—'}</span></td>
                        <td>
                            <div class="action-buttons">
                                <button onclick="hapusAlumni(${item.id}, event)" 
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
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 40px; color: #999;"><i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>Belum ada data alumni.</td></tr>';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        document.getElementById('tableBody').innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 30px; color: #e74c3c;"><i class="fas fa-exclamation-triangle"></i> Gagal memuat data</td></tr>';
    });
}

function editAlumni(id, event) {
    // Jangan navigate jika klik tombol delete
    if (event && event.target.closest('.btn-delete')) {
        return;
    }
    navigate('admin/alumni/' + id + '/edit');
}

function hapusAlumni(id, event) {
    // Mencegah event bubbling ke row
    if (event) {
        event.stopPropagation();
    }
    
    if(confirm('Apakah Anda yakin ingin menghapus data alumni ini?')) {
        fetch(API_URL + '/alumni/' + id, { method: 'DELETE' })
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