/**
 * Admin IC-Labs Apps Management JavaScript
 * Mengelola CRUD aplikasi portal lab, konfigurasi ikon, warna, tautan web tujuan, dan toggle status aktif/nonaktif.
 */

let allApps = [];
let currentStatusFilter = 'semua';
let searchQuery = '';

const COLOR_NAMES = {
    'color-blue': 'Biru (color-blue)',
    'color-green': 'Hijau (color-green)',
    'color-purple': 'Ungu (color-purple)',
    'color-orange': 'Oranye (color-orange)',
    'color-red': 'Merah (color-red)',
    'color-cyan': 'Cyan (color-cyan)',
    'color-yellow': 'Kuning (color-yellow)',
    'color-indigo': 'Indigo (color-indigo)',
    'color-pink': 'Pink (color-pink)'
};

document.addEventListener('DOMContentLoaded', function () {
    loadApps();

    // Event listener live search
    const searchInput = document.getElementById('searchAppInput');
    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            searchQuery = e.target.value.trim().toLowerCase();
            renderAppTable();
        });
    }

    // Event listener icon input typing -> update preview
    const ikonInput = document.getElementById('appIkon');
    if (ikonInput) {
        ikonInput.addEventListener('input', function (e) {
            updateLiveIconPreview(e.target.value);
        });
    }

    // Event listener form submission
    const appForm = document.getElementById('appForm');
    if (appForm) {
        appForm.addEventListener('submit', handleAppSubmit);
    }
});

/**
 * Fetch semua data aplikasi dari backend API
 */
async function loadApps() {
    const tableBody = document.getElementById('appTableBody');
    if (tableBody && allApps.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-10 text-gray-400">
                    <i class="fas fa-spinner fa-spin text-xl mr-2"></i> Memuat daftar aplikasi...
                </td>
            </tr>
        `;
    }

    try {
        const apiUrl = window.API_URL || '';
        const res = await fetch(`${apiUrl}/apps`, {
            headers: { 'Accept': 'application/json' }
        });
        const json = await res.json();

        if (json.status === 'success') {
            allApps = json.data || [];
            updateAppStats(json.meta);
            renderAppTable();
        } else {
            throw new Error(json.message || 'Gagal memuat data aplikasi');
        }
    } catch (err) {
        console.error('Error fetching apps:', err);
        if (tableBody) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-10 text-red-500">
                        <i class="fas fa-exclamation-triangle text-xl mb-2"></i>
                        <p class="font-medium">Gagal memuat data: ${escapeHtml(err.message)}</p>
                        <button onclick="loadApps()" class="mt-3 px-4 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-semibold">
                            Coba Lagi
                        </button>
                    </td>
                </tr>
            `;
        }
    }
}

/**
 * Perbarui angka statistik overview
 */
function updateAppStats(meta) {
    const totalEl = document.getElementById('statTotalApps');
    const activeEl = document.getElementById('statActiveApps');
    const inactiveEl = document.getElementById('statInactiveApps');

    const total = meta ? meta.total : allApps.length;
    const active = meta ? meta.active : allApps.filter(a => parseInt(a.is_active, 10) === 1).length;
    const inactive = meta ? meta.inactive : total - active;

    if (totalEl) totalEl.textContent = total;
    if (activeEl) activeEl.textContent = active;
    if (inactiveEl) inactiveEl.textContent = inactive;
}

/**
 * Render Tabel Desktop dan Card Mobile
 */
function renderAppTable() {
    const tableBody = document.getElementById('appTableBody');
    const cardsContainer = document.getElementById('appCardsMobile');

    // Filter berdasarkan status dan query pencarian
    let filtered = allApps.filter(app => {
        const isActive = parseInt(app.is_active, 10) === 1;
        if (currentStatusFilter === 'aktif' && !isActive) return false;
        if (currentStatusFilter === 'nonaktif' && isActive) return false;

        if (searchQuery) {
            const judul = (app.judul || '').toLowerCase();
            const deskripsi = (app.deskripsi || '').toLowerCase();
            const url = (app.url || '').toLowerCase();
            return judul.includes(searchQuery) || deskripsi.includes(searchQuery) || url.includes(searchQuery);
        }

        return true;
    });

    // Urutkan berdasarkan urutan ASC
    filtered.sort((a, b) => (parseInt(a.urutan, 10) || 0) - (parseInt(b.urutan, 10) || 0));

    // Handle data kosong
    if (filtered.length === 0) {
        const emptyHtml = `
            <tr>
                <td colspan="6" class="text-center py-12 text-gray-400">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center text-2xl mb-3">
                            <i class="ri-search-line"></i>
                        </div>
                        <p class="font-semibold text-gray-600 text-sm">Tidak ada aplikasi yang sesuai</p>
                        <p class="text-xs text-gray-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter status.</p>
                    </div>
                </td>
            </tr>
        `;
        if (tableBody) tableBody.innerHTML = emptyHtml;
        if (cardsContainer) {
            cardsContainer.innerHTML = `
                <div class="bg-white p-8 rounded-2xl border border-gray-100 text-center text-gray-400">
                    <i class="ri-search-line text-3xl mb-2"></i>
                    <p class="font-semibold text-gray-600 text-sm">Tidak ada aplikasi yang sesuai</p>
                    <p class="text-xs text-gray-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter status.</p>
                </div>
            `;
        }
        return;
    }

    // Render Baris Tabel Desktop
    if (tableBody) {
        tableBody.innerHTML = filtered.map(app => {
            const isActive = parseInt(app.is_active, 10) === 1;
            const warna = app.warna || 'color-blue';
            const ikon = app.ikon || 'ri-apps-line';
            const target = app.target || '_blank';
            const targetLabel = target === '_blank' ? 'Tab Baru' : 'Tab Ini';
            const targetBadgeClass = target === '_blank' ? 'bg-indigo-50 text-indigo-600 border-indigo-100' : 'bg-slate-50 text-slate-600 border-slate-200';

            // Navigasi URL
            let displayUrl = app.url || '#';
            let testUrl = app.url || '#';
            if (testUrl === '/' || testUrl === '') {
                testUrl = (window.BASE_URL || '') + '/';
            }

            return `
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <!-- Urutan -->
                    <td class="px-4 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 text-gray-700 font-bold text-xs">
                            ${parseInt(app.urutan, 10) || 0}
                        </span>
                    </td>

                    <!-- Aplikasi & Ikon -->
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3.5">
                            <div class="app-icon-preview ${escapeHtml(warna)} shadow-sm">
                                <i class="${escapeHtml(ikon)}"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-gray-800 text-sm truncate" title="${escapeHtml(app.judul)}">
                                    ${escapeHtml(app.judul)}
                                </h4>
                                <span class="text-[10px] text-gray-400 font-mono">
                                    ${escapeHtml(ikon)}
                                </span>
                            </div>
                        </div>
                    </td>

                    <!-- Deskripsi -->
                    <td class="px-5 py-4">
                        <p class="text-xs text-gray-600 line-clamp-2 max-w-sm leading-relaxed" title="${escapeHtml(app.deskripsi)}">
                            ${escapeHtml(app.deskripsi)}
                        </p>
                    </td>

                    <!-- Link Web Tujuan -->
                    <td class="px-5 py-4">
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-1.5">
                                <a href="${escapeHtml(testUrl)}" target="${escapeHtml(target)}" rel="noopener" 
                                   class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1 max-w-[200px] truncate"
                                   title="Buka: ${escapeHtml(displayUrl)}">
                                    <i class="ri-links-line text-xs flex-shrink-0"></i>
                                    <span class="truncate">${escapeHtml(displayUrl)}</span>
                                    <i class="ri-external-link-line text-[10px] flex-shrink-0"></i>
                                </a>
                                <button type="button" onclick="copyToClipboard('${escapeHtml(displayUrl)}')" 
                                        class="p-1 rounded text-gray-400 hover:text-gray-600 hover:bg-gray-100 text-xs transition-colors"
                                        title="Salin Tautan Web">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-medium border ${targetBadgeClass}">
                                Target: ${targetLabel}
                            </span>
                        </div>
                    </td>

                    <!-- Status Toggle Switch -->
                    <td class="px-4 py-4 text-center">
                        <div class="flex flex-col items-center justify-center gap-1">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" ${isActive ? 'checked' : ''} 
                                       onchange="toggleAppStatus(${app.id}, '${escapeHtml(app.judul)}', this.checked)"
                                       class="sr-only peer">
                                <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                            <span class="text-[10px] font-semibold ${isActive ? 'text-emerald-600' : 'text-amber-600'}">
                                ${isActive ? 'Aktif' : 'Nonaktif'}
                            </span>
                        </div>
                    </td>

                    <!-- Aksi -->
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <button type="button" onclick="editApp(${app.id})" 
                                    class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors flex items-center justify-center text-xs"
                                    title="Edit Aplikasi">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button type="button" onclick="deleteApp(${app.id}, '${escapeHtml(app.judul)}')" 
                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-colors flex items-center justify-center text-xs"
                                    title="Hapus Aplikasi">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Render Cards Mobile
    if (cardsContainer) {
        cardsContainer.innerHTML = filtered.map(app => {
            const isActive = parseInt(app.is_active, 10) === 1;
            const warna = app.warna || 'color-blue';
            const ikon = app.ikon || 'ri-apps-line';
            const target = app.target || '_blank';
            let displayUrl = app.url || '#';
            let testUrl = app.url || '#';
            if (testUrl === '/' || testUrl === '') {
                testUrl = (window.BASE_URL || '') + '/';
            }

            return `
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="app-icon-preview ${escapeHtml(warna)} shadow-sm">
                                <i class="${escapeHtml(ikon)}"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="px-1.5 py-0.5 rounded bg-gray-100 text-gray-700 text-[10px] font-bold">#${parseInt(app.urutan, 10) || 0}</span>
                                    <h4 class="font-bold text-gray-800 text-sm">${escapeHtml(app.judul)}</h4>
                                </div>
                                <span class="text-[10px] text-gray-400 font-mono">${escapeHtml(ikon)}</span>
                            </div>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" ${isActive ? 'checked' : ''} 
                                   onchange="toggleAppStatus(${app.id}, '${escapeHtml(app.judul)}', this.checked)"
                                   class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>

                    <p class="text-xs text-gray-600 leading-relaxed">${escapeHtml(app.deskripsi)}</p>

                    <!-- URL Info -->
                    <div class="p-2.5 rounded-xl bg-blue-50/50 border border-blue-100 flex items-center justify-between gap-2">
                        <a href="${escapeHtml(testUrl)}" target="${escapeHtml(target)}" rel="noopener" 
                           class="text-xs font-semibold text-blue-600 hover:underline flex items-center gap-1.5 truncate">
                            <i class="ri-links-line text-xs"></i>
                            <span class="truncate">${escapeHtml(displayUrl)}</span>
                            <i class="ri-external-link-line text-[10px]"></i>
                        </a>
                        <button type="button" onclick="copyToClipboard('${escapeHtml(displayUrl)}')" class="text-gray-400 hover:text-gray-600 text-xs">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-gray-100 text-xs">
                        <span class="font-semibold ${isActive ? 'text-emerald-600' : 'text-amber-600'}">
                            <i class="fas ${isActive ? 'fa-check-circle text-emerald-500' : 'fa-power-off text-amber-500'} mr-1"></i>
                            ${isActive ? 'Aktif' : 'Nonaktif'}
                        </span>

                        <div class="flex items-center gap-2">
                            <button type="button" onclick="editApp(${app.id})" 
                                    class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-semibold flex items-center gap-1">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </button>
                            <button type="button" onclick="deleteApp(${app.id}, '${escapeHtml(app.judul)}')" 
                                    class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold flex items-center gap-1">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }
}

/**
 * Filter status aplikasi
 */
function filterAppStatus(status) {
    currentStatusFilter = status;

    const btnSemua = document.getElementById('btnFilterSemua');
    const btnAktif = document.getElementById('btnFilterAktif');
    const btnNonaktif = document.getElementById('btnFilterNonaktif');

    const activeClass = 'bg-blue-600 text-white shadow-sm';
    const inactiveClass = 'bg-gray-100 text-gray-600 hover:bg-gray-200';

    [btnSemua, btnAktif, btnNonaktif].forEach(btn => {
        if (!btn) return;
        btn.className = `px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all ${inactiveClass}`;
    });

    if (status === 'semua' && btnSemua) {
        btnSemua.className = `px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all ${activeClass}`;
    } else if (status === 'aktif' && btnAktif) {
        btnAktif.className = `px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all ${activeClass}`;
    } else if (status === 'nonaktif' && btnNonaktif) {
        btnNonaktif.className = `px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all ${activeClass}`;
    }

    renderAppTable();
}

/**
 * Buka modal form aplikasi
 */
function openAppModal(id = null) {
    const modal = document.getElementById('appModal');
    const form = document.getElementById('appForm');
    const titleEl = document.getElementById('modalAppTitle');
    const idInput = document.getElementById('appId');

    if (!modal || !form) return;

    if (id) {
        // Mode Edit
        const app = allApps.find(a => parseInt(a.id, 10) === parseInt(id, 10));
        if (!app) return;

        titleEl.textContent = 'Edit Informasi Aplikasi';
        idInput.value = app.id;
        document.getElementById('appJudul').value = app.judul || '';
        document.getElementById('appDeskripsi').value = app.deskripsi || '';
        document.getElementById('appUrl').value = app.url || '';
        document.getElementById('appTarget').value = app.target || '_blank';
        document.getElementById('appUrutan').value = app.urutan || 1;
        document.getElementById('appIkon').value = app.ikon || 'ri-apps-line';
        document.getElementById('appIsActive').checked = parseInt(app.is_active, 10) === 1;

        // Set Warna
        selectColorTheme(app.warna || 'color-blue');
        updateLiveIconPreview(app.ikon || 'ri-apps-line');
    } else {
        // Mode Tambah Baru
        form.reset();
        idInput.value = '';
        titleEl.textContent = 'Tambah Aplikasi Baru';
        
        // Auto hitung urutan berikutnya
        const maxUrutan = allApps.reduce((max, a) => Math.max(max, parseInt(a.urutan, 10) || 0), 0);
        document.getElementById('appUrutan').value = maxUrutan + 1;
        document.getElementById('appIkon').value = 'ri-apps-line';
        document.getElementById('appTarget').value = '_blank';
        document.getElementById('appIsActive').checked = true;

        selectColorTheme('color-blue');
        updateLiveIconPreview('ri-apps-line');
    }

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

/**
 * Tutup modal form aplikasi
 */
function closeAppModal() {
    const modal = document.getElementById('appModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

/**
 * Update live preview ikon
 */
function updateLiveIconPreview(iconClass) {
    const iconEl = document.getElementById('livePreviewIcon');
    if (iconEl) {
        iconEl.className = iconClass ? iconClass.trim() : 'ri-apps-line';
    }
}

/**
 * Pilih ikon dari preset chips
 */
function selectPresetIcon(iconClass) {
    const input = document.getElementById('appIkon');
    if (input) {
        input.value = iconClass;
        updateLiveIconPreview(iconClass);
    }
}

/**
 * Pilih warna gradien
 */
function selectColorTheme(colorClass) {
    const hiddenInput = document.getElementById('appWarnaInput');
    const previewBox = document.getElementById('livePreviewIconBox');
    const labelColor = document.getElementById('labelActiveColor');

    if (hiddenInput) hiddenInput.value = colorClass;

    // Update active swatch
    document.querySelectorAll('.color-swatch-btn').forEach(btn => {
        const checkIcon = btn.querySelector('i');
        if (btn.getAttribute('data-color') === colorClass) {
            btn.classList.add('active');
            if (checkIcon) checkIcon.classList.remove('hidden');
        } else {
            btn.classList.remove('active');
            if (checkIcon) checkIcon.classList.add('hidden');
        }
    });

    // Update Live Preview Box Class
    if (previewBox) {
        // Hapus class color-* sebelumnya
        const currentClasses = previewBox.className.split(' ').filter(c => !c.startsWith('color-'));
        currentClasses.push(colorClass);
        previewBox.className = currentClasses.join(' ');
    }

    if (labelColor) {
        labelColor.textContent = COLOR_NAMES[colorClass] || colorClass;
    }
}

/**
 * Shortcut pembantu untuk mengisi awalan URL
 */
function setAppUrlPrefix(prefix) {
    const urlInput = document.getElementById('appUrl');
    if (!urlInput) return;

    if (!urlInput.value || urlInput.value === '#' || urlInput.value === '/') {
        urlInput.value = prefix;
    } else if (!urlInput.value.startsWith('http://') && !urlInput.value.startsWith('https://') && !urlInput.value.startsWith('/')) {
        urlInput.value = prefix + urlInput.value;
    }
    urlInput.focus();
}

/**
 * Handler Submit Form (Create / Update)
 */
async function handleAppSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const submitBtn = document.getElementById('btnSimpanApp');
    const originalText = submitBtn ? submitBtn.innerHTML : 'Simpan';

    const id = document.getElementById('appId').value;
    const judul = document.getElementById('appJudul').value.trim();
    const deskripsi = document.getElementById('appDeskripsi').value.trim();
    const url = document.getElementById('appUrl').value.trim();
    const target = document.getElementById('appTarget').value;
    const urutan = document.getElementById('appUrutan').value;
    const ikon = document.getElementById('appIkon').value.trim();
    const warna = document.getElementById('appWarnaInput').value;
    const isActive = document.getElementById('appIsActive').checked ? 1 : 0;

    if (!judul || !deskripsi || !url) {
        Swal.fire({
            icon: 'warning',
            title: 'Formulir Belum Lengkap',
            text: 'Judul, deskripsi singkat, dan link web tujuan wajib diisi.',
            confirmButtonColor: '#2563eb'
        });
        return;
    }

    // Set loading state
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';
    }

    try {
        const apiUrl = window.API_URL || '';
        const endpoint = id ? `${apiUrl}/apps/${id}` : `${apiUrl}/apps`;
        
        const payload = {
            id: id,
            judul: judul,
            deskripsi: deskripsi,
            url: url,
            target: target,
            urutan: urutan,
            ikon: ikon,
            warna: warna,
            is_active: isActive
        };

        const formData = new FormData();
        Object.keys(payload).forEach(key => {
            if (payload[key] !== null && payload[key] !== undefined) {
                formData.append(key, payload[key]);
            }
        });

        const res = await fetch(endpoint, {
            method: 'POST',
            body: formData,
            headers: { 'Accept': 'application/json' }
        });

        const json = await res.json();

        if (json.status === 'success') {
            closeAppModal();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: json.message || 'Data aplikasi berhasil disimpan!',
                timer: 1800,
                showConfirmButton: false
            });
            await loadApps();
        } else {
            throw new Error(json.message || 'Gagal menyimpan aplikasi');
        }
    } catch (err) {
        console.error('Submit app error:', err);
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: err.message || 'Gagal terhubung ke server',
            confirmButtonColor: '#2563eb'
        });
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }
}

/**
 * Edit Aplikasi
 */
function editApp(id) {
    openAppModal(id);
}

/**
 * Toggle Status Aktif/Nonaktif Aplikasi secara Instan
 */
async function toggleAppStatus(id, title, newCheckedState) {
    try {
        const apiUrl = window.API_URL || '';
        const res = await fetch(`${apiUrl}/apps/${id}/toggle`, {
            method: 'POST',
            headers: { 'Accept': 'application/json' }
        });
        const json = await res.json();

        if (json.status === 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: json.message || `Status aplikasi diperbarui.`
            });
            await loadApps();
        } else {
            throw new Error(json.message || 'Gagal mengubah status');
        }
    } catch (err) {
        console.error('Toggle status error:', err);
        Swal.fire({
            icon: 'error',
            title: 'Gagal Mengubah Status',
            text: err.message,
            confirmButtonColor: '#2563eb'
        });
        // Rollback tabel
        renderAppTable();
    }
}

/**
 * Hapus Aplikasi dengan Konfirmasi SweetAlert2
 */
function deleteApp(id, title) {
    Swal.fire({
        title: 'Hapus Aplikasi?',
        html: `Apakah Anda yakin ingin menghapus aplikasi <b class="text-blue-600">"${escapeHtml(title)}"</b> dari daftar IC-Labs Apps?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const apiUrl = window.API_URL || '';
                const res = await fetch(`${apiUrl}/apps/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });
                const json = await res.json();

                if (json.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: json.message || 'Aplikasi berhasil dihapus.',
                        timer: 1600,
                        showConfirmButton: false
                    });
                    await loadApps();
                } else {
                    throw new Error(json.message || 'Gagal menghapus aplikasi');
                }
            } catch (err) {
                console.error('Delete app error:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menghapus',
                    text: err.message,
                    confirmButtonColor: '#2563eb'
                });
            }
        }
    });
}

/**
 * Helper: Salin teks ke clipboard
 */
function copyToClipboard(text) {
    if (!text || text === '#') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
        Toast.fire({
            icon: 'info',
            title: 'Tautan kosong (#)'
        });
        return;
    }

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(() => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
            Toast.fire({
                icon: 'success',
                title: 'Tautan disalin ke clipboard'
            });
        }).catch(() => fallbackCopyText(text));
    } else {
        fallbackCopyText(text);
    }
}

function fallbackCopyText(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        document.execCommand('copy');
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
        Toast.fire({
            icon: 'success',
            title: 'Tautan disalin ke clipboard'
        });
    } catch (err) {
        console.error('Fallback copy failed', err);
    }
    document.body.removeChild(textArea);
}

/**
 * Escape string HTML untuk keamanan XSS
 */
function escapeHtml(text) {
    if (text === null || text === undefined) return '';
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
