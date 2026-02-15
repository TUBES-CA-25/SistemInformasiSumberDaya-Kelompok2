/**
 * Asisten Admin Orchestrator
 * Mengelola pemuatan data, pencarian, dan manajemen asisten via API.
 */

// Debugging helper
console.log('[admin/asisten.js] script loaded at', new Date().toISOString());

// 1. KONFIGURASI JALUR (Sinkronisasi variabel global)
// Pastikan window.BASE_URL sudah dicetak di View sebelum script ini dimuat
const baseUrl = window.BASE_URL || "";

// Jika baseUrl kosong, jangan paksa fetch agar tidak muncul /undefined/
if (!baseUrl) {
    console.error("Gagal memuat BASE_URL. Pastikan variabel window.BASE_URL sudah diset di View.");
}

const API_ENDPOINT = window.API_URL || (baseUrl + "/api");
const UPLOADS_URL = baseUrl + "/assets/uploads";

let allAsistenData = [];
let allAsistenCache = {};
let searchTimeout;
let photoObserver;

// --- 1. INITIALIZATION ---
document.addEventListener("DOMContentLoaded", function () {
    // Gunakan ID unik 'adminAsistenPage' sebagai scope agar tidak bentrok dengan halaman lain
    const scope = document.getElementById('adminAsistenPage');

    // Jika scope tidak ditemukan, coba cari tableBody secara langsung sebagai cadangan
    if (!scope && !document.getElementById('tableBody')) return;

    loadAsisten();
    if (typeof initTaggingSystem === "function") initTaggingSystem();

    console.log('[admin/asisten.js] Initialization success');

    // Search dengan Debounce (300ms)
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
        searchInput.addEventListener("keyup", function (e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const keyword = e.target.value.toLowerCase();
                const filtered = allAsistenData.filter(item =>
                    (item.nama && item.nama.toLowerCase().includes(keyword)) ||
                    (item.email && item.email.toLowerCase().includes(keyword)) ||
                    (item.jurusan && item.jurusan.toLowerCase().includes(keyword))
                );
                renderTable(filtered);
            }, 300);
        });
    }

    // Event Delegation untuk Tabel
    const tbody = document.getElementById("tableBody");
    if (tbody) {
        tbody.addEventListener("click", function (e) {
            const row = e.target.closest("tr[data-id]");
            if (!row) return;

            const id = row.dataset.id;

            if (e.target.closest(".edit-btn")) {
                e.stopPropagation();
                if (typeof openFormModal === "function") openFormModal(id);
            }
            else if (e.target.closest(".delete-btn")) {
                e.stopPropagation();
                if (typeof hapusAsisten === "function") hapusAsisten(id);
            }
            else {
                if (typeof openDetailModal === "function") openDetailModal(id);
            }
        });
    }

    // Event listener untuk Koordinator Form
    const koordinatorForm = document.getElementById("koordinatorForm");
    if (koordinatorForm) {
        koordinatorForm.addEventListener("submit", async function (e) {
            e.preventDefault();

            const selectedCoord = document.querySelector('input[name="koordinator"]:checked');
            if (!selectedCoord) {
                Swal.fire("Peringatan", "Silakan pilih asisten sebagai koordinator", "warning");
                return;
            }

            const btnSave = document.getElementById("btnSaveCoord");
            const originalText = btnSave.innerHTML;
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

            try {
                const response = await fetch(API_ENDPOINT + "/asisten/coordinator/set", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        idAsisten: selectedCoord.value
                    })
                });

                const res = await response.json();

                if (res.status === true || res.status === "success") {
                    Swal.fire("Berhasil!", "Koordinator telah dipilih", "success");
                    closeModal("koordinatorModal");
                    loadAsisten();
                } else {
                    Swal.fire("Gagal", res.message || "Gagal menyimpan pilihan koordinator", "error");
                }
            } catch (err) {
                console.error(err);
                Swal.fire("Error", "Gagal menghubungi server", "error");
            } finally {
                btnSave.disabled = false;
                btnSave.innerHTML = originalText;
            }
        });
    }

    // Event listener untuk Asisten Form (Tambah/Edit)
    const asistenForm = document.getElementById("asistenForm");
    if (asistenForm) {
        asistenForm.addEventListener("submit", async function (e) {
            e.preventDefault();

            const idAsisten = document.getElementById("inputIdAsisten").value;
            const formData = new FormData(this);

            // Add skills data
            const skills = [];
            const skillTags = document.querySelectorAll(".skill-tag");
            skillTags.forEach(tag => {
                const text = tag.textContent.trim().slice(0, -1); // Remove the × character
                skills.push(text);
            });
            formData.set("skills", JSON.stringify(skills));

            const btnSave = document.getElementById("btnSave");
            const originalText = btnSave.innerHTML;
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

            try {
                let url = API_ENDPOINT + "/asisten";
                let method = "POST";

                // Jika edit mode
                if (idAsisten) {
                    url = API_ENDPOINT + "/asisten/" + idAsisten;
                    // Use POST with _method override for compatibility with FormData and file uploads
                    formData.set("_method", "PUT");
                    method = "POST";
                }

                const response = await fetch(url, {
                    method: method,
                    body: formData
                });

                const res = await response.json();

                if (res.status === true || res.status === "success") {
                    Swal.fire("Berhasil!", idAsisten ? "Asisten berhasil diperbarui" : "Asisten berhasil ditambahkan", "success");
                    closeModal("formModal");
                    loadAsisten();
                } else {
                    Swal.fire("Gagal", res.message || "Gagal menyimpan data asisten", "error");
                }
            } catch (err) {
                console.error("Save error:", err);
                Swal.fire("Error", "Gagal menghubungi server: " + err.message, "error");
            } finally {
                btnSave.disabled = false;
                btnSave.innerHTML = originalText;
            }
        });
    }
});

// --- 2. DATA LOADING & RENDERING ---
async function loadAsisten() {
    const tbody = document.getElementById('tableBody');
    if (!tbody) return;

    try {
        // Mengarahkan ke /api/asisten sesuai route Controller
        const response = await fetch(API_ENDPOINT + "/asisten");

        if (!response.ok) throw new Error("Network response was not ok");

        const res = await response.json();

        // Jika berhasil, bersihkan pesan "Memuat data..."
        tbody.innerHTML = "";

        if (res.status === true || res.status === "success") {
            allAsistenData = res.data || [];

            allAsistenData.forEach(item => {
                allAsistenCache[item.idAsisten] = item;
            });

            renderTable(allAsistenData);
        } else {
            renderTable([]);
        }
    } catch (err) {
        console.error("Fetch error:", err);
        tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-red-500"><i class="fas fa-exclamation-triangle"></i> Gagal memuat data dari server.</td></tr>`;
    }
}

function renderTable(data) {
    const tbody = document.getElementById("tableBody");
    if (!tbody) return;
    const totalEl = document.getElementById('totalData') || document.getElementById('asistenTotalData');

    if (!tbody) return;
    if (totalEl) totalEl.innerText = `Total: ${data.length}`;

    if (data && data.length > 0) {
        const fragment = document.createDocumentFragment();
        const placeholderImg = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"%3E%3Crect fill="%23e5e7eb" width="50" height="50"/%3E%3C/svg%3E';

        data.forEach((item, index) => {
            const row = document.createElement("tr");
            row.className = "hover:bg-gray-50 transition-colors group border-b border-gray-100 cursor-pointer";
            row.setAttribute("data-id", item.idAsisten);

            let statusBadge = "";
            if (item.isKoordinator == 1) {
                statusBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200"><i class="fas fa-crown mr-1"></i> Koordinator</span>`;
            } else {
                let st = "Tidak Aktif";
                let cls = "bg-red-100 text-red-800";

                if (item.statusAktif == "1" || item.statusAktif === "Asisten") {
                    st = "Asisten";
                    cls = "bg-green-100 text-green-800";
                } else if (item.statusAktif === "CA") {
                    st = "Calon Asisten";
                    cls = "bg-yellow-100 text-yellow-800";
                }

                statusBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${cls}">${st}</span>`;
            }

            const fotoUrl = item.foto
                ? (item.foto.startsWith('http') ? item.foto : UPLOADS_URL + "/" + item.foto)
                : "https://placehold.co/50x50?text=Foto";

            row.innerHTML = `
                <td class="px-6 py-4 text-center font-medium text-gray-500">${index + 1}</td>
                <td class="px-6 py-4 text-center">
                    <img src="${placeholderImg}" data-src="${fotoUrl}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm inline-block" alt="${item.nama}">
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-800 group-hover:text-blue-600 transition-colors">${item.nama}</span>
                        <span class="text-xs text-gray-500"><i class="fas fa-envelope text-gray-300"></i> ${item.email || "-"}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600 font-medium">${item.jurusan || "-"}</td>
                <td class="px-6 py-4 text-center">${statusBadge}</td>
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
        initPhotoObserver();
        tbody.querySelectorAll("img[data-src]").forEach(img => photoObserver.observe(img));
    } else {
        tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Data asisten tidak ditemukan</td></tr>`;
    }
}

function initPhotoObserver() {
    if (photoObserver) return;
    photoObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute("data-src");
                photoObserver.unobserve(img);
            }
        });
    }, { rootMargin: "50px" });
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add("hidden");
    document.body.style.overflow = "auto";
}

function openFormModal(id = null) {
    const modal = document.getElementById("formModal");
    if (!modal) return;

    modal.classList.remove("hidden");
    document.body.style.overflow = "hidden";

    const titleEl = document.getElementById("formModalTitle");
    const form = document.getElementById("asistenForm");

    if (form) form.reset();

    // Clear skills tags
    const skillsTagContainer = document.getElementById("skillsTagContainer");
    if (skillsTagContainer) {
        const tags = skillsTagContainer.querySelectorAll(".skill-tag");
        tags.forEach(tag => tag.remove());
    }

    if (id) {
        // Edit mode
        if (titleEl) titleEl.innerHTML = '<i class="fas fa-edit text-blue-600 mr-2"></i> Edit Asisten';
        const item = allAsistenData.find(a => a.idAsisten == id);
        if (item) {
            document.getElementById("inputIdAsisten").value = item.idAsisten || "";
            document.getElementById("inputNama").value = item.nama || "";
            document.getElementById("inputEmail").value = item.email || "";
            document.getElementById("inputJurusan").value = item.jurusan || "";
            document.getElementById("inputStatus").value = item.statusAktif || "Asisten";
            document.getElementById("inputBio").value = item.bio || "";

            // Handle skills
            if (item.skills) {
                try {
                    const skillsArray = typeof item.skills === 'string' ? JSON.parse(item.skills) : item.skills;
                    document.getElementById("inputSkills").value = JSON.stringify(skillsArray);

                    // Render skill tags
                    if (Array.isArray(skillsArray)) {
                        skillsArray.forEach(skill => {
                            addSkillTag(skill);
                        });
                    }
                } catch (e) {
                    console.error("Error parsing skills:", e);
                }
            }

            // Show existing foto info if available
            if (item.foto) {
                const fotoPreviewInfo = document.getElementById("fotoPreviewInfo");
                if (fotoPreviewInfo) {
                    fotoPreviewInfo.classList.remove("hidden");
                    fotoPreviewInfo.classList.add("flex");
                }
            } else {
                const fotoPreviewInfo = document.getElementById("fotoPreviewInfo");
                if (fotoPreviewInfo) {
                    fotoPreviewInfo.classList.add("hidden");
                }
            }
        }
    } else {
        // Add mode
        if (titleEl) titleEl.innerHTML = '<i class="fas fa-plus text-blue-600 mr-2"></i> Tambah Asisten Baru';
        document.getElementById("inputIdAsisten").value = "";

        // Hide foto preview info for new record
        const fotoPreviewInfo = document.getElementById("fotoPreviewInfo");
        if (fotoPreviewInfo) {
            fotoPreviewInfo.classList.add("hidden");
        }
    }
}

function addSkillTag(skill) {
    const container = document.getElementById("skillsTagContainer");
    if (!container) return;

    const tagInput = container.querySelector("#tagInput");

    const tag = document.createElement("span");
    tag.className = "skill-tag inline-flex items-center gap-1.5 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium border border-blue-200";
    tag.innerHTML = `
        ${skill}
        <button type="button" onclick="this.parentElement.remove(); updateSkillsInput()" class="hover:text-blue-900 font-bold">×</button>
    `;

    container.insertBefore(tag, tagInput);
}

function updateSkillsInput() {
    const container = document.getElementById("skillsTagContainer");
    const tags = container.querySelectorAll(".skill-tag");
    const skills = [];

    tags.forEach(tag => {
        const text = tag.textContent.trim().slice(0, -1); // Remove the × character
        skills.push(text);
    });

    document.getElementById("inputSkills").value = JSON.stringify(skills);
}

function openDetailModal(id) {
    const modal = document.getElementById("detailModal");
    if (!modal) return;

    const item = allAsistenData.find(a => a.idAsisten == id);
    if (!item) return;

    document.getElementById("detailNama").innerText = item.nama || "-";
    document.getElementById("detailEmail").innerText = item.email || "-";
    document.getElementById("detailJurusan").innerText = item.jurusan || "-";

    let status = "Tidak Aktif";
    if (item.isKoordinator == 1) {
        status = "Koordinator";
    } else if (item.statusAktif == "1" || item.statusAktif === "Asisten") {
        status = "Asisten";
    } else if (item.statusAktif === "CA") {
        status = "Calon Asisten";
    }
    document.getElementById("detailStatus").innerText = status;

    modal.classList.remove("hidden");
    document.body.style.overflow = "hidden";
}

function openKoordinatorModal() {
    const modal = document.getElementById("koordinatorModal");
    if (!modal) return;

    modal.classList.remove("hidden");
    document.body.style.overflow = "hidden";

    // Load daftar asisten untuk dipilih
    loadKoordinatorList();
    loadCurrentCoordinator();
}

async function loadCurrentCoordinator() {
    try {
        const response = await fetch(API_ENDPOINT + "/asisten/coordinator/current");
        if (!response.ok) throw new Error("Failed to fetch current coordinator");

        const res = await response.json();
        const coordNameEl = document.getElementById("currentCoordName");

        if (res.status === true && res.data) {
            coordNameEl.innerText = res.data.nama || "Belum ditentukan";
        } else {
            coordNameEl.innerText = "Belum ditentukan";
        }
    } catch (err) {
        console.error("Error loading current coordinator:", err);
        document.getElementById("currentCoordName").innerText = "Belum ditentukan";
    }
}

async function loadKoordinatorList() {
    const coordList = document.getElementById("coordList");
    if (!coordList) return;

    coordList.innerHTML = '<div class="text-center py-4 text-gray-400">Memuat data...</div>';

    try {
        const response = await fetch(API_ENDPOINT + "/asisten");
        if (!response.ok) throw new Error("Network response was not ok");

        const res = await response.json();

        if (res.status === true && res.data && res.data.length > 0) {
            const asistenList = res.data;
            let html = '';

            asistenList.forEach(asisten => {
                html += `
                    <label class="flex items-center p-3 bg-white hover:bg-gray-50 rounded-lg cursor-pointer border border-gray-100 hover:border-emerald-300 transition-all">
                        <input type="radio" name="koordinator" value="${asisten.idAsisten}" class="w-4 h-4 text-emerald-600">
                        <div class="ml-3">
                            <p class="font-medium text-gray-900">${asisten.nama}</p>
                            <p class="text-xs text-gray-500">${asisten.email}</p>
                        </div>
                    </label>
                `;
            });

            coordList.innerHTML = html;
        } else {
            coordList.innerHTML = '<div class="text-center py-4 text-gray-400">Tidak ada asisten tersedia</div>';
        }
    } catch (err) {
        console.error("Error loading coordinator list:", err);
        coordList.innerHTML = '<div class="text-center py-4 text-red-500">Gagal memuat data</div>';
    }
}

function hapusAsisten(id) {
    Swal.fire({
        title: "Hapus Asisten?",
        text: "Data asisten akan dihapus secara permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#64748b",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal"
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await fetch(API_ENDPOINT + "/asisten/" + id, {
                    method: "DELETE"
                });
                const res = await response.json();

                if (res.status === true || res.status === "success") {
                    Swal.fire("Berhasil!", "Asisten telah dihapus.", "success");
                    loadAsisten();
                } else {
                    Swal.fire("Gagal", res.message || "Gagal menghapus asisten", "error");
                }
            } catch (err) {
                console.error(err);
                Swal.fire("Error", "Gagal menghubungi server", "error");
            }
        }
    });
}