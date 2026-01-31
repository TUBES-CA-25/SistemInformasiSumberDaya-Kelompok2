let allModul = [];

// PERBAIKAN 1: Gunakan window.BASE_URL, bukan tag PHP
const API_MODUL = window.BASE_URL + "/modul";

document.addEventListener("DOMContentLoaded", function () {
  loadData();
  document.getElementById("searchInput").addEventListener("keyup", renderTable);
});

async function loadData() {
  const tbody = document.getElementById("tableBody");
  tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-circle-notch fa-spin text-2xl mb-2"></i><p>Memuat data...</p></td></tr>`;

  try {
    const res = await fetch(API_MODUL, {
      headers: { "X-Requested-With": "XMLHttpRequest" },
    });
    const response = await res.json();
    if (response.status === "success" && response.data) {
      allModul = response.data;
      renderTable();
    } else {
      tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada data modul.</td></tr>`;
    }
  } catch (err) {
    console.error(err);
    tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-red-500">Gagal koneksi ke server</td></tr>`;
  }
}

function renderTable() {
  const keyword = document.getElementById("searchInput").value.toLowerCase();
  const filtered = allModul.filter((item) => {
    return (
      (item.nama_matakuliah || "").toLowerCase().includes(keyword) ||
      (item.judul || "").toLowerCase().includes(keyword) ||
      (item.jurusan || "").toLowerCase().includes(keyword)
    );
  });

  const tbody = document.getElementById("tableBody");
  if (filtered.length === 0) {
    tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Data tidak ditemukan</td></tr>`;
    return;
  }

  tbody.innerHTML = filtered
    .map(
      (m, i) => `
        <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100">
            <td class="px-6 py-4 text-center font-medium text-gray-400 text-xs">${i + 1}</td>
            <td class="px-6 py-4">
                <span class="px-3 py-1 ${m.jurusan === "TI" ? "bg-blue-50 text-blue-600" : "bg-emerald-50 text-emerald-600"} rounded-full text-[10px] font-black tracking-widest uppercase">
                    ${m.jurusan}
                </span>
            </td>
            <td class="px-6 py-4 font-bold text-gray-800 text-sm">${escapeHtml(m.nama_matakuliah)}</td>
            <td class="px-6 py-4 text-gray-600 text-sm">${escapeHtml(m.judul)}</td>
            <td class="px-6 py-4">
                ${
                  m.file
                    ? // PERBAIKAN 2: Gunakan Template Literal JS (${window.BASE_URL})
                      `<a href="${window.BASE_URL}/assets/uploads/modul/${m.file}" target="_blank" class="flex items-center gap-2 text-xs font-medium text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full hover:bg-blue-100 w-fit">
                        <i class="fas fa-file-pdf"></i> Lihat PDF
                    </a>`
                    : '<span class="text-gray-400 text-xs italic">Tidak ada file</span>'
                }
            </td>
            <td class="px-6 py-4 text-center">
                <div class="flex justify-center gap-2">
                    <button onclick="openFormModal(${m.id_modul})" class="w-8 h-8 rounded bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center">
                        <i class="fas fa-pen text-xs"></i>
                    </button>
                    <button onclick="deleteData(${m.id_modul})" class="w-8 h-8 rounded bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>
                </div>
            </td>
        </tr>
    `,
    )
    .join("");
}

function openFormModal(id = null) {
  const modal = document.getElementById("formModal");
  const form = document.getElementById("modulForm");
  modal.classList.remove("hidden");
  form.reset();
  document.getElementById("inputId").value = "";
  document.getElementById("currentFileBox").classList.add("hidden");

  if (id) {
    document.getElementById("formModalTitle").innerText = "Edit Modul";
    // Pakai == karena id dari HTML string, id dari data bisa number
    const data = allModul.find((i) => i.id_modul == id);
    if (data) {
      document.getElementById("inputId").value = data.id_modul;
      document.getElementById("inputJurusan").value = data.jurusan;
      document.getElementById("inputMk").value = data.nama_matakuliah;
      document.getElementById("inputJudul").value = data.judul;
      if (data.file) {
        document.getElementById("currentFileBox").classList.remove("hidden");
        document.getElementById("currentFileName").innerText = data.file;
      }
    }
  } else {
    document.getElementById("formModalTitle").innerText = "Tambah Modul Baru";
  }
}

document
  .getElementById("modulForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();
    const id = document.getElementById("inputId").value;
    const formData = new FormData(this);
    if (id) formData.append("_method", "PUT");

    const btn = document.getElementById("btnSave");
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    // PERBAIKAN 3: Gunakan variabel API_MODUL yang sudah benar
    try {
      const url = id ? `${API_MODUL}/${id}` : API_MODUL;
      const res = await fetch(url, {
        method: "POST",
        body: formData,
        headers: { "X-Requested-With": "XMLHttpRequest" },
      });
      const data = await res.json();
      if (data.status === "success") {
        closeModal();
        loadData();
        // Cek helper function
        if (typeof showSuccess === "function") {
          showSuccess(
            id
              ? "Modul berhasil diperbarui!"
              : "Modul baru berhasil ditambahkan!",
          );
        } else {
          alert("Berhasil menyimpan data!");
        }
      } else {
        const msg = data.message || "Terjadi kesalahan";
        if (typeof showError === "function") showError("Gagal: " + msg);
        else alert(msg);
      }
    } catch (err) {
      if (typeof showError === "function")
        showError("Error System: " + err.message);
      else console.error(err);
    } finally {
      btn.disabled = false;
      btn.innerHTML = originalText;
    }
  });

async function deleteData(id) {
  const doDelete = async () => {
    try {
      const res = await fetch(`${API_MODUL}/${id}`, {
        method: "DELETE",
        headers: { "X-Requested-With": "XMLHttpRequest" },
      });
      const result = await res.json();
      if (result.status === "success") {
        loadData();
        if (typeof showSuccess === "function")
          showSuccess("Modul berhasil dihapus!");
        else alert("Berhasil dihapus!");
      } else {
        if (typeof showError === "function") showError("Gagal menghapus modul");
        else alert("Gagal menghapus");
      }
    } catch (err) {
      if (typeof showError === "function") showError("Error: " + err.message);
    }
  };

  if (typeof confirmDelete === "function") {
    confirmDelete(doDelete, "Yakin ingin menghapus modul ini?");
  } else {
    if (confirm("Yakin hapus data ini?")) doDelete();
  }
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
