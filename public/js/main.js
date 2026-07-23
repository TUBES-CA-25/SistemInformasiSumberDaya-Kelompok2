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
      if (window.innerWidth <= 992) {
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

  // ============================================
  // GLOBAL DAY / NIGHT THEME & STARFIELD ENGINE
  // ============================================
  const themeToggle = document.getElementById("themeToggle");
  const bgDay = document.getElementById("bgDay");
  const bgNight = document.getElementById("bgNight");
  let nightImageAvailable = true;

  if (bgNight && bgDay) {
    let bgUrl = window.getComputedStyle(bgNight).backgroundImage;
    if (bgUrl && bgUrl !== "none") {
      bgUrl = bgUrl.replace(/^url\(['"]?([^'"]+)['"]?\)$/, '$1');
      const testImg = new Image();
      testImg.src = bgUrl;
      testImg.onerror = function () {
        nightImageAvailable = false;
        bgNight.style.display = "none";
        if (document.body.classList.contains("night-mode")) {
          bgDay.style.opacity = "1";
          bgDay.classList.add("night-mode-filter");
        }
      };
    }
  }

  let starCanvas = null;
  let starCtx = null;
  let starAnimationId = null;
  let stars = [];
  let shootingStars = [];
  let mousePos = { x: 0, y: 0, targetX: 0, targetY: 0 };
  let lastShootingStarTime = 0;
  let isStarfieldActive = false;

  const initNightStarfield = () => {
    if (!starCanvas) {
      starCanvas = document.createElement("canvas");
      starCanvas.id = "nightStarCanvas";
      document.body.appendChild(starCanvas);
      starCtx = starCanvas.getContext("2d");

      window.addEventListener("resize", resizeStarfield);
      window.addEventListener("mousemove", (e) => {
        mousePos.targetX = (e.clientX - window.innerWidth / 2) * 0.03;
        mousePos.targetY = (e.clientY - window.innerHeight / 2) * 0.03;
      });

      document.addEventListener("visibilitychange", () => {
        if (document.hidden) {
          stopStarfield();
        } else if (document.body.classList.contains("night-mode")) {
          startStarfield();
        }
      });
    }

    resizeStarfield();
    generateStars();
    startStarfield();
  };

  const resizeStarfield = () => {
    if (!starCanvas) return;
    const dpr = Math.min(window.devicePixelRatio || 1, 2);
    starCanvas.width = window.innerWidth * dpr;
    starCanvas.height = window.innerHeight * dpr;
    starCanvas.style.width = `${window.innerWidth}px`;
    starCanvas.style.height = `${window.innerHeight}px`;
    if (starCtx) {
      starCtx.scale(dpr, dpr);
    }
  };

  const generateStars = () => {
    stars = [];
    // Subtle density: ~50-80 stars max for clean aesthetics
    const count = Math.min(75, Math.max(35, Math.floor((window.innerWidth * window.innerHeight) / 18000)));
    const colors = [
      "rgba(255, 255, 255, ",
      "rgba(186, 230, 253, ", // Soft Cyan
      "rgba(254, 240, 138, ", // Soft Gold
      "rgba(233, 213, 255, "  // Soft Lavender
    ];

    for (let i = 0; i < count; i++) {
      // 90% micro stars, 10% slightly larger featured stars
      const radius = Math.random() < 0.90 ? Math.random() * 0.5 + 0.4 : Math.random() * 0.6 + 0.9;
      const isSparkle = radius > 1.1 && Math.random() < 0.25;
      const colorBase = colors[Math.floor(Math.random() * (Math.random() < 0.8 ? 1 : colors.length))];
      
      const baseAlpha = Math.random() * 0.4 + 0.15;
      stars.push({
        x: Math.random() * window.innerWidth,
        y: Math.random() * window.innerHeight,
        radius: radius,
        depth: Math.random() * 0.5 + 0.2, // Delicate parallax
        colorBase: colorBase,
        alpha: baseAlpha,
        baseAlpha: baseAlpha,
        twinkleSpeed: Math.random() * 0.012 + 0.003,
        twinkleDir: Math.random() < 0.5 ? 1 : -1,
        isSparkle: isSparkle,
        angle: Math.random() * Math.PI * 2
      });
    }
  };

  const spawnShootingStar = () => {
    const startX = Math.random() * (window.innerWidth * 1.2) - window.innerWidth * 0.1;
    const startY = Math.random() * (window.innerHeight * 0.4);
    const length = Math.random() * 120 + 80;
    const angle = Math.PI / 4 + (Math.random() * 0.2 - 0.1); // ~45 deg
    const speed = Math.random() * 10 + 12;

    shootingStars.push({
      x: startX,
      y: startY,
      dx: Math.cos(angle) * speed,
      dy: Math.sin(angle) * speed,
      length: length,
      alpha: 1,
      decay: Math.random() * 0.015 + 0.015,
      width: Math.random() * 1.5 + 1.2,
      color: Math.random() < 0.6 ? "#ffffff" : "#93c5fd"
    });
  };

  const drawCrossSparkle = (ctx, x, y, radius, alpha, colorBase, angle) => {
    ctx.save();
    ctx.translate(x, y);
    ctx.rotate(angle);
    ctx.strokeStyle = colorBase + alpha + ")";
    ctx.lineWidth = 0.8;

    const armLength = radius * 3.5;
    ctx.beginPath();
    ctx.moveTo(-armLength, 0);
    ctx.lineTo(armLength, 0);
    ctx.moveTo(0, -armLength);
    ctx.lineTo(0, armLength);
    ctx.stroke();

    ctx.restore();
  };

  const renderStarfield = (timestamp) => {
    if (!isStarfieldActive || !starCtx) return;

    const width = window.innerWidth;
    const height = window.innerHeight;

    starCtx.clearRect(0, 0, width, height);

    // Smooth Mouse Parallax Interpolation
    mousePos.x += (mousePos.targetX - mousePos.x) * 0.05;
    mousePos.y += (mousePos.targetY - mousePos.y) * 0.05;

    // Render Twinkling Stars
    for (let i = 0; i < stars.length; i++) {
      const s = stars[i];

      // Twinkle alpha updates
      s.alpha += s.twinkleSpeed * s.twinkleDir;
      if (s.alpha >= 0.95) {
        s.alpha = 0.95;
        s.twinkleDir = -1;
      } else if (s.alpha <= s.baseAlpha * 0.4) {
        s.alpha = s.baseAlpha * 0.4;
        s.twinkleDir = 1;
      }

      // Parallax position adjustment
      const px = s.x + mousePos.x * s.depth;
      const py = s.y + mousePos.y * s.depth;

      // Draw star core
      starCtx.beginPath();
      starCtx.arc(px, py, s.radius, 0, Math.PI * 2);
      starCtx.fillStyle = s.colorBase + s.alpha + ")";
      starCtx.fill();

      // Soft glow halo for larger stars
      if (s.radius > 1.3) {
        starCtx.beginPath();
        starCtx.arc(px, py, s.radius * 2.2, 0, Math.PI * 2);
        starCtx.fillStyle = s.colorBase + (s.alpha * 0.25) + ")";
        starCtx.fill();
      }

      // 4-pointed cross sparkle flare
      if (s.isSparkle) {
        s.angle += 0.008;
        drawCrossSparkle(starCtx, px, py, s.radius, s.alpha * 0.8, s.colorBase, s.angle);
      }
    }

    // Periodically spawn shooting stars (every 6-12s for elegance)
    if (timestamp - lastShootingStarTime > Math.random() * 6000 + 6000) {
      spawnShootingStar();
      lastShootingStarTime = timestamp;
    }

    // Render Shooting Stars (Meteors)
    for (let i = shootingStars.length - 1; i >= 0; i--) {
      const st = shootingStars[i];
      st.x += st.dx;
      st.y += st.dy;
      st.alpha -= st.decay;

      if (st.alpha <= 0 || st.x > width + 100 || st.y > height + 100) {
        shootingStars.splice(i, 1);
        continue;
      }

      const tailX = st.x - (st.dx / Math.hypot(st.dx, st.dy)) * st.length;
      const tailY = st.y - (st.dy / Math.hypot(st.dx, st.dy)) * st.length;

      const gradient = starCtx.createLinearGradient(st.x, st.y, tailX, tailY);
      gradient.addColorStop(0, st.color);
      gradient.addColorStop(0.3, "rgba(147, 197, 253, " + (st.alpha * 0.8) + ")");
      gradient.addColorStop(1, "rgba(147, 197, 253, 0)");

      starCtx.beginPath();
      starCtx.moveTo(st.x, st.y);
      starCtx.lineTo(tailX, tailY);
      starCtx.strokeStyle = gradient;
      starCtx.lineWidth = st.width;
      starCtx.lineCap = "round";
      starCtx.stroke();

      // Glowing meteor head
      starCtx.beginPath();
      starCtx.arc(st.x, st.y, st.width * 1.4, 0, Math.PI * 2);
      starCtx.fillStyle = `rgba(255, 255, 255, ${st.alpha})`;
      starCtx.fill();
    }

    starAnimationId = requestAnimationFrame(renderStarfield);
  };

  const startStarfield = () => {
    if (!isStarfieldActive) {
      isStarfieldActive = true;
      if (starAnimationId) cancelAnimationFrame(starAnimationId);
      starAnimationId = requestAnimationFrame(renderStarfield);
    }
  };

  const stopStarfield = () => {
    isStarfieldActive = false;
    if (starAnimationId) {
      cancelAnimationFrame(starAnimationId);
      starAnimationId = null;
    }
    if (starCtx && starCanvas) {
      starCtx.clearRect(0, 0, starCanvas.width, starCanvas.height);
    }
  };

  const setDayMode = () => {
    document.body.classList.remove("night-mode");
    document.documentElement.classList.remove("night-mode");
    localStorage.setItem("theme", "day");
    document.cookie = "theme=day; path=/; max-age=31536000";
    stopStarfield();
    if (bgDay) {
      bgDay.classList.remove("night-mode-filter");
      bgDay.style.opacity = "1";
    }
    if (bgNight && nightImageAvailable) {
      bgNight.style.opacity = "0";
    }
  };

  const setNightMode = () => {
    document.body.classList.add("night-mode");
    document.documentElement.classList.add("night-mode");
    localStorage.setItem("theme", "night");
    document.cookie = "theme=night; path=/; max-age=31536000";
    initNightStarfield();
    if (bgNight && nightImageAvailable) {
      bgNight.style.opacity = "1";
      bgNight.classList.remove("night-mode-filter");
      if (bgDay) {
        bgDay.style.opacity = "0.08";
        bgDay.classList.remove("night-mode-filter");
      }
    } else if (bgDay) {
      bgDay.style.opacity = "1";
      bgDay.classList.add("night-mode-filter");
    }
  };

  // Toggle Action Click (Support desktop & mobile buttons)
  const themeBtns = document.querySelectorAll(".theme-toggle-btn");
  themeBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      if (document.body.classList.contains("night-mode")) {
        setDayMode();
      } else {
        setNightMode();
      }
    });
  });

  // Load Saved Preference
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme === "night" || document.documentElement.classList.contains("night-mode")) {
    setNightMode();
  } else if (savedTheme === "day") {
    setDayMode();
  }
});

