/**
 * PRAKTIKUM SHARED SCRIPT - MULTI FILTER VERSION
 * File: public/assets/js/praktikum.js
 */

const hariIndo = [
  "Minggu",
  "Senin",
  "Selasa",
  "Rabu",
  "Kamis",
  "Jumat",
  "Sabtu",
];
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

/* ================= UTILS ================= */
function startClock() {
  const clockElement = document.getElementById("live-clock");
  if (clockElement) {
    updateTime(clockElement);
    setInterval(() => {
      updateTime(clockElement);
    }, 1000);
  }
}

function updateTime(element) {
  const now = new Date();
  element.innerText = now
    .toLocaleTimeString("id-ID", { hour12: false })
    .replace(/\./g, ":");
}

/* ================= JADWAL PAGE LOGIC (MODIFIED) ================= */
let globalJadwalData = [];
const activeFilters = {
  hari: new Set(),
  jam: new Set(),
  kelas: new Set(),
  matkul: new Set(),
  dosen: new Set(),
  asisten: new Set(),
  status: new Set(),
};

function initJadwalPage() {
  // 1. Fetch Data Menggunakan Cara Kode Lama
  if (typeof API_JADWAL_URL === "undefined") return;

  fetch(API_JADWAL_URL)
    .then((res) => res.json())
    .then((res) => {
      // Simpan data di variabel global
      globalJadwalData = res.data;

      // Set Default Filter ke Hari Ini (Jika data ada)
      const now = new Date();
      const hariIni = hariIndo[now.getDay()];
      if (hariIni !== "Minggu") {
        activeFilters.hari.add(hariIni);
      } else {
        activeFilters.hari.add("Senin");
      }

      // Inisialisasi Opsi Filter & Render Awal
      initFilterDropdowns();
      renderFilteredSchedule();
    })
    .catch((err) => {
      console.error(err);
      const container = document.getElementById("lab-tables-container");
      if (container) {
        container.innerHTML = `
                    <div class="empty-schedule" style="border-color:#fca5a5; text-align:center; padding:30px;">
                        <i class="fas fa-exclamation-circle" style="color:#ef4444; font-size:2rem;"></i>
                        <h3 style="margin-top:10px;">Gagal Memuat Data</h3>
                        <p>Terjadi kesalahan saat menghubungi server API.</p>
                    </div>`;
      }
    });

  // Event Listener untuk Tombol Reset & Dropdown UI
  setupFilterUI();
}

// === FUNGSI LOGIKA FILTER ===
function initFilterDropdowns() {
  // Definisi Mapping Data
  const configs = [
    { id: "hari", key: "hari" },
    {
      id: "jam",
      val: (r) =>
        `${r.waktuMulai.substring(0, 5)} - ${r.waktuSelesai.substring(0, 5)}`,
    },
    { id: "kelas", key: "kelas" },
    { id: "matkul", key: "namaMatakuliah" },
    { id: "dosen", key: "dosen" },
    { id: "asisten", val: (r) => [r.namaAsisten1, r.namaAsisten2] },
    { id: "status", val: (r) => r.status || "Aktif" },
  ];

  configs.forEach((cfg) => {
    const uniqueSet = new Set();
    globalJadwalData.forEach((row) => {
      let val;
      if (cfg.val) val = cfg.val(row);
      else val = row[cfg.key];

      if (Array.isArray(val)) {
        val.forEach((v) => {
          if (v && v !== "-") uniqueSet.add(v.trim());
        });
      } else if (val) {
        uniqueSet.add(val.toString().trim());
      }
    });

    // Sorting
    let items = Array.from(uniqueSet);
    if (cfg.id === "hari") {
      const order = {
        Senin: 1,
        Selasa: 2,
        Rabu: 3,
        Kamis: 4,
        Jumat: 5,
        Sabtu: 6,
      };
      items.sort((a, b) => (order[a] || 99) - (order[b] || 99));
    } else {
      items.sort();
    }

    renderDropdownOptions(cfg.id, items);
  });

  // Update UI tombol filter (terutama Hari yg default terpilih)
  Object.keys(activeFilters).forEach((key) => updateFilterButton(key));
}

function renderDropdownOptions(id, items) {
  const container = document.querySelector(`#dd-${id} .adv-drop-content`);
  if (!container) return;

  container.innerHTML = items
    .map((item) => {
      // Cek apakah item ini sedang aktif (misal Hari Ini)
      const isChecked = activeFilters[id].has(item) ? "checked" : "";
      return `
        <label class="filter-option">
            <input type="checkbox" value="${item}" data-cat="${id}" ${isChecked}>
            <span>${item}</span>
        </label>`;
    })
    .join("");

  // Event Listener Checkbox Change
  container.querySelectorAll("input").forEach((cb) => {
    cb.addEventListener("change", (e) => {
      const cat = e.target.dataset.cat;
      const val = e.target.value;
      if (e.target.checked) activeFilters[cat].add(val);
      else activeFilters[cat].delete(val);

      updateFilterButton(cat);
      renderFilteredSchedule(); // Re-render saat filter berubah
    });
  });
}

function updateFilterButton(id) {
  const btn = document.querySelector(`#dd-${id} .adv-drop-btn`);
  const count = activeFilters[id].size;
  const labelMap = {
    hari: "Hari",
    jam: "Jam",
    kelas: "Kelas",
    matkul: "Mata Kuliah",
    dosen: "Dosen",
    asisten: "Asisten",
    status: "Status",
  };

  if (count > 0) {
    btn.classList.add("active");
    btn.innerHTML = `${labelMap[id]} <span style="background:#2563eb; color:#fff; padding:1px 6px; border-radius:10px; font-size:0.7rem; margin-left:5px;">${count}</span> <i class="fas fa-filter" style="font-size:0.8rem;"></i>`;
  } else {
    btn.classList.remove("active");
    btn.innerHTML = `${labelMap[id]} <i class="fas fa-chevron-down"></i>`;
  }

  const hasAny = Object.values(activeFilters).some((s) => s.size > 0);
  const resetBtn = document.getElementById("btn-reset-filter");
  if (resetBtn) resetBtn.style.display = hasAny ? "inline-flex" : "none"; // Ubah display ke inline-flex agar rapi
}

function setupFilterUI() {
  // Toggle Dropdown
  document.querySelectorAll(".adv-drop-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      const content = btn.nextElementSibling;
      document.querySelectorAll(".adv-drop-content").forEach((d) => {
        if (d !== content) d.classList.remove("show");
      });
      content.classList.toggle("show");
    });
  });

  document.addEventListener("click", () => {
    document.querySelectorAll(".adv-drop-content").classList.remove("show");
  });

  document
    .querySelectorAll(".adv-drop-content")
    .forEach((d) => d.addEventListener("click", (e) => e.stopPropagation()));

  // Reset Button Logic (DIPERBAIKI)
  const resetBtn = document.getElementById("btn-reset-filter");
  if (resetBtn) {
    resetBtn.addEventListener("click", (e) => {
      // [PENTING] Mencegah klik tembus ke Header (Accordion)
      e.stopPropagation();

      // Logika Reset yang sudah ada
      Object.values(activeFilters).forEach((s) => s.clear());
      document
        .querySelectorAll('input[type="checkbox"]')
        .forEach((c) => (c.checked = false));

      // Update UI Button (Reset Teks Tombol)
      // Kita perlu definisikan ulang labelMap di sini atau ambil dari luar
      const labelMap = {
        hari: "Hari",
        jam: "Jam",
        kelas: "Kelas",
        matkul: "Mata Kuliah",
        dosen: "Dosen",
        asisten: "Asisten",
        status: "Status",
      };

      Object.keys(activeFilters).forEach((id) => {
        const btn = document.querySelector(`#dd-${id} .adv-drop-btn`);
        if (btn) {
          btn.classList.remove("active");
          btn.innerHTML = `${labelMap[id]} <i class="fas fa-chevron-down"></i>`;
        }
      });

      // Sembunyikan tombol reset sendiri
      resetBtn.style.display = "none";

      // Jalankan filter ulang (menampilkan semua data)
      renderFilteredSchedule();
    });
  }
}

// === FUNGSI RENDER (MIRIP KODE LAMA TAPI FILTERED) ===
function renderFilteredSchedule() {
  const container = document.getElementById("lab-tables-container");
  if (!container) return;

  // 1. Filter Data
  const filteredData = globalJadwalData.filter((row) => {
    // Cek Hari
    if (activeFilters.hari.size > 0 && !activeFilters.hari.has(row.hari))
      return false;
    // Cek Jam
    const jam = `${row.waktuMulai.substring(0, 5)} - ${row.waktuSelesai.substring(0, 5)}`;
    if (activeFilters.jam.size > 0 && !activeFilters.jam.has(jam)) return false;
    // Cek Kelas
    if (activeFilters.kelas.size > 0 && !activeFilters.kelas.has(row.kelas))
      return false;
    // Cek Matkul
    if (
      activeFilters.matkul.size > 0 &&
      !activeFilters.matkul.has(row.namaMatakuliah)
    )
      return false;
    // Cek Dosen
    if (activeFilters.dosen.size > 0 && !activeFilters.dosen.has(row.dosen))
      return false;
    // Cek Status
    const st = row.status || "Aktif";
    if (activeFilters.status.size > 0 && !activeFilters.status.has(st))
      return false;
    // Cek Asisten (Array logic)
    if (activeFilters.asisten.size > 0) {
      const rowAsisten = [row.namaAsisten1, row.namaAsisten2];
      const hasMatch = rowAsisten.some(
        (a) => a && activeFilters.asisten.has(a),
      );
      if (!hasMatch) return false;
    }
    return true;
  });

  // 2. Cek Jika Kosong
  if (filteredData.length === 0) {
    container.innerHTML = `
            <div class="empty-schedule" style="text-align:center; padding:40px; border: 2px dashed #e2e8f0; border-radius:12px;">
                <i class="far fa-calendar-times" style="font-size:3rem; color:#cbd5e1; margin-bottom:15px;"></i>
                <h3 style="color:#64748b;">Tidak Ada Jadwal</h3>
                <p style="color:#94a3b8;">Tidak ada data yang cocok dengan filter yang dipilih.</p>
            </div>`;
    return;
  }

  // 3. Render Group by Lab (Sama seperti kode lama)
  const labs = [...new Set(filteredData.map((item) => item.namaLab))].sort();
  let finalHtml = "";
  const now = new Date();
  const jamSekarang =
    now.getHours().toString().padStart(2, "0") +
    ":" +
    now.getMinutes().toString().padStart(2, "0");
  const hariIniReal = hariIndo[now.getDay()];

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
                            <th class="text-nowrap" width="5%">Hari</th>
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

      // Logic Status Badge (Dari kode lama)
      let statusBadge = "status-label badge-scheduled";
      let statusText = "TERJADWAL";
      let rowStyle = "";
      const statusDb = item.status ? item.status.toLowerCase() : "aktif";

      if (
        statusDb === "nonaktif" ||
        statusDb === "0" ||
        statusDb === "dibatalkan"
      ) {
        statusText = "DIBATALKAN";
        statusBadge = "status-label badge-canceled";
        rowStyle = "opacity: 0.6; background-color: #fef2f2;";
      } else if (item.hari === hariIniReal) {
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
                    <td style="font-weight:600; font-size:0.9rem;">${item.hari}</td>
                    <td class="text-nowrap" style="font-family:'JetBrains Mono', monospace; font-size:0.95rem;">${start} - ${end}</td>
                    <td style="color: #0f172a; font-weight: 700;">${item.namaMatakuliah}</td>
                    <td class="text-nowrap">${kelasFreq}</td>
                    <td><div style="display:flex; align-items:center; gap:8px;"><i class="fas fa-chalkboard-teacher" style="color:#64748b;"></i><span style="font-weight:500;">${item.dosen}</span></div></td>
                    <td>${asistenDisplay}</td>
                    <td style="text-align:center;">
                        ${statusText === "DIBATALKAN" ? `<span style="background-color:#ef4444; color:white; padding:4px 8px; border-radius:4px; font-size:0.75rem; font-weight:bold;">${statusText}</span>` : `<span class="${statusBadge}">${statusText}</span>`}
                    </td>
                </tr>`;
    });
    finalHtml += `</tbody></table></div></div>`;
  });

  container.innerHTML = finalHtml;
}

// ==========================================================================
// UPK & FORMAT PENULISAN (TIDAK DIUBAH, HANYA DISALIN ULANG AGAR AMAN)
// ==========================================================================

function initUpkPage() {
  const upkHeader = document.getElementById("upk-header-day");
  if (!upkHeader) return;

  function updateUpkHeader() {
    const now = new Date();
    const hari = hariIndo[now.getDay()];
    const tanggal = now.getDate();
    const bulan = bulanIndo[now.getMonth()];
    const tahun = now.getFullYear();
    upkHeader.innerText = `Jadwal ${hari}, ${tanggal} ${bulan} ${tahun}`;
  }
  updateUpkHeader();
  setInterval(updateUpkHeader, 1000);
}

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
        pedomanContainer.innerHTML = `<div style="grid-column: 1/-1; text-align:center; padding:40px;"><p>Data tidak ditemukan.</p></div>`;
      }
    } catch (error) {
      pedomanContainer.innerHTML = `<div style="grid-column: 1/-1; text-align:center; padding:40px; color:#ef4444;"><p>Gagal memuat data.</p></div>`;
    }
  }

  function renderFormatContent(data) {
    const unduhanContainer = document.getElementById("unduhan-container");
    const unduhanSection = document.getElementById("unduhan-section");

    // Render Pedoman
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
    }

    // Render Unduhan
    const unduhan = data.filter(
      (item) => (item.kategori || "").toLowerCase() === "unduhan",
    );
    if (unduhan.length > 0 && unduhanSection) {
      unduhanSection.style.display = "block";
      unduhanContainer.innerHTML = unduhan
        .map((item) => {
          const fileName = item.file ? item.file.trim() : "";
          const downloadPath = `assets/uploads/format_penulisan/${fileName}`;
          return `
                <div class="download-card">
                    <div class="file-icon-box"><i class="ri-file-text-line"></i></div>
                    <div class="download-content">
                        <h4>${item.judul}</h4>
                        <div class="action-buttons">
                            ${item.file ? `<a href="${downloadPath}" target="_blank" download class="btn-download"><i class="ri-download-cloud-2-fill"></i> Unduh</a>` : ""}
                            ${item.link_external ? `<a href="${item.link_external}" target="_blank" class="btn-external"><i class="ri-external-link-line"></i> Link</a>` : ""}
                        </div>
                    </div>
                </div>`;
        })
        .join("");
    }
  }
  loadFormatContent();
}

// MAIN EXECUTION
document.addEventListener("DOMContentLoaded", () => {
  startClock();
  if (
    document.getElementById("lab-tables-container") &&
    document.getElementById("dd-hari")
  ) {
    initJadwalPage();
  }
  if (document.getElementById("upk-header-day")) initUpkPage();
  if (document.getElementById("pedoman-container")) initFormatPenulisanPage();

  const filterHeader = document.querySelector(".filter-header");
  const filterGrid = document.querySelector(".filter-grid");

  if (filterHeader && filterGrid) {
    filterHeader.addEventListener("click", () => {
      // Hanya aktif di mode mobile/tablet (< 900px)
      if (window.innerWidth <= 900) {
        filterGrid.classList.toggle("active");
        filterHeader.classList.toggle("active");
      }
    });
  }
});
