/**
 * ASISTEN.JS (OPTIMIZED)
 * Fitur:
 * 1. Debounce pencarian (300ms) untuk mengurangi DOM updates
 * 2. Event Delegation untuk detail link
 * 3. Passive event listeners untuk scroll performance
 */

document.addEventListener("DOMContentLoaded", function () {
  // ============================================
  // 1. DEBOUNCE untuk PENCARIAN (300ms)
  // ============================================
  const debounce = (func, delay) => {
    let timeoutId;
    return (...args) => {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => func(...args), delay);
    };
  };

  const FILTER_TRANSITION_MS = 220;

  const setCardVisibility = (card, visible) => {
    if (visible) {
      if (card.style.display === "none") {
        card.style.display = "";
        // Force reflow so the fade-in transition actually plays.
        void card.offsetWidth;
      }
      card.classList.remove("is-filtered");
    } else if (!card.classList.contains("is-filtered")) {
      card.classList.add("is-filtered");
      window.setTimeout(() => {
        if (card.classList.contains("is-filtered")) {
          card.style.display = "none";
        }
      }, FILTER_TRANSITION_MS);
    }
  };

  const toggleEmptyState = (show) => {
    const emptyEl = document.getElementById("searchEmptyState");
    if (!emptyEl) return;

    if (show) {
      emptyEl.style.display = "block";
      requestAnimationFrame(() => emptyEl.classList.add("show"));
    } else {
      emptyEl.classList.remove("show");
      window.setTimeout(() => {
        if (!emptyEl.classList.contains("show")) {
          emptyEl.style.display = "none";
        }
      }, FILTER_TRANSITION_MS);
    }
  };

  const performSearch = (term) => {
    const cards = document.querySelectorAll(".card-link");
    let visibleCount = 0;

    cards.forEach((card) => {
      const nameEl = card.querySelector(".staff-name");
      const roleEl = card.querySelector(".overlay-role, .staff-role");
      const metaEl = card.querySelector(".staff-content");

      let fullText = "";
      if (nameEl) fullText += nameEl.textContent.toLowerCase();
      if (roleEl) fullText += " " + roleEl.textContent.toLowerCase();
      if (metaEl) fullText += " " + metaEl.textContent.toLowerCase();

      const matches = fullText.includes(term);
      if (matches) visibleCount++;
      setCardVisibility(card, matches);
    });

    toggleEmptyState(term !== "" && visibleCount === 0);
  };

  const debouncedSearch = debounce(performSearch, 300);

  // ============================================
  // 2. SEARCH INPUT dengan DEBOUNCE
  // ============================================
  const searchInput = document.getElementById("searchAsisten");

  if (searchInput) {
    // Gunakan event 'input' dengan debounce
    searchInput.addEventListener(
      "input",
      function (e) {
        const term = e.target.value.toLowerCase().trim();
        debouncedSearch(term);
      },
      { passive: true },
    ); // Passive listener untuk scroll performance
  }

  // ============================================
  // 3. EVENT DELEGATION untuk DETAIL LINKS (MODAL)
  // ============================================
  const modal = document.getElementById("profileModal");
  const modalLoading = document.getElementById("modalLoading");
  const modalError = document.getElementById("modalError");
  const modalBody = document.getElementById("modalBody");

  const modalImg = document.getElementById("modalImg");
  const modalCategory = document.getElementById("modalCategory");
  const modalName = document.getElementById("modalName");
  const modalRole = document.getElementById("modalRole");
  const modalSubInfo = document.querySelector("#modalSubInfo span");
  const modalSubIcon = document.querySelector("#modalSubInfo i");
  const modalEmailBox = document.getElementById("modalEmailBox");
  const modalEmail = document.getElementById("modalEmail");
  const modalBio = document.getElementById("modalBio");
  const modalSkillsSection = document.getElementById("modalSkillsSection");
  const modalSkillsContainer = document.getElementById("modalSkillsContainer");
  const modalMailBtn = document.getElementById("modalMailBtn");
  const modalMailDisabled = document.getElementById("modalMailDisabled");

  const openModal = () => {
    if (!modal) return;
    modal.classList.add("active");
    document.body.style.overflow = "hidden"; // Prevent scrolling behind
  };

  const closeModal = () => {
    if (!modal) return;
    modal.classList.remove("active");
    document.body.style.overflow = ""; // Restore scrolling
  };

  const closeBtn = document.getElementById("closeProfileModal");
  if (closeBtn) {
    closeBtn.addEventListener("click", closeModal);
  }

  if (modal) {
    // Close modal on click outside container
    modal.addEventListener("click", function (e) {
      if (e.target === modal) {
        closeModal();
      }
    });

    // Close modal on ESC key
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && modal.classList.contains("active")) {
        closeModal();
      }
    });
  }

  document.addEventListener(
    "click",
    function (e) {
      const detailLink = e.target.closest(".asisten-detail-link");
      if (detailLink) {
        e.preventDefault();
        
        const id = detailLink.getAttribute("data-id");
        let type = detailLink.getAttribute("data-type") || "asisten";

        // Alumni and asisten are both fetched from the asisten table API
        if (type === "alumni") {
          type = "asisten";
        }

        if (id) {
          // Reset modal state
          modalLoading.style.display = "flex";
          modalError.style.display = "none";
          modalBody.style.display = "none";
          openModal();

          const base = window.PUBLIC_URL || '';
          const apiUrl = `${base}/api/sumberdaya/detail/${id}?type=${type}`;

          fetch(apiUrl)
            .then(res => {
              if (!res.ok) throw new Error("Gagal mengambil data");
              return res.json();
            })
            .then(res => {
              if (!res.status || !res.data) throw new Error(res.message || "Data kosong");
              const d = res.data;

              // Populate Modal Content
              modalImg.src = d.foto_url;
              modalImg.alt = d.nama;
              modalImg.style.objectPosition = `${d.foto_pos_x}% ${d.foto_pos_y}%`;
              
              modalCategory.textContent = d.kategori;
              // Apply category badge styling class
              modalCategory.className = `category-badge ${d.badge_style}`;
              
              modalName.textContent = d.nama;
              modalRole.textContent = d.jabatan;
              modalSubInfo.textContent = d.sub_info;
              
              if (modalSubIcon) {
                modalSubIcon.className = d.sub_icon || 'ri-graduation-cap-line';
              }

              // Email
              if (d.email && d.email !== "-") {
                modalEmail.textContent = d.email;
                modalEmailBox.style.display = "flex";
                modalMailBtn.href = `mailto:${d.email}`;
                modalMailBtn.style.display = "inline-flex";
                modalMailDisabled.style.display = "none";
              } else {
                modalEmailBox.style.display = "none";
                modalMailBtn.style.display = "none";
                modalMailDisabled.style.display = "inline-flex";
              }

              // Bio
              modalBio.innerHTML = d.bio.replace(/\n/g, "<br>");

              // Skills
              modalSkillsContainer.innerHTML = "";
              if (d.skills && d.skills.length > 0) {
                d.skills.forEach(skill => {
                  const tag = document.createElement("span");
                  tag.className = "skill-tag";
                  tag.textContent = skill;
                  modalSkillsContainer.appendChild(tag);
                });
                modalSkillsSection.style.display = "block";
              } else {
                modalSkillsSection.style.display = "none";
              }

              // Show content
              modalLoading.style.display = "none";
              modalBody.style.display = "block";
            })
            .catch(err => {
              console.error(err);
              modalLoading.style.display = "none";
              modalError.style.display = "flex";
            });
        }
      }
    },
    { passive: false } // Crucial to prevent standard link redirects
  );

  // ============================================
  // 4. SCROLL-REVEAL dengan STAGGER (Intersection Observer)
  // ============================================
  const prefersReducedMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)",
  ).matches;

  const revealTargets = document.querySelectorAll(
    ".exec-card, .staff-grid .card-link, .section-label",
  );

  if (revealTargets.length && !prefersReducedMotion && "IntersectionObserver" in window) {
    // Beri delay stagger per elemen berdasarkan urutannya di dalam parent yang sama
    // (mis. tiap kartu dalam satu staff-grid), lalu ulang polanya tiap 8 agar
    // delay tidak menumpuk terlalu lama untuk grid yang panjang.
    const siblingIndex = new WeakMap();
    revealTargets.forEach((el) => {
      const parent = el.parentElement;
      const idx = siblingIndex.get(parent) || 0;
      siblingIndex.set(parent, idx + 1);
      el.classList.add("reveal-card");
      el.style.setProperty("--i", idx % 8);
    });

    const revealObserver = new IntersectionObserver(
      (entries, observer) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("in-view");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: "0px 0px -40px 0px" },
    );

    revealTargets.forEach((el) => revealObserver.observe(el));
  }

  // ============================================
  // 5. SPOTLIGHT KURSOR pada STAFF CARD
  // ============================================
  const supportsHover = window.matchMedia("(hover: hover) and (pointer: fine)").matches;

  if (supportsHover && !prefersReducedMotion) {
    document.addEventListener(
      "pointermove",
      (e) => {
        const card = e.target.closest(".staff-card");
        if (!card) return;
        const rect = card.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        card.style.setProperty("--spot-x", `${x}%`);
        card.style.setProperty("--spot-y", `${y}%`);
      },
      { passive: true },
    );
  }
});
