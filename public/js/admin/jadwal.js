// Pastikan BASE_URL dan API_URL sudah didefinisikan di layout utama/header
// window.BASE_URL = '...';

let allJadwalData = [];

document.addEventListener("DOMContentLoaded", function () {
  loadJadwal();

  // Live Search
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("keyup", function (e) {
      const keyword = e.target.value.toLowerCase();
      const filtered = allJadwalData.filter(
        (item) =>
          (item.namaMatakuliah &&
            item.namaMatakuliah.toLowerCase().includes(keyword)) ||
          (item.namaLab && item.namaLab.toLowerCase().includes(keyword)) ||
          (item.hari && item.hari.toLowerCase().includes(keyword)) ||
          (item.kelas && item.kelas.toLowerCase().includes(keyword)),
      );
      renderTable(filtered);
    });
  }

  // File Input Handler (Untuk Preview Nama File)
  const fileInput = document.getElementById("fileExcel");
  if (fileInput) {
    fileInput.addEventListener("change", function (e) {
      const fileName = e.target.files[0] ? e.target.files[0].name : "";
      const fileSize = e.target.files[0]
        ? (e.target.files[0].size / 1024 / 1024).toFixed(2)
        : 0;

      const displayDate = document.getElementById("fileNameDisplay");
      const displaySize = document.getElementById("fileSizeDisplay");
      const infoBox = document.getElementById("fileInfo");

      if (fileName) {
        displayDate.textContent = fileName;
        displaySize.textContent = fileSize + " MB";
        infoBox.classList.remove("hidden");
      } else {
        infoBox.classList.add("hidden");
      }
    });
  }
});

// --- 1. LOAD DATA ---
function loadJadwal() {
  fetch(API_URL + "/jadwal")
    .then((res) => res.json())
    .then((res) => {
      if ((res.status === "success" || res.code === 200) && res.data) {
        allJadwalData = res.data;
        renderTable(allJadwalData);
      } else {
        renderTable([]);
      }
    })
    .catch((err) => {
      console.error(err);
      document.getElementById("tableBody").innerHTML =
        `<tr><td colspan="8" class="px-6 py-12 text-center text-red-500">Gagal memuat data: ${err.message}</td></tr>`;
    });
}

function renderTable(data) {
  const tbody = document.getElementById("tableBody");
  const totalEl = document.getElementById("totalData");
  tbody.innerHTML = "";

  // Reset Select All
  const selectAll = document.getElementById("selectAll");
  if (selectAll) selectAll.checked = false;

  if (totalEl) totalEl.innerText = `Total: ${data.length}`;

  if (!data || data.length === 0) {
    tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-search text-2xl mb-2"></i><p>Tidak ada data ditemukan</p></td></tr>`;
    updateBulkActionsVisibility();
    return;
  }

  let rowsHtml = "";
  data.forEach((item, index) => {
    const statusClass =
      item.status === "Aktif"
        ? "bg-emerald-100 text-emerald-700 border-emerald-200"
        : "bg-red-100 text-red-700 border-red-200";

    const waktuMulai = item.waktuMulai
      ? item.waktuMulai.substring(0, 5)
      : "--:--";
    const waktuSelesai = item.waktuSelesai
      ? item.waktuSelesai.substring(0, 5)
      : "--:--";

    rowsHtml += `
            <tr class="hover:bg-blue-50 transition-colors duration-150 group border-b border-gray-100">
                <td class="px-6 py-4 text-center">
                    <input type="checkbox" name="selectedIds[]" value="${item.idJadwal}" 
                           onchange="updateBulkActionsVisibility()"
                           onclick="event.stopPropagation()"
                           class="row-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                </td>
                <td class="px-6 py-4 text-center font-medium text-gray-500">${index + 1}</td>
                <td class="px-6 py-4 cursor-pointer" onclick="openFormModal(${item.idJadwal}, event)">
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-800 text-sm group-hover:text-blue-600 transition-colors">${item.namaMatakuliah || "-"}</span>
                        <span class="text-xs text-gray-400 font-mono mt-0.5">${item.kodeMatakuliah || "-"}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600 text-sm font-medium cursor-pointer" onclick="openFormModal(${item.idJadwal}, event)">${item.namaLab || "-"}</td>
                <td class="px-6 py-4 cursor-pointer min-w-[160px]" onclick="openFormModal(${item.idJadwal}, event)">
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-700 text-sm">${item.hari || "-"}</span>
                        <span class="text-xs text-gray-500 flex items-center gap-1">
                            <i class="far fa-clock"></i> ${waktuMulai} - ${waktuSelesai}
                        </span>
                    </div>
                </td>
                
                <!-- Kolom Asisten -->
                <td class="px-6 py-4 cursor-pointer" onclick="openFormModal(${item.idJadwal}, event)">
                    <div class="flex flex-col gap-2">
                        ${renderAsistenBadge(item.namaAsisten1, item.fotoAsisten1, 1)}
                        ${renderAsistenBadge(item.namaAsisten2, item.fotoAsisten2, 2)}
                    </div>
                </td>

                <td class="px-6 py-4 text-center cursor-pointer" onclick="openFormModal(${item.idJadwal}, event)">
                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-bold border border-gray-200">${item.kelas || "-"}</span>
                </td>
                <td class="px-6 py-4 text-center cursor-pointer" onclick="openFormModal(${item.idJadwal}, event)">
                    <span class="${statusClass} px-2.5 py-1 rounded-full text-xs font-semibold border">${item.status || "Nonaktif"}</span>
                </td>
            </tr>`;
  });
  tbody.innerHTML = rowsHtml;

  updateBulkActionsVisibility();
}

// --- HELPER RENDER ---
function renderAsistenBadge(nama, foto, num) {
  if (!nama) return `<span class="text-xs text-gray-400 italic">- Kosong -</span>`;

  // Resolve URL Foto
  let imgUrl = getFotoUrl(nama, foto);

  // Warna badge beda untuk asisten 1 vs 2
  const colorClass = num === 1 ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-purple-50 text-purple-700 border-purple-100';

  return `
    <div class="flex items-center gap-2 ${colorClass} px-2 py-1.5 rounded-lg border max-w-fit">
        <span class="text-xs font-semibold truncate max-w-[150px]" title="${nama}">${nama}</span>
    </div>`;
}

function getFotoUrl(nama, foto) {
  if (foto && foto.includes('http')) return foto;
  if (foto) return `${BASE_URL}/assets/uploads/${foto}`;
  // Default Avatar
  return `https://ui-avatars.com/api/?name=${encodeURIComponent(nama)}&background=random&color=fff&size=64`;
}

// --- BULK ACTION HELPERS ---
function updateBulkActionsVisibility() {
  const checkboxes = document.querySelectorAll(".row-checkbox:checked");
  const bulkActions = document.getElementById("bulkActions");
  const countSpan = document.getElementById("selectedCount");

  if (checkboxes.length > 0) {
    bulkActions.classList.remove("hidden");
    countSpan.innerText = `${checkboxes.length} terpilih`;
  } else {
    bulkActions.classList.add("hidden");
  }
}

// Event Listener Select All
document.addEventListener("DOMContentLoaded", function () {
  const selectAll = document.getElementById("selectAll");
  if (selectAll) {
    selectAll.addEventListener("change", function () {
      const checkboxes = document.querySelectorAll(".row-checkbox");
      checkboxes.forEach((cb) => (cb.checked = this.checked));
      updateBulkActionsVisibility();
    });
  }
});

function bulkDelete() {
  const checkboxes = document.querySelectorAll(".row-checkbox:checked");
  const ids = Array.from(checkboxes).map((cb) => cb.value);

  if (ids.length === 0) return;

  confirmDelete(() => {
    showLoading(`Menghapus ${ids.length} data...`);
    fetch(API_URL + "/jadwal/delete-multiple", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ ids: ids }),
    })
      .then((res) => res.json())
      .then((data) => {
        hideLoading();
        if (data.status === "success") {
          loadJadwal();
          showSuccess(`${ids.length} data berhasil dihapus!`);
        } else {
          throw new Error(data.message || "Gagal menghapus data");
        }
      })
      .catch((err) => {
        hideLoading();
        showError(err.message || "Gagal menghapus data terpilih");
      });
  }, `Apakah Anda yakin ingin menghapus ${ids.length} data yang dipilih?`);
}

// --- 2. MODAL FORM & OPTIONS ---
function openFormModal(id = null, event = null) {
  if (event) event.stopPropagation();
  document.getElementById("formModal").classList.remove("hidden");
  document.body.style.overflow = "hidden";
  document.getElementById("formMessage").classList.add("hidden");
  document.getElementById("jadwalForm").reset();
  document.getElementById("inputId").value = "";

  // Load Dropdown Options (MK, Lab, Asisten)
  Promise.all([
    fetch(API_URL + "/matakuliah").then((r) => r.json()),
    fetch(API_URL + "/laboratorium").then((r) => r.json()),
    fetch(API_URL + "/asisten")
      .then((r) => r.json())
      .catch(() => ({ data: [] })),
  ]).then(([mkData, labData, asistenData]) => {
    // Isi Select MK
    const mkSelect = document.getElementById("inputMatakuliah");
    mkSelect.innerHTML = '<option value="">-- Pilih MK --</option>';
    if (mkData.data) {
      mkData.data.forEach((m) => {
        mkSelect.innerHTML += `<option value="${m.idMatakuliah}">${m.kodeMatakuliah} - ${m.namaMatakuliah}</option>`;
      });
    }

    // Isi Select Lab
    const labSelect = document.getElementById("inputLab");
    labSelect.innerHTML = '<option value="">-- Pilih Lab --</option>';
    if (labData.data) {
      labData.data.forEach((l) => {
        labSelect.innerHTML += `<option value="${l.idLaboratorium}">${l.nama}</option>`;
      });
    }

    // Filter Asisten 1 (statusAktif = 'Asisten')
    const asisten1List = asistenData.data
      ? asistenData.data.filter((a) => a.statusAktif === "Asisten")
      : [];
    const asisten1Select = document.getElementById("inputAsisten1");
    asisten1Select.innerHTML =
      '<option value="">-- Pilih Asisten 1 --</option>';
    asisten1List.forEach((a) => {
      asisten1Select.innerHTML += `<option value="${a.idAsisten}">${a.nama}</option>`;
    });

    // Filter Asisten 2 (statusAktif = 'CA')
    const asisten2List = asistenData.data
      ? asistenData.data.filter((a) => a.statusAktif === "CA")
      : [];
    const asisten2Select = document.getElementById("inputAsisten2");
    asisten2Select.innerHTML =
      '<option value="">-- Pilih Asisten 2 --</option>';
    asisten2List.forEach((a) => {
      asisten2Select.innerHTML += `<option value="${a.idAsisten}">${a.nama}</option>`;
    });

    // Jika Mode Edit, Isi Data
    if (id) {
      document.getElementById("formModalTitle").innerHTML =
        '<i class="fas fa-edit text-blue-600"></i> Edit Jadwal';
      document.getElementById("btnSave").innerHTML =
        '<i class="fas fa-save"></i> Update Jadwal';

      const data = allJadwalData.find((i) => i.idJadwal == id);
      if (data) {
        document.getElementById("inputId").value = data.idJadwal;
        document.getElementById("inputMatakuliah").value = data.idMatakuliah;
        document.getElementById("inputLab").value = data.idLaboratorium;
        document.getElementById("inputHari").value = data.hari;
        document.getElementById("inputKelas").value = data.kelas;
        document.getElementById("inputMulai").value = data.waktuMulai;
        document.getElementById("inputSelesai").value = data.waktuSelesai;
        document.getElementById("inputFrekuensi").value = data.frekuensi || "";
        document.getElementById("inputDosen").value = data.dosen || "";
        document.getElementById("inputAsisten1").value = data.idAsisten1 || "";
        document.getElementById("inputAsisten2").value = data.idAsisten2 || "";

        // Radio Status
        const radios = document.getElementsByName("status");
        for (let r of radios) {
          if (r.value === data.status) r.checked = true;
        }
      }
    } else {
      document.getElementById("formModalTitle").innerHTML =
        '<i class="fas fa-plus text-emerald-600"></i> Tambah Jadwal Baru';
      document.getElementById("btnSave").innerHTML =
        '<i class="fas fa-save"></i> Simpan Jadwal';
    }
  });
}

// --- SUBMIT FORM TAMBAH/EDIT ---
document.getElementById("jadwalForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const btn = document.getElementById("btnSave");
  const msg = document.getElementById("formMessage");

  const id = document.getElementById("inputId").value;
  const url = id ? API_URL + "/jadwal/" + id : API_URL + "/jadwal";
  const method = id ? "PUT" : "POST";

  const formData = new FormData(this);
  const dataObj = Object.fromEntries(formData.entries());

  if (!dataObj.idMatakuliah || !dataObj.idLaboratorium) {
    alert("Mohon pilih Mata Kuliah dan Laboratorium");
    return;
  }

  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';

  console.log("Form Data:", dataObj);
  console.log("Request URL:", url);
  console.log("Method:", method);

  fetch(url, {
    method: method,
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(dataObj),
  })
    .then((res) => {
      console.log("Response Status:", res.status);
      return res.json();
    })
    .then((data) => {
      console.log("Response Data:", data);
      hideLoading();
      if (
        data.status === true ||
        data.status === "success" ||
        data.code === 200 ||
        data.code === 201
      ) {
        closeModal("formModal");
        loadJadwal();
        showSuccess(
          id
            ? "Jadwal berhasil diperbarui!"
            : "Jadwal baru berhasil ditambahkan!",
        );
      } else {
        throw new Error(data.message || "Gagal menyimpan data");
      }
    })
    .catch((err) => {
      hideLoading();
      console.error(err);
      msg.innerHTML = `<div class="bg-red-50 text-red-800 p-3 rounded text-sm mb-4 border border-red-200 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> ${err.message}</div>`;
      msg.classList.remove("hidden");
      showError(err.message);
    })
    .finally(() => {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-save"></i> Simpan Jadwal';
    });
});

// --- 3. MODAL UPLOAD ---
function openUploadModal() {
  document.getElementById("uploadModal").classList.remove("hidden");
  document.body.style.overflow = "hidden";

  // Reset Form UI
  document.getElementById("uploadForm").reset();
  document.getElementById("fileInfo").classList.add("hidden");
  document.getElementById("uploadProgress").classList.add("hidden");
  document.getElementById("uploadMessage").classList.add("hidden");
  document.getElementById("btnUpload").disabled = false;
  document.getElementById("btnUpload").innerHTML =
    '<i class="fas fa-upload"></i> Upload & Proses';
}

// Handler Submit Form Upload (PERBAIKAN UTAMA: DIJADIKAN SATU)
document
  .getElementById("uploadForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const fileInputEl = document.getElementById("fileExcel");
    if (!fileInputEl || fileInputEl.files.length === 0) {
      alert("Pilih file terlebih dahulu!");
      return;
    }

    const file = fileInputEl.files[0];

    // UI Elements
    const btn = document.getElementById("btnUpload");
    const msgDiv = document.getElementById("uploadMessage");
    const progressDiv = document.getElementById("uploadProgress");
    const progressBar = document.getElementById("progressBar");
    const progressText = document.getElementById("progressText");
    const progressPercent = document.getElementById("progressPercent");

    // Reset UI State
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Memproses...';
    msgDiv.classList.add("hidden");
    progressDiv.classList.remove("hidden");
    progressBar.style.width = "0%";

    const formData = new FormData();
    formData.append("excel_file", file);

    try {
      // Simulasi Progress
      let progress = 0;
      const interval = setInterval(() => {
        if (progress < 90) {
          progress += Math.random() * 10;
          const p = Math.min(progress, 90).toFixed(0);
          progressBar.style.width = p + "%";
          progressPercent.innerText = p + "%";
        }
      }, 300);

      // Request ke API
      const response = await fetch(API_URL + "/jadwal-praktikum/upload", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      clearInterval(interval);
      progressBar.style.width = "100%";
      progressPercent.innerText = "100%";
      progressText.innerText = "Selesai!";

      if (result.status === "success" || result.code === 200) {
        msgDiv.className =
          "mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-800";
        let successMsg = `<div class="flex items-center gap-2 mb-1 font-bold text-emerald-700"><i class="fas fa-check-circle text-lg"></i> Upload Berhasil!</div>`;
        successMsg += `<p>${result.message}</p>`;

        if (
          result.data &&
          result.data.errors &&
          result.data.errors.length > 0
        ) {
          successMsg += `<div class="mt-3 pt-3 border-t border-emerald-200">
                    <p class="font-bold text-xs text-orange-600 mb-1">Peringatan (${result.data.errors.length} data dilewati):</p>
                    <ul class="list-disc ml-4 text-xs text-orange-700 max-h-24 overflow-y-auto custom-scrollbar">`;
          result.data.errors.forEach(
            (err) => (successMsg += `<li>${err}</li>`),
          );
          successMsg += `</ul></div>`;
        }

        msgDiv.innerHTML = successMsg;
        msgDiv.classList.remove("hidden");

        setTimeout(() => {
          closeModal("uploadModal");
          loadJadwal();
        }, 3000);
      } else {
        throw new Error(result.message || "Gagal upload");
      }
    } catch (error) {
      console.error("Upload Error:", error);

      msgDiv.className =
        "mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800";
      msgDiv.innerHTML = `
            <div class="flex items-center gap-2 mb-2 font-bold text-red-700"><i class="fas fa-exclamation-triangle"></i> Upload Gagal</div>
            <p>${error.message}</p>
        `;
      msgDiv.classList.remove("hidden");
      progressText.innerText = "Gagal";
      progressBar.classList.add("bg-red-500");
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-upload"></i> Upload & Proses';
    }
  });

// --- HELPER ---
function closeModal(modalId) {
  document.getElementById(modalId).classList.add("hidden");
  document.body.style.overflow = "auto";
}

function hapusJadwal(id, event) {
  if (event) event.stopPropagation();
  confirmDelete(() => {
    showLoading("Menghapus data...");
    fetch(API_URL + "/jadwal/" + id, { method: "DELETE" })
      .then((res) => res.json())
      .then(() => {
        hideLoading();
        loadJadwal();
        showSuccess("Jadwal berhasil dihapus!");
      })
      .catch((err) => {
        hideLoading();
        showError("Gagal menghapus data");
      });
  });
}

document.onkeydown = function (evt) {
  if (evt.keyCode == 27) {
    closeModal("formModal");
    closeModal("uploadModal");
  }
};
