/**
 * KEPALA.JS
 * Logika Pencarian untuk Halaman Struktur Pimpinan
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ID Input di kepala.php adalah 'searchStaff'
    const searchInput = document.getElementById('searchStaff');
    const cards = document.querySelectorAll('.card-link'); // Target pembungkus kartu

    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            const term = e.target.value.toLowerCase();

            cards.forEach(card => {
                const nameEl = card.querySelector('.staff-name');
                const roleEl = card.querySelector('.staff-role');

                if (nameEl && roleEl) {
                    const name = nameEl.textContent.toLowerCase();
                    const role = roleEl.textContent.toLowerCase();

                    // Filter berdasarkan Nama atau Jabatan
                    if (name.includes(term) || role.includes(term)) {
                        card.style.display = ''; // Reset display (muncul)
                    } else {
                        card.style.display = 'none'; // Sembunyikan
                    }
                }
            });
        });
    }
});