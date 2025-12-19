<div class="admin-header">
    <h1>Manajemen Jadwal Praktikum</h1>
    <div class="btn-group">
        <a href="javascript:void(0)" onclick="navigate('admin/jadwal/upload')" class="btn btn-upload" style="background: #17a2b8; color: white; margin-right: 10px;">
            <i class="fas fa-upload"></i> Upload Excel
        </a>
        <a href="javascript:void(0)" onclick="navigate('admin/jadwal/create')" class="btn btn-add">
            <i class="fas fa-plus"></i> Tambah Jadwal
        </a>
    </div>
</div>

<div class="card">
    <div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="text" id="searchInput" placeholder="Cari mata kuliah, lab, hari..." 
                   style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; width: 300px;">
            <button onclick="loadJadwal()" class="btn btn-primary" style="background: #3498db; padding: 8px 15px;">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
        <div id="totalData" style="color: #666; font-size: 0.9rem;">Total: 0 jadwal</div>
    </div>

    <div style="overflow-x: auto;">
        <table class="crud-table">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th style="min-width: 200px;">Mata Kuliah</th>
                    <th style="min-width: 150px;">Laboratorium</th>
                    <th style="min-width: 150px;">Hari & Waktu</th>
                    <th style="width: 80px; text-align: center;">Kelas</th>
                    <th style="width: 100px; text-align: center;">Status</th>
                    <th style="width: 180px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr><td colspan="7" style="text-align:center; padding: 40px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #ccc;"></i>
                    <p style="color: #999; margin-top: 10px;">Memuat data...</p>
                </td></tr>
            </tbody>
        </table>
    </div>
</div>

<style>
    .status-badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }
    .status-aktif {
        background: #d4edda;
        color: #155724;
    }
    .status-nonaktif {
        background: #f8d7da;
        color: #721c24;
    }
    .time-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .day-label {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.95rem;
    }
    .time-label {
        color: #7f8c8d;
        font-size: 0.85rem;
    }
    .mk-info {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .mk-name {
        font-weight: 600;
        color: #2c3e50;
    }
    .mk-code {
        color: #95a5a6;
        font-size: 0.85rem;
    }
    .crud-table tbody tr {
        cursor: pointer;
        transition: background-color 0.2s, transform 0.2s;
    }
    .crud-table tbody tr:hover {
        background-color: #f8f9fa;
        transform: translateX(2px);
    }
    .action-buttons {
        display: flex;
        gap: 5px;
        justify-content: center;
    }
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        font-size: 0.85rem;
    }
</style>

<script>
let allJadwalData = [];

document.addEventListener('DOMContentLoaded', function() {
    loadJadwal();
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        filterTable(searchTerm);
    });
});

function loadJadwal() {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding: 40px;"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #ccc;"></i><p style="color: #999; margin-top: 10px;">Memuat data...</p></td></tr>';
    
    fetch(API_URL + '/jadwal')
    .then(res => res.json())
    .then(response => {
        if((response.status === 'success' || response.code === 200) && response.data && response.data.length > 0) {
            allJadwalData = response.data;
            renderTable(allJadwalData);
        } else {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding: 40px;"><i class="fas fa-calendar-times" style="font-size: 2rem; color: #ddd;"></i><p style="color: #999; margin-top: 10px;">Belum ada jadwal praktikum.</p></td></tr>';
            document.getElementById('totalData').textContent = 'Total: 0 jadwal';
        }
    })
    .catch(err => {
        console.error(err);
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding: 40px;"><i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #e74c3c;"></i><p style="color: #e74c3c; margin-top: 10px;">Gagal memuat data. Silakan coba lagi.</p></td></tr>';
    });
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '';
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding: 40px;"><i class="fas fa-search" style="font-size: 2rem; color: #ddd;"></i><p style="color: #999; margin-top: 10px;">Tidak ada data yang cocok dengan pencarian.</p></td></tr>';
        return;
    }
    
    data.forEach((item, index) => {
        const statusClass = item.status === 'Aktif' ? 'status-aktif' : 'status-nonaktif';
        const statusBadge = `<span class="status-badge ${statusClass}">${item.status || 'Nonaktif'}</span>`;
        
        // Format waktu untuk menghapus detik (HH:MM:SS -> HH:MM)
        const waktuMulai = item.waktuMulai ? item.waktuMulai.substring(0, 5) : '-';
        const waktuSelesai = item.waktuSelesai ? item.waktuSelesai.substring(0, 5) : '-';
        
        const row = `
            <tr onclick="editJadwal(${item.idJadwal}, event)">
                <td style="text-align: center;">${index + 1}</td>
                <td>
                    <div class="mk-info">
                        <span class="mk-name">${item.namaMatakuliah || 'MK tidak ditemukan'}</span>
                        <span class="mk-code">${item.kodeMatakuliah || '-'}</span>
                    </div>
                </td>
                <td>${item.namaLab || 'Lab tidak ditemukan'}</td>
                <td>
                    <div class="time-info">
                        <span class="day-label">${item.hari}</span>
                        <span class="time-label">${waktuMulai} - ${waktuSelesai}</span>
                    </div>
                </td>
                <td style="text-align: center; font-weight: 600;">${item.kelas || '-'}</td>
                <td style="text-align: center;">${statusBadge}</td>
                <td>
                    <div class="action-buttons">
                        <button onclick="hapusJadwal(${item.idJadwal}, event)" 
                                class="btn btn-delete btn-icon" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
    
    document.getElementById('totalData').textContent = `Total: ${data.length} jadwal`;
}

function filterTable(searchTerm) {
    if (!searchTerm) {
        renderTable(allJadwalData);
        return;
    }
    
    const filteredData = allJadwalData.filter(item => {
        return (
            (item.namaMatakuliah && item.namaMatakuliah.toLowerCase().includes(searchTerm)) ||
            (item.kodeMatakuliah && item.kodeMatakuliah.toLowerCase().includes(searchTerm)) ||
            (item.namaLab && item.namaLab.toLowerCase().includes(searchTerm)) ||
            (item.hari && item.hari.toLowerCase().includes(searchTerm)) ||
            (item.kelas && item.kelas.toLowerCase().includes(searchTerm))
        );
    });
    
    renderTable(filteredData);
}

function editJadwal(id, event) {
    // Jangan navigate jika klik tombol delete
    if (event && event.target.closest('.btn-delete')) {
        return;
    }
    navigate('admin/jadwal/' + id + '/edit');
}

function hapusJadwal(id, event) {
    // Mencegah event bubbling ke row
    if (event) {
        event.stopPropagation();
    }
    
    if(confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
        fetch(API_URL + '/jadwal/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success' || data.code === 200) {
                alert('Jadwal berhasil dihapus!');
                loadJadwal();
            } else {
                alert('Gagal menghapus: ' + (data.message || 'Error tidak diketahui'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error: ' + err.message);
        });
    }
}

function navigate(route) {
    if (window.location.port === '8000') {
        // PHP built-in server
        window.location.href = '/index.php?route=' + route;
    } else {
        // XAMPP/Apache
        window.location.href = '/' + route;
    }
}
</script>