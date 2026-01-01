<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-user-graduate text-blue-600"></i> 
        Data Alumni Asisten
    </h1>
    
    <a href="javascript:void(0)" onclick="navigate('admin/alumni/create')" 
       class="mt-4 sm:mt-0 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center gap-2 font-medium transform hover:-translate-y-0.5">
        <i class="fas fa-plus"></i> Tambah Alumni
    </a>
</div>

<div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th class="px-6 py-4 font-semibold text-center w-24">Foto</th>
                    <th class="px-6 py-4 font-semibold">Nama Alumni</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Angkatan</th>
                    <th class="px-6 py-4 font-semibold">Pekerjaan Sekarang</th>
                    <th class="px-6 py-4 font-semibold text-center w-40">Aksi</th>
                </tr>
            </thead>
            
            <tbody id="tableBody" class="divide-y divide-gray-200 text-gray-700 text-sm">
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex justify-center items-center gap-2">
                            <i class="fas fa-circle-notch fa-spin text-blue-500 text-xl"></i>
                            <span class="font-medium">Sedang memuat data...</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
// Load data saat halaman siap
document.addEventListener('DOMContentLoaded', loadAlumni);

function loadAlumni() {
    // Pastikan API_URL sudah didefinisikan di layout utama
    fetch(API_URL + '/alumni') 
    .then(res => res.json())
    .then(response => {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = ''; // Bersihkan loading state

        if((response.status === 'success' || response.code === 200) && response.data && response.data.length > 0) {
            response.data.forEach((item, index) => {
                // Logika Foto (Cek apakah URL lengkap atau path lokal)
                const fotoUrl = item.foto 
                    ? (item.foto.includes('http') ? item.foto : ASSETS_URL + '/assets/uploads/' + item.foto) 
                    : 'https://placehold.co/50x50?text=Foto';

                // --- RENDER BARIS TABEL (OPSI 1: SOFT BADGE BUTTONS) ---
                const row = `
                    <tr class="hover:bg-gray-50 transition-colors duration-150 group border-b border-gray-100">
                        
                        <td class="px-6 py-4 text-center font-medium text-gray-500">
                            ${index + 1}
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                <img src="${fotoUrl}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-md" alt="Foto">
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800 text-sm">${item.nama}</span>
                                <span class="text-xs text-gray-500 uppercase tracking-wide mt-0.5">
                                    ${item.divisi || 'Asisten'}
                                </span>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 text-center">
                            <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-semibold border border-blue-100">
                                ${item.angkatan || '-'}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 text-gray-600 text-sm font-medium">
                            ${item.pekerjaan || '<span class="text-gray-400 italic text-xs">Belum diisi</span>'}
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex justify-center items-center gap-2">
                                
                                <button onclick="btnEditClick(${item.id}, event)" 
                                        class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-all duration-200 shadow-sm flex items-center justify-center border border-transparent hover:shadow-md"
                                        title="Edit Data">
                                    <i class="fas fa-pen text-xs"></i>
                                </button>

                                <button onclick="hapusAlumni(${item.id}, event)" 
                                        class="w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-all duration-200 shadow-sm flex items-center justify-center border border-transparent hover:shadow-md"
                                        title="Hapus Data">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>

                            </div>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } else {
            // Tampilan Jika Data Kosong
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-gray-100 p-4 rounded-full mb-3">
                                <i class="fas fa-inbox text-3xl text-gray-400"></i>
                            </div>
                            <p class="font-medium">Belum ada data alumni</p>
                            <p class="text-xs text-gray-400 mt-1">Silakan tambahkan data baru</p>
                        </div>
                    </td>
                </tr>`;
        }
    })
    .catch(err => {
        console.error('Error:', err);
        // Tampilan Jika Error Fetch
        document.getElementById('tableBody').innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-red-500 bg-red-50 border border-red-100 m-4 rounded">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-exclamation-triangle text-2xl mb-2"></i> 
                        <span class="font-semibold">Gagal memuat data</span>
                        <span class="text-xs text-red-400 mt-1">Cek koneksi atau hubungi admin</span>
                    </div>
                </td>
            </tr>`;
    });
}

// Fungsi Navigasi ke Halaman Edit
function btnEditClick(id, event) {
    if(event) event.stopPropagation();
    // Sesuaikan path routing 'edit' di sini
    navigate('admin/alumni/edit/' + id); 
}

// Fungsi Hapus Data
function hapusAlumni(id, event) {
    if(event) event.stopPropagation();
    
    if(confirm('Apakah Anda yakin ingin menghapus data alumni ini?')) {
        fetch(API_URL + '/alumni/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success' || data.code === 200) {
                // Notifikasi sukses (bisa diganti SweetAlert jika ada)
                alert('✓ Data berhasil dihapus');
                loadAlumni(); // Reload tabel otomatis
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