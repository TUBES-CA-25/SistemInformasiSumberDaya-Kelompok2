<div class="admin-header">
    <h1>Manajemen Mata Kuliah</h1>
    <a href="javascript:void(0)" onclick="navigate('admin/matakuliah/create')" class="btn btn-add">
        <i class="fas fa-plus"></i> Tambah Mata Kuliah
    </a>
</div>

<div class="card">
    <div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="text" id="searchInput" placeholder="Cari kode atau nama mata kuliah..." 
                   style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; width: 300px;">
            <button onclick="loadMatakuliah()" class="btn btn-primary" style="background: #3498db; padding: 8px 15px;">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
        <div id="totalData" style="color: #666; font-size: 0.9rem;">Total: 0 mata kuliah</div>
    </div>

    <div style="overflow-x: auto;">
        <table class="crud-table">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th style="width: 120px;">Kode MK</th>
                    <th>Nama Mata Kuliah</th>
                    <th style="width: 100px; text-align: center;">Semester</th>
                    <th style="width: 80px; text-align: center;">SKS</th>
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
    .kode-badge {
        background: #e8f0fe;
        color: #1967d2;
        padding: 4px 10px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-block;
        font-family: monospace;
    }
    .semester-badge {
        background-color: #fff3cd;
        color: #856404;
        padding: 4px 12px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-block;
    }
    .sks-badge {
        background-color: #e2e6ea;
        color: #343a40;
        padding: 4px 12px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-block;
    }
    .mk-name {
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
let allMatakuliahData = [];

document.addEventListener('DOMContentLoaded', function() {
    loadMatakuliah();
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        filterTable(searchTerm);
    });
});

function loadMatakuliah() {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 40px;"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #ccc;"></i><p style="color: #999; margin-top: 10px;">Memuat data...</p></td></tr>';
    
    fetch(API_URL + '/matakuliah')
    .then(res => res.json())
    .then(response => {
        if((response.status === 'success' || response.code === 200) && response.data && response.data.length > 0) {
            allMatakuliahData = response.data;
            renderTable(allMatakuliahData);
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 40px;"><i class="fas fa-book-open" style="font-size: 2rem; color: #ddd;"></i><p style="color: #999; margin-top: 10px;">Belum ada data mata kuliah.</p></td></tr>';
            document.getElementById('totalData').textContent = 'Total: 0 mata kuliah';
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
        const row = `
            <tr onclick="editMatakuliah(${item.idMatakuliah}, event)">
                <td style="text-align: center;">${index + 1}</td>
                <td>
                    <span class="kode-badge">${item.kodeMatakuliah}</span>
                </td>
                <td>
                    <span class="mk-name">${item.namaMatakuliah}</span>
                </td>
                <td style="text-align: center;">
                    <span class="semester-badge">Sem ${item.semester || '-'}</span>
                </td>
                <td style="text-align: center;">
                    <span class="sks-badge">${item.sksKuliah || '-'} SKS</span>
                </td>
                <td>
                    <div class="action-buttons">
                        <button onclick="hapusMatakuliah(${item.idMatakuliah}, event)" 
                                class="btn btn-delete btn-icon" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
    
    document.getElementById('totalData').textContent = `Total: ${data.length} mata kuliah`;
}

function filterTable(searchTerm) {
    if (!searchTerm) {
        renderTable(allMatakuliahData);
        return;
    }
    
    const filteredData = allMatakuliahData.filter(item => {
        return (
            (item.namaMatakuliah && item.namaMatakuliah.toLowerCase().includes(searchTerm)) ||
            (item.kodeMatakuliah && item.kodeMatakuliah.toLowerCase().includes(searchTerm)) ||
            (item.semester && item.semester.toString().includes(searchTerm))
        );
    });
    
    renderTable(filteredData);
}

function editMatakuliah(id, event) {
    // Jangan navigate jika klik tombol delete
    if (event && event.target.closest('.btn-delete')) {
        return;
    }
    navigate('admin/matakuliah/' + id + '/edit');
}

function hapusMatakuliah(id, event) {
    // Mencegah event bubbling ke row
    if (event) {
        event.stopPropagation();
    }
    
    if(confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?')) {
        fetch(API_URL + '/matakuliah/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success' || data.code === 200) {
                alert('Data berhasil dihapus!');
                loadMatakuliah();
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

</script>