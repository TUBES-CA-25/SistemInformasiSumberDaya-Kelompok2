/**
 * HOME.JS - Performance-Optimized
 * Uses IntersectionObserver instead of scroll events for reveal animations.
 * Marks html.js-loaded to enable CSS reveal animations (progressive enhancement).
 */

// Mark JS as loaded — enables CSS reveal animations
document.documentElement.classList.add('js-loaded');

// =========================================
// 1. REVEAL ANIMATION (IntersectionObserver)
// =========================================
(function initReveal() {
  var reveals = document.querySelectorAll(".reveal");
  if (!reveals.length) return;

  // Use IntersectionObserver for better performance (no scroll event thrashing)
  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add("active");
          observer.unobserve(entry.target); // Only animate once
        }
      });
    }, {
      rootMargin: '0px 0px -80px 0px', // Trigger slightly before fully in view
      threshold: 0.1
    });

    reveals.forEach(function(el) {
      observer.observe(el);
    });
  } else {
    // Fallback: make all visible immediately
    reveals.forEach(function(el) {
      el.classList.add("active");
    });
  }
})();

// =========================================
// 2. SLIDER FUNCTIONALITY
// =========================================
var currentSlide = 0;
var track = document.getElementById("sliderTrack");
var dots = document.querySelectorAll(".dot");
var totalSlides = dots.length;

var autoSlideTimer = null;

function startAutoSlide() {
  stopAutoSlide();
  autoSlideTimer = setInterval(function() {
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
    track.style.transform = "translateX(-" + (currentSlide * 100) + "%)";

    dots.forEach(function(dot) { dot.classList.remove("active"); });
    if (dots[currentSlide]) {
      dots[currentSlide].classList.add("active");
    }
  }
}

function moveSlide(direction, isUserAction) {
  if (isUserAction === undefined) isUserAction = true;
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

// Touch Swipe & Card Click Navigation
if (track) {
  var touchStartX = 0;
  var touchEndX = 0;

  // Click card to navigate (Right side -> next, Left side -> prev)
  var slideWrappers = track.querySelectorAll(".slide-content-wrapper");
  slideWrappers.forEach(function(card) {
    card.addEventListener("click", function(e) {
      // Don't trigger slide change if user clicked a link or button directly
      if (e.target.closest("a, button")) {
        return;
      }

      var rect = card.getBoundingClientRect();
      var clickX = e.clientX - rect.left;

      if (clickX > rect.width / 2) {
        moveSlide(-1, true); // Clicked right side -> slide to the right
      } else {
        moveSlide(1, true); // Clicked left side -> slide to the left
      }
    });
  });

  track.addEventListener("touchstart", function(e) {
    touchStartX = e.changedTouches[0].screenX;
    stopAutoSlide();
  }, { passive: true });

  track.addEventListener("touchend", function(e) {
    touchEndX = e.changedTouches[0].screenX;
    var swipeThreshold = 40;
    if (touchEndX < touchStartX - swipeThreshold) {
      moveSlide(1, true);
    } else if (touchEndX > touchStartX + swipeThreshold) {
      moveSlide(-1, true);
    } else {
      startAutoSlide();
    }
  }, { passive: true });
}

// Start initial auto slide
startAutoSlide();

// =========================================
// 3. COUNTER ANIMATION (IntersectionObserver)
// =========================================
var counters = document.querySelectorAll(".stat-number");
var speed = 200;

var animateCounters = function() {
  counters.forEach(function(counter) {
    var updateCount = function() {
      var target = +counter.getAttribute("data-target");
      var count = +counter.innerText;
      var inc = target / speed;

      if (count < target) {
        counter.innerText = Math.ceil(count + inc);
        setTimeout(updateCount, 20);
      } else {
        counter.innerText = target + "+";
      }
    };
    updateCount();
  });
};

var statsSection = document.querySelector(".stats-section");
if (statsSection) {
  var statsObserver = new IntersectionObserver(function(entries) {
    if (entries[0].isIntersecting) {
      animateCounters();
      statsObserver.unobserve(statsSection);
    }
  });
  statsObserver.observe(statsSection);
}
