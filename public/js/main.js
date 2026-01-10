/**
 * MAIN.JS
 * Logika Javascript Global (Navbar, Footer, & Utility)
 * Dipanggil di footer.php agar aktif di semua halaman.
 */

document.addEventListener("DOMContentLoaded", function () {
  // =========================================
  // 1. MOBILE MENU TOGGLE (Navbar)
  // =========================================
  const menuToggle = document.querySelector(".menu-toggle");
  const navLinks = document.querySelector(".nav-links");

  if (menuToggle && navLinks) {
    menuToggle.addEventListener("click", function () {
      // Toggle visibilitas menu (Muncul/Hilang)
      navLinks.classList.toggle("active");

      // Toggle animasi ikon (Burger <-> Silang)
      menuToggle.classList.toggle("active");
    });
  }

  // =========================================
  // 2. BACK TO TOP BUTTON (Footer)
  // =========================================
  const backToTopButton = document.getElementById("backToTop");

  if (backToTopButton) {
    // Event saat di-scroll
    window.addEventListener("scroll", () => {
      // Jika scroll lebih dari 300px ke bawah, tambahkan class .show
      // Kita TIDAK mengubah style.display agar animasi CSS opacity berjalan
      if (window.scrollY > 300) {
        backToTopButton.classList.add("show");
      } else {
        backToTopButton.classList.remove("show");
      }
    });

    // Event saat tombol diklik (Smooth Scroll)
    backToTopButton.addEventListener("click", (e) => {
      e.preventDefault(); // Mencegah loncat default hash #
      window.scrollTo({
        top: 0,
        behavior: "smooth", // Animasi scroll halus ke atas
      });
    });
  }

  // =========================================
  // 3. KEYBOARD SHORTCUT (Ctrl + Shift + L + O)
  // =========================================
  const keysPressed = {};
  
  document.addEventListener('keydown', function(e) {
    keysPressed[e.code] = true;
    
    // Debug (Bisa dihapus jika sudah lancar)
    // console.log('Keys:', e.code, 'Ctrl:', e.ctrlKey, 'Shift:', e.shiftKey);

    // Cek jika Ctrl + Shift + L + ; ditekan bersamaan
    // Menggunakan e.code agar tidak terpengaruh CapsLock/layout
    if (e.ctrlKey && e.shiftKey && keysPressed['KeyL'] && keysPressed['Semicolon']) {
        e.preventDefault(); // Mencegah shortcut bawaan browser
        
        console.log('Shortcut detected! Redirecting to login...');
        window.location.href = 'pintuSISDA'; 
    }
  });

  document.addEventListener('keyup', function(e) {
    keysPressed[e.code] = false;
  });

  // =========================================
  // 4. HIDDEN CLICK TRIGGER (Click Footer Logo IMAGE Only 5x)
  // =========================================
  const footerLogoImg = document.querySelector(".footer-logo img");
  if (footerLogoImg) {
    let clickCount = 0;
    let lastClickTime = 0;

    footerLogoImg.addEventListener("click", function(e) {
      // Pastikan event tidak diteruskan ke parent
      e.stopPropagation();
      
      const currentTime = new Date().getTime();
      
      if (currentTime - lastClickTime < 800) {
        clickCount++;
      } else {
        clickCount = 1;
      }
      lastClickTime = currentTime;

      if (clickCount === 5) {
        clickCount = 0;
        window.location.href = 'pintuSISDA';
      }
    }, true); // Use capture phase for extra precision
    
    footerLogoImg.style.cursor = "default";
  }
});
