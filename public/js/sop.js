document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.querySelector(".search-input");
  const sopItems = document.querySelectorAll(".sop-item");
  const sopWrapper = document.querySelector(".sop-wrapper");

  // 1. Siapkan Pesan "Data Tidak Ditemukan"
  const noResultMsg = document.createElement("div");
  noResultMsg.innerHTML = `
        <div style="text-align:center; padding: 60px 20px; color: #64748b;">
            <i class="ri-file-search-line" style="font-size: 3rem; margin-bottom: 10px; display:block; color: #cbd5e1;"></i>
            <h3 style="color: #1e293b; margin-bottom: 5px;">Dokumen tidak ditemukan</h3>
            <p style="font-size:0.9rem;">Coba kata kunci lain atau periksa ejaan Anda.</p>
        </div>
    `;
  noResultMsg.style.display = "none";
  if (sopWrapper) sopWrapper.appendChild(noResultMsg);

  // 2. Logika Pencarian Real-time
  if (searchInput) {
    searchInput.addEventListener("input", function (e) {
      const keyword = e.target.value.toLowerCase().trim();
      let hasResults = false;

      sopItems.forEach(function (item) {
        const title = item.querySelector(".sop-title").innerText.toLowerCase();
        const desc = item.querySelector(".sop-desc").innerText.toLowerCase();

        if (title.includes(keyword) || desc.includes(keyword)) {
          item.style.display = "flex";
          hasResults = true;
        } else {
          item.style.display = "none";
        }
      });

      // 3. Kontrol Tampilan Header & Pesan Kosong
      const headerRow = document.querySelector(".sop-header-row");
      if (!hasResults && sopItems.length > 0) {
        noResultMsg.style.display = "block";
        if (headerRow) headerRow.style.display = "none";
      } else {
        noResultMsg.style.display = "none";
        if (headerRow) headerRow.style.display = "flex";
      }
    });
  }
});
