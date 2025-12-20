/**
 * ASISTEN.JS
 * Fokus: Fitur Interaktif (Pencarian Real-time)
 * Data sudah dimuat oleh PHP (Server Side Rendering), jadi tidak perlu Fetch API lagi.
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Ambil elemen input pencarian
    const searchInput = document.getElementById('searchAsisten');
    
    // Ambil semua elemen pembungkus kartu (sesuai asisten.php V2)
    // Kita menargetkan .card-link karena itu adalah pembungkus terluar
    const cards = document.querySelectorAll('.card-link');

    // Cek apakah input pencarian ada di halaman ini
    if (searchInput) {
        
        searchInput.addEventListener('keyup', function(e) {
            // Ambil teks yang diketik user & ubah ke huruf kecil
            const term = e.target.value.toLowerCase();

            cards.forEach(card => {
                // Cari elemen Nama dan Jabatan di dalam setiap kartu
                const nameEl = card.querySelector('.staff-name');
                const roleEl = card.querySelector('.staff-role');

                if (nameEl && roleEl) {
                    const name = nameEl.textContent.toLowerCase();
                    const role = roleEl.textContent.toLowerCase();

                    // LOGIKA FILTER:
                    // Jika Nama ATAU Jabatan mengandung teks pencarian...
                    if (name.includes(term) || role.includes(term)) {
                        card.style.display = ''; // Tampilkan (reset CSS display)
                    } else {
                        card.style.display = 'none'; // Sembunyikan
                    }
                }
            });
        });
    }
});