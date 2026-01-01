<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-user-graduate text-blue-600"></i> 
        Formulir Alumni
    </h1>
    <a href="javascript:void(0)" onclick="navigate('admin/alumni')" 
       class="mt-4 sm:mt-0 bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center gap-2 font-medium">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 max-w-4xl mx-auto">
    
    <form id="alumniForm" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" id="id" name="id">

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-user mr-1 text-gray-400"></i> Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap alumni" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt mr-1 text-gray-400"></i> Tahun Angkatan <span class="text-red-500">*</span>
                </label>
                <input type="number" id="angkatan" name="angkatan" placeholder="2020" min="2000" max="2030" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-sitemap mr-1 text-gray-400"></i> Divisi / Posisi
                </label>
                <input type="text" id="divisi" name="divisi" placeholder="Contoh: Asisten Lab"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-briefcase mr-1 text-gray-400"></i> Pekerjaan Saat Ini
                </label>
                <input type="text" id="pekerjaan" name="pekerjaan" placeholder="Contoh: Software Engineer"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-building mr-1 text-gray-400"></i> Perusahaan
                </label>
                <input type="text" id="perusahaan" name="perusahaan" placeholder="Contoh: PT Teknologi Indonesia"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-graduation-cap mr-1 text-gray-400"></i> Tahun Lulus
                </label>
                <input type="text" id="tahun_lulus" name="tahun_lulus" placeholder="2023"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-camera mr-1 text-gray-400"></i> Upload Foto
                </label>
                <input type="file" id="foto" name="foto" accept="image/*"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors">
                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG. Max 2MB</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-code mr-1 text-gray-400"></i> Keahlian
            </label>
            <textarea id="keahlian" name="keahlian" rows="2" placeholder="Contoh: PHP, Laravel, React, MySQL"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-1 text-gray-400"></i> Email
                </label>
                <input type="email" id="email" name="email" placeholder="email@example.com"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fab fa-linkedin mr-1 text-gray-400"></i> LinkedIn
                </label>
                <input type="url" id="linkedin" name="linkedin" placeholder="https://linkedin.com/in/..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-globe mr-1 text-gray-400"></i> Portfolio
                </label>
                <input type="url" id="portfolio" name="portfolio" placeholder="https://portfolio.com"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-comment-alt mr-1 text-gray-400"></i> Kesan & Pesan
            </label>
            <textarea id="kesan_pesan" name="kesan_pesan" rows="4" placeholder="Tulis pengalaman selama menjadi asisten..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"></textarea>
        </div>

        <button type="submit" id="btnSave" 
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-lg shadow transition-all duration-200 transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
            <i class="fas fa-save"></i> <span>Simpan Data</span>
        </button>

        <div id="message" class="mt-4"></div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- PERBAIKAN UTAMA DISINI ---
    // Kita ambil ID langsung dari variabel PHP yang sudah ditangkap di index.php
    // Ini jauh lebih stabil daripada parsing URL manual di JS
    const alumniId = "<?= isset($id) ? $id : '' ?>"; 
    
    console.log('Current ID from PHP:', alumniId); // Untuk Debugging

    // Jika ID ada isinya (artinya mode EDIT), panggil data
    if (alumniId && alumniId !== '') {
        loadAlumniData(alumniId);
    }
});

function loadAlumniData(id) {
    // Tampilkan loading indicator di form selagi menunggu
    const formInputs = document.querySelectorAll('#alumniForm input, #alumniForm textarea');
    formInputs.forEach(input => input.classList.add('animate-pulse'));

    fetch(API_URL + '/alumni/' + id)
    .then(res => res.json())
    .then(response => {
        // Hapus efek loading
        formInputs.forEach(input => input.classList.remove('animate-pulse'));

        if ((response.status === 'success' || response.code === 200) && response.data) {
            const data = response.data;
            
            // --- DEBUG DATA ---
            // Cek di Console browser (F12) apakah data masuk
            console.log('Data fetched:', data); 

            // ISI FORM (Pastikan nama property 'data.xxx' sesuai dengan database)
            document.getElementById('id').value = data.id || '';
            document.getElementById('nama').value = data.nama || '';
            document.getElementById('angkatan').value = data.angkatan || '';
            document.getElementById('divisi').value = data.divisi || '';
            document.getElementById('pekerjaan').value = data.pekerjaan || '';
            document.getElementById('perusahaan').value = data.perusahaan || '';
            document.getElementById('tahun_lulus').value = data.tahun_lulus || '';
            document.getElementById('keahlian').value = data.keahlian || '';
            document.getElementById('email').value = data.email || '';
            document.getElementById('linkedin').value = data.linkedin || '';
            document.getElementById('portfolio').value = data.portfolio || '';
            document.getElementById('kesan_pesan').value = data.kesan_pesan || '';
            
            // Ubah Judul & Tombol jadi Mode Edit
            document.querySelector('h1').innerHTML = '<i class="fas fa-user-edit text-blue-600 mr-2"></i> Edit Alumni';
            
            const btn = document.getElementById('btnSave');
            btn.innerHTML = '<i class="fas fa-save"></i> <span>Update Data</span>';
            btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-700');
            btn.classList.add('bg-blue-600', 'hover:bg-blue-700');
        } else {
            console.error('Data tidak ditemukan atau format salah');
        }
    })
    .catch(err => {
        console.error('Error loading alumni:', err);
        alert('Gagal mengambil data untuk diedit. Cek koneksi API.');
    });
}

// ... (Sisa kode event listener submit tetap sama) ...
document.getElementById('alumniForm').addEventListener('submit', function(e) {
    // ... copy kode submit yang sebelumnya di sini ...
    e.preventDefault();

    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    const originalContent = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> <span>Menyimpan...</span>';
    btn.classList.add('opacity-75', 'cursor-not-allowed');

    const formData = new FormData(this);
    const id = document.getElementById('id').value;
    const url = id ? API_URL + '/alumni/' + id : API_URL + '/alumni';
    
    // Khusus untuk Edit dengan Upload File di PHP Native/CodeIgniter kadang butuh trick ini
    // Jika backend tidak support method PUT via FormData, gunakan POST + _method
    // formData.append('_method', id ? 'PUT' : 'POST'); 

    fetch(url, {
        method: 'POST', // Gunakan POST untuk menghandle file upload lebih aman
        body: formData
    })
    .then(res => res.text())
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.status === 'success' || data.code === 201 || data.code === 200) {
                msg.innerHTML = `
                    <div class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
                        <i class="fas fa-check-circle mr-2 text-lg"></i>
                        <span class="font-medium">Berhasil disimpan!</span> Mengalihkan...
                    </div>`;
                setTimeout(() => { navigate('admin/alumni'); }, 1500);
            } else {
                throw new Error(data.message || 'Gagal menyimpan');
            }
        } catch (e) {
            console.error(e);
            msg.innerHTML = `
                <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
                    <i class="fas fa-exclamation-circle mr-2"></i> ${e.message}
                </div>`;
            btn.disabled = false;
            btn.innerHTML = originalContent;
            btn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
    })
    .catch(err => {
        console.error(err);
        btn.disabled = false;
        btn.innerHTML = originalContent;
        btn.classList.remove('opacity-75', 'cursor-not-allowed');
    });
});
</script>