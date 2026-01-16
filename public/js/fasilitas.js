/**
 * SCRIPT SLIDER & INTERAKSI FASILITAS
 */

// Variabel Global untuk Slider
let currentSlide = 0;
let slideInterval;
let track, slides, dots;
let totalSlides = 0;

document.addEventListener('DOMContentLoaded', () => {
    // Inisialisasi Elemen
    track = document.getElementById('sliderTrack');
    slides = document.querySelectorAll('.slide-item');
    dots = document.querySelectorAll('.dot');
    
    if (!track || slides.length === 0) return;

    totalSlides = slides.length;

    // Jalankan Timer Otomatis
    startTimer();
});

// Fungsi Update Posisi Slider
function updateSlider() {
    if (!track) return;
    track.style.transform = `translateX(-${currentSlide * 100}%)`;

    // Update Dots
    if (dots.length > 0) {
        dots.forEach(dot => dot.classList.remove('active'));
        if (dots[currentSlide]) {
            dots[currentSlide].classList.add('active');
        }
    }
}

// Fungsi Navigasi (Next/Prev) - Wajib ada di window agar bisa dipanggil onclick=""
window.moveSlide = function(n) {
    if (totalSlides <= 1) return;
    currentSlide = (currentSlide + n + totalSlides) % totalSlides;
    updateSlider();
    resetTimer();
};

// Fungsi Navigasi (Dots)
window.goToSlide = function(n) {
    currentSlide = n;
    updateSlider();
    resetTimer();
};

// Timer Logic
function startTimer() {
    if (totalSlides > 1) {
        slideInterval = setInterval(() => {
            window.moveSlide(1);
        }, 4000); // Geser setiap 4 detik
    }
}

function resetTimer() {
    clearInterval(slideInterval);
    startTimer();
}

// ============================================
// OPTIMISASI: Event Delegation untuk Facility Links
// ============================================
document.addEventListener('click', function(e) {
    const facilityLink = e.target.closest('[data-link]');
    if (facilityLink) {
        e.preventDefault();
        const href = facilityLink.getAttribute('href');
        if (href) {
            window.location.href = href;
        }
    }
}, { passive: false }); // Non-passive karena ada preventDefault()

// ============================================
// OPTIMISASI: Passive listener untuk scroll performance
// ============================================
document.addEventListener('scroll', () => {}, { passive: true });
