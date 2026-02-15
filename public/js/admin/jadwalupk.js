/**
 * Jadwal UPK Admin Orchestrator
 */

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
  const form = document.getElementById("uploadForm");
  if (form) form.reset();

  const fileInfo = document.getElementById("fileInfo");
  if (fileInfo) fileInfo.classList.add("hidden");

  const prog = document.getElementById("uploadProgress");
  if (prog) prog.classList.add("hidden");

  const msg = document.getElementById("uploadMessage");
  if (msg) msg.classList.add("hidden");
};

// Inisialisasi
document.addEventListener("DOMContentLoaded", () => {
  // Gunakan API_URL global jika ada
  if (!window.API_URL) {
    const base = window.BASE_URL || window.location.origin;
    window.API_URL = base + "/api";
  }

  loadDropdownData();
  loadJadwal();

  // Handler Upload
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
      btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Memproses...';
      progressDiv.classList.remove("hidden");
      msgDiv.classList.add("hidden");

      try {
        let progress = 0;
        const interval = setInterval(() => {
          if (progress < 90) {
            progress += 10;
            progressBar.style.width = progress + "%";
            progressPercent.innerText = progress + "%";
          }
        }, 100);

        const response = await fetch(`${window.API_URL}/jadwal-upk/upload`, {
          method: "POST",
          body: formData,
        });

        clearInterval(interval);
        const result = await response.json();

        progressBar.style.width = "100%";
        progressPercent.innerText = "100%";

        if (result.status === "success" || result.status === true) {
          msgDiv.className = "mb-4 p-3 rounded-lg text-sm bg-emerald-50 text-emerald-800 border border-emerald-100";
          msgDiv.innerHTML = `<i class="fas fa-check-circle mr-1"></i> ${result.message}`;
          msgDiv.classList.remove("hidden");

          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: result.message,
            timer: 2000,
            showConfirmButton: false
          });

          setTimeout(() => {
            closeModal("uploadModal");
            loadJadwal();
          }, 1500);
        } else {
          throw new Error(result.message || "Gagal upload file");
        }
      } catch (err) {
        msgDiv.className = "mb-4 p-3 rounded-lg text-sm bg-red-50 text-red-800 border border-red-100";
        msgDiv.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i> ${err.message}`;
        msgDiv.classList.remove("hidden");
        Swal.fire('Gagal!', err.message, 'error');
      } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-upload"></i> Upload & Proses';
      }
    });

    // File selection listener for UI feedback
    const fileInput = document.getElementById("fileExcel");
    const fileInfo = document.getElementById("fileInfo");
    const fileNameDisplay = document.getElementById("fileNameDisplay");
    const fileSizeDisplay = document.getElementById("fileSizeDisplay");

    if (fileInput) {
      fileInput.addEventListener("change", function () {
        if (this.files && this.files.length > 0) {
          const file = this.files[0];
          fileNameDisplay.innerText = file.name;
          fileSizeDisplay.innerText = (file.size / (1024 * 1024)).toFixed(2) + " MB";
          fileInfo.classList.remove("hidden");
        } else {
          fileInfo.classList.add("hidden");
        }
      });
    }
  }

  // Handler Jadwal Form (Add/Edit)
  const jadwalForm = document.getElementById("jadwalForm");
  if (jadwalForm) {
    jadwalForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      const id = formData.get("id");
      const data = Object.fromEntries(formData.entries());

      const url = id
        ? `${window.API_URL}/jadwal-upk/${id}`
        : `${window.API_URL}/jadwal-upk`;

      const method = id ? "PUT" : "POST";

      try {
        const response = await fetch(url, {
          method: method,
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        });

        const result = await response.json();
        if (result.status === "success" || result.status === true) {
          Swal.fire("Berhasil!", result.message, "success");
          closeModal("formModal");
          loadJadwal();
        } else {
          Swal.fire("Gagal", result.message || "Gagal menyimpan data", "error");
        }
      } catch (err) {
        console.error("Submit Error:", err);
        Swal.fire("Error", "Gagal menghubungi server", "error");
      }
    });
  }

  // Search Handler
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("keyup", (e) => {
      const key = e.target.value.toLowerCase();
      const filtered = allJadwalData.filter(i =>
        (i.mata_kuliah && i.mata_kuliah.toLowerCase().includes(key)) ||
        (i.dosen && i.dosen.toLowerCase().includes(key))
      );
      renderTable(filtered);
    });
  }

  // Select All Handler
  const selectAll = document.getElementById("selectAll");
  if (selectAll) {
    selectAll.addEventListener("change", function () {
      const checkboxes = document.querySelectorAll(".row-checkbox");
      checkboxes.forEach((cb) => (cb.checked = this.checked));
      if (typeof window.updateBulkActionsVisibility === "function") {
        window.updateBulkActionsVisibility();
      }
    });
  }
});

// --- LOAD DATA ---
async function loadJadwal() {
  const tbody = document.getElementById("tableBody");
  try {
    const response = await fetch(`${window.API_URL}/jadwal-upk`);
    const result = await response.json();
    if (result.status === "success" || result.status === true) {
      allJadwalData = result.data;
      renderTable(allJadwalData);
    } else {
      allJadwalData = [];
      renderTable([]);
    }
  } catch (err) {
    console.error("API Error:", err);
    if (tbody) {
      tbody.innerHTML = `<tr><td colspan="8" class="text-center text-red-400 py-10 font-bold">Gagal sinkronisasi API: ${err.message}</td></tr>`;
    }
  }
}

async function loadDropdownData() {
  try {
    // Load Matakuliah
    const mkRes = await fetch(`${window.API_URL}/matakuliah`);
    const mkData = await mkRes.json();
    const mkSelect = document.getElementById("inputMK");

    if (mkData.status === "success" || mkData.status === true) {
      mkSelect.innerHTML = '<option value="">-- Pilih Mata Kuliah --</option>';
      const uniqueMK = mkData.data ? [...new Map(mkData.data.map(item => [item.namaMatakuliah, item])).values()] : [];

      uniqueMK.forEach(mk => {
        const option = document.createElement("option");
        option.value = mk.namaMatakuliah;
        option.textContent = `${mk.kodeMatakuliah || ""} - ${mk.namaMatakuliah}`;
        option.dataset.frekuensi = mk.frekuensi || "";
        mkSelect.appendChild(option);
      });
    }

    // Load Ruangan/Fasilitas
    const labRes = await fetch(`${window.API_URL}/fasilitas`);
    const labData = await labRes.json();
    const labSelect = document.getElementById("inputRuangan");

    if (labData.status === "success" || labData.status === true) {
      labSelect.innerHTML = '<option value="">-- Pilih Ruangan --</option>';
      if (Array.isArray(labData.data)) {
        labData.data.forEach(lab => {
          const option = document.createElement("option");
          option.value = lab.nama;
          option.textContent = lab.nama;
          labSelect.appendChild(option);
        });
      }
    }

    // Auto-fill frekuensi listener
    mkSelect.addEventListener("change", function () {
      const selectedOption = this.options[this.selectedIndex];
      const frekuensi = selectedOption.dataset.frekuensi || "";
      const freqInput = document.getElementById("inputFreq");
      const idInput = document.getElementById("inputId");

      if (freqInput && (!idInput.value)) {
        freqInput.value = frekuensi;
      }
    });

  } catch (err) {
    console.error("Error loading dropdown data:", err);
  }
}

function renderTable(data) {
  const tbody = document.getElementById("tableBody");
  const totalData = document.getElementById("totalData");

  if (totalData) totalData.innerText = `Total: ${data.length}`;

  const selectAll = document.getElementById("selectAll");
  if (selectAll) selectAll.checked = false;

  if (!data || data.length === 0) {
    if (tbody) {
      tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-20 text-center text-gray-500"><i class="fas fa-search text-2xl mb-2"></i><p>Tidak ada data ditemukan</p></td></tr>`;
    }
    updateBulkActionsVisibility();
    return;
  }

  let rowsHtml = "";
  data.forEach((item, index) => {
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
                    <span class="text-[10px] font-bold px-2 py-0.5 bg-gray-100 text-gray-600 rounded border border-gray-200 uppercase tracking-tight">${item.prodi || "-"}</span>
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
                    <span class="bg-emerald-50 text-emerald-700 px-2 py-1 rounded text-xs font-bold border border-emerald-100 whitespace-nowrap">
                        ${item.ruangan || "-"}
                    </span>
                </td>
            </tr>`;
  });
  if (tbody) {
    tbody.innerHTML = rowsHtml;
  }
  updateBulkActionsVisibility();
}

window.openFormModal = function (id = null, event = null) {
  if (event) event.stopPropagation();
  openModal("formModal");
  const title = document.getElementById("formModalTitle");
  const form = document.getElementById("jadwalForm");
  form.reset();
  document.getElementById("inputId").value = "";

  if (id) {
    title.innerHTML = '<i class="fas fa-edit text-blue-600 mr-2"></i> Edit Jadwal UPK';
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
    title.innerHTML = '<i class="fas fa-plus text-blue-600 mr-2"></i> Tambah Jadwal Baru';
  }
};

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
        const response = await fetch(`${window.API_URL}/jadwal-upk/${id}`, {
          method: "DELETE",
        });
        const res = await response.json();
        if (res.status === "success" || res.status === true) {
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

window.updateBulkActionsVisibility = function () {
  const checks = document.querySelectorAll(".row-checkbox");
  const checked = document.querySelectorAll(".row-checkbox:checked").length;
  const selectAll = document.getElementById("selectAll");
  const actions = document.getElementById("bulkActions");
  const countSpan = document.getElementById("selectedCount");

  if (countSpan) countSpan.innerText = `${checked} terpilih`;

  if (selectAll) {
    selectAll.checked = checks.length > 0 && checked === checks.length;
  }

  if (actions) {
    checked > 0 ? actions.classList.remove("hidden") : actions.classList.add("hidden");
  }
};

window.bulkDelete = function () {
  const selected = Array.from(document.querySelectorAll(".row-checkbox:checked")).map((cb) => cb.value);
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
        didOpen: () => { Swal.showLoading(); },
      });

      try {
        const response = await fetch(`${window.API_URL}/jadwal-upk/delete-multiple`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ ids: selected }),
        });

        const res = await response.json();
        if (res.status === "success" || res.status === true) {
          Swal.fire("Berhasil!", res.message || "Data pilihan telah dihapus.", "success");
          loadJadwal();
        } else {
          Swal.fire("Gagal", res.message || "Terjadi kesalahan saat menghapus data.", "error");
        }
      } catch (err) {
        console.error("Delete Error:", err);
        Swal.fire("Error", "Gagal menghubungi server.", "error");
      }
    }
  });
};