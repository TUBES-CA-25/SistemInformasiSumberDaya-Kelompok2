<div class="admin-header">
    <h1>Manajemen Laboratorium</h1>
    <a href="<?php echo BASE_URL; ?>/public/admin-laboratorium-form.php" class="btn btn-add">
        <i class="fas fa-plus"></i> Tambah Laboratorium
    </a>
</div>

<div class="card">
    <div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="text" id="searchInput" placeholder="Cari nama laboratorium..." 
                   style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; width: 300px;">
            <button onclick="loadLaboratorium()" class="btn btn-primary" style="background: #3498db; padding: 8px 15px;">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
        <div id="totalData" style="color: #666; font-size: 0.9rem;">Total: 0 laboratorium</div>
    </div>

    <div style="overflow-x: auto;">
        <table class="crud-table">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th style="width: 100px; text-align: center;">Gambar</th>
                    <th>Nama Laboratorium</th>
                    <th>Deskripsi</th>
                    <th style="width: 150px; text-align: center;">Kapasitas</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr><td colspan="6" style="text-align:center; padding: 40px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #ccc;"></i>
                    <p style="color: #999; margin-top: 10px;">Memuat data...</p>
                </td></tr>
            </tbody>
        </table>
    </div>
</div>

<style>
    .capacity-badge {
        background: #e8f0fe;
        color: #1967d2;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.85rem;
        display: inline-block;
        margin: 2px;
    }
    .lab-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1rem;
    }
    .lab-image {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #eee;
    }
    .crud-table tbody tr:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s;
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
let allLabData = [];

document.addEventListener('DOMContentLoaded', function() {
    loadLaboratorium();
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        filterTable(searchTerm);
    });
});

function loadLaboratorium() {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 40px;"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #ccc;"></i><p style="color: #999; margin-top: 10px;">Memuat data...</p></td></tr>';
    
    fetch(API_URL + '/laboratorium')
    .then(res => res.json())
    .then(response => {
        if((response.status === 'success' || response.code === 200) && response.data && response.data.length > 0) {
            allLabData = response.data;
            renderTable(allLabData);
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 40px;"><i class="fas fa-desktop" style="font-size: 2rem; color: #ddd;"></i><p style="color: #999; margin-top: 10px;">Belum ada data laboratorium.</p></td></tr>';
            document.getElementById('totalData').textContent = 'Total: 0 laboratorium';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 40px;"><i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #e74c3c;"></i><p style="color: #e74c3c; margin-top: 10px;">Gagal memuat data. Silakan coba lagi.</p></td></tr>';
    });
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '';
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 40px;"><i class="fas fa-search" style="font-size: 2rem; color: #ddd;"></i><p style="color: #999; margin-top: 10px;">Tidak ada data yang cocok dengan pencarian.</p></td></tr>';
        return;
    }
    
    data.forEach((item, index) => {
        const deskripsi = item.deskripsi ? (item.deskripsi.length > 60 ? item.deskripsi.substring(0, 60) + '...' : item.deskripsi) : '-';
        const imageSrc = item.gambar ? (ASSETS_URL + '/uploads/' + item.gambar) : (ASSETS_URL + '/img/no-image.jpg');
        
        // Handle image error with fallback (prevent infinite loop with this.onerror=null)
        const fallbackImage = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='60' viewBox='0 0 80 60'%3E%3Crect width='80' height='60' fill='%23eee'/%3E%3Ctext x='50%25' y='50%25' font-family='Arial' font-size='10' fill='%23999' dominant-baseline='middle' text-anchor='middle'%3ENo Img%3C/text%3E%3C/svg%3E";
        const imgHtml = `<img src="${imageSrc}" class="lab-image" onerror="this.onerror=null;this.src='${fallbackImage}'">`;

        const row = `
            <tr>
                <td style="text-align: center;">${index + 1}</td>
                <td style="text-align: center;">${imgHtml}</td>
                <td>
                    <span class="lab-name">${escapeHtml(item.nama)}</span>
                </td>
                <td>${escapeHtml(deskripsi)}</td>
                <td style="text-align: center;">
                    <div class="capacity-badge"><i class="fas fa-desktop"></i> ${item.jumlahPc || 0} PC</div>
                    <div class="capacity-badge"><i class="fas fa-chair"></i> ${item.jumlahKursi || 0} Kursi</div>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="<?php echo BASE_URL; ?>/public/admin-laboratorium-form.php?id=${item.idLaboratorium}" 
                           class="btn btn-edit btn-icon" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="hapusLaboratorium(${item.idLaboratorium})" 
                                class="btn btn-delete btn-icon" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
    
    document.getElementById('totalData').textContent = `Total: ${data.length} laboratorium`;
}

function filterTable(searchTerm) {
    if (!searchTerm) {
        renderTable(allLabData);
        return;
    }
    
    const filteredData = allLabData.filter(item => {
        return (
            (item.nama && item.nama.toLowerCase().includes(searchTerm)) ||
            (item.deskripsi && item.deskripsi.toLowerCase().includes(searchTerm))
        );
    });
    
    renderTable(filteredData);
}

function hapusLaboratorium(id) {
    if(confirm('Apakah Anda yakin ingin menghapus laboratorium ini?')) {
        fetch(API_URL + '/laboratorium/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success' || data.code === 200) {
                alert('Data berhasil dihapus!');
                loadLaboratorium();
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
</script>