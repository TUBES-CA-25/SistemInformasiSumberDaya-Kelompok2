// Variable untuk melacak slide saat ini
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');
const track = document.getElementById('sliderTrack');

// Fungsi utama untuk pindah slide
function goToSlide(index) {
    // 1. Hapus class 'active' dari semua dot
    dots.forEach(dot => dot.classList.remove('active'));
    
    // 2. Tambahkan class 'active' ke dot yang diklik
    dots[index].classList.add('active');
    
    // 3. Geser Track (Logika Utama)
    // Jika index 0, geser 0%. Jika index 1, geser -100%, dst.
    const amountToMove = -index * 100;
    track.style.transform = `translateX(${amountToMove}%)`;
    
    // Update variable
    currentSlide = index;
}

// (Opsional) Auto Slide setiap 5 detik
setInterval(() => {
    let nextSlide = currentSlide + 1;
    if (nextSlide >= slides.length) {
        nextSlide = 0; // Kembali ke awal
    }
    goToSlide(nextSlide);
}, 5000);

