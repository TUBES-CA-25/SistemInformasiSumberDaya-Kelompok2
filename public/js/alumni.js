/**
 * ALUMNI.JS
 * Fitur pencarian real-time untuk halaman alumni
 */

document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchAlumni");
  const cards = document.querySelectorAll(".card-link");
  const yearGroups = document.querySelectorAll(".alumni-group");

  if (searchInput) {
    searchInput.addEventListener("keyup", function (e) {
      const term = e.target.value.toLowerCase();

      cards.forEach((card) => {
        // 1. Ambil data dari dalam kartu (Nama, Role, Meta)
        const nameEl = card.querySelector(".staff-name");
        const roleEl = card.querySelector(".staff-role");
        const metaEls = card.querySelectorAll(".meta-item");

        // 2. Ambil data TAHUN dari Header Grup (Parent Element)
        // Ini logika tambahan dari script PHP sebelumnya
        const group = card.closest(".alumni-group");
        const yearLabel = group
          ? group.querySelector(".section-label span")
          : null;

        // 3. Gabungkan semua teks untuk pencarian
        let textContent = "";

        if (nameEl) textContent += nameEl.textContent.toLowerCase() + " ";
        if (roleEl) textContent += roleEl.textContent.toLowerCase() + " ";
        if (yearLabel) textContent += yearLabel.textContent.toLowerCase() + " "; // Tambahkan Tahun ke pencarian

        metaEls.forEach(
          (meta) => (textContent += meta.textContent.toLowerCase() + " "),
        );

        // 4. Cek Pencarian
        if (textContent.includes(term)) {
          card.style.display = "";
          card.classList.remove("hidden-by-search");
        } else {
          card.style.display = "none";
          card.classList.add("hidden-by-search");
        }
      });

      // 5. Sembunyikan Header Tahun jika semua alumni di dalamnya tersembunyi
      yearGroups.forEach((group) => {
        const allCards = group.querySelectorAll(".card-link");
        const hiddenCards = group.querySelectorAll(
          ".card-link.hidden-by-search",
        );

        // Jika jumlah semua kartu == jumlah kartu yang disembunyikan, maka sembunyikan grup
        if (allCards.length > 0 && allCards.length === hiddenCards.length) {
          group.style.display = "none";
        } else {
          group.style.display = "block";
        }
      });
    });
  }
});
