/**
 * PRAKTIKUM SHARED SCRIPT - MULTI FILTER VERSION (JADWAL & UPK)
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

function formatDateIndo(dateStr) {
  if (!dateStr) return "-";
  const d = new Date(dateStr);
  if (isNaN(d.getTime())) return dateStr;
  return `${d.getDate()} ${bulanIndo[d.getMonth()]} ${d.getFullYear()}`;
}

// [UPDATE] Helper: Deteksi Prodi dari String Frekuensi atau Kelas
function detectProdi(row) {
  // 1. Cek dari field 'prodi' jika ada
  if (row.prodi) return row.prodi;

  // 2. Cek dari String Frekuensi (Priority: TI_SD-7 -> TI)
  if (row.frekuensi) {
    const f = row.frekuensi.toUpperCase();
    // Cek prefix TI atau SI di awal string frekuensi
    if (f.startsWith("TI") || f.includes("TI_")) return "TI";
    if (f.startsWith("SI") || f.includes("SI_")) return "SI";
  }

  // 3. Fallback: Cek dari Nama Kelas (Misal: "TI-A" -> "TI")
  if (row.kelas) {
    const k = row.kelas.toUpperCase();
    if (k.includes("TI") || k.includes("INFORMATIKA")) return "TI";
    if (k.includes("SI") || k.includes("SISTEM")) return "SI";
  }

  return "Lainnya";
}

/* ==========================================================================
   PART A: LOGIKA JADWAL REGULER
   ========================================================================== */
let globalJadwalData = [];
// Tambahkan 'prodi' ke state filter
const activeFilters = {
  prodi: new Set(),
  hari: new Set(),
  jam: new Set(),
  kelas: new Set(),
  matkul: new Set(),
  dosen: new Set(),
  asisten: new Set(),
  status: new Set(),
};

function initJadwalPage() {
  if (typeof API_JADWAL_URL === "undefined") return;

  fetch(API_JADWAL_URL)
    .then((res) => res.json())
    .then((res) => {
      globalJadwalData = res.data || [];
      const now = new Date();
      const hariIni = hariIndo[now.getDay()];
      if (hariIni !== "Minggu") activeFilters.hari.add(hariIni);
      else activeFilters.hari.add("Senin");

      initFilterDropdowns();
      renderFilteredSchedule();
    })
    .catch((err) => handleError("lab-tables-container", err));

  setupFilterUI();
}

function handleError(containerId, err) {
  console.error(err);
  const container = document.getElementById(containerId);
  if (container) {
    container.innerHTML = `
            <div class="empty-schedule" style="border-color:#fca5a5; text-align:center; padding:30px;">
                <i class="fas fa-exclamation-circle" style="color:#ef4444; font-size:2rem;"></i>
                <h3 style="margin-top:10px;">Gagal Memuat Data</h3>
                <p>Terjadi kesalahan saat menghubungi server.</p>
            </div>`;
  }
}

function initFilterDropdowns() {
  // [UPDATE] Gunakan detectProdi di config 'prodi'
  const configs = [
    { id: "prodi", val: (r) => detectProdi(r) }, // Deteksi TI/SI
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
  populateDropdowns(
    globalJadwalData,
    configs,
    activeFilters,
    "dd-",
    renderFilteredSchedule,
    updateFilterButton,
  );
}

function renderFilteredSchedule() {
  const container = document.getElementById("lab-tables-container");
  if (!container) return;

  const filtered = globalJadwalData.filter((row) => {
    // Filter Prodi
    if (!checkFilter(activeFilters.prodi, detectProdi(row))) return false;

    if (!checkFilter(activeFilters.hari, row.hari)) return false;
    const jam = `${row.waktuMulai.substring(0, 5)} - ${row.waktuSelesai.substring(0, 5)}`;
    if (!checkFilter(activeFilters.jam, jam)) return false;
    if (!checkFilter(activeFilters.kelas, row.kelas)) return false;
    if (!checkFilter(activeFilters.matkul, row.namaMatakuliah)) return false;
    if (!checkFilter(activeFilters.dosen, row.dosen)) return false;
    if (!checkFilter(activeFilters.status, row.status || "Aktif")) return false;
    if (activeFilters.asisten.size > 0) {
      const rowAst = [row.namaAsisten1, row.namaAsisten2];
      if (!rowAst.some((a) => a && activeFilters.asisten.has(a))) return false;
    }
    return true;
  });

  renderTableGeneric(container, filtered, "hari");
}

/* ==========================================================================
   PART B: LOGIKA JADWAL UPK
   ========================================================================== */
let globalUpkData = [];
const activeUpkFilters = {
  prodi: new Set(),
  tanggal: new Set(),
  ruang: new Set(),
  kelas: new Set(),
  matkul: new Set(),
  dosen: new Set(),
};

function initUpkPage() {
  const data = window.UPK_DATA || [];
  globalUpkData = data;

  if (data.length === 0) {
    document.getElementById("upk-tables-container").innerHTML = `
            <div class="empty-schedule">
                <i class="far fa-calendar-times" style="font-size:3rem; margin-bottom:15px; color:#cbd5e1;"></i>
                <h3 style="color:#64748b;">Belum Ada Jadwal</h3>
                <p style="color:#94a3b8;">Jadwal UPK belum dirilis oleh admin.</p>
            </div>`;
    return;
  }

  initUpkFilterDropdowns(data);
  renderUpkFilteredSchedule(data);
  setupUpkFilterUI(data);
}

function initUpkFilterDropdowns(data) {
  // [UPDATE] Gunakan detectProdi di config 'prodi'
  const configs = [
    { id: "prodi", val: (r) => detectProdi(r) }, // Deteksi TI/SI
    { id: "tanggal", key: "tanggal" },
    { id: "ruang", key: "ruangan" },
    { id: "matkul", key: "mata_kuliah" },
    { id: "kelas", key: "kelas" },
    { id: "dosen", key: "dosen" },
  ];

  populateDropdowns(
    data,
    configs,
    activeUpkFilters,
    "dd-upk-",
    () => renderUpkFilteredSchedule(data),
    updateUpkFilterButton,
  );
}

function renderUpkFilteredSchedule(rawData) {
  const container = document.getElementById("upk-tables-container");
  if (!container) return;

  const filtered = rawData.filter((row) => {
    // Filter Prodi
    if (!checkFilter(activeUpkFilters.prodi, detectProdi(row))) return false;

    if (!checkFilter(activeUpkFilters.tanggal, row.tanggal)) return false;
    if (!checkFilter(activeUpkFilters.ruang, row.ruangan)) return false;
    if (!checkFilter(activeUpkFilters.matkul, row.mata_kuliah)) return false;
    if (!checkFilter(activeUpkFilters.kelas, row.kelas)) return false;
    if (!checkFilter(activeUpkFilters.dosen, row.dosen)) return false;
    return true;
  });

  if (filtered.length === 0) {
    container.innerHTML = `
            <div class="empty-schedule" style="text-align:center; padding:40px; border: 2px dashed #e2e8f0; border-radius:16px;">
                <i class="fas fa-search" style="font-size:2.5rem; color:#cbd5e1; margin-bottom:15px;"></i>
                <h3 style="color:#64748b; font-size:1.2rem;">Tidak Ditemukan</h3>
                <p style="color:#94a3b8;">Tidak ada data yang cocok dengan filter.</p>
            </div>`;
    return;
  }

  const groups = {};
  filtered.forEach((item) => {
    const r = item.ruangan || "Ruangan Belum Ditentukan";
    if (!groups[r]) groups[r] = [];
    groups[r].push(item);
  });

  const sortedRuangan = Object.keys(groups).sort();
  let finalHtml = "";

  const now = new Date();
  const today = now.toISOString().split("T")[0];

  sortedRuangan.forEach((ruang) => {
    const items = groups[ruang];
    items.sort((a, b) => (a.tanggal + a.jam).localeCompare(b.tanggal + b.jam));

    finalHtml += `
        <div class="schedule-wrapper" style="margin-bottom: 40px;">
            <div class="lab-header">
                <div class="lab-icon"><i class="fas fa-door-open"></i></div>
                <h2 class="lab-title">${ruang}</h2>
            </div>
            <div class="table-responsive">
                <table class="table-schedule">
                    <thead>
                        <tr>
                            <th width="20%">Waktu & Tanggal</th>
                            <th width="30%">Mata Kuliah</th>
                            <th width="15%">Kelas / Freq</th>
                            <th width="20%">Dosen Pengampu</th>
                            <th width="15%" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>`;

    items.forEach((item) => {
      const formattedDate = formatDateIndo(item.tanggal);

      let statusHtml = "";
      if (item.tanggal === today) {
        statusHtml = '<span class="status-label badge-ongoing">HARI INI</span>';
      } else if (item.tanggal < today) {
        statusHtml = '<span class="status-label badge-finished">SELESAI</span>';
      } else {
        statusHtml =
          '<span class="status-label badge-upcoming">AKAN DATANG</span>';
      }

      finalHtml += `
                <tr>
                    <td>
                        <div class="schedule-date" style="margin-bottom:4px;">${formattedDate}</div>
                        <div class="schedule-time">${item.jam}</div>
                    </td>
                    <td>
                        <span class="schedule-matkul">${item.mata_kuliah}</span>
                        ${item.prodi ? `<span class="badge-prodi">${item.prodi}</span>` : ""}
                    </td>
                    <td>
                        <span class="schedule-kelas">Kelas ${item.kelas}</span>
                        <span class="schedule-freq" style="display:block; font-size:0.8rem; color:#94a3b8;">Freq: ${item.frekuensi}</span>
                    </td>
                    <td>
                        <div class="dosen-info">
                            <i class="fas fa-user-tie" style="color:#64748b;"></i>
                            <span class="dosen-name">${item.dosen}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        ${statusHtml}
                    </td>
                </tr>`;
    });
    finalHtml += `</tbody></table></div></div>`;
  });

  container.innerHTML = finalHtml;
}

/* ==========================================================================
   UI HANDLERS (JADWAL & UPK)
   ========================================================================== */

function setupFilterUI() {
  const filterHeader = document.querySelector(".filter-card .filter-header");
  const filterGrid = document.querySelector(".filter-grid");

  if (filterHeader && filterGrid && !filterGrid.id.includes("upk")) {
    filterHeader.addEventListener("click", () => {
      if (window.innerWidth <= 900) {
        filterGrid.classList.toggle("active");
        filterHeader.classList.toggle("active");
      }
    });
  }

  const resetBtn = document.getElementById("btn-reset-filter");
  if (resetBtn) {
    resetBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      Object.values(activeFilters).forEach((s) => s.clear());
      document
        .querySelectorAll('.filter-grid input[type="checkbox"]')
        .forEach((c) => (c.checked = false));

      const map = {
        prodi: "Prodi",
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
          btn.innerHTML = `${map[id]} <i class="fas fa-chevron-down"></i>`;
        }
      });

      resetBtn.style.display = "none";
      renderFilteredSchedule();
    });
  }
}

function updateFilterButton(id) {
  const btn = document.querySelector(`#dd-${id} .adv-drop-btn`);
  const count = activeFilters[id].size;
  const labelMap = {
    prodi: "Prodi",
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
  if (resetBtn) resetBtn.style.display = hasAny ? "inline-flex" : "none";
}

function setupUpkFilterUI(data) {
  const filterHeader = document.querySelector(".filter-card .filter-header");
  const filterGrid = document.getElementById("upk-filter-grid");

  if (filterHeader && filterGrid) {
    filterHeader.addEventListener("click", () => {
      if (window.innerWidth <= 900) {
        filterGrid.classList.toggle("active");
        filterHeader.classList.toggle("active");
      }
    });
  }

  const resetBtn = document.getElementById("btn-reset-upk");
  if (resetBtn) {
    resetBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      Object.values(activeUpkFilters).forEach((s) => s.clear());
      document
        .querySelectorAll('#upk-filter-grid input[type="checkbox"]')
        .forEach((c) => (c.checked = false));

      const labelMap = {
        prodi: "Prodi",
        tanggal: "Tanggal",
        ruang: "Ruangan",
        kelas: "Kelas",
        matkul: "Mata Kuliah",
        dosen: "Dosen",
      };
      Object.keys(activeUpkFilters).forEach((id) => {
        const btn = document.querySelector(`#dd-upk-${id} .adv-drop-btn`);
        if (btn) {
          btn.classList.remove("active");
          btn.innerHTML = `${labelMap[id]} <i class="fas fa-chevron-down"></i>`;
        }
      });
      resetBtn.style.display = "none";
      renderUpkFilteredSchedule(data);
    });
  }
}

function updateUpkFilterButton(id) {
  const btn = document.querySelector(`#dd-upk-${id} .adv-drop-btn`);
  const count = activeUpkFilters[id].size;
  const labelMap = {
    prodi: "Prodi",
    tanggal: "Tanggal",
    ruang: "Ruangan",
    kelas: "Kelas",
    matkul: "Mata Kuliah",
    dosen: "Dosen",
  };

  if (count > 0) {
    btn.classList.add("active");
    btn.innerHTML = `${labelMap[id]} <span style="background:#2563eb; color:#fff; padding:1px 6px; border-radius:10px; font-size:0.7rem; margin-left:5px;">${count}</span> <i class="fas fa-filter" style="font-size:0.8rem;"></i>`;
  } else {
    btn.classList.remove("active");
    btn.innerHTML = `${labelMap[id]} <i class="fas fa-chevron-down"></i>`;
  }

  const hasAny = Object.values(activeUpkFilters).some((s) => s.size > 0);
  const resetBtn = document.getElementById("btn-reset-upk");
  if (resetBtn) resetBtn.style.display = hasAny ? "inline-flex" : "none";
}

/* ==========================================================================
   SHARED HELPERS (CORE)
   ========================================================================== */
function populateDropdowns(
  data,
  configs,
  filterState,
  idPrefix,
  renderCallback,
  updateBtnCallback,
) {
  configs.forEach((cfg) => {
    const uniqueSet = new Set();
    data.forEach((row) => {
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

    let items = Array.from(uniqueSet).sort();
    const container = document.querySelector(
      `#${idPrefix}${cfg.id} .adv-drop-content`,
    );
    if (!container) return;

    container.innerHTML = items
      .map((item) => {
        const isChecked = filterState[cfg.id].has(item) ? "checked" : "";
        return `
            <label class="filter-option">
                <input type="checkbox" value="${item}" data-cat="${cfg.id}" ${isChecked}>
                <span>${item}</span>
            </label>`;
      })
      .join("");

    container.querySelectorAll("input").forEach((cb) => {
      cb.addEventListener("change", (e) => {
        const cat = e.target.dataset.cat;
        const val = e.target.value;
        if (e.target.checked) filterState[cat].add(val);
        else filterState[cat].delete(val);
        updateBtnCallback(cat);
        renderCallback();
      });
    });

    // Init update
    updateBtnCallback(cfg.id);
  });
  setupDropdownToggle();
}

function setupDropdownToggle() {
  document.querySelectorAll(".adv-drop-btn").forEach((btn) => {
    const newBtn = btn.cloneNode(true);
    btn.parentNode.replaceChild(newBtn, btn);
    newBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      const content = newBtn.nextElementSibling;
      document.querySelectorAll(".adv-drop-content").forEach((d) => {
        if (d !== content) d.classList.remove("show");
      });
      content.classList.toggle("show");
    });
  });
  document.addEventListener("click", () =>
    document.querySelectorAll(".adv-drop-content").classList.remove("show"),
  );
  document
    .querySelectorAll(".adv-drop-content")
    .forEach((d) => d.addEventListener("click", (e) => e.stopPropagation()));
}

function checkFilter(set, val) {
  if (set.size === 0) return true;
  return set.has(val);
}

function renderTableGeneric(container, data, type = "hari") {
  if (data.length === 0) {
    container.innerHTML = `
            <div class="empty-schedule" style="text-align:center; padding:40px; border: 2px dashed #e2e8f0; border-radius:12px;">
                <i class="far fa-calendar-times" style="font-size:3rem; color:#cbd5e1; margin-bottom:15px;"></i>
                <h3 style="color:#64748b;">Tidak Ada Jadwal</h3>
                <p style="color:#94a3b8;">Tidak ada data yang cocok dengan filter yang dipilih.</p>
            </div>`;
    return;
  }

  const labs = [...new Set(data.map((item) => item.namaLab))].sort();
  let finalHtml = "";
  const now = new Date();
  const jamSekarang =
    now.getHours().toString().padStart(2, "0") +
    ":" +
    now.getMinutes().toString().padStart(2, "0");
  const hariIniReal = hariIndo[now.getDay()];

  labs.forEach((lab) => {
    const jadwalLab = data.filter((item) => item.namaLab === lab);
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
                            <th class="text-nowrap" width="12%">${type === "upk" ? "Tanggal" : "Hari"}</th>
                            <th class="text-nowrap">Waktu</th>
                            <th width="25%">Mata Kuliah</th>
                            <th class="text-nowrap">Kls/Freq</th>
                            <th width="20%">Dosen/Pengawas</th>
                            <th width="20%">Asisten</th>
                            <th width="10%" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>`;

    jadwalLab.forEach((item) => {
      const start = item.waktuMulai.substring(0, 5);
      const end = item.waktuSelesai.substring(0, 5);

      let statusBadge = "status-label badge-scheduled";
      let statusText = "TERJADWAL";
      let rowStyle = "";
      const statusDb = item.status ? item.status.toLowerCase() : "aktif";

      if (["nonaktif", "0", "dibatalkan"].includes(statusDb)) {
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
                    <td class="text-nowrap" style="font-family:'JetBrains Mono', monospace; font-size:0.9rem;">${start} - ${end}</td>
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

/* ==========================================================================
   FORMAT PENULISAN (MODUL)
   ========================================================================== */
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

/* ==========================================================================
   MAIN EXECUTION
   ========================================================================== */
document.addEventListener("DOMContentLoaded", () => {
  startClock();
  if (
    document.getElementById("lab-tables-container") &&
    document.getElementById("dd-hari")
  ) {
    initJadwalPage();
  }
  if (document.getElementById("upk-tables-container")) {
    initUpkPage();
  }
  if (document.getElementById("pedoman-container")) {
    initFormatPenulisanPage();
  }
});
