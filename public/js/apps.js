document.addEventListener("DOMContentLoaded", function () {
  const container = document.querySelector(".apps-grid");
  if (!container) return;

  const cards = document.querySelectorAll(".app-card");

  cards.forEach((card) => {
    const isMaintenance =
      card.classList.contains("maintenance") ||
      card.getAttribute("data-status") === "maintenance";

    if (isMaintenance) {
      card.classList.add("maintenance");
      card.removeAttribute("href");

      // Tambahkan badge jika belum ada
      if (!card.querySelector(".maintenance-badge")) {
        const badge = document.createElement("span");
        badge.className = "maintenance-badge";
        badge.innerText = "MAINTENANCE";
        card.appendChild(badge);
      }

      card.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
      });
    }
  });

  // Sort: Aktif di atas, Maintenance di bawah
  const sortedCards = Array.from(cards).sort((a, b) => {
    const isMaintA = a.classList.contains("maintenance");
    const isMaintB = b.classList.contains("maintenance");
    return isMaintA - isMaintB;
  });

  sortedCards.forEach((card) => {
    container.appendChild(card);
  });
});
