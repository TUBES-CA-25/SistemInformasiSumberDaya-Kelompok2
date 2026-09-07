document.addEventListener("DOMContentLoaded", function () {
    let allShowcaseData = [];

    const searchInput = document.getElementById("searchInput");
    const formModal = document.getElementById("formModal");
    const formModalTitle = document.getElementById("formModalTitle");
    const showcaseForm = document.getElementById("showcaseForm");
    const inputId = document.getElementById("inputId");
    const inputBadge = document.getElementById("inputBadge");
    const inputJudul = document.getElementById("inputJudul");
    const inputDeskripsi = document.getElementById("inputDeskripsi");
    const inputLinkUrl = document.getElementById("inputLinkUrl");
    const inputLinkLabel = document.getElementById("inputLinkLabel");
    const inputUrutan = document.getElementById("inputUrutan");
    const inputStatus = document.getElementById("inputStatus");
    const inputGambar = document.getElementById("inputGambar");
    const imagePreview = document.getElementById("imagePreview");
    const previewImg = document.getElementById("previewImg");

    // Fetch data showcase dari API
    function fetchShowcaseData() {
        const baseUrl = window.PUBLIC_URL || window.BASE_URL || "";
        fetch(`${baseUrl}/api/showcase`)
            .then((res) => res.json())
            .then((res) => {
                if (res.status === "success") {
                    allShowcaseData = res.data || [];
                    renderTable(allShowcaseData);
                } else {
                    console.error("Gagal memuat data:", res.message);
                }
            })
            .catch((err) => console.error("API Error:", err));
    }

    // Render data ke tabel desktop & card mobile
    function renderTable(data) {
        const tableBodyDesktop = document.getElementById("tableBodyDesktop") || document.getElementById("tableBody");
        const cardContainerMobile = document.getElementById("cardContainerMobile");

        if (tableBodyDesktop) tableBodyDesktop.innerHTML = "";
        if (cardContainerMobile) cardContainerMobile.innerHTML = "";

        if (data.length === 0) {
            const emptyHtml = `
                <div class="text-center py-10 px-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-3 text-xl">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <p class="text-gray-500 font-medium text-xs sm:text-sm">Belum ada data slide showcase.</p>
                </div>
            `;
            if (tableBodyDesktop) {
                tableBodyDesktop.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-400">
                            <i class="fas fa-inbox text-3xl mb-2 block"></i> Belum ada data slide showcase.
                        </td>
                    </tr>
                `;
            }
            if (cardContainerMobile) {
                cardContainerMobile.innerHTML = emptyHtml;
            }
            return;
        }

        data.forEach((item, index) => {
            const statusBadge = item.is_active == 1
                ? `<span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs px-2.5 py-0.5 rounded-full font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</span>`
                : `<span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 border border-gray-200 text-xs px-2.5 py-0.5 rounded-full font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Draft</span>`;

            // 1. Render Desktop Row
            if (tableBodyDesktop) {
                const tr = document.createElement("tr");
                tr.className = "hover:bg-blue-50/40 transition-colors";
                tr.innerHTML = `
                    <td class="px-5 py-4 text-center font-medium text-gray-400 text-xs">${index + 1}</td>
                    <td class="px-5 py-4">
                        <img src="${item.img_url}" alt="Img" class="h-12 w-20 object-cover rounded-xl border border-gray-200 shadow-sm">
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-block bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider mb-1 border border-blue-100">${escapeHtml(item.badge_text)}</span>
                        <div class="font-bold text-gray-900 text-sm">${item.judul}</div>
                    </td>
                    <td class="px-5 py-4 text-xs text-gray-600 max-w-xs truncate" title="${escapeHtml(item.deskripsi)}">
                        ${escapeHtml(item.deskripsi)}
                    </td>
                    <td class="px-5 py-4 text-xs font-mono text-gray-500">
                        <div class="font-semibold text-gray-700">${escapeHtml(item.link_url || '-')}</div>
                        <span class="text-gray-400 text-[11px]">[${escapeHtml(item.link_label || 'Label Default')}]</span>
                    </td>
                    <td class="px-5 py-4 text-center font-bold text-gray-700 text-xs">#${item.urutan}</td>
                    <td class="px-5 py-4 text-center">${statusBadge}</td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <button onclick="editShowcase(${item.id})" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center shadow-sm border border-amber-100" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </button>
                            <button onclick="deleteShowcase(${item.id})" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center shadow-sm border border-red-100" title="Hapus">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                    </td>
                `;
                tableBodyDesktop.appendChild(tr);
            }

            // 2. Render Mobile Card
            if (cardContainerMobile) {
                const card = document.createElement("div");
                card.className = "bg-white p-4 rounded-2xl border border-gray-100 shadow-sm space-y-3 relative";
                card.innerHTML = `
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <img src="${item.img_url}" alt="Img" class="h-14 w-20 object-cover rounded-xl border border-gray-200 shadow-sm shrink-0">
                            <div>
                                <span class="inline-block bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider mb-1 border border-blue-100">${escapeHtml(item.badge_text)}</span>
                                <h4 class="font-bold text-gray-900 text-sm leading-snug">${item.judul}</h4>
                            </div>
                        </div>
                        <div class="shrink-0">
                            ${statusBadge}
                        </div>
                    </div>

                    <p class="text-xs text-gray-600 leading-relaxed line-clamp-2 bg-gray-50/70 p-2.5 rounded-xl border border-gray-100">
                        ${escapeHtml(item.deskripsi)}
                    </p>

                    <div class="flex items-center justify-between text-xs pt-1 border-t border-gray-100 text-gray-500">
                        <div class="flex items-center gap-2 overflow-hidden">
                            <span class="font-mono text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100 font-semibold truncate">${escapeHtml(item.link_url || '-')}</span>
                            <span class="text-gray-400 text-[11px] truncate">${escapeHtml(item.link_label || '')}</span>
                        </div>
                        <span class="font-bold text-gray-600 text-xs shrink-0 ml-2">Urutan: #${item.urutan}</span>
                    </div>

                    <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                        <button onclick="editShowcase(${item.id})" class="flex-1 py-2 bg-amber-50 hover:bg-amber-500 text-amber-700 hover:text-white font-semibold rounded-xl text-xs transition-all flex items-center justify-center gap-1.5 border border-amber-200/60 shadow-sm">
                            <i class="fas fa-pen text-xs"></i> Edit
                        </button>
                        <button onclick="deleteShowcase(${item.id})" class="flex-1 py-2 bg-red-50 hover:bg-red-500 text-red-700 hover:text-white font-semibold rounded-xl text-xs transition-all flex items-center justify-center gap-1.5 border border-red-200/60 shadow-sm">
                            <i class="fas fa-trash-alt text-xs"></i> Hapus
                        </button>
                    </div>
                `;
                cardContainerMobile.appendChild(card);
            }
        });
    }

    // Modal Form Controllers
    window.openFormModal = function () {
        if (!showcaseForm) return;
        showcaseForm.reset();
        inputId.value = "";
        formModalTitle.innerText = "Tambah Slide Showcase Baru";
        imagePreview.classList.add("hidden");
        formModal.classList.remove("hidden");
        document.body.style.overflow = "hidden";
    };

    window.closeModal = function () {
        if (!formModal) return;
        formModal.classList.add("hidden");
        document.body.style.overflow = "auto";
    };

    window.editShowcase = function (id) {
        const item = allShowcaseData.find((d) => d.id == id);
        if (!item) return;

        inputId.value = item.id;
        inputBadge.value = item.badge_text || "";
        inputJudul.value = item.judul || "";
        inputDeskripsi.value = item.deskripsi || "";
        inputLinkUrl.value = item.link_url || "";
        inputLinkLabel.value = item.link_label || "";
        inputUrutan.value = item.urutan || 1;
        inputStatus.value = item.is_active;

        if (item.img_url) {
            previewImg.src = item.img_url;
            imagePreview.classList.remove("hidden");
        } else {
            imagePreview.classList.add("hidden");
        }

        formModalTitle.innerText = "Edit Slide Showcase";
        formModal.classList.remove("hidden");
        document.body.style.overflow = "hidden";
    };

    window.deleteShowcase = function (id) {
        if (typeof Swal !== "undefined") {
            Swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Data slide showcase ini akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#64748b",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    executeDelete(id);
                }
            });
        } else {
            if (confirm("Apakah Anda yakin ingin menghapus slide ini?")) {
                executeDelete(id);
            }
        }
    };

    function executeDelete(id) {
        const baseUrl = window.PUBLIC_URL || window.BASE_URL || "";
        fetch(`${baseUrl}/api/showcase/${id}`, {
            method: "DELETE",
        })
            .then((res) => res.json())
            .then((res) => {
                if (res.status === "success") {
                    showToast("Slide berhasil dihapus!", "success");
                    fetchShowcaseData();
                } else {
                    showToast(res.message || "Gagal menghapus", "error");
                }
            })
            .catch((err) => showToast("Error koneksi server", "error"));
    }

    // Submit Form (Add / Edit)
    if (showcaseForm) {
        showcaseForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const baseUrl = window.PUBLIC_URL || window.BASE_URL || "";
            const formData = new FormData(showcaseForm);
            const id = inputId.value;

            const url = id ? `${baseUrl}/api/showcase/${id}` : `${baseUrl}/api/showcase`;

            fetch(url, {
                method: "POST",
                body: formData,
            })
                .then((res) => res.json())
                .then((res) => {
                    if (res.status === "success") {
                        showToast(res.message, "success");
                        closeModal();
                        fetchShowcaseData();
                    } else {
                        showToast(res.message || "Gagal menyimpan data", "error");
                    }
                })
                .catch((err) => showToast("Terjadi kesalahan server", "error"));
        });
    }

    // Preview Gambar saat upload
    if (inputGambar) {
        inputGambar.addEventListener("change", function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove("hidden");
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Fitur Pencarian
    if (searchInput) {
        searchInput.addEventListener("input", function (e) {
            const val = e.target.value.toLowerCase().trim();
            const filtered = allShowcaseData.filter((item) => {
                return (
                    (item.badge_text && item.badge_text.toLowerCase().includes(val)) ||
                    (item.judul && item.judul.toLowerCase().includes(val)) ||
                    (item.deskripsi && item.deskripsi.toLowerCase().includes(val))
                );
            });
            renderTable(filtered);
        });
    }

    function showToast(msg, icon = "info") {
        if (typeof Swal !== "undefined") {
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: icon,
                title: msg,
                showConfirmButton: false,
                timer: 3000,
            });
        } else {
            alert(msg);
        }
    }

    function escapeHtml(str) {
        if (!str) return "";
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;");
    }

    // Initial fetch
    fetchShowcaseData();
});
