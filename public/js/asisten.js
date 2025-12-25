/**
 * ASISTEN.JS (FIXED)
 * Fitur: Pencarian Real-time untuk Koordinator & Asisten
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Ambil elemen input pencarian
    const searchInput = document.getElementById('searchAsisten');
    
    // 2. Ambil SEMUA kartu (Koordinator & Asisten sama-sama pakai class .card-link)
    const cards = document.querySelectorAll('.card-link');

    // Cek apakah elemen ada untuk menghindari error di halaman lain
    if (searchInput) {
        
        // Gunakan event 'input' agar langsung bereaksi saat ketik/paste/hapus
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase().trim();

            cards.forEach(card => {
                // Ambil elemen penting di dalam kartu
                const nameEl = card.querySelector('.staff-name');
                const roleEl = card.querySelector('.staff-role');
                const footerEl = card.querySelector('.staff-footer'); // Tambahan: Agar bisa cari jurusan juga

                // Gabungkan semua teks agar pencarian mencakup Nama, Jabatan, & Jurusan
                let fullText = '';
                if (nameEl) fullText += nameEl.textContent.toLowerCase();
                if (roleEl) fullText += ' ' + roleEl.textContent.toLowerCase();
                if (footerEl) fullText += ' ' + footerEl.textContent.toLowerCase();

                // LOGIKA TAMPIL/SEMBUNYI
                if (fullText.includes(term)) {
                    card.style.display = ''; // Reset ke default (tampil)
                } else {
                    card.style.display = 'none'; // Sembunyikan element
                }
            });
        });
    }
});