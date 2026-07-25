/**
 * PRAKTIKUM SHARED SCRIPT
 * File: public/assets/js/praktikum.js
 * Digunakan untuk: Jadwal, Jadwal UPK, Format Penulisan
 */

// ==========================================================================
// 1. UTILITIES (BISA DIPAKAI DI SEMUA HALAMAN)
// ==========================================================================

const hariIndo = [
  "Minggu",
  "Senin",
  "Selasa",
  "Rabu",
  "Kamis",
  "Jumat",
  "Sabtu",
];

// Kita taruh bulanIndo di Global juga untuk keamanan
const bulanIndo = [
  "Januari",
  "Februari",
  "Maret",
  "April",
  "Mei",
  "Juni",
  "Juli",
  "Agustus",
  "September",
  "Oktober",
  "November",
  "Desember",
];

/**
 * Fungsi Jam Digital Global
 */
function startClock() {
  const clockElement = document.getElementById("live-clock");
  if (clockElement) {
    // Update pertama kali langsung
    updateTime(clockElement);

    // Update setiap detik
    setInterval(() => {
      updateTime(clockElement);
    }, 1000);
  }
}

function updateTime(element) {
  const now = new Date();
  const timeString = now
    .toLocaleTimeString("id-ID", { hour12: false })
    .replace(/\./g, ":");
  element.innerText = timeString;
}

// ==========================================================================
// 2. LOGIKA HALAMAN: JADWAL PRAKTIKUM (REGULER)
// ==========================================================================

let jadwalData = [];

function initJadwalPage() {
  const now = new Date();
  const hariIni = hariIndo[now.getDay()];
  const dropdown = document.getElementById("day-select");

  if (dropdown) {
    dropdown.value = hariIni !== "Minggu" ? hariIni : "Senin";
  }

  fetchJadwalData();
}

function fetchJadwalData() {
  if (typeof API_JADWAL_URL === "undefined") return;

  fetch(API_JADWAL_URL)
    .then((res) => res.json())
    .then((res) => {
      jadwalData = res.data;
      populateLabFilter();
      renderJadwalDashboard();
    })
    .catch((err) => {
      console.error(err);
      const container = document.getElementById("lab-tables-container");
      if (container) {
        container.innerHTML = `
            <div class="empty-schedule" style="border-color:#fca5a5;">
                <i class="fas fa-exclamation-circle" style="color:#ef4444;"></i>
                <h3>Gagal Memuat Data</h3>
                <p>Terjadi kesalahan saat menghubungi server API.</p>
            </div>`;
      }
    });
}

function populateLabFilter() {
  const labSelect = document.getElementById("lab-select");
  if (!labSelect) return;
  
  const currentVal = labSelect.value;
  labSelect.innerHTML = '<option value="">Semua Lab</option>';
  
  if (Array.isArray(jadwalData)) {
    const allLabs = [...new Set(jadwalData.map(item => item.namaLab))].filter(Boolean).sort();
    allLabs.forEach(lab => {
      const opt = document.createElement("option");
      opt.value = lab;
      opt.innerText = lab;
      if (lab === currentVal) opt.selected = true;
      labSelect.appendChild(opt);
    });
  }
}

function matchesProdiFilter(targetProdi, targetMatkul, targetFrekuensi, filterVal) {
  if (!filterVal || filterVal === "") return true;
  
  const val = filterVal.toLowerCase().trim();
  const prodi = (targetProdi || "").toLowerCase().trim();
  const freq = (targetFrekuensi || "").toLowerCase().trim();

  if (val === "ti") {
    return freq.startsWith("ti") || 
           prodi === "ti" || 
           prodi.includes("informatika") || 
           prodi.includes("teknik informatika");
  } 
  
  if (val === "si") {
    return freq.startsWith("si") || 
           prodi === "si" || 
           prodi.includes("sistem informasi");
  }

  return prodi.includes(val) || freq.includes(val);
}

function renderJadwalDashboard() {
  const container = document.getElementById("lab-tables-container");
  const headerDay = document.getElementById("header-day");
  const dropdown = document.getElementById("day-select");

  if (!container || !headerDay || !dropdown) return;

  const selectedDay = dropdown.value;
  headerDay.innerText = "Jadwal Hari " + selectedDay;

  const filteredData = jadwalData.filter((item) => item.hari === selectedDay);

  const labSelect = document.getElementById("lab-select");
  const prodiSelect = document.getElementById("prodi-select");
  const searchInput = document.getElementById("search-input");
  
  let finalFiltered = filteredData;
  if (labSelect && labSelect.value) {
    finalFiltered = finalFiltered.filter(item => item.namaLab === labSelect.value);
  }
  if (prodiSelect && prodiSelect.value) {
    finalFiltered = finalFiltered.filter(item => matchesProdiFilter(item.prodi, item.namaMatakuliah, item.frekuensi, prodiSelect.value));
  }
  if (searchInput && searchInput.value.trim() !== "") {
    const kw = searchInput.value.toLowerCase().trim();
    finalFiltered = finalFiltered.filter(item => {
      const fullText = (
        (item.namaMatakuliah || "") + " " +
        (item.dosen || "") + " " +
        (item.namaAsisten1 || "") + " " +
        (item.namaAsisten2 || "") + " " +
        (item.frekuensi || "") + " " +
        "kelas " + (item.kelas || "") + " " +
        (item.kelas || "") + " " +
        (item.kodeMatakuliah || "") + " " +
        (item.namaLab || "") + " " +
        (item.prodi || "")
      ).toLowerCase();
      return fullText.includes(kw);
    });
  }

  const now = new Date();
  const realToday = hariIndo[now.getDay()];
  const isToday = selectedDay === realToday;
  const jamSekarang =
    now.getHours().toString().padStart(2, "0") +
    ":" +
    now.getMinutes().toString().padStart(2, "0");

  if (finalFiltered.length === 0) {
    let emptyIcon = "far fa-calendar-times";
    let emptyTitle = "Tidak Ada Jadwal Praktikum";
    let emptyMessage = `Tidak ada jadwal praktikum yang ditemukan.`;

    if (!jadwalData || jadwalData.length === 0) {
      emptyIcon = "far fa-calendar-alt";
      emptyTitle = "Belum Ada Jadwal Praktikum";
      emptyMessage = "Saat ini belum ada data jadwal praktikum yang terdaftar di dalam sistem.";
    } else if (searchInput && searchInput.value.trim() !== "") {
      emptyIcon = "fas fa-search";
      emptyTitle = "Pencarian Tidak Ditemukan";
      emptyMessage = `Tidak ditemukan jadwal praktikum yang cocok dengan kata kunci "${searchInput.value.trim()}".`;
    } else if (labSelect && labSelect.value !== "") {
      emptyIcon = "fas fa-desktop";
      emptyTitle = "Ruangan Kosong";
      emptyMessage = `Tidak ada jadwal praktikum di ${labSelect.value} untuk hari ${selectedDay}.`;
    } else if (filteredData.length === 0) {
      emptyIcon = "far fa-calendar-check";
      emptyTitle = `Tidak Ada Jadwal Hari ${selectedDay}`;
      emptyMessage = `Tidak ada kegiatan praktikum yang berlangsung pada hari ${selectedDay}. Silakan pilih hari lainnya pada menu filter di atas.`;
    }

    container.innerHTML = `
        <div class="empty-schedule">
            <i class="${emptyIcon}"></i>
            <h3>${emptyTitle}</h3>
            <p>${emptyMessage}</p>
        </div>`;
    return;
  }

  const labs = [...new Set(finalFiltered.map((item) => item.namaLab))].sort();
  let finalHtml = "";

  labs.forEach((lab) => {
    const jadwalLab = finalFiltered.filter((item) => item.namaLab === lab);
    jadwalLab.sort((a, b) => a.waktuMulai.localeCompare(b.waktuMulai));

    finalHtml += `
    <div class="schedule-wrapper" style="margin-bottom: 60px;">
        <div class="lab-header">
            <div class="lab-icon"><i class="fas fa-desktop"></i></div>
            <h2 class="lab-title">${lab}</h2>
        </div>
        <div class="table-responsive">
            <table class="table-schedule">
                <thead>
                    <tr>
                        <th class="text-nowrap">Waktu</th>
                        <th>Mata Kuliah</th>
                        <th class="text-nowrap">Kls/Freq</th>
                        <th>Dosen</th>
                        <th>Asisten</th>
                        <th class="text-center text-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody>`;

    jadwalLab.forEach((item) => {
      const start = item.waktuMulai.substring(0, 5);
      const end = item.waktuSelesai.substring(0, 5);

      let statusBadge = "status-label badge-scheduled";
      let statusText = "TERJADWAL";

      if (isToday) {
        if (jamSekarang >= start && jamSekarang < end) {
          statusText = "BERLANGSUNG";
          statusBadge = "status-label badge-ongoing";
        } else if (jamSekarang < start) {
          statusText = "AKAN DATANG";
          statusBadge = "status-label badge-upcoming";
        } else {
          statusText = "SELESAI";
          statusBadge = "status-label badge-finished";
        }
      }

      const kelasFreq = `<b>${item.kelas || "-"}</b> <span style="color:#94a3b8">/</span> ${item.frekuensi || "-"}`;
      const cleanName = (val) => (!val || val === "-" || /^\d+$/.test(String(val).trim())) ? "" : String(val).trim();
      const a1Name = cleanName(item.namaAsisten1);
      const a2Name = cleanName(item.namaAsisten2);

      const asistenDisplay =
        (a1Name || a2Name)
          ? `<div class="asisten-cell">
               ${a1Name ? `<div class="asisten-name"><i class="fas fa-user-check" style="color:#2563eb; font-size:0.8rem; flex-shrink:0;"></i> <span>${a1Name}</span></div>` : ""}
               ${a2Name ? `<div class="asisten-name"><i class="fas fa-user-check" style="color:#2563eb; font-size:0.8rem; flex-shrink:0;"></i> <span>${a2Name}</span></div>` : ""}
             </div>`
          : '<span style="color:#cbd5e1">-</span>';

      let prodiText = item.prodi;
      if (!prodiText && item.frekuensi) {
        const fUpper = String(item.frekuensi).toUpperCase().trim();
        if (fUpper.startsWith("TI")) prodiText = "TI";
        else if (fUpper.startsWith("SI")) prodiText = "SI";
      }
      const prodiDisplay = prodiText ? `<span class="badge-prodi">${prodiText}</span>` : "";

      finalHtml += `
            <tr>
                <td class="text-nowrap time-cell" style="font-family:'JetBrains Mono', monospace; font-size:0.88rem; font-weight:700;">
                    <span class="time-range">${start} - ${end}</span>
                    <span class="mobile-status-badge ${statusBadge}">${statusText}</span>
                </td>
                <td>
                    <span class="schedule-matkul">
                        ${item.namaMatakuliah}
                        ${prodiDisplay}
                    </span>
                </td>
                <td class="text-nowrap">${kelasFreq}</td>
                <td><div class="dosen-cell"><i class="fas fa-chalkboard-teacher"></i><span>${item.dosen || "-"}</span></div></td>
                <td>${asistenDisplay}</td>
                <td class="desktop-status-cell" style="text-align:center;"><span class="${statusBadge}">${statusText}</span></td>
            </tr>`;
    });
    finalHtml += `</tbody></table></div></div>`;
  });
  container.innerHTML = finalHtml;
}

// ==========================================================================
// 3. LOGIKA HALAMAN: JADWAL UPK (DIPERBAIKI)
// ==========================================================================

function initUpkPage() {
  const upkHeader = document.getElementById("upk-header-day");

  // Debugging: Cek apakah elemen ditemukan
  if (!upkHeader) {
    console.error("Elemen 'upk-header-day' tidak ditemukan!");
    return;
  }

  function updateUpkHeader() {
    const now = new Date();

    // Kita definisikan ulang array bulan lokal untuk memastikan ketersediaan
    const namaBulan = [
      "Januari",
      "Februari",
      "Maret",
      "April",
      "Mei",
      "Juni",
      "Juli",
      "Agustus",
      "September",
      "Oktober",
      "November",
      "Desember",
    ];

    const namaHari = [
      "Minggu",
      "Senin",
      "Selasa",
      "Rabu",
      "Kamis",
      "Jumat",
      "Sabtu",
    ];

    const hari = namaHari[now.getDay()];
    const tanggal = now.getDate();
    const bulan = namaBulan[now.getMonth()];
    const tahun = now.getFullYear();

    upkHeader.innerText = `Jadwal ${hari}, ${tanggal} ${bulan} ${tahun}`;
  }

  // Jalankan segera
  updateUpkHeader();

  // Update per detik
  setInterval(updateUpkHeader, 1000);
}

function filterJadwalUpk() {
  const labSelect = document.getElementById("upk-lab-select");
  const prodiSelect = document.getElementById("upk-prodi-select");
  const searchInput = document.getElementById("upk-search-input");
  
  const labVal = labSelect ? labSelect.value.toLowerCase() : "";
  const prodiVal = prodiSelect ? prodiSelect.value.toLowerCase() : "";
      const searchVal = searchInput ? searchInput.value.toLowerCase().trim() : "";
      
      const wrappers = document.querySelectorAll(".schedule-wrapper");
      let anyVisibleWrapper = false;
      
      wrappers.forEach(wrapper => {
        const rows = wrapper.querySelectorAll("tbody tr");
        let visibleRowsCount = 0;
        
        rows.forEach(row => {
          const rowText = (row.textContent || "").toLowerCase();
          const rowRuangan = (row.dataset.ruangan || "").toLowerCase();
          const rowMatkul = (row.dataset.matkul || "").toLowerCase();
          const rowDosen = (row.dataset.dosen || "").toLowerCase();
          const rowProdi = (row.dataset.prodi || "").toLowerCase();
          const rowFreq = (row.dataset.frekuensi || "").toLowerCase();
          const rowKelas = (row.dataset.kelas || "").toLowerCase();
          
          const matchesLab = !labVal || rowRuangan === labVal;
          const matchesProdi = matchesProdiFilter(rowProdi, rowMatkul, rowFreq, prodiVal);
          const matchesSearch = !searchVal || 
                                rowText.includes(searchVal) ||
                                rowMatkul.includes(searchVal) || 
                                rowDosen.includes(searchVal) ||
                                rowProdi.includes(searchVal) ||
                                rowFreq.includes(searchVal) ||
                                rowRuangan.includes(searchVal) ||
                                rowKelas.includes(searchVal);
      
      if (matchesLab && matchesProdi && matchesSearch) {
        row.style.removeProperty("display");
        visibleRowsCount++;
      } else {
        row.style.setProperty("display", "none", "important");
      }
    });
    
    if (visibleRowsCount > 0) {
      wrapper.style.removeProperty("display");
      anyVisibleWrapper = true;
    } else {
      wrapper.style.setProperty("display", "none", "important");
    }
  });
  
  const emptyMsg = document.getElementById("upk-empty-message");
  if (emptyMsg) {
    if (!anyVisibleWrapper) {
      emptyMsg.style.removeProperty("display");
    } else {
      emptyMsg.style.setProperty("display", "none", "important");
    }
  }
}

// ==========================================================================
// 3.5 LOGIKA HALAMAN: MODUL PRAKTIKUM (FILTER PRODI & SEARCH)
// ==========================================================================

function filterModulPage() {
  const prodiSelect = document.getElementById("modul-prodi-select");
  const searchInput = document.getElementById("modul-search-input");

  const prodiVal = prodiSelect ? prodiSelect.value.toLowerCase().trim() : "";
  const searchVal = searchInput ? searchInput.value.toLowerCase().trim() : "";

  const cards = document.querySelectorAll(".modul-card");
  let anyVisibleCard = false;

  cards.forEach(card => {
    const cardProdi = (card.dataset.prodi || "").toLowerCase().trim();
    const matchesProdi = !prodiVal || cardProdi === prodiVal;

    if (!matchesProdi) {
      card.style.setProperty("display", "none", "important");
      return;
    }

    const rows = card.querySelectorAll("tbody tr");
    let visibleRowsCount = 0;

    rows.forEach(row => {
      if (row.querySelector(".empty-state")) return;

      const rowText = (row.textContent || "").toLowerCase();
      const matchesSearch = !searchVal || rowText.includes(searchVal);

      if (matchesSearch) {
        row.style.removeProperty("display");
        visibleRowsCount++;
      } else {
        row.style.setProperty("display", "none", "important");
      }
    });

    if (visibleRowsCount > 0 || (rows.length === 1 && rows[0].querySelector(".empty-state"))) {
      card.style.removeProperty("display");
      anyVisibleCard = true;
    } else {
      card.style.setProperty("display", "none", "important");
    }
  });

  const emptyMsg = document.getElementById("modul-empty-message");
  if (emptyMsg) {
    if (!anyVisibleCard) {
      emptyMsg.style.removeProperty("display");
    } else {
      emptyMsg.style.setProperty("display", "none", "important");
    }
  }
}

// ==========================================================================
// 4. LOGIKA HALAMAN: FORMAT PENULISAN
// ==========================================================================

function initFormatPenulisanPage() {
  const pedomanContainer = document.getElementById("pedoman-container");
    // Gunakan API_URL jika tersedia; jika tidak, fallback ke BASE_URL + /api.php
    const apiUrl = (window.API_URL || ((window.BASE_URL || "") + "/api.php")) + "/api/formatpenulisan";
  async function loadFormatContent() {
    try {
      const response = await fetch(apiUrl);
      const result = await response.json();

      if (result.status === "success" || result.code === 200) {
        renderFormatContent(result.data);
      } else {
        showFormatEmptyState();
      }
    } catch (error) {
      console.error("API Error:", error);
      showFormatErrorState();
    }
  }

  function renderFormatContent(data) {
    const unduhanContainer = document.getElementById("unduhan-container");
    const unduhanSection = document.getElementById("unduhan-section");

    // RENDER PEDOMAN
    const pedoman = data.filter(
      (item) => (item.kategori || "pedoman").toLowerCase() === "pedoman",
    );
    if (pedoman.length > 0) {
      pedomanContainer.innerHTML = pedoman
        .map(
          (info) => `
        <article class="rule-card">
            <h3>${info.judul}</h3>
            <ul class="rule-list">
                ${(info.deskripsi || "")
                  .split("\n")
                  .filter((l) => l.trim())
                  .map(
                    (l) =>
                      `<li><i class="ri-checkbox-circle-fill" style="color: #2563eb;"></i> <span>${l.trim()}</span></li>`,
                  )
                  .join("")}
            </ul>
        </article>`,
        )
        .join("");
    } else {
      pedomanContainer.innerHTML = `<div style="grid-column: 1/-1; text-align: center; color: #64748b;">Belum ada data pedoman.</div>`;
    }

    // RENDER UNDUHAN
    const unduhan = data.filter(
      (item) => (item.kategori || "").toLowerCase() === "unduhan",
    );
    if (unduhan.length > 0 && unduhanSection) {
      unduhanSection.style.display = "block";
      unduhanContainer.innerHTML = unduhan
        .map((item) => {
          const fileName = item.file ? item.file.trim() : "";
          const downloadPath = `assets/uploads/format_penulisan/${fileName}`;

          let fileIcon = "ri-file-text-line";
          if (fileName.endsWith(".pdf")) fileIcon = "ri-file-pdf-line";
          if (fileName.endsWith(".doc") || fileName.endsWith(".docx"))
            fileIcon = "ri-file-word-line";
          if (fileName.endsWith(".zip") || fileName.endsWith(".rar"))
            fileIcon = "ri-file-zip-line";

          return `
            <div class="download-card">
                <div class="file-icon-box"><i class="${fileIcon}"></i></div>
                <div class="download-content">
                    <h4>${item.judul}</h4>
                    <div class="file-meta"><i class="ri-information-line"></i> Dokumen Resmi ICLabs</div>
                    <div class="action-buttons">
                        ${item.file ? `<a href="${downloadPath}" target="_blank" download="${fileName}" class="btn-download"><i class="ri-download-cloud-2-fill"></i> Unduh</a>` : ""}
                        ${item.link_external ? `<a href="${item.link_external}" target="_blank" class="btn-external"><i class="ri-external-link-line"></i> Link Drive</a>` : ""}
                    </div>
                </div>
            </div>`;
        })
        .join("");
    }
  }

  function showFormatEmptyState() {
    pedomanContainer.innerHTML = `<div style="grid-column: 1/-1; text-align:center; padding:40px;"><p>Data tidak ditemukan.</p></div>`;
  }
  function showFormatErrorState() {
    pedomanContainer.innerHTML = `<div style="grid-column: 1/-1; text-align:center; padding:40px; color:#ef4444;"><p>Gagal memuat data dari server.</p></div>`;
  }

  loadFormatContent();
}

// ==========================================================================
// 5. MAIN EXECUTION (DOCUMENT READY)
// ==========================================================================

document.addEventListener("DOMContentLoaded", () => {
  // A. Jalankan Jam Digital (Semua Halaman)
  startClock();

  // B. Halaman: JADWAL REGULER
  if (
    document.getElementById("lab-tables-container") &&
    document.getElementById("day-select")
  ) {
    initJadwalPage();
    setInterval(fetchJadwalData, 60000);
    setInterval(() => {
      const dropdown = document.getElementById("day-select");
      const now = new Date();
      if (dropdown && dropdown.value === hariIndo[now.getDay()]) {
        renderJadwalDashboard();
      }
    }, 60000);
  }

  // C. Halaman: JADWAL UPK
  if (document.getElementById("upk-header-day")) {
    // console.log("Halaman UPK terdeteksi!"); // Uncomment untuk debug
    initUpkPage();
  }

  // D. Halaman: FORMAT PENULISAN
  if (document.getElementById("pedoman-container")) {
    initFormatPenulisanPage();
  }
});
