/**
 * Admin Kontak & Kotak Masuk JavaScript
 * Mengelola CRUD saluran kontak lab dan penanganan inbox pesan masuk pengunjung
 */

let allKontak = [];
let allPesan = [];
let currentTab = 'saluran';
let inboxFilter = 'semua';

document.addEventListener('DOMContentLoaded', function () {
    loadData();

    // Event listener pencarian
    const searchKontak = document.getElementById('searchKontakInput');
    if (searchKontak) {
        searchKontak.addEventListener('input', function () {
            renderKontakTable();
        });
    }

    const searchInbox = document.getElementById('searchInboxInput');
    if (searchInbox) {
        searchInbox.addEventListener('input', function () {
            renderInboxTable();
        });
    }

    // Event listener form kontak
    const kontakForm = document.getElementById('kontakForm');
    if (kontakForm) {
        kontakForm.addEventListener('submit', handleKontakSubmit);
    }
});

/**
 * Memuat data awal dari server
 */
async function loadData() {
    await Promise.all([fetchKontak(), fetchPesan()]);
}

/**
 * Fetch Saluran Kontak
 */
async function fetchKontak() {
    try {
        const res = await fetch(`${window.API_URL || ''}/kontak-info`);
        const json = await res.json();
        if (json.status === 'success') {
            allKontak = json.data || [];
            updateKontakStats();
            renderKontakTable();

            // Perbarui label email penerima di header admin jika ada
            if (json.meta && json.meta.recipient_email) {
                const elEmail = document.getElementById('activeRecipientEmail');
                const elSource = document.getElementById('activeRecipientSource');
                if (elEmail) elEmail.textContent = json.meta.recipient_email;
                if (elSource) {
                    elSource.textContent = json.meta.email_source === 'kontak' 
                        ? '(Otomatis dari Kontak)' 
                        : '(Dari .env)';
                }
            }
        }
    } catch (err) {
        console.error('Error fetching kontak:', err);
    }
}

/**
 * Fetch Kotak Masuk Pesan
 */
async function fetchPesan() {
    try {
        const res = await fetch(`${window.API_URL || ''}/kontak-pesan`);
        const json = await res.json();
        if (json.status === 'success') {
            allPesan = json.data || [];
            updatePesanStats();
            renderInboxTable();
        }
    } catch (err) {
        console.error('Error fetching pesan:', err);
    }
}

/**
 * Perbarui statistik kontak
 */
function updateKontakStats() {
    const statTotalEl = document.getElementById('statTotalKontak');
    const badgeSaluranEl = document.getElementById('badgeCountSaluran');

    if (statTotalEl) statTotalEl.textContent = allKontak.length;
    if (badgeSaluranEl) badgeSaluranEl.textContent = allKontak.length;
}

/**
 * Perbarui statistik pesan
 */
function updatePesanStats() {
    const unreadCount = allPesan.filter(p => p.status === 'belum_dibaca').length;
    const statUnreadEl = document.getElementById('statPesanUnread');
    const statTotalPesanEl = document.getElementById('statTotalPesan');
    const badgeInboxEl = document.getElementById('badgeCountInbox');

    if (statUnreadEl) statUnreadEl.textContent = unreadCount;
    if (statTotalPesanEl) statTotalPesanEl.textContent = allPesan.length;

    if (badgeInboxEl) {
        if (unreadCount > 0) {
            badgeInboxEl.textContent = unreadCount;
            badgeInboxEl.classList.remove('hidden');
        } else {
            badgeInboxEl.classList.add('hidden');
        }
    }
}

/**
 * Switch Tab (Saluran vs Kotak Masuk)
 */
function switchTab(tab) {
    currentTab = tab;
    const secSaluran = document.getElementById('sectionSaluran');
    const secInbox = document.getElementById('sectionInbox');
    const tabSaluran = document.getElementById('tabBtnSaluran');
    const tabInbox = document.getElementById('tabBtnInbox');
    const btnTambah = document.getElementById('btnTambahKontak');

    if (tab === 'saluran') {
        secSaluran.classList.remove('hidden');
        secInbox.classList.add('hidden');

        tabSaluran.className = 'flex-1 py-2.5 px-4 rounded-xl font-semibold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all bg-blue-600 text-white shadow-sm';
        tabInbox.className = 'flex-1 py-2.5 px-4 rounded-xl font-semibold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all text-gray-600 hover:text-gray-900 hover:bg-gray-50';

        if (btnTambah) btnTambah.classList.remove('hidden');
    } else {
        secSaluran.classList.add('hidden');
        secInbox.classList.remove('hidden');

        tabInbox.className = 'flex-1 py-2.5 px-4 rounded-xl font-semibold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all bg-blue-600 text-white shadow-sm';
        tabSaluran.className = 'flex-1 py-2.5 px-4 rounded-xl font-semibold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all text-gray-600 hover:text-gray-900 hover:bg-gray-50';

        if (btnTambah) btnTambah.classList.add('hidden');
    }
}

/**
 * Render Tabel & Card Saluran Kontak (Sederhana & Bersih)
 */
function renderKontakTable() {
    const tbody = document.getElementById('kontakTableBody');
    const mobileContainer = document.getElementById('kontakCardContainer');
    const query = (document.getElementById('searchKontakInput')?.value || '').toLowerCase().trim();

    let filtered = allKontak.filter(k => {
        return (k.nama && k.nama.toLowerCase().includes(query)) ||
               (k.nilai && k.nilai.toLowerCase().includes(query));
    });

    if (filtered.length === 0) {
        const emptyState = `
            <tr>
                <td colspan="5" class="text-center py-12 text-gray-400">
                    <i class="fas fa-address-book text-4xl mb-3 text-gray-300"></i>
                    <p class="font-medium text-sm">Tidak ada saluran kontak ditemukan</p>
                    <p class="text-xs text-gray-400 mt-1">Klik "+ Tambah Saluran Kontak" untuk menambahkan data baru.</p>
                </td>
            </tr>
        `;
        if (tbody) tbody.innerHTML = emptyState;
        if (mobileContainer) {
            mobileContainer.innerHTML = `
                <div class="bg-white p-8 rounded-2xl border border-gray-100 text-center text-gray-400">
                    <i class="fas fa-address-book text-3xl mb-2 text-gray-300"></i>
                    <p class="text-sm font-medium">Tidak ada data kontak</p>
                </div>
            `;
        }
        return;
    }

    // Render Desktop Table
    if (tbody) {
        tbody.innerHTML = filtered.map((item, index) => {
            const displayValue = item.nilai.length > 65 ? item.nilai.substring(0, 62) + '...' : item.nilai;
            const iconClass = item.ikon || 'ri-information-line';

            return `
                <tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="px-5 py-4 text-center font-medium text-gray-500">${index + 1}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg flex-shrink-0 border border-blue-100">
                                <i class="${escapeHtml(iconClass)}"></i>
                            </div>
                            <h4 class="font-bold text-gray-800 text-sm">${escapeHtml(item.nama)}</h4>
                        </div>
                    </td>
                    <td class="px-5 py-4 max-w-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-700 truncate" title="${escapeHtml(item.nilai)}">
                                ${escapeHtml(displayValue)}
                            </span>
                            <button onclick="copyToClipboard('${escapeJsString(item.nilai)}')" title="Salin teks" class="text-gray-400 hover:text-blue-600 transition-colors">
                                <i class="far fa-copy text-xs"></i>
                            </button>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        ${item.tautan ? `
                            <a href="${escapeHtml(item.tautan)}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-blue-600 hover:underline max-w-[200px] truncate" title="${escapeHtml(item.tautan)}">
                                <i class="fas fa-external-link-alt text-[10px]"></i>
                                <span class="truncate">${escapeHtml(item.tautan)}</span>
                            </a>
                        ` : '<span class="text-gray-300 text-xs italic">-</span>'}
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <button onclick="editKontak(${item.id})" title="Edit Saluran"
                                    class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center text-xs">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteKontak(${item.id}, '${escapeJsString(item.nama)}')" title="Hapus Saluran"
                                    class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all flex items-center justify-center text-xs">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Render Mobile Cards
    if (mobileContainer) {
        mobileContainer.innerHTML = filtered.map((item) => {
            const iconClass = item.ikon || 'ri-information-line';
            return `
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg flex-shrink-0 border border-blue-100">
                                <i class="${escapeHtml(iconClass)}"></i>
                            </div>
                            <h4 class="font-bold text-gray-800 text-sm">${escapeHtml(item.nama)}</h4>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button onclick="editKontak(${item.id})" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteKontak(${item.id}, '${escapeJsString(item.nama)}')" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 text-xs text-gray-700 space-y-1">
                        <div class="flex items-start justify-between gap-2">
                            <span class="font-semibold text-gray-500">Nilai:</span>
                            <span class="text-right break-all font-mono">${escapeHtml(item.nilai)}</span>
                        </div>
                        ${item.tautan ? `
                            <div class="flex items-center justify-between gap-2 pt-1 border-t border-gray-200/60">
                                <span class="font-semibold text-gray-500">Tautan:</span>
                                <a href="${escapeHtml(item.tautan)}" target="_blank" class="text-blue-600 hover:underline truncate max-w-[180px]">
                                    ${escapeHtml(item.tautan)}
                                </a>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
        }).join('');
    }
}

/**
 * Filter status pesan kotak masuk
 */
function filterInboxStatus(status) {
    inboxFilter = status;
    const btns = {
        'semua': document.getElementById('btnFilterSemua'),
        'belum_dibaca': document.getElementById('btnFilterUnread'),
        'dibaca': document.getElementById('btnFilterRead')
    };

    Object.keys(btns).forEach(key => {
        const btn = btns[key];
        if (!btn) return;
        if (key === status) {
            btn.className = 'px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-200';
        } else {
            btn.className = 'px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100';
        }
    });

    renderInboxTable();
}

/**
 * Render Tabel Kotak Masuk
 */
function renderInboxTable() {
    const tbody = document.getElementById('inboxTableBody');
    const mobileContainer = document.getElementById('inboxCardContainer');
    const query = (document.getElementById('searchInboxInput')?.value || '').toLowerCase().trim();

    let filtered = allPesan.filter(p => {
        if (inboxFilter !== 'semua' && p.status !== inboxFilter) return false;

        return (p.nama && p.nama.toLowerCase().includes(query)) ||
               (p.email && p.email.toLowerCase().includes(query)) ||
               (p.subjek && p.subjek.toLowerCase().includes(query)) ||
               (p.pesan && p.pesan.toLowerCase().includes(query));
    });

    if (filtered.length === 0) {
        const emptyState = `
            <tr>
                <td colspan="6" class="text-center py-12 text-gray-400">
                    <i class="fas fa-envelope-open text-4xl mb-3 text-gray-300"></i>
                    <p class="font-medium text-sm">Tidak ada pesan masuk</p>
                    <p class="text-xs text-gray-400 mt-1">Pesan yang dikirim pengunjung melalui form kontak akan muncul di sini.</p>
                </td>
            </tr>
        `;
        if (tbody) tbody.innerHTML = emptyState;
        if (mobileContainer) {
            mobileContainer.innerHTML = `
                <div class="bg-white p-8 rounded-2xl border border-gray-100 text-center text-gray-400">
                    <i class="fas fa-envelope-open text-3xl mb-2 text-gray-300"></i>
                    <p class="text-sm font-medium">Tidak ada pesan masuk</p>
                </div>
            `;
        }
        return;
    }

    if (tbody) {
        tbody.innerHTML = filtered.map(msg => {
            const isUnread = msg.status === 'belum_dibaca';
            const snippet = msg.pesan.length > 70 ? msg.pesan.substring(0, 67) + '...' : msg.pesan;
            const waktuFormatted = formatTanggalWaktu(msg.created_at);

            return `
                <tr class="hover:bg-gray-50/80 transition-colors ${isUnread ? 'bg-amber-50/30 font-semibold' : ''}">
                    <td class="px-4 py-4 text-center">
                        <span class="inline-block w-2.5 h-2.5 rounded-full ${isUnread ? 'bg-amber-500 animate-pulse' : 'bg-gray-300'}" title="${isUnread ? 'Belum dibaca' : 'Sudah dibaca'}"></span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="text-sm font-bold text-gray-800">${escapeHtml(msg.nama)}</div>
                        <a href="mailto:${escapeHtml(msg.email)}" class="text-xs text-blue-600 hover:underline flex items-center gap-1 mt-0.5 font-normal">
                            <i class="fas fa-envelope text-[10px]"></i> ${escapeHtml(msg.email)}
                        </a>
                    </td>
                    <td class="px-5 py-4 max-w-sm cursor-pointer" onclick="showPesanDetail(${msg.id})">
                        <div class="text-sm text-gray-900 font-semibold flex items-center gap-1.5">
                            ${escapeHtml(msg.subjek || '(Tanpa Subjek)')}
                        </div>
                        <p class="text-xs text-gray-500 truncate mt-0.5 font-normal">${escapeHtml(snippet)}</p>
                    </td>
                    <td class="px-5 py-4 text-center text-xs text-gray-500 font-normal">
                        ${waktuFormatted}
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold ${
                            isUnread ? 'bg-amber-100 text-amber-800' : 'bg-emerald-50 text-emerald-700'
                        }">
                            ${isUnread ? 'Baru' : 'Dibaca'}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <button onclick="showPesanDetail(${msg.id})" title="Buka & Baca Pesan" 
                                    class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center text-xs">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="deletePesan(${msg.id})" title="Hapus Pesan" 
                                    class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all flex items-center justify-center text-xs">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    if (mobileContainer) {
        mobileContainer.innerHTML = filtered.map(msg => {
            const isUnread = msg.status === 'belum_dibaca';
            const waktuFormatted = formatTanggalWaktu(msg.created_at);

            return `
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm space-y-2.5 ${isUnread ? 'border-l-4 border-l-amber-500 bg-amber-50/20' : ''}">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">${escapeHtml(msg.nama)}</h4>
                            <span class="text-xs text-blue-600">${escapeHtml(msg.email)}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold ${
                            isUnread ? 'bg-amber-100 text-amber-800' : 'bg-emerald-50 text-emerald-700'
                        }">
                            ${isUnread ? 'Baru' : 'Dibaca'}
                        </span>
                    </div>

                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 cursor-pointer" onclick="showPesanDetail(${msg.id})">
                        <h5 class="text-xs font-bold text-gray-900">${escapeHtml(msg.subjek || '(Tanpa Subjek)')}</h5>
                        <p class="text-xs text-gray-600 mt-1 line-clamp-2">${escapeHtml(msg.pesan)}</p>
                    </div>

                    <div class="flex items-center justify-between pt-1 text-xs text-gray-400">
                        <span><i class="far fa-clock mr-1"></i>${waktuFormatted}</span>
                        <div class="flex items-center gap-2">
                            <button onclick="showPesanDetail(${msg.id})" class="px-3 py-1 rounded-lg bg-blue-50 text-blue-600 font-semibold">
                                Baca
                            </button>
                            <button onclick="deletePesan(${msg.id})" class="px-3 py-1 rounded-lg bg-rose-50 text-rose-600 font-semibold">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }
}

/**
 * Format tanggal & waktu ke bahasa Indonesia
 */
function formatTanggalWaktu(dateStr) {
    if (!dateStr) return '-';
    try {
        const d = new Date(dateStr.replace(' ', 'T'));
        if (isNaN(d.getTime())) return dateStr;
        return d.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (e) {
        return dateStr;
    }
}

/**
 * Modal Kontak: Buka & Reset (Sederhana)
 */
function openKontakModal(id = null) {
    const modal = document.getElementById('kontakModal');
    const form = document.getElementById('kontakForm');
    const title = document.getElementById('kontakModalTitle');
    const inputId = document.getElementById('kontakId');

    form.reset();
    inputId.value = '';
    title.innerHTML = '<i class="fas fa-address-card text-blue-600"></i> <span>Tambah Saluran Kontak</span>';

    modal.classList.remove('hidden');
}

/**
 * Modal Kontak: Tutup
 */
function closeKontakModal() {
    const modal = document.getElementById('kontakModal');
    if (modal) modal.classList.add('hidden');
}

/**
 * Edit Saluran Kontak (Sederhana)
 */
function editKontak(id) {
    const item = allKontak.find(k => parseInt(k.id) === parseInt(id));
    if (!item) return;

    const modal = document.getElementById('kontakModal');
    const title = document.getElementById('kontakModalTitle');
    const inputId = document.getElementById('kontakId');
    const inputNama = document.getElementById('kontakNama');
    const inputNilai = document.getElementById('kontakNilai');
    const inputTautan = document.getElementById('kontakTautan');

    inputId.value = item.id;
    inputNama.value = item.nama;
    inputNilai.value = item.nilai;
    inputTautan.value = item.tautan || '';

    title.innerHTML = '<i class="fas fa-edit text-blue-600"></i> <span>Edit Saluran Kontak</span>';
    modal.classList.remove('hidden');
}

/**
 * Submit Form Kontak (Tambah / Edit)
 */
async function handleKontakSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSimpanKontak');
    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    try {
        const form = e.target;
        const formData = new FormData(form);
        const id = document.getElementById('kontakId').value;
        const isEdit = Boolean(id);

        const url = isEdit 
            ? `${window.API_URL || ''}/kontak-info/${id}` 
            : `${window.API_URL || ''}/kontak-info`;

        const res = await fetch(url, {
            method: 'POST',
            body: formData
        });

        const json = await res.json();
        if (json.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: json.message || 'Data kontak tersimpan.',
                timer: 1500,
                showConfirmButton: false
            });
            closeKontakModal();
            await fetchKontak();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: json.message || 'Terjadi kesalahan saat menyimpan.'
            });
        }
    } catch (err) {
        console.error('Error submitting form:', err);
        Swal.fire({
            icon: 'error',
            title: 'Error Jaringan',
            text: 'Tidak dapat menghubungi server.'
        });
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

/**
 * Hapus Saluran Kontak
 */
function deleteKontak(id, nama) {
    Swal.fire({
        title: 'Hapus Saluran Kontak?',
        text: `Apakah Anda yakin ingin menghapus "${nama}"? Data yang terhapus tidak dapat dikembalikan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const res = await fetch(`${window.API_URL || ''}/kontak-info/${id}`, {
                    method: 'DELETE'
                });
                const json = await res.json();
                if (json.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: json.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    await fetchKontak();
                } else {
                    Swal.fire('Gagal', json.message, 'error');
                }
            } catch (err) {
                Swal.fire('Error', 'Gagal menghapus saluran kontak', 'error');
            }
        }
    });
}

/**
 * Tampilkan Modal Detail Pesan
 */
async function showPesanDetail(id) {
    try {
        const res = await fetch(`${window.API_URL || ''}/kontak-pesan/${id}`);
        const json = await res.json();
        if (json.status === 'success') {
            const data = json.data;

            document.getElementById('pesanNama').textContent = data.nama;
            document.getElementById('pesanEmail').textContent = data.email;
            document.getElementById('pesanEmailLink').href = `mailto:${data.email}?subject=Re: ${encodeURIComponent(data.subjek || 'Pesan Anda di IC-Labs')}`;
            document.getElementById('pesanSubjek').textContent = data.subjek || '(Tanpa Subjek)';
            document.getElementById('pesanIsi').textContent = data.pesan;
            document.getElementById('pesanWaktuHeader').textContent = `Diterima pada: ${formatTanggalWaktu(data.created_at)}`;

            // Tombol Balas Email
            const btnBalas = document.getElementById('btnBalasEmail');
            btnBalas.href = `mailto:${data.email}?subject=Re: ${encodeURIComponent(data.subjek || 'Pesan Anda di IC-Labs')}`;

            // Tombol Hapus di modal detail
            const btnHapus = document.getElementById('btnHapusPesanDetail');
            btnHapus.onclick = function () {
                closePesanModal();
                deletePesan(data.id);
            };

            // Update status di lokal
            const localMsg = allPesan.find(p => parseInt(p.id) === parseInt(id));
            if (localMsg) {
                localMsg.status = 'dibaca';
            }
            updatePesanStats();
            renderInboxTable();

            document.getElementById('pesanModal').classList.remove('hidden');
        }
    } catch (e) {
        console.error('Error fetching pesan detail:', e);
    }
}

/**
 * Tutup Modal Detail Pesan
 */
function closePesanModal() {
    const modal = document.getElementById('pesanModal');
    if (modal) modal.classList.add('hidden');
}

/**
 * Hapus Pesan dari Kotak Masuk
 */
function deletePesan(id) {
    Swal.fire({
        title: 'Hapus Pesan?',
        text: 'Pesan ini akan dihapus permanen dari Kotak Masuk.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const res = await fetch(`${window.API_URL || ''}/kontak-pesan/${id}`, {
                    method: 'DELETE'
                });
                const json = await res.json();
                if (json.status === 'success') {
                    allPesan = allPesan.filter(p => parseInt(p.id) !== parseInt(id));
                    updatePesanStats();
                    renderInboxTable();

                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: json.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Gagal', json.message, 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Gagal menghapus pesan', 'error');
            }
        }
    });
}

/**
 * Salin teks ke Clipboard
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true
        });
        Toast.fire({
            icon: 'info',
            title: 'Disalin ke clipboard!'
        });
    });
}

/**
 * Utility sanitasi string HTML
 */
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, "&amp;")
              .replace(/</g, "&lt;")
              .replace(/>/g, "&gt;")
              .replace(/"/g, "&quot;")
              .replace(/'/g, "&#039;");
}

/**
 * Utility escape string JS
 */
function escapeJsString(str) {
    if (!str) return '';
    return str.replace(/'/g, "\\'").replace(/"/g, '\\"').replace(/\n/g, ' ');
}
