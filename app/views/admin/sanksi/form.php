<div class="admin-header">
    <h1 id="pageTitle">Tambah Sanksi Baru</h1>
    <a href="/SistemInformasiSumberDaya-Kelompok2/public/admin-sanksi.php" class="btn btn-secondary">← Kembali</a>
</div>

<div class="card">
    <form id="sanksiForm" style="max-width: 600px;">
        <div class="form-group">
            <label>Judul Sanksi *</label>
            <input type="text" name="judul" id="judul" required placeholder="Contoh: Keterlambatan Hadir" class="form-control">
            <small class="form-text">Nama singkat sanksi</small>
        </div>

        <div class="form-group">
            <label>Deskripsi Sanksi</label>
            <textarea name="deskripsi" id="deskripsi" rows="6" placeholder="Jelaskan konsekuensi dan hukuman yang berlaku..." class="form-control"></textarea>
            <small class="form-text">Penjelasan lengkap tentang sanksi</small>
        </div>

        <div class="form-group">
            <label>Upload Gambar (Opsional)</label>
            <input type="file" name="gambar" id="gambar" accept="image/*" class="form-control">
            <small class="form-text">Format: JPG, PNG (Max 2MB)</small>
        </div>

        <div class="form-group" style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Simpan Sanksi</button>
            <a href="/SistemInformasiSumberDaya-Kelompok2/public/admin-sanksi.php" class="btn btn-secondary" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
</div>

<style>
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-family: Arial, sans-serif;
        font-size: 14px;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
    }

    .form-text {
        display: block;
        color: #7f8c8d;
        font-size: 12px;
        margin-top: 4px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background-color: #3498db;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }

    .btn-secondary {
        background-color: #95a5a6;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #7f8c8d;
    }
</style>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const editId = urlParams.get('id');

    document.addEventListener('DOMContentLoaded', function() {
        if (editId) {
            loadSanksiEdit(editId);
        }
    });

    function loadSanksiEdit(id) {
        const apiUrl = `/SistemInformasiSumberDaya-Kelompok2/public/api.php/sanksi-lab/${id}`;
        fetch(apiUrl)
            .then(res => res.json())
            .then(response => {
                if (response.status === 'success') {
                    const data = response.data;
                    document.getElementById('pageTitle').innerText = 'Edit Sanksi';
                    document.getElementById('judul').value = data.judul || '';
                    document.getElementById('deskripsi').value = data.deskripsi || '';
                    
                    // Simpan ID di form untuk digunakan saat submit
                    document.getElementById('sanksiForm').dataset.editId = id;
                } else {
                    alert('Gagal memuat data: ' + response.message);
                    window.location.href = '/SistemInformasiSumberDaya-Kelompok2/public/admin-sanksi.php';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat data sanksi');
                window.location.href = '/SistemInformasiSumberDaya-Kelompok2/public/admin-sanksi.php';
            });
    }

    document.getElementById('sanksiForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const judul = document.getElementById('judul').value.trim();
        const deskripsi = document.getElementById('deskripsi').value.trim();
        const gambarInput = document.getElementById('gambar');
        const gambar = gambarInput.files[0];
        const editId = this.dataset.editId;

        console.log('=== FORM SUBMIT DEBUG ===');
        console.log('Judul:', judul);
        console.log('Deskripsi:', deskripsi);
        console.log('Gambar input element:', gambarInput);
        console.log('Gambar files length:', gambarInput.files.length);
        console.log('Gambar file:', gambar);
        console.log('Edit ID:', editId);

        // Validasi
        if (!judul) {
            alert('Judul sanksi harus diisi!');
            return;
        }

        // SELALU gunakan FormData agar bisa handle file upload + text fields
        let data = new FormData();
        data.append('judul', judul);
        data.append('deskripsi', deskripsi || '');
        if (gambar) {
            console.log('Adding file to FormData:', gambar.name, gambar.size);
            data.append('gambar', gambar);
        } else {
            console.log('No file selected');
        }

        // Untuk edit dengan file upload, HARUS gunakan POST dengan method override
        // Karena PHP tidak bisa auto-parse $_FILES untuk PUT request
        const method = 'POST'; // Selalu POST untuk FormData dengan file
        const apiUrl = editId 
            ? `/SistemInformasiSumberDaya-Kelompok2/public/api.php/sanksi-lab/${editId}`
            : '/SistemInformasiSumberDaya-Kelompok2/public/api.php/sanksi-lab';

        console.log('Method:', method);
        console.log('API URL:', apiUrl);

        const options = {
            method: method,
            body: data
            // Jangan set Content-Type - browser akan set otomatis dengan FormData
        };

        console.log('Sending request...');
        fetch(apiUrl, options)
            .then(res => {
                // Log untuk debugging
                console.log('✓ Response received');
                console.log('Response status:', res.status);
                console.log('Response headers:', res.headers);
                return res.text().then(text => {
                    console.log('Response text:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse JSON:', e);
                        throw new Error('Invalid JSON response: ' + text.substring(0, 200));
                    }
                });
            })
            .then(response => {
                console.log('Response object:', response);
                if (response.status === 'success') {
                    alert(editId ? 'Sanksi berhasil diperbarui!' : 'Sanksi berhasil ditambahkan!');
                    window.location.href = '/SistemInformasiSumberDaya-Kelompok2/public/admin-sanksi.php';
                } else {
                    alert('Gagal menyimpan: ' + response.message);
                }
            })
            .catch(error => {
                console.error('✗ Error:', error);
                alert('Gagal menyimpan sanksi: ' + error.message);
            });
    });
</script>
