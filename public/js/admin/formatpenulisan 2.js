let allData = [];

document.addEventListener("DOMContentLoaded", function () {
  loadData();

  // Live Search
  document.getElementById("searchInput").addEventListener("keyup", filterData);
});

function filterData() {
  const keyword = document.getElementById("searchInput").value.toLowerCase();
  const kategori = document.getElementById("filterKategori").value;

  const filtered = allData.filter((item) => {
    const matchesSearch =
      (item.judul && item.judul.toLowerCase().includes(keyword)) ||
      (item.kategori && item.kategori.toLowerCase().includes(keyword)) ||
      (item.deskripsi && item.deskripsi.toLowerCase().includes(keyword));

    const matchesKategori = kategori === "all" || item.kategori === kategori;

    return matchesSearch && matchesKategori;
  });

  renderTable(filtered);
}

function loadData() {
  const tbody = document.getElementById("tableBody");
  tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-circle-notch fa-spin text-2xl mb-2"></i><p>Memuat data...</p></td></tr>`;

  fetch(API_URL + "/formatpenulisan")
    .then((res) => res.json())
    .then((response) => {
      if (
        (response.status === "success" || response.code === 200) &&
        response.data
      ) {
        allData = response.data;
        filterData();
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
  document.getElementById("totalData").innerText = `Total: ${data.length}`;

  if (data.length === 0) {
    tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">Data tidak ditemukan</td></tr>`;
    return;
  }

  let html = "";
  let lastCategory = "";
  let categoryIndex = 0;

  data.forEach((item) => {
    const isPedoman = item.kategori === "pedoman";

    // Tambahkan Header Grup jika kategori berganti
    if (item.kategori !== lastCategory) {
      lastCategory = item.kategori;
      categoryIndex = 0; // Reset index per kategori
      html += `
                <tr class="bg-gray-100/80">
                    <td colspan="4" class="px-6 py-2 text-[11px] font-bold text-gray-600 uppercase tracking-widest">
                        <i class="fas ${isPedoman ? "fa-book" : "fa-download"} mr-1"></i> 
                        Kelompok: ${isPedoman ? "Pedoman Penulisan" : "Pusat Unduhan"}
                    </td>
                </tr>
            `;
    }

    categoryIndex++;
    const linkDisplay = item.link_external
      ? `<a href="${item.link_external}" target="_blank" class="text-blue-500 underline ml-2"><i class="fas fa-external-link-alt text-[10px]"></i> Link</a>`
      : "";
    const fileDisplay = item.file
      ? `<span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded ml-2 border border-blue-100 text-[10px]">📎 ${item.file}</span>`
      : "";

    html += `
            <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100">
                <td class="px-6 py-4 text-center font-medium text-gray-400 text-xs">${categoryIndex}</td>
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-800">${escapeHtml(item.judul)}</div>
                    <div class="text-[10px] text-gray-500 italic">
                        <span class="px-1.5 py-0.5 rounded ${isPedoman ? "bg-blue-50 text-blue-600" : "bg-green-50 text-green-600"}">
                            ${isPedoman ? "Pedoman" : "Unduhan"}
                        </span>
                    </div>
                </td>
                <td class="px-6 py-4 text-xs text-gray-600">
                    ${isPedoman ? (item.deskripsi ? item.deskripsi.substring(0, 100) + "..." : "-") : fileDisplay + linkDisplay}
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center gap-2">
                        <button onclick="openFormModal(${item.id_format})" class="w-8 h-8 rounded bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-colors flex items-center justify-center shadow-sm">
                            <i class="fas fa-pen text-xs"></i>
                        </button>
                        <button onclick="deleteData(${item.id_format})" class="w-8 h-8 rounded bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center shadow-sm">
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
  const form = document.getElementById("formatForm");
  const title = document.getElementById("formModalTitle");

  modal.classList.remove("hidden");
  document.body.style.overflow = "hidden";
  form.reset();
  document.getElementById("inputId").value = "";
  document.getElementById("formMessage").classList.add("hidden");
  document.getElementById("currentFile").classList.add("hidden");

  if (id) {
    title.innerText = "Edit Format Penulisan";
    const data = allData.find((i) => i.id_format == id);
    if (data) {
      document.getElementById("inputId").value = data.id_format;
      document.getElementById("inputJudul").value = data.judul;
      document.getElementById("inputKategori").value =
        data.kategori || "pedoman";
      document.getElementById("inputDeskripsi").value = data.deskripsi || "";
      document.getElementById("inputLink").value = data.link_external || "";

      if (data.file) {
        const cf = document.getElementById("currentFile");
        const cfn = document.getElementById("currentFileName");
        if (cfn) {
          cfn.innerText = data.file;
        } else {
          cf.innerText = "File saat ini: " + data.file;
        }
        cf.classList.remove("hidden");
      }

      updateIconPreview(data.icon);
      toggleFormFields(data.kategori || "pedoman");
    }
  } else {
    title.innerText = "Tambah Format Baru";
    updateIconPreview("");
    toggleFormFields("pedoman");
  }
}

function toggleFormFields(val) {
  const sectionPedoman = document.getElementById("sectionPedoman");
  const sectionUnduhan = document.getElementById("sectionUnduhan");

  if (val === "pedoman") {
    sectionPedoman.classList.remove("hidden");
    sectionUnduhan.classList.add("hidden");
  } else {
    sectionPedoman.classList.add("hidden");
    sectionUnduhan.classList.remove("hidden");
  }
}

function updateIconPreview(val) {
  const preview = document.getElementById("previewIcon");
  if (val && val.trim() !== "") {
    preview.className = val;
  } else {
    preview.className = "ri-question-line";
  }
}

document.getElementById("formatForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const id = document.getElementById("inputId").value;
  const url = id
    ? API_URL + "/formatpenulisan/" + id
    : API_URL + "/formatpenulisan";

  const formData = new FormData(this);
  if (id) formData.append("_method", "PUT");

  const btn = document.getElementById("btnSave");
  btn.disabled = true;
  btn.innerText = "Menyimpan...";

  fetch(url, {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "success" || data.code === 200) {
        closeModal("formModal");
        loadData();
        if (typeof showSuccess === "function") {
          showSuccess("Berhasil menyimpan data");
        } else {
          alert("Berhasil menyimpan data");
        }
      } else {
        throw new Error(data.message || "Gagal menyimpan data");
      }
    })
    .catch((err) => {
      if (typeof showError === "function") {
        showError(err.message);
      } else {
        alert("Error: " + err.message);
      }
    })
    .finally(() => {
      btn.disabled = false;
      btn.innerText = "Simpan Data";
    });
});

function deleteData(id) {
  if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
    fetch(API_URL + "/formatpenulisan/" + id, { method: "DELETE" })
      .then((res) => res.json())
      .then((res) => {
        if (res.status === "success" || res.code === 200) {
          loadData();
          if (typeof showSuccess === "function") {
            showSuccess("Data berhasil dihapus");
          } else {
            alert("Data berhasil dihapus");
          }
        } else {
          if (typeof showError === "function") {
            showError(res.message);
          } else {
            alert("Error: " + res.message);
          }
        }
      })
      .catch((err) => {
        console.error(err);
        alert("Terjadi kesalahan saat menghapus data");
      });
  }
}

function closeModal(id) {
  document.getElementById(id).classList.add("hidden");
  document.body.style.overflow = "auto";
}

function escapeHtml(text) {
  if (!text) return "";
  const map = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#039;",
  };
  return text.replace(/[&<>"']/g, (m) => map[m]);
}
