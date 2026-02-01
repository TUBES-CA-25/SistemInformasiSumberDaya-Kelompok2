let allJadwalData = [];

// Helper untuk membuka modal
window.openModal = function (id) {
  const modal = document.getElementById(id);
  if (modal) {
    modal.classList.remove("hidden");
    document.body.style.overflow = "hidden";
  }
};

// Helper untuk menutup modal
window.closeModal = function (id) {
  const modal = document.getElementById(id);
  if (modal) {
    modal.classList.add("hidden");
    document.body.style.overflow = "auto";
  }
};

window.openUploadModal = function () {
  openModal("uploadModal");
  // Reset form states
  const form = document.getElementById("uploadForm");
  if (form) form.reset();

  const fileInfo = document.getElementById("fileInfo");
  if (fileInfo) fileInfo.classList.add("hidden");

  const prog = document.getElementById("uploadProgress");
  if (prog) prog.classList.add("hidden");

  const msg = document.getElementById("uploadMessage");
  if (msg) msg.classList.add("hidden");
};

// Handler Upload File & Inisialisasi
document.addEventListener("DOMContentLoaded", () => {
  loadDropdownData();
  loadJadwal();

  const uploadForm = document.getElementById("uploadForm");
  if (uploadForm) {
    uploadForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const fileInput = document.getElementById("fileExcel");
      if (!fileInput.files.length) return;

      const formData = new FormData();
      formData.append("excel_file", fileInput.files[0]);

      const btn = document.getElementById("btnUpload");
      const progressDiv = document.getElementById("uploadProgress");
      const progressBar = document.getElementById("progressBar");
      const progressPercent = document.getElementById("progressPercent");
      const msgDiv = document.getElementById("uploadMessage");

      btn.disabled = true;
      btn.innerHTML =
        '<i class="fas fa-circle-notch fa-spin"></i> Memproses...';
      progressDiv.classList.remove("hidden");
      msgDiv.classList.add("hidden");

      try {
        // Simulate progress
        let progress = 0;
        const interval = setInterval(() => {
          if (progress < 90) {
            progress += 10;
            progressBar.style.width = progress + "%";
            progressPercent.innerText = progress + "%";
          }
        }, 100);

        // PERBAIKAN: Gunakan window.API_URL
        const response = await fetch(`${window.API_URL}/jadwal-upk/upload`, {
          method: "POST",
          body: formData,
        });

        clearInterval(interval);
        const result = await response.json();

        progressBar.style.width = "100%";
        progressPercent.innerText = "100%";

        if (result.status === "success") {
          msgDiv.className =
            "mb-4 p-3 rounded-lg text-sm bg-emerald-50 text-emerald-800 border border-emerald-100";
          msgDiv.innerHTML = `<i class="fas fa-check-circle mr-1"></i> ${result.message}`;
          msgDiv.classList.remove("hidden");
          setTimeout(() => {
            closeModal("uploadModal");
            loadJadwal();
          }, 1500);
        } else {
          throw new Error(result.message);
        }
      } catch (err) {
        msgDiv.className =
          "mb-4 p-3 rounded-lg text-sm bg-red-50 text-red-800 border border-red-100";
        msgDiv.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i> ${err.message}`;
        msgDiv.classList.remove("hidden");
      } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-upload"></i> Upload & Proses';
      }
    });
  }

  const fileInput = document.getElementById("fileExcel");
  if (fileInput) {
    fileInput.addEventListener("change", function (e) {
      const file = e.target.files[0];
      const info = document.getElementById("fileInfo");
      if (file) {
        document.getElementById("fileNameDisplay").innerText = file.name;
        document.getElementById("fileSizeDisplay").innerText =
          (file.size / 1024 / 1024).toFixed(2) + " MB";
        info.classList.remove("hidden");
      } else {
        info.classList.add("hidden");
      }
    });
  }

  // Search Handler
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("keyup", (e) => {
      const key = e.target.value.toLowerCase();
      const filtered = allJadwalData.filter(
        (i) =>
          (i.mata_kuliah && i.mata_kuliah.toLowerCase().includes(key)) ||
          (i.dosen && i.dosen.toLowerCase().includes(key)),
      );
      renderTable(filtered);
    });
  }

  // Select All Handler
  const selectAll = document.getElementById("selectAll");
  if (selectAll) {
    selectAll.addEventListener("change", function () {
      const checks = document.querySelectorAll(".row-checkbox");
      checks.forEach((c) => (c.checked = this.checked));
      updateBulkActionsVisibility();
    });
  }

  // Form Submit Handler
  const jadwalForm = document.getElementById("jadwalForm");
  if (jadwalForm) {
    jadwalForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const id = document.getElementById("inputId").value;
      const method = id ? "PUT" : "POST";

      // PERBAIKAN: Gunakan window.API_URL
      const url = id
        ? `${window.API_URL}/jadwal-upk/${id}`
        : `${window.API_URL}/jadwal-upk`;

      // Convert FormData to JSON object for API
      const formData = new FormData(this);
      const data = Object.fromEntries(formData.entries());

      try {
        const response = await fetch(url, {
          method: method,
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(data),
        });
        const res = await response.json();
        if (res.status === "success" || res.code === 200) {
          closeModal("formModal");
          loadJadwal();
          Swal.fire("Berhasil!", "Data telah disimpan.", "success");
        } else {
          Swal.fire("Gagal", res.message || "Gagal menyimpan data", "error");
        }
      } catch (err) {
        Swal.fire("Error", "Gagal menghubungi server.", "error");
      }
    });
  }
});

window.openDetailModal = function (id, event = null) {
  if (event) event.stopPropagation();
  const data = allJadwalData.find((i) => i.id == id);
  if (data) {
    document.getElementById("detailMK").innerText = data.mata_kuliah || "-";
    document.getElementById("detailDosen").innerText = data.dosen || "-";
    document.getElementById("detailProdi").innerText = data.prodi || "-";

    let tgl = "-";
    if (data.tanggal) {
      tgl = new Date(data.tanggal).toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "long",
        year: "numeric",
      });
    }
    document.getElementById("detailTanggal").innerText = tgl;
    document.getElementById("detailJam").innerText = data.jam || "-";
    document.getElementById("detailKelas").innerText = data.kelas || "-";
    document.getElementById("detailRuangan").innerText = data.ruangan || "-";
    document.getElementById("detailFreq").innerText = data.frekuensi || "-";
    openModal("detailModal");
  }
};

window.openFormModal = function (id = null, event = null) {
  if (event) event.stopPropagation();
  openModal("formModal");
  const title = document.getElementById("formModalTitle");
  const form = document.getElementById("jadwalForm");
  form.reset();
  document.getElementById("inputId").value = "";

  if (id) {
    title.innerHTML =
      '<i class="fas fa-edit text-blue-600 mr-2"></i> Edit Jadwal UPK';
    // Pakai == untuk handle id string vs number
    const data = allJadwalData.find((i) => i.id == id);
    if (data) {
      document.getElementById("inputId").value = data.id;
      document.getElementById("inputProdi").value = data.prodi;
      document.getElementById("inputMK").value = data.mata_kuliah;
      document.getElementById("inputDosen").value = data.dosen;
      document.getElementById("inputTanggal").value = data.tanggal;
      document.getElementById("inputJam").value = data.jam;
      document.getElementById("inputRuangan").value = data.ruangan;
      document.getElementById("inputKelas").value = data.kelas;
      document.getElementById("inputFreq").value = data.frekuensi;
    }
  } else {
    title.innerHTML =
      '<i class="fas fa-plus text-blue-600 mr-2"></i> Tambah Jadwal Baru';
  }
};

window.hapusJadwal = function (id, event = null) {
  if (event) event.stopPropagation();
  Swal.fire({
    title: "Hapus data?",
    text: "Data yang dihapus tidak bisa dikembalikan!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#ef4444",
    cancelButtonColor: "#64748b",
    confirmButtonText: "Ya, Hapus!",
    cancelButtonText: "Batal",
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        // PERBAIKAN: Gunakan window.API_URL
        const response = await fetch(`${window.API_URL}/jadwal-upk/${id}`, {
          method: "DELETE",
        });
        const res = await response.json();
        if (res.status === "success") {
          Swal.fire("Berhasil!", "Data telah dihapus.", "success");
          loadJadwal();
        } else {
          Swal.fire("Gagal", res.message || "Gagal menghapus data.", "error");
        }
      } catch (err) {
        console.error("Delete Error:", err);
        Swal.fire("Error", "Gagal menghubungi server.", "error");
      }
    }
  });
};

async function loadJadwal() {
  const tbody = document.getElementById("tableBody");
  try {
    // PERBAIKAN: Gunakan window.API_URL (bukan PUBLIC_URL/api.php karena API_URL sudah include base path api)
    // Jika API_URL anda "http://localhost/api.php", maka cukup panggil API_URL + "/jadwal-upk"
    const response = await fetch(`${window.API_URL}/jadwal-upk`);
    const result = await response.json();
    if (result.status === "success") {
      allJadwalData = result.data;
      console.log("DEBUG - Data dari API:", allJadwalData);
      if (allJadwalData.length > 0) {
        console.log("DEBUG - Keys dari data pertama:", Object.keys(allJadwalData[0]));
        console.table([allJadwalData[0]]); // Print dengan table format
        console.log("DEBUG - Data pertama (detail):");
        for (let key in allJadwalData[0]) {
          console.log(`  ${key}: ${allJadwalData[0][key]}`);
        }
      }
      renderTable(allJadwalData);
    } else {
      // Handle jika data kosong dari backend
      allJadwalData = [];
      renderTable([]);
    }
  } catch (err) {
    console.error("API Error:", err);
    tbody.innerHTML = `<tr><td colspan="8" class="text-center text-red-400 py-10 font-bold">Gagal sinkronisasi API.</td></tr>`;
  }
}

function renderTable(data) {
  const tbody = document.getElementById("tableBody");
  const totalData = document.getElementById("totalData");

  if (totalData) totalData.innerText = `Total: ${data.length}`;

  // Reset Select All
  const selectAll = document.getElementById("selectAll");
  if (selectAll) selectAll.checked = false;

  if (!data || data.length === 0) {
    tbody.innerHTML = `<tr><td colspan="8" class="px-6 py-20 text-center text-gray-500"><i class="fas fa-search text-2xl mb-2"></i><p>Tidak ada data ditemukan</p></td></tr>`;
    updateBulkActionsVisibility();
    return;
  }

  let rowsHtml = "";
  data.forEach((item, index) => {
    // Format tanggal dengan benar dari kolom tanggal
    let tgl = "-";
    if (item.tanggal) {
      tgl = new Date(item.tanggal).toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "long",
        year: "numeric",
      });
    }

    rowsHtml += `
            <tr class="hover:bg-blue-50 transition-colors duration-150 group border-b border-gray-100">
                <td class="px-6 py-4 text-center">
                    <input type="checkbox" value="${item.id}" 
                           onchange="updateBulkActionsVisibility()" 
                           onclick="event.stopPropagation()"
                           class="row-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                </td>
                <td class="px-6 py-4 text-center text-gray-400 font-medium font-mono text-xs">${index + 1}</td>
                <td class="px-6 py-4 cursor-pointer w-48" onclick="openFormModal(${item.id}, event)">
                    <div class="flex flex-col gap-1">
                        <span class="font-bold text-gray-800 text-sm group-hover:text-blue-600 transition-colors truncate" title="${item.mata_kuliah || ""}">${item.mata_kuliah || "-"}</span>
                        <span class="text-xs text-gray-500 flex items-center gap-1 truncate" title="${item.dosen || ""}"><i class="fas fa-user-tie text-[10px] flex-shrink-0"></i> ${item.dosen || "-"}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-center cursor-pointer" onclick="openFormModal(${item.id}, event)">
                    <span class="text-[10px] font-bold px-2 py-0.5 bg-gray-100 text-gray-600 rounded border border-gray-200 uppercase tracking-tight">${item.prodi || "TI"}</span>
                </td>
                <td class="px-6 py-4 cursor-pointer w-40" onclick="openFormModal(${item.id}, event)">
                    <div class="flex flex-col gap-1">
                        <span class="font-bold text-gray-700 text-xs text-center">${tgl}</span>
                        <span class="text-xs text-blue-600 flex items-center justify-center gap-1 font-medium">
                            <i class="far fa-clock text-[10px]"></i> ${item.jam || "-"}
                        </span>
                    </div>
                </td>
                <td class="px-6 py-4 text-center cursor-pointer" onclick="openFormModal(${item.id}, event)">
                    <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-bold border border-blue-100 whitespace-nowrap">${item.kelas || "-"}</span>
                </td>
                <td class="px-6 py-4 text-center cursor-pointer" onclick="openFormModal(${item.id}, event)">
                    <span class="bg-amber-50 text-amber-700 px-2 py-1 rounded text-xs font-bold border border-amber-100 whitespace-nowrap">
                        ${item.frekuensi || "-"}
                    </span>
                </td>
                <td class="px-6 py-4 text-center cursor-pointer" onclick="openFormModal(${item.id}, event)">
                    <span class="bg-purple-50 text-purple-700 px-2 py-1 rounded text-xs font-bold border border-purple-100 whitespace-nowrap">
                        ${item.ruangan || "-"}
                    </span>
                </td>
            </tr>`;
  });
  tbody.innerHTML = rowsHtml;

  // Panggil update visibility setelah DOM terupdate
  updateBulkActionsVisibility();
}

window.updateBulkActionsVisibility = function () {
  const checks = document.querySelectorAll(".row-checkbox");
  const checked = document.querySelectorAll(".row-checkbox:checked").length;
  const selectAll = document.getElementById("selectAll");
  const actions = document.getElementById("bulkActions");
  const countSpan = document.getElementById("selectedCount");

  if (countSpan) countSpan.innerText = `${checked} terpilih`;

  // Sinkronisasi checkbox Select All
  if (selectAll) {
    selectAll.checked = checks.length > 0 && checked === checks.length;
  }

  if (actions) {
    checked > 0
      ? actions.classList.remove("hidden")
      : actions.classList.add("hidden");
  }
};

window.bulkDelete = function () {
  const selected = Array.from(
    document.querySelectorAll(".row-checkbox:checked"),
  ).map((cb) => cb.value);
  if (selected.length === 0) return;

  Swal.fire({
    title: `Hapus ${selected.length} data?`,
    text: "Data yang dipilih akan dihapus permanen!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#ef4444",
    cancelButtonColor: "#64748b",
    confirmButtonText: "Ya, Hapus Semua!",
    cancelButtonText: "Batal",
  }).then(async (result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Sedang menghapus...",
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });

      try {
        // PERBAIKAN: Gunakan window.API_URL
        const response = await fetch(
          `${window.API_URL}/jadwal-upk/delete-multiple`,
          {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ ids: selected }),
          },
        );

        const res = await response.json();
        if (res.status === "success") {
          Swal.fire(
            "Berhasil!",
            res.message || "Data pilihan telah dihapus.",
            "success",
          );
          loadJadwal();
        } else {
          Swal.fire(
            "Gagal",
            res.message || "Terjadi kesalahan saat menghapus data.",
            "error",
          );
        }
      } catch (err) {
        console.error("Delete Error:", err);
        Swal.fire("Error", "Gagal menghubungi server.", "error");
      }
    }
  });
};

// Load Mata Kuliah dan Laboratorium dari API
async function loadDropdownData() {
  try {
    // Load Matakuliah
    // PERBAIKAN: Gunakan window.API_URL
    const mkRes = await fetch(`${window.API_URL}/jadwal-praktikum`);
    const mkData = await mkRes.json();
    const mkSelect = document.getElementById("inputMK");
    const mkMap = {}; // Store MK data for frekuensi lookup

    if (mkData.status === "success" && Array.isArray(mkData.data)) {
      // Get unique mata kuliah
      const uniqueMK = [
        ...new Map(
          mkData.data.map((item) => [item.namaMatakuliah, item]),
        ).values(),
      ];
      uniqueMK.forEach((mk) => {
        const option = document.createElement("option");
        option.value = mk.namaMatakuliah;
        option.textContent = `${mk.kodeMatakuliah || ""} - ${mk.namaMatakuliah}`;
        option.dataset.frekuensi = mk.frekuensi || "";
        mkSelect.appendChild(option);
        mkMap[mk.namaMatakuliah] = mk.frekuensi || "";
      });
    }

    // Load Laboratorium
    // PERBAIKAN: Gunakan window.API_URL
    const labRes = await fetch(`${window.API_URL}/laboratorium`);
    const labData = await labRes.json();
    const labSelect = document.getElementById("inputRuangan");
    if (labData.status === "success" && Array.isArray(labData.data)) {
      labData.data.forEach((lab) => {
        const option = document.createElement("option");
        option.value = lab.nama;
        option.textContent = lab.nama;
        labSelect.appendChild(option);
      });
    }

    // Event listener untuk auto-fill frekuensi (hanya saat form baru)
    mkSelect.addEventListener("change", function () {
      const selectedOption = this.options[this.selectedIndex];
      const frekuensi = selectedOption.dataset.frekuensi || "";
      const currentFreq = document.getElementById("inputFreq").value;
      const id = document.getElementById("inputId").value;

      // Hanya isi frekuensi otomatis jika form baru (tidak edit) dan frekuensi masih kosong
      if (!id && !currentFreq) {
        document.getElementById("inputFreq").value = frekuensi;
      }
    });
  } catch (err) {
    console.error("Error loading dropdown data:", err);
  }
}