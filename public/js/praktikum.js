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

function renderJadwalDashboard() {
  const container = document.getElementById("lab-tables-container");
  const headerDay = document.getElementById("header-day");
  const dropdown = document.getElementById("day-select");

  if (!container || !headerDay || !dropdown) return;

  const selectedDay = dropdown.value;
  headerDay.innerText = "Jadwal Hari " + selectedDay;

  const filteredData = jadwalData.filter((item) => item.hari === selectedDay);

  const now = new Date();
  const realToday = hariIndo[now.getDay()];
  const isToday = selectedDay === realToday;
  const jamSekarang =
    now.getHours().toString().padStart(2, "0") +
    ":" +
    now.getMinutes().toString().padStart(2, "0");

  if (filteredData.length === 0) {
    container.innerHTML = `
        <div class="empty-schedule">
            <i class="far fa-calendar-times"></i>
            <h3>Tidak Ada Praktikum</h3>
            <p>Tidak ada jadwal praktikum terdaftar untuk hari ${selectedDay}.</p>
        </div>`;
    return;
  }

  const labs = [...new Set(filteredData.map((item) => item.namaLab))].sort();
  let finalHtml = "";

  labs.forEach((lab) => {
    const jadwalLab = filteredData.filter((item) => item.namaLab === lab);
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
                        <th width="25%">Mata Kuliah</th>
                        <th class="text-nowrap">Kls/Freq</th>
                        <th width="20%">Dosen</th>
                        <th width="20%">Asisten</th>
                        <th width="15%" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>`;

    jadwalLab.forEach((item) => {
      const start = item.waktuMulai.substring(0, 5);
      const end = item.waktuSelesai.substring(0, 5);

      // --- [PERBAIKAN STATUS] ---
      let statusBadge = "status-label badge-scheduled"; // Default abu-abu
      let statusText = "TERJADWAL";
      let rowStyle = ""; // Style tambahan untuk baris tabel

      // 1. Cek Status Database Dulu (Nonaktif / Dibatalkan)
      // Pastikan API mengirim field 'status'. Jika tidak ada, anggap 'Aktif'.
      const statusDb = item.status ? item.status.toLowerCase() : "aktif";

      if (
        statusDb === "nonaktif" ||
        statusDb === "0" ||
        statusDb === "dibatalkan"
      ) {
        statusText = "DIBATALKAN";
        statusBadge = "status-label badge-canceled"; // Perlu CSS baru untuk warna merah
        rowStyle = "opacity: 0.6; background-color: #fef2f2;"; // Efek transparan & agak merah
      }
      // 2. Jika Aktif, baru cek jam
      else if (isToday) {
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
      const asistenDisplay =
        item.namaAsisten1 || item.namaAsisten2
          ? `<div class="asisten-cell">
               ${item.namaAsisten1 ? `<div class="asisten-name"><i class="fas fa-user-check" style="color:#2563eb; font-size:0.8rem; margin-right:5px;"></i> ${item.namaAsisten1}</div>` : ""}
               ${item.namaAsisten2 ? `<div class="asisten-name"><i class="fas fa-user-check" style="color:#2563eb; font-size:0.8rem; margin-right:5px;"></i> ${item.namaAsisten2}</div>` : ""}
             </div>`
          : '<span style="color:#cbd5e1">-</span>';

      finalHtml += `
            <tr style="${rowStyle}">
                <td class="text-nowrap" style="font-family:'JetBrains Mono', monospace; font-size:0.95rem;">${start} - ${end}</td>
                <td style="color: #0f172a; font-weight: 700;">${item.namaMatakuliah}</td>
                <td class="text-nowrap">${kelasFreq}</td>
                <td><div style="display:flex; align-items:center; gap:8px;"><i class="fas fa-chalkboard-teacher" style="color:#64748b;"></i><span style="font-weight:500;">${item.dosen}</span></div></td>
                <td>${asistenDisplay}</td>
                <td style="text-align:center;">
                    ${
                      statusText === "DIBATALKAN"
                        ? `<span style="background-color:#ef4444; color:white; padding:4px 8px; border-radius:4px; font-size:0.75rem; font-weight:bold;">${statusText}</span>`
                        : `<span class="${statusBadge}">${statusText}</span>`
                    }
                </td>
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

// ==========================================================================
// 4. LOGIKA HALAMAN: FORMAT PENULISAN
// ==========================================================================

function initFormatPenulisanPage() {
  const pedomanContainer = document.getElementById("pedoman-container");
  const apiUrl = (window.BASE_URL || "") + "/api.php/formatpenulisan";

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
            <div class="rule-icon icon-blue"><i class="${info.icon || "ri-book-open-line"}"></i></div>
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
