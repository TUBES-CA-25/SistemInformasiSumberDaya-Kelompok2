<div class="admin-header">
    <h1 id="pageTitle">Tambah Peraturan Baru</h1>
    <a href="/SistemInformasiSumberDaya-Kelompok2/public/admin-peraturan.php" class="btn btn-secondary">← Kembali</a>
</div>

<div class="card">
    <form id="peraturanForm" style="max-width: 600px;">
        <div class="form-group">
            <label>Nama Peraturan *</label>
            <input type="text" name="namaFile" id="namaFile" required placeholder="Contoh: Disiplin Waktu Kehadiran" class="form-control">
            <small class="form-text">Judul singkat peraturan</small>
        </div>

        <div class="form-group">
            <label>Deskripsi / Uraian Peraturan</label>
            <textarea name="uraFile" id="uraFile" rows="6" placeholder="Jelaskan aturan dan konsekuensi yang berlaku..." class="form-control"></textarea>
            <small class="form-text">Penjelasan lengkap tentang peraturan ini</small>
        </div>

        <div class="form-group" style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Simpan Peraturan</button>
            <a href="/SistemInformasiSumberDaya-Kelompok2/public/admin-peraturan.php" class="btn btn-secondary" style="margin-left: 10px;">Batal</a>
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
            loadPeraturanEdit(editId);
        }
    });

    function loadPeraturanEdit(id) {
        const apiUrl = `/SistemInformasiSumberDaya-Kelompok2/public/api.php/tata-tertib/${id}`;
        fetch(apiUrl)
            .then(res => res.json())
            .then(response => {
                if (response.status === 'success') {
                    const data = response.data;
                    document.getElementById('pageTitle').innerText = 'Edit Peraturan';
                    document.getElementById('namaFile').value = data.namaFile || '';
                    document.getElementById('uraFile').value = data.uraFile || '';
                    
                    // Simpan ID di form untuk digunakan saat submit
                    document.getElementById('peraturanForm').dataset.editId = id;
                } else {
                    alert('Gagal memuat data: ' + response.message);
                    window.location.href = '/admin-peraturan.php';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat data peraturan');
                window.location.href = '/admin-peraturan.php';
            });
    }

    document.getElementById('peraturanForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const namaFile = document.getElementById('namaFile').value.trim();
        const uraFile = document.getElementById('uraFile').value.trim();
        const editId = this.dataset.editId;

        // Validasi
        if (!namaFile) {
            alert('Nama peraturan harus diisi!');
            return;
        }

        const data = {
            namaFile: namaFile,
            uraFile: uraFile || null
        };

        const method = editId ? 'PUT' : 'POST';
        const apiUrl = editId 
            ? `/SistemInformasiSumberDaya-Kelompok2/public/api.php/tata-tertib/${editId}`
            : '/SistemInformasiSumberDaya-Kelompok2/public/api.php/tata-tertib';

        fetch(apiUrl, {
            method: method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(res => res.json())
            .then(response => {
                if (response.status === 'success') {
                    alert(editId ? 'Peraturan berhasil diperbarui!' : 'Peraturan berhasil ditambahkan!');
                    window.location.href = '/SistemInformasiSumberDaya-Kelompok2/public/admin-peraturan.php';
                } else {
                    alert('Gagal menyimpan: ' + response.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal menyimpan peraturan');
            });
    });
</script>
