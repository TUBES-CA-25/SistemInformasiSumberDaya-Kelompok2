document.addEventListener("DOMContentLoaded", function () {
  // =================================================================
  // 1. KONFIGURASI: Masukkan link yang sedang maintenance di sini
  // =================================================================
  const maintenanceLinks = [
    "https://website-rusak.com",
    "https://fitur-belum-siap.id",
  ];

  // =================================================================
  // 2. LOGIKA PENANDAAN (MARKING)
  // =================================================================
  const container = document.querySelector(".apps-grid");
  const cards = document.querySelectorAll(".app-card");

  cards.forEach((card) => {
    // Ambil href dari kartu saat ini
    const cardLink = card.getAttribute("href");

    // Cek apakah link kartu ini ada di daftar maintenance?
    if (maintenanceLinks.includes(cardLink)) {
      // Tambahkan class maintenance
      card.classList.add("maintenance");

      // Matikan Link
      card.removeAttribute("href");

      // Tambahkan Badge "MAINTENANCE" jika belum ada
      if (!card.querySelector(".maintenance-badge")) {
        const badge = document.createElement("span");
        badge.className = "maintenance-badge";
        badge.innerText = "MAINTENANCE";
        card.appendChild(badge);
      }

      // Cegah klik
      card.addEventListener("click", function (e) {
        e.preventDefault();
      });
    }
  });

  // =================================================================
  // 3. LOGIKA PENGURUTAN OTOMATIS (SORTING) - NEW UPDATE 🚀
  // =================================================================

  // Ubah NodeList menjadi Array agar bisa disortir
  const sortedCards = Array.from(cards).sort((a, b) => {
    // Cek apakah kartu A dan B maintenance
    const isMaintA = a.classList.contains("maintenance");
    const isMaintB = b.classList.contains("maintenance");

    // Logic: False (Active) harus di atas (0), True (Maintenance) di bawah (1)
    return isMaintA - isMaintB;
  });

  // Masukkan ulang kartu yang sudah diurutkan ke dalam container
  // Ini akan memindahkan posisi elemen di HTML secara otomatis
  sortedCards.forEach((card) => {
    container.appendChild(card);
  });
});
