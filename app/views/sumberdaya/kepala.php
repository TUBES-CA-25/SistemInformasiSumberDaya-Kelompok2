<section class="resource-section">
    <div class="container">
        <div class="page-header">
            <h1>Kepala Laboratorium</h1>
            <p>Dosen dan tenaga ahli yang memimpin laboratorium FIKOM UMI.</p>
        </div>

        <div class="profile-grid" id="profileGrid">
            <p style="text-align: center; color: #999;">Loading...</p>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadKepalaLab();
});

function loadKepalaLab() {
    fetch(API_URL + '/manajemen')
    .then(response => response.json())
    .then(res => {
        const grid = document.getElementById('profileGrid');
        grid.innerHTML = '';

        if ((res.status === 'success' || res.code === 200) && res.data && res.data.length > 0) {
            res.data.forEach(item => {
                // Construct image URL - check if it's already full URL or just filename
                let imgSrc = item.foto;
                if (item.foto && !item.foto.includes('http')) {
                    imgSrc = '/SistemInformasiSumberDaya-Kelompok2/storage/uploads/' + item.foto;
                } else if (!item.foto) {
                    imgSrc = 'https://placehold.co/300x300/bdc3c7/white?text=' + encodeURIComponent(item.nama || 'Foto');
                }

                const card = document.createElement('a');
                card.href = '#';
                card.className = 'profile-card';
                card.innerHTML = `
                    <div class="profile-img">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Crect fill='%23f0f0f0' width='300' height='300'/%3E%3C/svg%3E" 
                             data-src="${imgSrc}" 
                             alt="${item.nama}"
                             loading="lazy"
                             style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    <div class="profile-info">
                        <h3>${item.nama || '—'}</h3>
                        <p>${item.jabatan || 'Kepala Laboratorium'}</p>
                    </div>
                `;
                grid.appendChild(card);
            });

            // Lazy load images with Intersection Observer
            lazyLoadImages();
        } else {
            grid.innerHTML = '<p style="text-align: center; color: #999;">Tidak ada data.</p>';
        }
    })
    .catch(error => {
        console.error('Error loading kepala lab:', error);
        document.getElementById('profileGrid').innerHTML = '<p style="text-align: center; color: red;">Gagal memuat data.</p>';
    });
}

function lazyLoadImages() {
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    images.forEach(img => imageObserver.observe(img));
}
</script>