/**
 * MAIN.JS
 * Logika Javascript Global (Navbar, Footer, & Utility)
 * Dipanggil di footer.php agar aktif di semua halaman.
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // =========================================
    // 1. MOBILE MENU TOGGLE (Navbar)
    // =========================================
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    
    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', function() {
            // Toggle visibilitas menu (Muncul/Hilang)
            navLinks.classList.toggle('active');
            
            // [PENTING] Toggle animasi ikon (Burger <-> Silang)
            menuToggle.classList.toggle('active');
        });
    }

    // =========================================
    // 2. BACK TO TOP BUTTON (Footer)
    // =========================================
    const backToTopButton = document.getElementById("backToTop");
    
    if (backToTopButton) {
        // Event saat di-scroll
        window.addEventListener("scroll", () => {
            // Jika scroll lebih dari 200px ke bawah, munculkan tombol
            if (window.scrollY > 200) {
                backToTopButton.classList.add("show");
            } else {
                backToTopButton.classList.remove("show");
            }
        });

        // Event saat tombol diklik
        backToTopButton.addEventListener("click", (e) => {
            e.preventDefault(); // Mencegah loncat default hash #
            window.scrollTo({
                top: 0,
                behavior: "smooth" // Animasi scroll halus ke atas
            });
        });
    }
});