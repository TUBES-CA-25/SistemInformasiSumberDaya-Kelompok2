/**
 * PRAKTIKUM SHARED SCRIPT - SEARCH, FILTER, HYBRID UI & SMART STATUS
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

function getTodayDate() {
  const now = new Date();
  const year = now.getFullYear();
  const month = String(now.getMonth() + 1).padStart(2, "0");
  const day = String(now.getDate()).padStart(2, "0");
  return `${year}-${month}-${day}`;
}

// Helper: Deteksi Prodi dari String Frekuensi atau Kelas
function detectProdi(row) {
  if (row.prodi) return row.prodi;
  if (row.frekuensi) {
    const f = row.frekuensi.toUpperCase();
    if (f.startsWith("TI") || f.includes("TI_")) return "TI";
    if (f.startsWith("SI") || f.includes("SI_")) return "SI";
  }
  if (row.kelas) {
    const k = row.kelas.toUpperCase();
    if (k.includes("TI") || k.includes("INFORMATIKA")) return "TI";
    if (k.includes("SI") || k.includes("SISTEM")) return "SI";
  }
  return "Lainnya";
}

// [UPDATE] Helper: Status Real-time yang Lebih Akurat
function getLiveStatus(row) {
  // 1. Cek Status Database (Prioritas)
  const dbStatus = (row.status || "Aktif").toLowerCase();
  if (["nonaktif", "0", "dibatalkan", "libur"].includes(dbStatus))
    return "Dibatalkan";

  const now = new Date();
  const currentHour =
    now.getHours().toString().padStart(2, "0") +
    ":" +
    now.getMinutes().toString().padStart(2, "0");

  // 2. Logika UPK (Berdasarkan Tanggal Spesifik)
  if (row.tanggal) {
    const todayDate = now.toISOString().split("T")[0]; // YYYY-MM-DD
    if (row.tanggal < todayDate) return "Selesai";
    if (row.tanggal > todayDate) return "Akan Datang";

    // Jika Hari Ini, Cek Jam
    if (row.waktuMulai && row.waktuSelesai) {
      const start = row.waktuMulai.substring(0, 5);
      const end = row.waktuSelesai.substring(0, 5);
      if (currentHour >= start && currentHour < end) return "Berlangsung";
      if (currentHour >= end) return "Selesai";
      return "Akan Datang";
    }
  }

  // 3. Logika Reguler (Berdasarkan Hari Senin-Sabtu)
  if (row.hari) {
    const dayMap = {
      senin: 1,
      selasa: 2,
      rabu: 3,
      kamis: 4,
      jumat: 5,
      sabtu: 6,
      minggu: 7,
    };
    const rowDayClean = row.hari.trim().toLowerCase();
    const rowDayIndex = dayMap[rowDayClean];

    // Jika data hari tidak valid, anggap akan datang
    if (!rowDayIndex) return "Akan Datang";

    let todayIndex = now.getDay();
    if (todayIndex === 0) todayIndex = 7; // Minggu jadi 7 agar urut

    // Logika Mingguan
    if (rowDayIndex < todayIndex) return "Selesai"; // Hari lewat minggu ini
    if (rowDayIndex > todayIndex) return "Akan Datang"; // Hari belum tiba

    // Jika Hari Ini, Cek Jam
    if (row.waktuMulai && row.waktuSelesai) {
      const start = row.waktuMulai.substring(0, 5);
      const end = row.waktuSelesai.substring(0, 5);
      if (currentHour >= start && currentHour < end) return "Berlangsung";
      if (currentHour >= end) return "Selesai";
      return "Akan Datang";
    }
  }

  return "Akan Datang"; // Default Fallback
}

// Helper: Generate HTML Badge Status
function getStatusBadgeHtml(statusStr) {
  if (statusStr === "Dibatalkan") {
    return '<span class="status-label badge-canceled" style="background:#fee2e2; color:#ef4444;">DIBATALKAN</span>';
  } else if (statusStr === "Berlangsung") {
    return '<span class="status-label badge-ongoing">BERLANGSUNG</span>';
  } else if (statusStr === "Selesai") {
    return '<span class="status-label badge-finished">SELESAI</span>';
  } else {
    return '<span class="status-label badge-upcoming">AKAN DATANG</span>';
  }
}

/* ==========================================================================
   GLOBAL SEARCH STATE
   ========================================================================== */
let globalSearchKeyword = "";

/* ==========================================================================
   PART A: LOGIKA JADWAL REGULER
   ========================================================================== */
let globalJadwalData = [];
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

      // Auto Select Hari Ini (Hanya jika belum ada pencarian)
      if (hariIni !== "Minggu") activeFilters.hari.add(hariIni);
      else activeFilters.hari.add("Senin");

      initFilterDropdowns();
      renderFilteredSchedule();
      setupSearchListener(renderFilteredSchedule); // Init Search
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
  const configs = [
    { id: "prodi", val: (r) => detectProdi(r) },
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
    // [UPDATE] Gunakan getLiveStatus agar dropdown status dinamis
    { id: "status", val: (r) => getLiveStatus(r) },
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
    // 1. Cek Keyword Pencarian (Search Bar)
    if (globalSearchKeyword) {
      const searchStr =
        `${row.namaMatakuliah} ${row.dosen} ${row.kelas} ${row.namaAsisten1} ${row.namaAsisten2} ${row.namaLab}`.toLowerCase();
      if (!searchStr.includes(globalSearchKeyword)) return false;
    }

    // 2. Cek Filter Dropdown
    if (!checkFilter(activeFilters.prodi, detectProdi(row))) return false;
    if (!checkFilter(activeFilters.hari, row.hari)) return false;
    const jam = `${row.waktuMulai.substring(0, 5)} - ${row.waktuSelesai.substring(0, 5)}`;
    if (!checkFilter(activeFilters.jam, jam)) return false;
    if (!checkFilter(activeFilters.kelas, row.kelas)) return false;
    if (!checkFilter(activeFilters.matkul, row.namaMatakuliah)) return false;
    if (!checkFilter(activeFilters.dosen, row.dosen)) return false;

    // [UPDATE] Filter menggunakan Status Real-time
    if (!checkFilter(activeFilters.status, getLiveStatus(row))) return false;

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
  status: new Set(),
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
  setupSearchListener(() => renderUpkFilteredSchedule(data)); // Init Search UPK
}

function initUpkFilterDropdowns(data) {
  const configs = [
    { id: "prodi", val: (r) => detectProdi(r) },
    { id: "tanggal", key: "tanggal" },
    { id: "ruang", key: "ruangan" },
    { id: "matkul", key: "mata_kuliah" },
    { id: "kelas", key: "kelas" },
    { id: "dosen", key: "dosen" },
    // [UPDATE] Status Real-time untuk UPK
    { id: "status", val: (r) => getLiveStatus(r) },
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
    // 1. Cek Keyword Pencarian
    if (globalSearchKeyword) {
      const searchStr =
        `${row.mata_kuliah} ${row.dosen} ${row.kelas} ${row.ruangan}`.toLowerCase();
      if (!searchStr.includes(globalSearchKeyword)) return false;
    }

    // 2. Cek Filter
    if (!checkFilter(activeUpkFilters.prodi, detectProdi(row))) return false;
    if (!checkFilter(activeUpkFilters.tanggal, row.tanggal)) return false;
    if (!checkFilter(activeUpkFilters.ruang, row.ruangan)) return false;
    if (!checkFilter(activeUpkFilters.matkul, row.mata_kuliah)) return false;
    if (!checkFilter(activeUpkFilters.kelas, row.kelas)) return false;
    if (!checkFilter(activeUpkFilters.dosen, row.dosen)) return false;
    // [UPDATE] Filter Status Real-time
    if (!checkFilter(activeUpkFilters.status, getLiveStatus(row))) return false;
    return true;
  });

  if (filtered.length === 0) {
    container.innerHTML = `
            <div class="empty-schedule" style="text-align:center; padding:40px; border: 2px dashed #e2e8f0; border-radius:16px;">
                <i class="fas fa-search" style="font-size:2.5rem; color:#cbd5e1; margin-bottom:15px;"></i>
                <h3 style="color:#64748b; font-size:1.2rem;">Tidak Ditemukan</h3>
                <p style="color:#94a3b8;">Tidak ada data yang cocok dengan "${globalSearchKeyword || "filter"}"</p>
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
      const prodiCode = detectProdi(item);
      const statusStr = getLiveStatus(item);
      let statusBadge = getStatusBadgeHtml(statusStr);

      finalHtml += `
                <tr>
                    <td>
                        <div class="schedule-date" style="margin-bottom:4px;">${formattedDate}</div>
                        <div class="schedule-time">${item.jam}</div>
                    </td>
                    <td>
                        <span class="schedule-matkul">${item.mata_kuliah}</span>
                        <span class="badge-prodi">${prodiCode}</span>
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
                    <td class="text-center">${statusBadge}</td>
                </tr>`;
    });
    finalHtml += `</tbody></table></div></div>`;
  });

  container.innerHTML = finalHtml;
}

/* ==========================================================================
   UI HANDLERS & SEARCH LISTENER
   ========================================================================== */

function setupSearchListener(renderCallback) {
  const searchInput = document.getElementById("keyword-search");
  const toggleBtn = document.getElementById("toggle-filter-btn");
  const filterCard = document.getElementById("filter-card");

  if (searchInput) {
    searchInput.addEventListener("input", (e) => {
      globalSearchKeyword = e.target.value.toLowerCase().trim();
      renderCallback();
    });
  }

  if (toggleBtn && filterCard) {
    toggleBtn.addEventListener("click", () => {
      filterCard.classList.toggle("show");
      toggleBtn.classList.toggle("active");

      const icon = toggleBtn.querySelector("i");
      if (filterCard.classList.contains("show")) {
        icon.classList.remove("fa-sliders-h");
        icon.classList.add("fa-times");
      } else {
        icon.classList.remove("fa-times");
        icon.classList.add("fa-sliders-h");
      }
    });
  }
}

function setupFilterUI() {
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
        status: "Status",
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
    status: "Status",
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
   SHARED HELPERS & HYBRID TOGGLE
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
      if (typeof cfg.val === "function") {
        val = cfg.val(row);
      } else if (cfg.val) {
        val = cfg.val;
      } else {
        val = row[cfg.key];
      }

      if (Array.isArray(val)) {
        val.forEach((v) => {
          if (v && v !== "-") uniqueSet.add(v.trim());
        });
      } else if (val) {
        uniqueSet.add(val.toString().trim());
      }
    });

    let items = Array.from(uniqueSet);
    if (cfg.id === "hari") {
      const order = {
        Senin: 1,
        Selasa: 2,
        Rabu: 3,
        Kamis: 4,
        Jumat: 5,
        Sabtu: 6,
        Minggu: 7,
      };
      items.sort((a, b) => (order[a] || 99) - (order[b] || 99));
    } else if (cfg.id === "status") {
      const order = {
        Berlangsung: 1,
        "Akan Datang": 2,
        Selesai: 3,
        Dibatalkan: 4,
      };
      items.sort((a, b) => (order[a] || 99) - (order[b] || 99));
    } else {
      items.sort();
    }

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

    updateBtnCallback(cfg.id);
  });
  setupDropdownToggle();
}

function setupDropdownToggle() {
  document.removeEventListener("click", closeAllDropdowns);
  document.addEventListener("click", closeAllDropdowns);

  const dropdowns = document.querySelectorAll(".adv-dropdown");

  dropdowns.forEach((dropdown) => {
    const btn = dropdown.querySelector(".adv-drop-btn");
    const content = dropdown.querySelector(".adv-drop-content");

    if (!btn || !content) return;

    const newBtn = btn.cloneNode(true);
    btn.parentNode.replaceChild(newBtn, btn);

    dropdown.addEventListener("mouseenter", () => {
      if (window.innerWidth > 900) {
        closeAllDropdowns(null, content);
        content.classList.add("show");
      }
    });
    dropdown.addEventListener("mouseleave", () => {
      if (window.innerWidth > 900) {
        content.classList.remove("show");
      }
    });

    newBtn.addEventListener("click", (e) => {
      if (window.innerWidth <= 900) {
        e.stopPropagation();
        const isOpen = content.classList.contains("show");
        closeAllDropdowns();
        if (!isOpen) content.classList.add("show");
      }
    });

    content.addEventListener("click", (e) => e.stopPropagation());
  });
}

function closeAllDropdowns(e, except = null) {
  document.querySelectorAll(".adv-drop-content").forEach((d) => {
    if (d !== except) d.classList.remove("show");
  });
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
      const statusStr = getLiveStatus(item);
      const statusBadge = getStatusBadgeHtml(statusStr);
      const kelasFreq = `<b>${item.kelas || "-"}</b> <span style="color:#94a3b8">/</span> ${item.frekuensi || "-"}`;
      const asistenDisplay =
        item.namaAsisten1 || item.namaAsisten2
          ? `<div class="asisten-cell">
                     ${item.namaAsisten1 ? `<div class="asisten-name"><i class="fas fa-user-check" style="color:#2563eb; font-size:0.8rem; margin-right:5px;"></i> ${item.namaAsisten1}</div>` : ""}
                     ${item.namaAsisten2 ? `<div class="asisten-name"><i class="fas fa-user-check" style="color:#2563eb; font-size:0.8rem; margin-right:5px;"></i> ${item.namaAsisten2}</div>` : ""}
                   </div>`
          : '<span style="color:#cbd5e1">-</span>';
      const prodiCode = detectProdi(item);

      finalHtml += `
                <tr>
                    <td style="font-weight:600; font-size:0.9rem;">${item.hari}</td>
                    <td class="text-nowrap" style="font-family:'JetBrains Mono', monospace; font-size:0.9rem;">${start} - ${end}</td>
                    <td style="color: #0f172a; font-weight: 700;">
                        ${item.namaMatakuliah}
                        <span class="badge-prodi">${prodiCode}</span>
                    </td>
                    <td class="text-nowrap">${kelasFreq}</td>
                    <td><div style="display:flex; align-items:center; gap:8px;"><i class="fas fa-chalkboard-teacher" style="color:#64748b;"></i><span style="font-weight:500;">${item.dosen}</span></div></td>
                    <td>${asistenDisplay}</td>
                    <td style="text-align:center;">${statusBadge}</td>
                </tr>`;
    });
    finalHtml += `</tbody></table></div></div>`;
  });
  container.innerHTML = finalHtml;
}

/* ==========================================================================
   FORMAT PENULISAN (MODUL) & PENCARIAN MODUL
   ========================================================================== */

// Variabel Global untuk menyimpan data modul agar bisa difilter
let globalModulData = [];

function initFormatPenulisanPage() {
  const pedomanContainer = document.getElementById("pedoman-container");
  // Gunakan endpoint yang sesuai
  const apiUrl = (window.BASE_URL || "") + "/api.php/formatpenulisan";

  async function loadFormatContent() {
    try {
      const response = await fetch(apiUrl);
      const result = await response.json();

      if (result.status === "success" || result.code === 200) {
        // 1. Simpan data ke variabel global
        globalModulData = result.data;

        // 2. Render awal (tampilkan semua)
        renderFormatContent(globalModulData);

        // 3. Aktifkan fitur pencarian
        setupModulSearchListener();
      } else {
        pedomanContainer.innerHTML = `<div style="grid-column: 1/-1; text-align:center; padding:40px;"><p>Data tidak ditemukan.</p></div>`;
      }
    } catch (error) {
      console.error(error);
      if (pedomanContainer) {
        pedomanContainer.innerHTML = `<div style="grid-column: 1/-1; text-align:center; padding:40px; color:#ef4444;"><p>Gagal memuat data.</p></div>`;
      }
    }
  }

  loadFormatContent();
}

// [BARU] Setup Listener Pencarian Modul
function setupModulSearchListener() {
  const searchInput = document.getElementById("modul-search");

  if (searchInput) {
    searchInput.addEventListener("input", (e) => {
      const keyword = e.target.value.toLowerCase().trim();

      // Filter data berdasarkan Judul atau Deskripsi
      const filteredData = globalModulData.filter((item) => {
        const judul = (item.judul || "").toLowerCase();
        const deskripsi = (item.deskripsi || "").toLowerCase();
        return judul.includes(keyword) || deskripsi.includes(keyword);
      });

      // Render ulang dengan data hasil filter
      renderFormatContent(filteredData);
    });
  }
}

function renderFormatContent(data) {
  const pedomanContainer = document.getElementById("pedoman-container");
  const unduhanContainer = document.getElementById("unduhan-container");
  const unduhanSection = document.getElementById("unduhan-section");

  // Jika data kosong hasil pencarian
  if (data.length === 0) {
    if (pedomanContainer)
      pedomanContainer.innerHTML = `<div style="grid-column: 1/-1; text-align:center; padding:30px; color:#94a3b8;">Tidak ada pedoman yang cocok.</div>`;
    if (unduhanContainer)
      unduhanContainer.innerHTML = `<div style="grid-column: 1/-1; text-align:center; padding:30px; color:#94a3b8;">Tidak ada file yang cocok.</div>`;
    return;
  }

  // A. Render Pedoman (Rules)
  const pedoman = data.filter(
    (item) => (item.kategori || "pedoman").toLowerCase() === "pedoman",
  );
  if (pedomanContainer) {
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
      pedomanContainer.innerHTML = ""; // Kosongkan jika tidak ada hasil filter kategori ini
    }
  }

  // B. Render Unduhan (Files)
  const unduhan = data.filter(
    (item) => (item.kategori || "").toLowerCase() === "unduhan",
  );
  if (unduhanSection && unduhanContainer) {
    if (unduhan.length > 0) {
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
                        <div class="file-meta">
                            <span><i class="ri-file-info-line"></i> Dokumen Pendukung</span>
                        </div>
                        <div class="action-buttons">
                            ${item.file ? `<a href="${downloadPath}" target="_blank" download class="btn-download"><i class="ri-download-cloud-2-fill"></i> Unduh</a>` : ""}
                            ${item.link_external ? `<a href="${item.link_external}" target="_blank" class="btn-external"><i class="ri-external-link-line"></i> Link</a>` : ""}
                        </div>
                    </div>
                </div>`;
        })
        .join("");
    } else {
      // Jika pencarian tidak menemukan file unduhan, sembunyikan atau tampilkan pesan kosong
      unduhanContainer.innerHTML = `<div style="grid-column: 1/-1; text-align:center; padding:20px; color:#cbd5e1;">Tidak ada file unduhan yang cocok.</div>`;
      // Opsional: unduhanSection.style.display = "none";
    }
  }
}

function initModulSearch() {
  const searchInput = document.getElementById("modul-praktikum-search");

  // Jika tidak ada elemen search (bukan halaman modul), hentikan
  if (!searchInput) return;

  searchInput.addEventListener("input", (e) => {
    const keyword = e.target.value.toLowerCase().trim();

    // Filter Tabel TI
    filterStaticTable("table-ti", keyword);
    // Filter Tabel SI
    filterStaticTable("table-si", keyword);
  });
}

function filterStaticTable(tableId, keyword) {
  const table = document.getElementById(tableId);
  if (!table) return;

  const rows = table.querySelectorAll("tbody tr.modul-item");
  const notFoundMsg = table.querySelector("tbody tr.not-found-msg");
  let visibleCount = 0;

  rows.forEach((row) => {
    // Ambil teks dari kolom Mata Kuliah dan Judul
    const matkul =
      row.querySelector(".matkul-name")?.innerText.toLowerCase() || "";
    const judul =
      row.querySelector(".modul-title")?.innerText.toLowerCase() || "";

    // Cek kecocokan
    if (matkul.includes(keyword) || judul.includes(keyword)) {
      row.style.display = ""; // Tampilkan
      visibleCount++;
    } else {
      row.style.display = "none"; // Sembunyikan
    }
  });

  // Tampilkan pesan "Tidak ditemukan" jika hasil 0
  if (notFoundMsg) {
    if (visibleCount === 0 && rows.length > 0) {
      notFoundMsg.style.display = "table-row";
    } else {
      notFoundMsg.style.display = "none";
    }
  }
}

/* ==========================================================================
   MAIN EXECUTION (UPDATE BAGIAN INI)
   ========================================================================== */
document.addEventListener("DOMContentLoaded", () => {
  startClock();

  // Init Halaman Jadwal
  if (
    document.getElementById("lab-tables-container") &&
    document.getElementById("dd-hari")
  ) {
    initJadwalPage();
  }

  // Init Halaman UPK
  if (document.getElementById("upk-tables-container")) {
    initUpkPage();
  }

  // Init Halaman Modul (Pencarian Static)
  if (document.getElementById("modul-praktikum-search")) {
    initModulSearch();
  }

  // Init Format Penulisan (Jika ada halaman lain yg pakai API)
  if (document.getElementById("pedoman-container")) {
    initFormatPenulisanPage();
  }
});
