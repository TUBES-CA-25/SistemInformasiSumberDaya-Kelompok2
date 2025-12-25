/**
 * KEPALA.JS - FIXED VERSION
 * Mendukung pencarian untuk Staff Card (.staff-role) dan Exec Card (.exec-role)
 */

document.addEventListener('DOMContentLoaded', function() {
    
    const searchInput = document.getElementById('searchStaff');
    const cards = document.querySelectorAll('.card-link'); // Target pembungkus kartu

    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            const term = e.target.value.toLowerCase();

            cards.forEach(card => {
                // 1. Ambil Nama (Classnya sama: .staff-name)
                const nameEl = card.querySelector('.staff-name');
                
                // 2. Ambil Jabatan (Cek apakah dia Staff atau Exec/Kepala)
                // Kita gunakan querySelector dengan koma untuk "OR" logic
                const roleEl = card.querySelector('.staff-role, .exec-role');

                if (nameEl && roleEl) {
                    const name = nameEl.textContent.toLowerCase();
                    const role = roleEl.textContent.toLowerCase();

                    // Filter Logic
                    if (name.includes(term) || role.includes(term)) {
                        card.style.display = ''; // Tampilkan
                        
                        // Fix layout Flexbox jika parentnya flex (opsional, untuk keamanan)
                        card.style.opacity = '1'; 
                    } else {
                        card.style.display = 'none'; // Sembunyikan
                        card.style.opacity = '0';
                    }
                }
            });
        });
    }
});