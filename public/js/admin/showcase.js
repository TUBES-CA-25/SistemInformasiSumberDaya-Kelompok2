document.addEventListener("DOMContentLoaded", function () {
    let allShowcaseData = [];

    const tableBody = document.getElementById("tableBody");
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

    // Render data ke tabel
    function renderTable(data) {
        if (!tableBody) return;
        tableBody.innerHTML = "";

        if (data.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-8 text-gray-400">
                        <i class="fas fa-inbox text-3xl mb-2 block"></i> Belum ada data slide showcase.
                    </td>
                </tr>
            `;
            return;
        }

        data.forEach((item, index) => {
            const tr = document.createElement("tr");
            tr.className = "hover:bg-gray-50/80 transition-colors";

            const statusBadge = item.is_active == 1
                ? `<span class="bg-emerald-100 text-emerald-800 text-xs px-2.5 py-1 rounded-full font-semibold">Aktif</span>`
                : `<span class="bg-gray-100 text-gray-600 text-xs px-2.5 py-1 rounded-full font-semibold">Draft</span>`;

            tr.innerHTML = `
                <td class="px-6 py-4 text-center font-medium text-gray-500">${index + 1}</td>
                <td class="px-6 py-4">
                    <img src="${item.img_url}" alt="Img" class="h-12 w-16 object-cover rounded-lg border border-gray-200 shadow-sm">
                </td>
                <td class="px-6 py-4">
                    <span class="inline-block bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider mb-1 border border-blue-100">${escapeHtml(item.badge_text)}</span>
                    <div class="font-bold text-gray-800">${item.judul}</div>
                </td>
                <td class="px-6 py-4 text-xs text-gray-600 max-w-xs truncate" title="${escapeHtml(item.deskripsi)}">
                    ${escapeHtml(item.deskripsi)}
                </td>
                <td class="px-6 py-4 text-xs font-mono text-gray-500">
                    <div>${escapeHtml(item.link_url || '-')}</div>
                    <span class="text-gray-400">[${escapeHtml(item.link_label || 'Label Default')}]</span>
                </td>
                <td class="px-6 py-4 text-center font-semibold text-gray-700">${item.urutan}</td>
                <td class="px-6 py-4 text-center">${statusBadge}</td>
                <td class="px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <button onclick="editShowcase(${item.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteShowcase(${item.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            `;

            tableBody.appendChild(tr);
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
    };

    window.closeModal = function () {
        if (!formModal) return;
        formModal.classList.add("hidden");
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
