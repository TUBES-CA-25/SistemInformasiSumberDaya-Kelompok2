let allManajemenData = [];

document.addEventListener("DOMContentLoaded", function () {
  loadManajemen();

  // Live Search
  document
    .getElementById("searchInput")
    .addEventListener("keyup", function (e) {
      const searchTerm = e.target.value.toLowerCase();
      filterTable(searchTerm);
    });
});

// --- 1. LOAD DATA ---
function loadManajemen() {
  const tbody = document.getElementById("tableBody");
  tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-circle-notch fa-spin text-2xl mb-2"></i><p>Memuat data...</p></td></tr>`;

  fetch("/api/manajemen")
    .then((res) => res.json())
    .then((response) => {
      if (
        (response.status === true || response.status === "success" || response.code === 200) &&
        response.data
      ) {
        allManajemenData = response.data;
        renderTable(allManajemenData);
      } else {
        renderTable([]);
      }
    })
    .catch((err) => {
      console.error(err);
      tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-red-500">Gagal memuat data</td></tr>`;
    });
}

function renderTable(data) {
  const tbody = document.getElementById("tableBody");
  const totalEl = document.getElementById("totalData");

  tbody.innerHTML = "";
  totalEl.innerText = `Total: ${data.length}`;

  if (data.length === 0) {
    tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">Data tidak ditemukan</td></tr>`;
    return;
  }

  let rowsHtml = "";
  data.forEach((item, index) => {
    // 1. Buat URL Avatar Default (Inisial Nama) sebagai cadangan
    const defaultAvatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(item.nama)}&background=random&color=fff&size=128`;

    // 2. Tentukan URL Foto Utama
    // Jika ada foto di DB, pakai path lokal. Jika tidak, pakai default.
    let photoUrl = item.foto
      ? `${BASE_URL}/assets/uploads/${item.foto}`
      : defaultAvatar;

    rowsHtml += `
            <tr onclick="openDetailModal(${item.idManajemen})" class="hover:bg-blue-50 transition-colors duration-150 group border-b border-gray-100 cursor-pointer">
                <td class="px-6 py-4 text-center font-medium text-gray-500">${index + 1}</td>
                <td class="px-6 py-4 text-center">
                    <img src="${photoUrl}" 
                         class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm inline-block bg-gray-100"
                         alt="${item.nama}"
                         onerror="this.onerror=null; this.src='${defaultAvatar}';">
                </td>
                <td class="px-6 py-4">
                    <span class="font-bold text-gray-800 text-sm block group-hover:text-blue-600 transition-colors">${escapeHtml(item.nama)}</span>
                    <div class="flex flex-col gap-0.5 mt-1">
                        <span class="text-xs text-blue-600 flex items-center gap-1.5 font-medium">
                            <i class="far fa-envelope text-[10px]"></i> ${escapeHtml(item.email || "-")}
                        </span>
                        ${item.nidn
        ? `
                        <span class="text-[10px] text-gray-500 flex items-center gap-1.5">
                            <i class="far fa-id-card text-[10px]"></i> ${escapeHtml(item.nidn)}
                        </span>`
        : ""
      }
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                        ${escapeHtml(item.jabatan)}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center items-center gap-2">
                        <button onclick="openFormModal(${item.idManajemen}, event)" class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-all shadow-sm flex items-center justify-center border border-transparent" title="Edit">
                            <i class="fas fa-pen text-xs"></i>
                        </button>
                        <button onclick="hapusManajemen(${item.idManajemen}, event)" class="w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-all shadow-sm flex items-center justify-center border border-transparent" title="Hapus">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
  });
  tbody.innerHTML = rowsHtml;
}

// --- 2. MODAL DETAIL ---
function openDetailModal(id) {
  const data = allManajemenData.find((i) => i.idManajemen == id);
  if (!data) return;

  const modal = document.getElementById("detailModal");
  modal.classList.remove("hidden");
  document.body.style.overflow = "hidden";

  document.getElementById("detailNama").innerText = data.nama;
  document.getElementById("detailEmail").innerText = data.email || "";
  document.getElementById("detailNidn").innerText = data.nidn
    ? `NIDN: ${data.nidn}`
    : "NIDN: -";
  document.getElementById("detailJabatan").innerText = data.jabatan;
  document.getElementById("detailTentang").innerText =
    data.tentang || "Belum ada informasi tambahan.";

  // Logika Gambar Detail
  const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(data.nama)}&background=random&color=fff&size=256`;
  const fotoUrl = data.foto
    ? `${BASE_URL}/assets/uploads/${data.foto}`
    : avatarUrl;

  const imgEl = document.getElementById("detailFoto");
  imgEl.src = fotoUrl;
  imgEl.onerror = function () {
    this.onerror = null;
    this.src = avatarUrl;
  };
}

// --- 3. MODAL FORM ---
function openFormModal(id = null, event = null) {
  if (event) event.stopPropagation();

  const modal = document.getElementById("formModal");
  const form = document.getElementById("manajemenForm");
  const title = document.getElementById("formModalTitle");
  const btnSave = document.getElementById("btnSave");

  // Reset Image Preview
  const previewEl = document.getElementById("previewFoto");
  const placeholderEl = document.getElementById("placeholderFoto");
  previewEl.classList.add("hidden");
  placeholderEl.classList.remove("hidden");
  previewEl.src = "";

  modal.classList.remove("hidden");
  document.body.style.overflow = "hidden";
  document.getElementById("formMessage").classList.add("hidden");
  form.reset();
  document.getElementById("inputId").value = "";

  if (id) {
    title.innerHTML = '<i class="fas fa-edit text-blue-600"></i> Edit Anggota';
    btnSave.innerHTML = '<i class="fas fa-save"></i> Update Data';

    const data = allManajemenData.find((i) => i.idManajemen == id);
    if (data) {
      document.getElementById("inputId").value = data.idManajemen;
      document.getElementById("inputNama").value = data.nama;
      document.getElementById("inputEmail").value = data.email || "";
      document.getElementById("inputNidn").value = data.nidn || "";
      document.getElementById("inputJabatan").value = data.jabatan;
      document.getElementById("inputTentang").value = data.tentang || "";

      // Logic Preview saat Edit
      if (data.foto) {
        const fotoUrl = `${BASE_URL}/assets/uploads/${data.foto}`;
        previewEl.src = fotoUrl;
        previewEl.classList.remove("hidden");
        placeholderEl.classList.add("hidden");

        // Fallback jika gambar edit juga 404
        previewEl.onerror = function () {
          // Jika error, kita sembunyikan preview dan tampilkan placeholder saja
          previewEl.classList.add("hidden");
          placeholderEl.classList.remove("hidden");
        };
      }
    }
  } else {
    title.innerHTML =
      '<i class="fas fa-user-plus text-emerald-600"></i> Tambah Anggota';
    btnSave.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
  }
}

// Submit Form
document
  .getElementById("manajemenForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    const btn = document.getElementById("btnSave");
    const msg = document.getElementById("formMessage");
    const id = document.getElementById("inputId").value;
    const url = id ? "/api/manajemen/" + id : "/api/manajemen";

    const formData = new FormData(this);
    if (id) formData.append("_method", "PUT"); // Trik untuk update file di PHP

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';

    fetch(url, { method: "POST", body: formData })
      .then((res) => res.json())
      .then((data) => {
        hideLoading();
        if (
          data.status === "success" ||
          data.code === 200 ||
          data.code === 201
        ) {
          closeModal("formModal");
          loadManajemen();
          showSuccess(
            id
              ? "Data anggota berhasil diperbarui!"
              : "Anggota baru berhasil ditambahkan!",
          );
        } else {
          throw new Error(data.message || "Gagal menyimpan data");
        }
      })
      .catch((err) => {
        hideLoading();
        msg.innerHTML = `<div class="bg-red-50 text-red-800 p-3 rounded text-sm mb-4 border border-red-200"><i class="fas fa-exclamation-circle"></i> ${err.message}</div>`;
        msg.classList.remove("hidden");
        showError(err.message);
      })
      .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
      });
  });

// Preview Image Local (Saat Upload)
function previewImage(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById("previewFoto").src = e.target.result;
      document.getElementById("previewFoto").classList.remove("hidden");
      document.getElementById("placeholderFoto").classList.add("hidden");
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// --- HELPER FUNCTIONS ---
function closeModal(modalId) {
  document.getElementById(modalId).classList.add("hidden");
  document.body.style.overflow = "auto";
}

function hapusManajemen(id, event) {
  if (event) event.stopPropagation();

  confirmDelete(() => {
    showLoading("Menghapus data...");
    fetch("/api/manajemen/" + id, { method: "DELETE" })
      .then((res) => res.json())
      .then((res) => {
        hideLoading();
        if (res.status === "success" || res.code === 200) {
          loadManajemen();
          showSuccess("Data anggota berhasil dihapus!");
        } else {
          showError(
            "Gagal menghapus: " + (res.message || "Error tidak diketahui"),
          );
        }
      })
      .catch((err) => {
        hideLoading();
        showError("Gagal menghapus (Network Error)");
      });
  });
}

function filterTable(searchTerm) {
  if (!searchTerm) {
    renderTable(allManajemenData);
    return;
  }
  const filtered = allManajemenData.filter(
    (item) =>
      (item.nama && item.nama.toLowerCase().includes(searchTerm)) ||
      (item.jabatan && item.jabatan.toLowerCase().includes(searchTerm)) ||
      (item.email && item.email.toLowerCase().includes(searchTerm)) ||
      (item.nidn && item.nidn.toLowerCase().includes(searchTerm)),
  );
  renderTable(filtered);
}

function escapeHtml(text) {
  if (!text) return "";
  return text
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

document.onkeydown = function (evt) {
  if (evt.keyCode == 27) {
    closeModal("formModal");
    closeModal("detailModal");
  }
};
