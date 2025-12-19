<div class="admin-header">
    <h1>Manajemen Peraturan Lab</h1>
    <a href="javascript:void(0)" onclick="navigate('admin/peraturan/create')" class="btn btn-add">
        <i class="fas fa-plus"></i> Tambah Peraturan
    </a>
</div>

<div class="card">
    <div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="text" id="searchInput" placeholder="Cari nama peraturan..." 
                   style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; width: 300px;">
            <button onclick="loadPeraturan()" class="btn btn-primary" style="background: #3498db; padding: 8px 15px;">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
        <div id="totalData" style="color: #666; font-size: 0.9rem;">Total: 0 peraturan</div>
    </div>

    <div style="overflow-x: auto;">
        <table class="crud-table">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>Nama Peraturan</th>
                    <th>Deskripsi</th>
                    <th style="width: 150px; text-align: center;">Tanggal Upload</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr><td colspan="5" style="text-align:center; padding: 40px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #ccc;"></i>
                    <p style="color: #999; margin-top: 10px;">Memuat data...</p>
                </td></tr>
            </tbody>
        </table>
    </div>
</div>

<style>
    .date-badge {
        background: #e8f0fe;
        color: #1967d2;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.85rem;
        display: inline-block;
    }
    .peraturan-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1rem;
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
let allPeraturanData = [];

document.addEventListener('DOMContentLoaded', function() {
    loadPeraturan();
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        filterTable(searchTerm);
    });
});

function loadPeraturan() {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding: 40px;"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #ccc;"></i><p style="color: #999; margin-top: 10px;">Memuat data...</p></td></tr>';
    
    fetch(API_URL + '/peraturan-lab')
    .then(res => res.json())
    .then(response => {
        if((response.status === 'success' || response.code === 200) && response.data && response.data.length > 0) {
            allPeraturanData = response.data;
            renderTable(allPeraturanData);
        } else {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding: 40px;"><i class="fas fa-gavel" style="font-size: 2rem; color: #ddd;"></i><p style="color: #999; margin-top: 10px;">Belum ada data peraturan.</p></td></tr>';
            document.getElementById('totalData').textContent = 'Total: 0 peraturan';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding: 40px;"><i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #e74c3c;"></i><p style="color: #e74c3c; margin-top: 10px;">Gagal memuat data. Silakan coba lagi.</p></td></tr>';
    });
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '';
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding: 40px;"><i class="fas fa-search" style="font-size: 2rem; color: #ddd;"></i><p style="color: #999; margin-top: 10px;">Tidak ada data yang cocok dengan pencarian.</p></td></tr>';
        return;
    }
    
    data.forEach((item, index) => {
        const deskripsi = item.deskripsi ? (item.deskripsi.length > 60 ? item.deskripsi.substring(0, 60) + '...' : item.deskripsi) : '-';
        const tanggal = item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', {
            day: 'numeric', month: 'short', year: 'numeric'
        }) : '-';

        const row = `
            <tr onclick="editPeraturan(${item.id}, event)">
                <td style="text-align: center;">${index + 1}</td>
                <td>
                    <span class="peraturan-name">${escapeHtml(item.judul)}</span>
                </td>
                <td>${escapeHtml(deskripsi)}</td>
                <td style="text-align: center;">
                    <span class="date-badge">${tanggal}</span>
                </td>
                <td>
                    <div class="action-buttons">
                        <button onclick="hapusPeraturan(${item.id}, event)" 
                                class="btn btn-delete btn-icon" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
    
    document.getElementById('totalData').textContent = `Total: ${data.length} peraturan`;
}

function filterTable(searchTerm) {
    if (!searchTerm) {
        renderTable(allPeraturanData);
        return;
    }
    
    const filteredData = allPeraturanData.filter(item => {
        return (
            (item.judul && item.judul.toLowerCase().includes(searchTerm)) ||
            (item.deskripsi && item.deskripsi.toLowerCase().includes(searchTerm))
        );
    });
    
    renderTable(filteredData);
}

function editPeraturan(id, event) {
    // Jangan navigate jika klik tombol delete
    if (event && event.target.closest('.btn-delete')) {
        return;
    }
    navigate('admin/peraturan/' + id + '/edit');
}

function hapusPeraturan(id, event) {
    // Mencegah event bubbling ke row
    if (event) {
        event.stopPropagation();
    }
    
    if(confirm('Apakah Anda yakin ingin menghapus peraturan ini?')) {
        fetch(API_URL + '/peraturan-lab/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success' || data.code === 200) {
                alert('Data berhasil dihapus!');
                loadPeraturan();
            } else {
                alert('Gagal menghapus: ' + (data.message || 'Error tidak diketahui'));
            }
        })
        .catch(err => {
            alert('Error: ' + err.message);
            console.error(err);
        });
    }
}

function escapeHtml(text) {
    if (!text) return '';
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function navigate(route) {
    if (window.location.port === '8000') {
        window.location.href = '/index.php?route=' + route;
    } else {
        window.location.href = '/' + route;
    }
}
</script>
