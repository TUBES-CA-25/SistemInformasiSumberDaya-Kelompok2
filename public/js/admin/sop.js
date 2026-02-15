let allData = [];

// Endpoint API baru khusus SOP
const ENDPOINT_SOP = API_URL + "/sop";

document.addEventListener("DOMContentLoaded", function () {
  loadData();
  document.getElementById("searchInput").addEventListener("keyup", renderTable);
});

function loadData() {
  const tbody = document.getElementById("tableBody");
  tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-circle-notch fa-spin text-2xl mb-2"></i><p>Memuat data SOP...</p></td></tr>`;

  fetch(ENDPOINT_SOP)
    .then((res) => res.json())
    .then((response) => {
      if (
        (response.status === "success" || response.code === 200) &&
        response.data
      ) {
        allData = response.data;
        renderTable();
      } else {
        tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada data SOP.</td></tr>`;
      }
    })
    .catch((err) => {
      console.error(err);
      tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-red-500">Gagal koneksi ke server</td></tr>`;
    });
}

// FUNGSI UTAMA PENCARIAN
function renderTable() {
  const keyword = document.getElementById("searchInput").value.toLowerCase();

  // Memfilter berdasarkan Judul ATAU Deskripsi
  const filtered = allData.filter((item) => {
    const matchJudul = item.judul && item.judul.toLowerCase().includes(keyword);
    const matchDesc =
      item.deskripsi && item.deskripsi.toLowerCase().includes(keyword);
    return matchJudul || matchDesc;
  });

  const tbody = document.getElementById("tableBody");

  if (filtered.length === 0) {
    tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Data tidak ditemukan</td></tr>`;
    return;
  }

  let html = "";
  filtered.forEach((item, index) => {
    html += `
            <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100">
                <td class="px-6 py-4 text-center font-medium text-gray-400 text-xs">${index + 1}</td>
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-800 text-sm">${escapeHtml(item.judul)}</div>
                </td>
                <td class="px-6 py-4">
                    ${item.file
        ? `<a href="${PUBLIC_URL}/assets/uploads/pdf/${item.file}" target="_blank" class="flex items-center gap-2 text-xs font-medium text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full hover:bg-blue-100 w-fit">
                            <i class="fas fa-file-pdf"></i> Lihat PDF
                        </a>`
        : '<span class="text-gray-400 text-xs italic">Tidak ada file</span>'
      }
                </td>
                <td class="px-6 py-4 text-xs text-gray-600 max-w-xs truncate">
                    ${escapeHtml(item.deskripsi || "-")}
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center gap-2">
                        <button onclick="openFormModal(${item.id_sop})" class="w-8 h-8 rounded bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center">
                            <i class="fas fa-pen text-xs"></i>
                        </button>
                        <button onclick="deleteData(${item.id_sop})" class="w-8 h-8 rounded bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
  });
  tbody.innerHTML = html;
}

function openFormModal(id = null) {
  const modal = document.getElementById("formModal");
  const form = document.getElementById("sopForm");
  modal.classList.remove("hidden");
  form.reset();
  document.getElementById("inputId").value = "";
  document.getElementById("currentFile").classList.add("hidden");

  if (id) {
    document.getElementById("formModalTitle").innerText = "Edit SOP";
    // Cari data berdasarkan ID SOP
    const data = allData.find((i) => i.id_sop == id);
    if (data) {
      document.getElementById("inputId").value = data.id_sop;
      document.getElementById("inputJudul").value = data.judul;

      document.getElementById("inputDeskripsi").value = data.deskripsi || "";

      if (data.file) {
        document.getElementById("currentFile").classList.remove("hidden");
        document.getElementById("currentFileName").innerText = data.file;
      }
    }
  } else {
    document.getElementById("formModalTitle").innerText = "Tambah SOP Baru";
  }
}

document.getElementById("sopForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const id = document.getElementById("inputId").value;
  // URL API disesuaikan ke /sop
  const url = id ? ENDPOINT_SOP + "/" + id : ENDPOINT_SOP;

  const formData = new FormData(this);
  if (id) {
    formData.append("_method", "PUT");
  } else {
    // Validasi file wajib untuk data baru
    const fileInput = document.getElementById("inputFile");
    if (!fileInput.files || fileInput.files.length === 0) {
      if (typeof showError === "function") {
        showError("File PDF wajib diunggah untuk SOP baru!");
      } else {
        alert("File PDF wajib diunggah untuk SOP baru!");
      }
      return;
    }
  }

  const btn = document.getElementById("btnSave");
  const originalText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = "Menyimpan...";

  fetch(url, { method: "POST", body: formData })
    .then((res) => {
      // Check if response is actually JSON
      const contentType = res.headers.get("content-type");
      if (!res.ok) {
        throw new Error(`HTTP ${res.status}: ${res.statusText}`);
      }
      if (!contentType || !contentType.includes("application/json")) {
        return res.text().then((text) => {
          throw new Error(
            "Server returned non-JSON response: " + text.substring(0, 200),
          );
        });
      }
      return res.json();
    })
    .then((data) => {
      if (data.status === "success" || data.code === 200) {
        closeModal();
        loadData();
        showSuccess(
          id ? "SOP berhasil diperbarui!" : "SOP baru berhasil ditambahkan!",
        );
      } else {
        showError("Gagal: " + (data.message || "Terjadi kesalahan"));
      }
    })
    .catch((err) => {
      console.error("SOP Save Error:", err);
      showError("Error System: " + err.message);
    })
    .finally(() => {
      btn.disabled = false;
      btn.innerHTML = originalText;
    });
});

function deleteData(id) {
  confirmDelete(() => {
    fetch(ENDPOINT_SOP + "/" + id, { method: "DELETE" })
      .then((res) => res.json())
      .then((res) => {
        if (res.status === "success") {
          loadData();
          showSuccess("SOP berhasil dihapus!");
        } else {
          showError("Gagal menghapus SOP");
        }
      })
      .catch((err) => {
        showError("Error: " + err.message);
      });
  }, "Apakah Anda yakin ingin menghapus SOP ini?");
}

function closeModal() {
  document.getElementById("formModal").classList.add("hidden");
}

function escapeHtml(text) {
  if (!text) return "";
  return text.replace(/[&<>"']/g, function (m) {
    return {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#039;",
    }[m];
  });
}
