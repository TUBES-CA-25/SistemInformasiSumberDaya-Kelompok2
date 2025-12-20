/**
 * ALUMNI.JS
 * Fitur pencarian real-time untuk halaman alumni
 */

document.addEventListener('DOMContentLoaded', function() {
    
    const searchInput = document.getElementById('searchAlumni');
    const cards = document.querySelectorAll('.card-link');
    const yearGroups = document.querySelectorAll('.alumni-group');

    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            const term = e.target.value.toLowerCase();

            cards.forEach(card => {
                const nameEl = card.querySelector('.staff-name');
                const roleEl = card.querySelector('.staff-role');
                const metaEls = card.querySelectorAll('.meta-item');
                
                let textContent = '';
                if (nameEl) textContent += nameEl.textContent.toLowerCase();
                if (roleEl) textContent += roleEl.textContent.toLowerCase();
                metaEls.forEach(meta => textContent += meta.textContent.toLowerCase());

                if (textContent.includes(term)) {
                    card.style.display = '';
                    card.classList.remove('hidden-by-search');
                } else {
                    card.style.display = 'none';
                    card.classList.add('hidden-by-search');
                }
            });

            // Opsional: Sembunyikan Header Tahun jika semua anak-anaknya tersembunyi
            yearGroups.forEach(group => {
                const allCards = group.querySelectorAll('.card-link');
                const hiddenCards = group.querySelectorAll('.card-link.hidden-by-search');
                
                if (allCards.length === hiddenCards.length) {
                    group.style.display = 'none';
                } else {
                    group.style.display = 'block';
                }
            });
        });
    }
});