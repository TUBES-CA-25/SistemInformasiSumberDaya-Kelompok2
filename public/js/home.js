window.addEventListener("scroll", reveal);

function reveal() {
  var reveals = document.querySelectorAll(".reveal");
  for (var i = 0; i < reveals.length; i++) {
    var windowheight = window.innerHeight;
    var revealtop = reveals[i].getBoundingClientRect().top;
    var revealpoint = 100;
    if (revealtop < windowheight - revealpoint) {
      reveals[i].classList.add("active");
    }
  }
}
reveal();

let currentSlide = 0;
const track = document.getElementById("sliderTrack");
const dots = document.querySelectorAll(".dot");
const totalSlides = dots.length;

let autoSlideTimer = null;

function startAutoSlide() {
  stopAutoSlide();
  autoSlideTimer = setInterval(() => {
    moveSlide(1, false);
  }, 6000);
}

function stopAutoSlide() {
  if (autoSlideTimer) {
    clearInterval(autoSlideTimer);
    autoSlideTimer = null;
  }
}

function updateSlider() {
  if (track) {
    track.style.transform = `translateX(-${currentSlide * 100}%)`;

    dots.forEach((dot) => dot.classList.remove("active"));
    if (dots[currentSlide]) {
      dots[currentSlide].classList.add("active");
    }
  }
}

function moveSlide(direction, isUserAction = true) {
  currentSlide += direction;

  if (currentSlide < 0) {
    currentSlide = totalSlides - 1;
  } else if (currentSlide >= totalSlides) {
    currentSlide = 0;
  }
  updateSlider();
  if (isUserAction) {
    startAutoSlide();
  }
}

function goToSlide(index) {
  currentSlide = index;
  updateSlider();
  startAutoSlide();
}

// Touch Swipe gesture support for mobile touchscreen with Auto-Pause
if (track) {
  let touchStartX = 0;
  let touchEndX = 0;

  track.addEventListener("touchstart", (e) => {
    touchStartX = e.changedTouches[0].screenX;
    stopAutoSlide(); // Pause timer while user is touching screen
  }, { passive: true });

  track.addEventListener("touchend", (e) => {
    touchEndX = e.changedTouches[0].screenX;
    const swipeThreshold = 40;
    if (touchEndX < touchStartX - swipeThreshold) {
      moveSlide(1, true);
    } else if (touchEndX > touchStartX + swipeThreshold) {
      moveSlide(-1, true);
    } else {
      startAutoSlide(); // Resume timer if user just tapped without swiping
    }
  }, { passive: true });
}

// Start initial auto slide
startAutoSlide();

// Fungsi Counter Animation
const counters = document.querySelectorAll(".stat-number");
const speed = 200; // Semakin kecil semakin cepat

const animateCounters = () => {
  counters.forEach((counter) => {
    const updateCount = () => {
      const target = +counter.getAttribute("data-target");
      const count = +counter.innerText;

      // Hitung increment agar animasi halus
      const inc = target / speed;

      if (count < target) {
        counter.innerText = Math.ceil(count + inc);
        setTimeout(updateCount, 20); // Kecepatan refresh
      } else {
        counter.innerText = target + "+"; // Tambah tanda + di akhir
      }
    };
    updateCount();
  });
};

// Trigger animasi hanya saat section terlihat (Intersection Observer)
const statsSection = document.querySelector(".stats-section");
if (statsSection) {
  const observer = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting) {
      animateCounters();
      observer.unobserve(statsSection); // Hanya jalankan sekali
    }
  });
  observer.observe(statsSection);
}


