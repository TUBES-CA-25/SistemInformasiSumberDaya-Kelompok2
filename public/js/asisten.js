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

  const performSearch = (term) => {
    const cards = document.querySelectorAll(".card-link");

    cards.forEach((card) => {
      const nameEl = card.querySelector(".staff-name");
      const roleEl = card.querySelector(".staff-role");
      const footerEl = card.querySelector(".staff-footer");

      let fullText = "";
      if (nameEl) fullText += nameEl.textContent.toLowerCase();
      if (roleEl) fullText += " " + roleEl.textContent.toLowerCase();
      if (footerEl) fullText += " " + footerEl.textContent.toLowerCase();

      card.style.display = fullText.includes(term) ? "" : "none";
    });
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
  // 3. EVENT DELEGATION untuk DETAIL LINKS
  // ============================================
  document.addEventListener(
    "click",
    function (e) {
      const detailLink = e.target.closest(".asisten-detail-link");
      if (detailLink) {
        e.preventDefault();
        const id = detailLink.getAttribute("data-id");
        const type = detailLink.getAttribute("data-type");

        if (id && type) {
          window.location.href = `index.php?page=detail&id=${id}&type=${type}`;
        }
      }
    },
    { passive: true },
  );
});
