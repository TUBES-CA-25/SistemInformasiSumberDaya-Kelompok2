window.BASE_URL = "<?= BASE_URL ?>";
let allLabData = [];
let pendingKordinator = null;
let searchTimeout; // Untuk debounce search

// Variabel Global untuk Slider
let currentLabImages = [];
let currentSlideIndex = 0;

document.addEventListener("DOMContentLoaded", function () {
  loadLaboratorium();
  loadAsistenOptions(); // Load data asisten untuk dropdown

  // ✅ OPTIMASI: Debounce search (tunggu 300ms setelah user selesai ketik)
  document
    .getElementById("searchInput")
    .addEventListener("keyup", function (e) {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        const keyword = e.target.value.toLowerCase();
        const filtered = allLabData.filter(
          (item) =>
            item.nama.toLowerCase().includes(keyword) ||
            (item.deskripsi && item.deskripsi.toLowerCase().includes(keyword)),
        );
        renderTable(filtered);
      }, 300);
    });

  // Image Preview (Form)
  document
    .getElementById("inputGambar")
    .addEventListener("change", function (e) {
      const container = document.getElementById("newImagesContainer");
      container.innerHTML = "";
      if (this.files) {
        Array.from(this.files).forEach((file) => {
          const reader = new FileReader();
          reader.onload = function (e) {
            const div = document.createElement("div");
            div.className =
              "relative aspect-square rounded-lg overflow-hidden border border-emerald-300 shadow-sm";
            div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">
                                     <div class="absolute top-0 right-0 p-1"><span class="bg-emerald-500 text-white text-[8px] px-1 rounded-bl">BARU</span></div>`;
            container.appendChild(div);
          };
          reader.readAsDataURL(file);
        });
      }
    });

  // ✅ OPTIMASI: Event Delegation (1 listener untuk semua row)
  document.getElementById("tableBody").addEventListener(
    "click",
    function (e) {
      if (e.target.closest(".edit-btn, .delete-btn")) {
        e.stopPropagation();
      }

      const row = e.target.closest("tr[data-id]");
      if (!row) return;

      const id = row.dataset.id;

      if (e.target.closest(".edit-btn")) {
        openFormModal(id, e);
      } else if (e.target.closest(".delete-btn")) {
        hapusLaboratorium(id, e);
      } else {
        openDetailModal(id);
      }
    },
    { passive: true },
  );
});

// --- 1. LOAD DATA ---
function loadLaboratorium() {
  fetch(API_URL + "/laboratorium")
    .then((res) => res.json())
    .then((res) => {
      if ((res.status === "success" || res.code === 200) && res.data) {
        allLabData = res.data;
        renderTable(allLabData);
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

function loadAsistenOptions() {
  fetch(API_URL + "/asisten")
    .then((res) => res.json())
    .then((res) => {
      if (res.data) {
        const select = document.getElementById("inputKoordinator");
        select.innerHTML = '<option value="">-- Pilih Asisten --</option>';
        res.data.forEach((a) => {
          const opt = document.createElement("option");
          opt.value = a.idAsisten;
          opt.text = a.nama;
          select.appendChild(opt);
        });
        if (pendingKordinator) {
          select.value = pendingKordinator;
          pendingKordinator = null;
        }
      }
    });
}

// --- OPTIMASI: Intersection Observer untuk Lazy Load Gambar Lab ---
let labPhotoObserver;
function initLabPhotoObserver() {
  if (labPhotoObserver) return;
  labPhotoObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.removeAttribute("data-src");
            labPhotoObserver.unobserve(img);
          }
        }
      });
    },
    { rootMargin: "50px" },
  );
}

function renderTable(data) {
  const tbody = document.getElementById("tableBody");
  const totalEl = document.getElementById("totalData");
  totalEl.innerText = `Total: ${data.length}`;

  if (data.length === 0) {
    tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-search text-2xl mb-2"></i><p>Tidak ada data ditemukan</p></td></tr>`;
    return;
  }

  // ✅ OPTIMASI: Gunakan DocumentFragment + Event Delegation
  const fragment = document.createDocumentFragment();
  const placeholderImg =
    'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 70"%3E%3Crect fill="%23e5e7eb" width="100" height="70"/%3E%3C/svg%3E';

  data.forEach((item, index) => {
    // Cek gambar pertama
    let firstImg = "https://placehold.co/100x70?text=No+Img";
    if (item.images && Array.isArray(item.images) && item.images.length > 0) {
      const path = item.images[0].namaGambar || item.images[0];
      firstImg = path.includes("http")
        ? path
        : ASSETS_URL + "/assets/uploads/" + path;
    } else if (item.gambar) {
      let clean = item.gambar.replace(/[\[\]"]/g, "").split(",")[0];
      firstImg = clean.includes("http")
        ? clean
        : ASSETS_URL + "/assets/uploads/" + clean;
    }

    let badgeColor = "bg-blue-50 text-blue-700 border-blue-100";
    if (item.jenis === "Riset")
      badgeColor = "bg-purple-50 text-purple-700 border-purple-100";
    if (item.jenis === "Multimedia")
      badgeColor = "bg-orange-50 text-orange-700 border-orange-100";

    const row = document.createElement("tr");
    row.className =
      "hover:bg-blue-50 transition-colors group border-b border-gray-100 cursor-pointer";
    row.dataset.id = item.idLaboratorium;
    row.innerHTML = `
            <td class="px-6 py-4 text-center font-medium text-gray-500 w-12">${index + 1}</td>
            <td class="px-6 py-4 text-center w-24">
                <img src="${placeholderImg}" data-src="${firstImg}" class="w-16 h-12 object-cover rounded-lg border border-gray-200 mx-auto shadow-sm group-hover:scale-105 transition-transform">
            </td>
            <td class="px-6 py-4 w-48">
                <span class="font-bold text-gray-800 text-sm truncate block" title="${item.nama}">${item.nama}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="${badgeColor} px-2.5 py-1 rounded-full text-xs font-semibold border">${item.jenis}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-xs font-bold"><i class="fas fa-users mr-1"></i> ${item.kapasitas || 0}</span>
            </td>
            <td class="px-6 py-4">
                <div class="flex justify-center items-center gap-2">
                    <button class="edit-btn w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-all shadow-sm flex items-center justify-center"><i class="fas fa-pen text-xs"></i></button>
                    <button class="delete-btn w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-all shadow-sm flex items-center justify-center"><i class="fas fa-trash-alt text-xs"></i></button>
                </div>
            </td>
        `;
    fragment.appendChild(row);
  });

  tbody.innerHTML = "";
  tbody.appendChild(fragment);

  // ✅ Setup Intersection Observer untuk lazy-load gambar
  initLabPhotoObserver();
  tbody
    .querySelectorAll("img[data-src]")
    .forEach((img) => labPhotoObserver.observe(img));
}

// --- 2. MODAL FORM ---
function openFormModal(id = null, event = null) {
  if (event) event.stopPropagation();
  document.getElementById("formModal").classList.remove("hidden");
  document.body.style.overflow = "hidden";
  document.getElementById("formMessage").classList.add("hidden");
  document.getElementById("labForm").reset();
  document.getElementById("inputId").value = "";
  document.getElementById("savedImagesContainer").innerHTML = "";
  document.getElementById("newImagesContainer").innerHTML = "";
  document.getElementById("gambarPreviewInfo").classList.add("hidden");

  if (id) {
    document.getElementById("formModalTitle").innerHTML =
      '<i class="fas fa-edit text-blue-600"></i> Edit Laboratorium';
    document.getElementById("btnSave").innerHTML =
      '<i class="fas fa-save"></i> Update Data';

    const data = allLabData.find((i) => i.idLaboratorium == id);
    if (data) {
      document.getElementById("inputId").value = data.idLaboratorium;
      document.getElementById("inputNama").value = data.nama;
      document.getElementById("inputJenis").value =
        data.jenis || "Laboratorium";
      document.getElementById("inputJumlahPc").value = data.jumlahPc;
      document.getElementById("inputKapasitas").value = data.kapasitas;
      document.getElementById("inputDeskripsi").value = data.deskripsi;

      // Specs
      document.getElementById("inputProcessor").value = data.processor || "";
      document.getElementById("inputRam").value = data.ram || "";
      document.getElementById("inputStorage").value = data.storage || "";
      document.getElementById("inputGpu").value = data.gpu || "";
      document.getElementById("inputMonitor").value = data.monitor || "";

      // Soft & Fasilitas
      document.getElementById("inputSoftware").value = data.software || "";
      document.getElementById("inputFasilitas").value =
        data.fasilitas_pendukung || data.fasilitas || "";

      // Koordinator
      const coordSelect = document.getElementById("inputKoordinator");
      if (coordSelect.options.length > 1)
        coordSelect.value = data.idKordinatorAsisten || "";
      else pendingKordinator = data.idKordinatorAsisten;

      // Preview Gambar Lama (dengan fitur hapus)
      const savedContainer = document.getElementById("savedImagesContainer");

      let images = [];
      if (data.images && Array.isArray(data.images)) images = data.images;
      else if (data.gambar)
        images = data.gambar
          .replace(/[\[\]"]/g, "")
          .split(",")
          .map((img) => ({ namaGambar: img.trim() }));

      if (images.length > 0) {
        document.getElementById("gambarPreviewInfo").classList.remove("hidden");
        images.forEach((img) => {
          let idGambar = img.idGambar || null;
          let namaFile = img.namaGambar || img;
          if (!namaFile || typeof namaFile !== "string") return;

          let imgUrl = namaFile.includes("http")
            ? namaFile
            : ASSETS_URL + "/assets/uploads/" + namaFile;

          const div = document.createElement("div");
          div.className =
            "relative group aspect-square rounded-lg overflow-hidden border border-blue-200 shadow-sm";
          div.innerHTML = `
                        <img src="${imgUrl}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            ${
                              idGambar
                                ? `
                                <button type="button" onclick="hapusGambar(${idGambar}, this)" class="bg-red-500 hover:bg-red-600 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-lg transform transition-transform hover:scale-110">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            `
                                : ""
                            }
                        </div>
                    `;
          savedContainer.appendChild(div);
        });
      }
    }
  } else {
    document.getElementById("formModalTitle").innerHTML =
      '<i class="fas fa-plus text-blue-600"></i> Tambah Laboratorium';
    document.getElementById("btnSave").innerHTML =
      '<i class="fas fa-save"></i> Simpan Data';
  }
}

document.getElementById("labForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const btn = document.getElementById("btnSave");
  const msg = document.getElementById("formMessage");
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';

  const formData = new FormData(this);
  const id = document.getElementById("inputId").value;
  const url = id ? API_URL + "/laboratorium/" + id : API_URL + "/laboratorium";

  fetch(url, {
    method: "POST",
    body: formData,
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((res) => {
      if (!res.ok) {
        throw new Error(`HTTP ${res.status}: ${res.statusText}`);
      }
      return res.json();
    })
    .then((data) => {
      hideLoading();
      if (data.status === "success" || data.code === 200 || data.code === 201) {
        closeModal("formModal");
        loadLaboratorium();
        showSuccess(
          id
            ? "Data laboratorium berhasil diperbarui!"
            : "Laboratorium baru berhasil ditambahkan!",
        );
      } else {
        throw new Error(data.message || "Gagal menyimpan data");
      }
    })
    .catch((err) => {
      hideLoading();
      console.error("Form submit error:", err);
      msg.innerHTML = `<div class="bg-red-50 text-red-800 p-3 rounded text-sm mb-4 border border-red-200"><i class="fas fa-exclamation-circle"></i> ${err.message}</div>`;
      msg.classList.remove("hidden");
      showError(err.message);
    })
    .finally(() => {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
    });
});

// --- 3. MODAL DETAIL & SLIDER LOGIC ---
function openDetailModal(id) {
  document.getElementById("detailModal").classList.remove("hidden");
  document.body.style.overflow = "hidden";

  // Reset Slider
  currentLabImages = [];
  currentSlideIndex = 0;

  const data = allLabData.find((i) => i.idLaboratorium == id);

  if (data) {
    document.getElementById("dNama").innerText = data.nama;
    document.getElementById("dJenis").innerText = data.jenis;
    document.getElementById("dKapasitasBadge").innerText = data.jumlahPc;
    document.getElementById("dDeskripsi").innerText =
      data.deskripsi || "Tidak ada deskripsi.";

    // --- PARSING GAMBAR UNTUK SLIDER ---
    if (data.images && Array.isArray(data.images) && data.images.length > 0) {
      // Jika data dari relasi images
      data.images.forEach((img) => {
        let path =
          typeof img === "object" && img.namaGambar ? img.namaGambar : img;
        if (path) {
          let fullUrl = path.includes("http")
            ? path
            : ASSETS_URL + "/assets/uploads/" + path;
          currentLabImages.push(fullUrl);
        }
      });
    } else if (data.gambar) {
      // Jika data string (mungkin koma separated atau JSON string)
      let raw = data.gambar.replace(/[\[\]"]/g, ""); // Hapus kurung siku & petik
      let parts = raw.split(",");
      parts.forEach((p) => {
        if (p.trim() !== "") {
          let fullUrl = p.trim().includes("http")
            ? p.trim()
            : ASSETS_URL + "/assets/uploads/" + p.trim();
          currentLabImages.push(fullUrl);
        }
      });
    }

    // Fallback
    if (currentLabImages.length === 0) {
      currentLabImages.push("https://placehold.co/600x400?text=No+Image");
    }

    renderSlider();

    // Data Lain
    document.getElementById("dProcessor").innerText = data.processor || "-";
    document.getElementById("dRam").innerText = data.ram || "-";
    document.getElementById("dGpu").innerText = data.gpu || "-";
    document.getElementById("dStorage").innerText = data.storage || "-";
    document.getElementById("dSoftware").innerText = data.software || "-";
    document.getElementById("dFasilitas").innerText =
      data.fasilitas_pendukung || data.fasilitas || "-";

    // Koordinator
    const coordSelect = document.getElementById("inputKoordinator");
    let coordName = "-";
    if (data.idKordinatorAsisten && coordSelect.options.length > 0) {
      for (let i = 0; i < coordSelect.options.length; i++) {
        if (coordSelect.options[i].value == data.idKordinatorAsisten) {
          coordName = coordSelect.options[i].text;
          break;
        }
      }
    }
    document.getElementById("dKoordinator").innerText = coordName;

    document.getElementById("btnEditDetail").onclick = () => {
      closeModal("detailModal");
      openFormModal(id);
    };
  }
}

function renderSlider() {
  const imgEl = document.getElementById("dSliderImage");
  const btnPrev = document.getElementById("btnPrevSlide");
  const btnNext = document.getElementById("btnNextSlide");
  const dotsContainer = document.getElementById("sliderDots");

  // 1. Set Image
  if (currentLabImages.length > 0) {
    imgEl.src = currentLabImages[currentSlideIndex];
  }

  // 2. Button Logic (Hapus hidden dulu, lalu tambah jika perlu)
  btnPrev.classList.remove("hidden", "flex");
  btnNext.classList.remove("hidden", "flex");

  if (currentLabImages.length > 1) {
    btnPrev.classList.add("flex");
    btnNext.classList.add("flex");
  } else {
    btnPrev.classList.add("hidden");
    btnNext.classList.add("hidden");
  }

  // 3. Dots Logic
  dotsContainer.innerHTML = "";
  if (currentLabImages.length > 1) {
    currentLabImages.forEach((_, idx) => {
      const dot = document.createElement("div");
      let activeClass =
        idx === currentSlideIndex
          ? "bg-white w-8 opacity-100"
          : "bg-white w-2 opacity-50 hover:opacity-80";
      dot.className = `h-1.5 rounded-full transition-all duration-300 cursor-pointer shadow-sm ${activeClass}`;
      dot.onclick = (e) => {
        e.stopPropagation();
        currentSlideIndex = idx;
        renderSlider();
      };
      dotsContainer.appendChild(dot);
    });
  }
}

function changeSlide(direction) {
  currentSlideIndex += direction;
  if (currentSlideIndex >= currentLabImages.length) currentSlideIndex = 0;
  else if (currentSlideIndex < 0)
    currentSlideIndex = currentLabImages.length - 1;
  renderSlider();
}

// --- HELPER FUNCTIONS ---
function closeModal(modalId) {
  document.getElementById(modalId).classList.add("hidden");
  document.body.style.overflow = "auto";
}

function hapusLaboratorium(id, event) {
  if (event) event.stopPropagation();
  confirmDelete(() => {
    showLoading("Menghapus data...");
    fetch(API_URL + "/laboratorium/" + id, { method: "DELETE" })
      .then((res) => res.json())
      .then(() => {
        hideLoading();
        loadLaboratorium();
        showSuccess("Laboratorium berhasil dihapus!");
      })
      .catch((err) => {
        hideLoading();
        showError("Gagal menghapus data");
      });
  }, "Data laboratorium dan gambar terkait akan dihapus permanen!");
}

function hapusGambar(idGambar, btnEl) {
  Swal.fire({
    title: "Hapus Gambar?",
    text: "Gambar akan dihapus permanen dari server.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Ya, Hapus!",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      showLoading("Menghapus gambar...");
      const deleteUrl = API_URL + "/laboratorium/image/" + idGambar;
      console.log("Deleting image:", deleteUrl);

      fetch(deleteUrl, {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
          Accept: "application/json",
        },
      })
        .then((res) => {
          console.log("Delete response status:", res.status, res.statusText);
          if (!res.ok) {
            throw new Error(`HTTP ${res.status}: ${res.statusText}`);
          }
          return res.json();
        })
        .then((data) => {
          console.log("Delete response data:", data);
          hideLoading();
          if (data.status === "success" || data.code === 200) {
            showSuccess("Gambar berhasil dihapus");
            // Hapus elemen dari UI
            const divImg = btnEl.closest(".relative.group");
            if (divImg) divImg.remove();

            // Refresh data agar slider terupdate jika nanti dibuka
            loadLaboratorium();
          } else {
            showError(data.message || "Gagal menghapus gambar");
          }
        })
        .catch((err) => {
          hideLoading();
          console.error("Delete error:", err);
          showError("Terjadi kesalahan saat menghapus gambar: " + err.message);
        });
    }
  });
}

document.onkeydown = function (evt) {
  if (evt.keyCode == 27) {
    closeModal("formModal");
    closeModal("detailModal");
  }
};
