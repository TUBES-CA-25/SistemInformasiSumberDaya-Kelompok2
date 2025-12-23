<div class="admin-header">
    <h1 id="labName">Detail Laboratorium</h1>
    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="javascript:void(0)" onclick="navigate('admin/laboratorium')" class="btn" style="background: #95a5a6; height: 45px; min-width: 120px; display: flex; align-items: center; justify-content: center; margin: 0; padding: 0 20px; box-sizing: border-box; border: none; font-size: 14px; text-decoration: none; color: white; border-radius: 5px;">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Kembali
        </a>
        <a href="javascript:void(0)" id="btnEdit" class="btn btn-add" style="height: 45px; min-width: 120px; display: flex; align-items: center; justify-content: center; margin: 0; padding: 0 20px; box-sizing: border-box; border: none; font-size: 14px; text-decoration: none; color: white; border-radius: 5px;">
            <i class="fas fa-edit" style="margin-right: 8px;"></i> Edit Data
        </a>
    </div>
</div>

<div class="detail-container">
    <!-- Header Section -->
    <div class="detail-header">
        <div class="lab-image">
            <img id="labImage" src="https://placehold.co/800x400" alt="Lab Image">
        </div>
        <div class="lab-title">
            <h2 id="labTitle">Laboratorium Rekayasa Perangkat Lunak</h2>
            <p class="location"><i class="fas fa-map-marker-alt"></i> <span id="labLocation">Gedung Fikom Lt. 2 (Ruang 204)</span></p>
        </div>
    </div>

    <!-- Spesifikasi Section -->
    <div class="detail-section">
        <h3><i class="fas fa-info-circle"></i> Spesifikasi & Fasilitas</h3>
        <p id="labDescription">Laboratorium ini dirancang untuk menunjang mata kuliah pemrograman tingkat lanjut dengan kapasitas 40 mahasiswa.</p>
    </div>

    <!-- Hardware Section -->
    <div class="detail-section">
        <h3><i class="fas fa-desktop"></i> Perangkat Keras (Hardware)</h3>
        <div class="specs-grid">
            <div class="spec-item">
                <strong>Processor:</strong>
                <span id="processor">Intel Core i7 Gen 12</span>
            </div>
            <div class="spec-item">
                <strong>RAM:</strong>
                <span id="ram">32 GB DDR4</span>
            </div>
            <div class="spec-item">
                <strong>Storage:</strong>
                <span id="storage">SSD NVMe 1 TB</span>
            </div>
            <div class="spec-item">
                <strong>GPU:</strong>
                <span id="gpu">NVIDIA RTX 3060</span>
            </div>
            <div class="spec-item">
                <strong>Monitor:</strong>
                <span id="monitor">24 Inch IPS Display</span>
            </div>
            <div class="spec-item">
                <strong>Jumlah Unit:</strong>
                <span id="jumlahPC">41 PC (1 Instruktur + 40 Praktikan)</span>
            </div>
        </div>
    </div>

    <!-- Software Section -->
    <div class="detail-section">
        <h3><i class="fas fa-code"></i> Perangkat Lunak (Software)</h3>
        <ul class="software-list" id="softwareList">
            <li>Visual Studio Code, JetBrains Suite</li>
            <li>XAMPP, Node.js, Python 3.10</li>
            <li>Android Studio, Flutter SDK</li>
            <li>Git, Docker Desktop</li>
        </ul>
    </div>

    <!-- Fasilitas Pendukung Section -->
    <div class="detail-section">
        <h3><i class="fas fa-tools"></i> Fasilitas Pendukung</h3>
        <ul class="facility-list" id="facilityList">
            <li><i class="fas fa-snowflake"></i> AC Central (2 Unit)</li>
            <li><i class="fas fa-project-diagram"></i> Proyektor HD & Sound System</li>
            <li><i class="fas fa-chalkboard"></i> Whiteboard Kaca</li>
            <li><i class="fas fa-wifi"></i> Koneksi Internet LAN Gigabit & WiFi 6</li>
        </ul>
    </div>

    <!-- Koordinator Section -->
    <div class="detail-section coordinator-section">
        <h3><i class="fas fa-user-tie"></i> Koordinator Lab</h3>
        <div class="coordinator-card" id="coordinatorCard">
            <img id="coordinatorImage" src="https://placehold.co/150x150" alt="Koordinator">
            <div>
                <h4 id="coordinatorName">Belum ada koordinator</h4>
                <p class="coordinator-role">Koordinator Laboratorium</p>
            </div>
        </div>
    </div>
</div>

<style>
.detail-container {
    max-width: 1000px;
    margin: 0 auto;
}

.detail-header {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.lab-image {
    width: 100%;
    height: 400px;
    overflow: hidden;
}

.lab-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.lab-title {
    padding: 30px;
}

.lab-title h2 {
    margin: 0 0 10px 0;
    color: #2c3e50;
    font-size: 28px;
}

.location {
    color: #7f8c8d;
    font-size: 16px;
    margin: 0;
}

.location i {
    color: #e74c3c;
    margin-right: 8px;
}

.detail-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.detail-section h3 {
    color: #2c3e50;
    margin: 0 0 20px 0;
    font-size: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ecf0f1;
}

.detail-section h3 i {
    color: #3498db;
    margin-right: 10px;
}

.detail-section p {
    color: #555;
    line-height: 1.8;
    margin: 0;
}

.specs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 15px;
}

.spec-item {
    display: flex;
    justify-content: space-between;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 3px solid #3498db;
}

.spec-item strong {
    color: #2c3e50;
}

.spec-item span {
    color: #555;
    text-align: right;
}

.software-list, .facility-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.software-list li, .facility-list li {
    padding: 12px;
    margin-bottom: 8px;
    background: #f8f9fa;
    border-radius: 8px;
    color: #555;
    border-left: 3px solid #27ae60;
}

.facility-list li i {
    color: #3498db;
    margin-right: 10px;
    width: 20px;
}

.coordinator-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.coordinator-section h3 {
    color: white;
    border-bottom-color: rgba(255,255,255,0.3);
}

.coordinator-section h3 i {
    color: white;
}

.coordinator-card {
    display: flex;
    align-items: center;
    gap: 20px;
    background: rgba(255,255,255,0.1);
    padding: 20px;
    border-radius: 12px;
}

.coordinator-card img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
}

.coordinator-card h4 {
    margin: 0 0 5px 0;
    font-size: 22px;
    color: white;
}

.coordinator-role {
    margin: 0;
    color: rgba(255,255,255,0.9);
    font-size: 14px;
}

@media (max-width: 768px) {
    .specs-grid {
        grid-template-columns: 1fr;
    }
    
    .coordinator-card {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    let route = params.get('route') || window.location.pathname;
    
    const matches = route.match(/admin\/laboratorium\/(\d+)/);
    
    if (matches && matches[1]) {
        const id = matches[1];
        loadLabDetail(id);
        
        // Set edit button URL
        document.getElementById('btnEdit').onclick = function() {
            navigate('admin/laboratorium/' + id + '/edit');
        };
    } else {
        alert('ID laboratorium tidak ditemukan');
        navigate('admin/laboratorium');
    }
});

function loadLabDetail(id) {
    fetch(API_URL + '/laboratorium/' + id)
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success' || response.code === 200) {
            const data = response.data;
            
            // Set data ke UI
            document.getElementById('labName').textContent = data.nama || 'Laboratorium';
            document.getElementById('labTitle').textContent = data.nama || 'Laboratorium';
            document.getElementById('labLocation').textContent = data.lokasi || 'Lokasi tidak tersedia';
            document.getElementById('labDescription').textContent = data.deskripsi || 'Deskripsi tidak tersedia';
            
            // Set gambar
            if (data.gambar) {
                const imagePath = data.gambar.includes('http') ? data.gambar : '/SistemInformasiSumberDaya-Kelompok2/public/assets/uploads/' + data.gambar;
                document.getElementById('labImage').src = imagePath;
            }
            
            // Set spesifikasi (jika ada di database)
            if (data.processor) document.getElementById('processor').textContent = data.processor;
            if (data.ram) document.getElementById('ram').textContent = data.ram;
            if (data.storage) document.getElementById('storage').textContent = data.storage;
            if (data.gpu) document.getElementById('gpu').textContent = data.gpu;
            if (data.monitor) document.getElementById('monitor').textContent = data.monitor;
            if (data.jumlahPc) {
                document.getElementById('jumlahPC').textContent = data.jumlahPc + ' PC';
            }
            
            // Load koordinator asisten jika ada
            if (data.idKordinatorAsisten) {
                loadKoordinatorAsisten(data.idKordinatorAsisten);
            } else {
                // Hide coordinator section if no coordinator
                document.getElementById('coordinatorCard').style.opacity = '0.6';
            }
            
        } else {
            alert('Data tidak ditemukan');
            navigate('admin/laboratorium');
        }
    })
    .catch(err => {
        console.error('Error loading data:', err);
        alert('Gagal memuat data laboratorium');
    });
}

function loadKoordinatorAsisten(idAsisten) {
    fetch(API_URL + '/asisten/' + idAsisten)
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success' || response.code === 200) {
            const asisten = response.data;
            document.getElementById('coordinatorName').textContent = asisten.nama || 'Nama tidak tersedia';
            
            if (asisten.foto) {
                const fotoPath = asisten.foto.includes('http') ? asisten.foto : '/SistemInformasiSumberDaya-Kelompok2/public/assets/uploads/' + asisten.foto;
                document.getElementById('coordinatorImage').src = fotoPath;
            }
            
            document.getElementById('coordinatorCard').style.opacity = '1';
        }
    })
    .catch(err => {
        console.error('Error loading koordinator:', err);
    });
}

</script>
