window.BASE_URL = "<?= BASE_URL ?>";
let allMatakuliahData = [];

document.addEventListener("DOMContentLoaded", function () {
  loadMatakuliah();

  // Live Search Listener
  document
    .getElementById("searchInput")
    .addEventListener("keyup", function (e) {
      const keyword = e.target.value.toLowerCase();
      const filtered = allMatakuliahData.filter(
        (item) =>
          (item.namaMatakuliah &&
            item.namaMatakuliah.toLowerCase().includes(keyword)) ||
          (item.kodeMatakuliah &&
            item.kodeMatakuliah.toLowerCase().includes(keyword)),
      );
      renderTable(filtered);
    });
});

// --- 1. LOAD DATA ---
function loadMatakuliah() {
  fetch("/api/matakuliah")
    .then((res) => res.json())
    .then((res) => {
      if ((res.status === "success" || res.code === 200) && res.data) {
        allMatakuliahData = res.data;
        renderTable(allMatakuliahData);
      } else {
        renderTable([]);
      }
    })
    .catch((err) => {
      console.error(err);
      document.getElementById("tableBody").innerHTML =
        `<tr><td colspan="6" class="px-6 py-12 text-center text-red-500">Gagal memuat data</td></tr>`;
    });
}

function renderTable(data) {
  const tbody = document.getElementById("tableBody");
  const totalEl = document.getElementById("totalData");
  tbody.innerHTML = "";
  totalEl.innerText = `Total: ${data.length}`;

  if (data.length === 0) {
    tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-search text-2xl mb-2"></i><p>Tidak ada data ditemukan</p></td></tr>`;
    return;
  }

  let rowsHtml = "";
  data.forEach((item, index) => {
    const semesterVal = item.semester || item.smt || item.Semester || "-";
    const sksVal = item.sksKuliah || item.sks || item.SKS || "-";
    const semColor =
      semesterVal !== "-" && semesterVal % 2 !== 0
        ? "bg-orange-100 text-orange-800 border-orange-200"
        : "bg-purple-100 text-purple-800 border-purple-200";

    rowsHtml += `
            <tr onclick="openFormModal(${item.idMatakuliah}, event)" class="hover:bg-blue-50 transition-colors duration-150 group border-b border-gray-100 cursor-pointer">
                <td class="px-6 py-4 text-center font-medium text-gray-500">${index + 1}</td>
                <td class="px-6 py-4 text-center">
                    <span class="font-mono text-sm bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-200 font-semibold">${item.kodeMatakuliah || "-"}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="font-bold text-gray-800 text-sm block">${item.namaMatakuliah || "-"}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="${semColor} px-2.5 py-1 rounded-full text-xs font-semibold border whitespace-nowrap">Sem ${semesterVal}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center justify-center bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold border border-gray-200 whitespace-nowrap gap-1">
                        <i class="fas fa-layer-group text-gray-400 text-[10px]"></i> 
                        ${sksVal} SKS
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center items-center gap-2">
                        <button onclick="openFormModal(${item.idMatakuliah}, event)" class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-all shadow-sm flex items-center justify-center border border-transparent" title="Edit">
                            <i class="fas fa-pen text-xs"></i>
                        </button>
                        <button onclick="hapusMatakuliah(${item.idMatakuliah}, event)" class="w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-all shadow-sm flex items-center justify-center border border-transparent" title="Hapus">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </td>
            </tr>`;
  });
  tbody.innerHTML = rowsHtml;
}

// --- 2. MODAL FORM ---
function openFormModal(id = null, event = null) {
  if (event) event.stopPropagation();
  document.getElementById("formModal").classList.remove("hidden");
  document.body.style.overflow = "hidden";
  document.getElementById("formMessage").classList.add("hidden");
  document.getElementById("matkulForm").reset();
  document.getElementById("inputId").value = "";

  if (id) {
    document.getElementById("formModalTitle").innerHTML =
      '<i class="fas fa-edit text-blue-600"></i> Edit Mata Kuliah';
    document.getElementById("btnSave").innerHTML =
      '<i class="fas fa-save"></i> Update Data';

    const data = allMatakuliahData.find((i) => i.idMatakuliah == id);
    if (data) {
      document.getElementById("inputId").value = data.idMatakuliah;
      document.getElementById("inputKode").value = data.kodeMatakuliah;
      document.getElementById("inputNama").value = data.namaMatakuliah;

      const sksVal = data.sksKuliah || data.sks || "";
      const semVal = data.semester || data.smt || "";

      document.getElementById("inputSks").value = sksVal;
      document.getElementById("inputSemester").value = semVal;
    }
  } else {
    document.getElementById("formModalTitle").innerHTML =
      '<i class="fas fa-plus text-emerald-600"></i> Tambah Mata Kuliah';
    document.getElementById("btnSave").innerHTML =
      '<i class="fas fa-save"></i> Simpan Data';
  }
}

// --- 3. SUBMIT FORM (JSON MODE) ---
document.getElementById("matkulForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const btn = document.getElementById("btnSave");
  const msg = document.getElementById("formMessage");

  const id = document.getElementById("inputId").value;
  const url = id ? "/api/matakuliah/" + id : "/api/matakuliah";
  const method = id ? "PUT" : "POST";

  const formData = new FormData(this);
  const dataObj = Object.fromEntries(formData.entries());

  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
  showLoading("Menyimpan mata kuliah...");

  fetch(url, {
    method: method,
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(dataObj),
  })
    .then((res) => res.json())
    .then((data) => {
      hideLoading();
      if (data.status === "success" || data.code === 200 || data.code === 201) {
        closeModal("formModal");
        loadMatakuliah();
        showSuccess(
          id
            ? "Mata kuliah berhasil diperbarui!"
            : "Mata kuliah baru berhasil ditambahkan!",
        );
      } else {
        throw new Error(data.message || "Gagal menyimpan data");
      }
    })
    .catch((err) => {
      hideLoading();
      msg.innerHTML = `<div class="bg-red-50 text-red-800 p-3 rounded text-sm mb-4 border border-red-200 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> ${err.message}</div>`;
      msg.classList.remove("hidden");
      showError(err.message);
    })
    .finally(() => {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
    });
});

// --- HELPER FUNCTIONS ---
function closeModal(modalId) {
  document.getElementById(modalId).classList.add("hidden");
  document.body.style.overflow = "auto";
}

function hapusMatakuliah(id, event) {
  if (event) event.stopPropagation();
  confirmDelete(() => {
    showLoading("Menghapus data...");
    fetch("/api/matakuliah/" + id, { method: "DELETE" })
      .then((res) => res.json())
      .then(() => {
        hideLoading();
        loadMatakuliah();
        showSuccess("Mata kuliah berhasil dihapus!");
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
  }
};
