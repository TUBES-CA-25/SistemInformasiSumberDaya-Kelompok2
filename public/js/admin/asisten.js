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

const API_ENDPOINT = baseUrl + "/api"; 
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
                const st = (item.statusAktif == "1" || item.statusAktif === "Asisten") ? "Asisten" : "Tidak Aktif";
                const cls = st === "Asisten" ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800";
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
                    <div class="flex justify-center gap-2">
                        <button class="edit-btn p-2 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-all"><i class="fas fa-pen text-xs"></i></button>
                        <button class="delete-btn p-2 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-all"><i class="fas fa-trash-alt text-xs"></i></button>
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