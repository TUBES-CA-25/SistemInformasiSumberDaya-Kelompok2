/**
 * MAIN.JS
 * Logika Javascript Global (Navbar, Footer, & Utility)
 * Dipanggil di footer.php agar aktif di semua halaman.
 */

document.addEventListener("DOMContentLoaded", function () {
  // =========================================
  // 1. MOBILE MENU TOGGLE (Hamburger Button)
  // =========================================
  const menuToggle = document.querySelector(".menu-toggle");
  const navLinks = document.querySelector(".nav-links");

  if (menuToggle && navLinks) {
    menuToggle.addEventListener("click", function (e) {
      e.stopPropagation(); // Mencegah klik burger menutup dirinya sendiri

      // Toggle visibilitas menu utama
      navLinks.classList.toggle("active");
      // Toggle animasi ikon burger
      menuToggle.classList.toggle("active");
    });
  }

  // =========================================
  // 1.5. MOBILE DROPDOWN ACCORDION (NEW!)
  // =========================================
  // Mengambil semua link yang punya parent class .dropdown
  const dropdownToggles = document.querySelectorAll(".dropdown > a");

  dropdownToggles.forEach((toggle) => {
    toggle.addEventListener("click", function (e) {
      // Logika ini hanya berjalan di layar Mobile/Tablet (lebar < 992px)
      // Di Desktop, biarkan CSS :hover yang bekerja.
      if (window.innerWidth <= 1024) {
        e.preventDefault(); // Mencegah link pindah halaman
        e.stopPropagation(); // Mencegah event naik ke atas

        const currentContent = this.nextElementSibling; // Ambil submenu di bawahnya

        // A. ACCORDION: Tutup semua dropdown LAIN yang sedang terbuka
        document.querySelectorAll(".dropdown-content").forEach((content) => {
          if (content !== currentContent) {
            content.classList.remove("show");
          }
        });

        // B. Toggle (Buka/Tutup) dropdown yang sedang diklik
        if (currentContent) {
          currentContent.classList.toggle("show");
        }
      }
    });
  });

  // Global Click: Tutup semua menu jika klik di luar area Navbar
  document.addEventListener("click", function (e) {
    if (!e.target.closest(".navbar")) {
      // 1. Tutup Menu Utama Mobile
      if (navLinks && navLinks.classList.contains("active")) {
        navLinks.classList.remove("active");
        if (menuToggle) menuToggle.classList.remove("active");
      }

      // 2. Tutup Semua Dropdown
      document.querySelectorAll(".dropdown-content").forEach((content) => {
        content.classList.remove("show");
      });
    }
  });

  // =========================================
  // 2. BACK TO TOP BUTTON (Footer)
  // =========================================
  const backToTopButton = document.getElementById("backToTop");

  if (backToTopButton) {
    // Event saat di-scroll
    window.addEventListener("scroll", () => {
      if (window.scrollY > 300) {
        backToTopButton.classList.add("show");
      } else {
        backToTopButton.classList.remove("show");
      }
    });

    // Event saat tombol diklik (Smooth Scroll)
    backToTopButton.addEventListener("click", (e) => {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    });
  }

  // =========================================
  // 3. KEYBOARD SHORTCUT (Ctrl + Shift + L + ;)
  // =========================================
  const keysPressed = {};

  document.addEventListener("keydown", function (e) {
    keysPressed[e.code] = true;

    // Shortcut: Ctrl + Shift + L + ;
    if (
      e.ctrlKey &&
      e.shiftKey &&
      keysPressed["KeyL"] &&
      keysPressed["Semicolon"]
    ) {
      e.preventDefault();
      console.log("Shortcut detected! Redirecting to login...");
      // Ganti URL ini sesuai kebutuhan routing Anda
      window.location.href = "/iclabs-login";
    }
  });

  document.addEventListener("keyup", function (e) {
    keysPressed[e.code] = false;
  });

  // =========================================
  // 4. HIDDEN CLICK TRIGGER (Click Footer Logo 5x)
  // =========================================
  const footerLogoImg = document.querySelector(".footer-logo img");
  if (footerLogoImg) {
    let clickCount = 0;
    let lastClickTime = 0;

    footerLogoImg.addEventListener(
      "click",
      function (e) {
        e.stopPropagation();

        const currentTime = new Date().getTime();

        // Reset jika jarak antar klik lebih dari 800ms
        if (currentTime - lastClickTime < 800) {
          clickCount++;
        } else {
          clickCount = 1;
        }
        lastClickTime = currentTime;

        if (clickCount === 5) {
          clickCount = 0;
          window.location.href = "/iclabs-login";
        }
      },
      true,
    );

    footerLogoImg.style.cursor = "default";
  }
});
