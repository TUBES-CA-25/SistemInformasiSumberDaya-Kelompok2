window.BASE_URL = "<?= BASE_URL ?>";
let allDosenData = [];

document.addEventListener("DOMContentLoaded", function () {
    loadDosen();

    // Live Search Listener
    document
        .getElementById("searchInput")
        .addEventListener("keyup", function (e) {
            const keyword = e.target.value.toLowerCase();
            const filtered = allDosenData.filter(
                (item) =>
                    (item.nama && item.nama.toLowerCase().includes(keyword)) ||
                    (item.nip && item.nip.toLowerCase().includes(keyword)) ||
                    (item.email && item.email.toLowerCase().includes(keyword))
            );
            renderTable(filtered);
        });
});

// --- 1. LOAD DATA ---
function loadDosen() {
    fetch(API_URL + "/dosen")
        .then((res) => res.json())
        .then((res) => {
            if ((res.status === "success" || res.code === 200) && res.data) {
                allDosenData = res.data;
                renderTable(allDosenData);
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

function renderTable(data) {
    const tbody = document.getElementById("tableBody");
    const totalEl = document.getElementById("totalData");
    tbody.innerHTML = "";
    totalEl.innerText = `Total: ${data.length}`;

    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-search text-2xl mb-2"></i><p>Tidak ada data ditemukan</p></td></tr>`;
        return;
    }

    let rowsHtml = "";
    data.forEach((item, index) => {
        const nipVal = item.nip || "-";
        const emailVal = item.email || "-";
        const statusVal = item.status || "Aktif";

        // Status Badge
        const statusColor =
            statusVal === "Aktif"
                ? "bg-green-100 text-green-800 border-green-200"
                : "bg-red-100 text-red-800 border-red-200";

        rowsHtml += `
            <tr onclick="openFormModal(${item.idDosen}, event)" class="hover:bg-blue-50 transition-colors duration-150 group border-b border-gray-100 cursor-pointer">
                <td class="px-6 py-4 text-center font-medium text-gray-500">${index + 1}</td>
                <td class="px-6 py-4 text-center">
                    <span class="font-mono text-sm bg-gray-50 text-gray-700 px-2 py-1 rounded border border-gray-200">${nipVal}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="font-bold text-gray-800 text-sm block">${item.nama || "-"}</span>
                </td>
                <td class="px-6 py-4 text-center font-medium text-gray-600">
                    ${emailVal}
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="${statusColor} px-2.5 py-1 rounded-full text-xs font-semibold border whitespace-nowrap">${statusVal}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center items-center gap-2">
                        <button onclick="openFormModal(${item.idDosen}, event)" class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-all shadow-sm flex items-center justify-center border border-transparent" title="Edit">
                            <i class="fas fa-pen text-xs"></i>
                        </button>
                        <button onclick="hapusDosen(${item.idDosen}, event)" class="w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-all shadow-sm flex items-center justify-center border border-transparent" title="Hapus">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </td>
            </tr>`;
    });
    tbody.innerHTML = rowsHtml;
}

// --- 2. MODAL FORM ---
function openFormModal(id = null, event = null) {
    if (event) event.stopPropagation();
    document.getElementById("formModal").classList.remove("hidden");
    document.body.style.overflow = "hidden";
    document.getElementById("formMessage").classList.add("hidden");
    document.getElementById("dosenForm").reset();
    document.getElementById("inputId").value = "";

    if (id) {
        document.getElementById("formModalTitle").innerHTML =
            '<i class="fas fa-edit text-blue-600"></i> Edit Dosen';
        document.getElementById("btnSave").innerHTML =
            '<i class="fas fa-save"></i> Update Data';

        const data = allDosenData.find((i) => i.idDosen == id);
        if (data) {
            document.getElementById("inputId").value = data.idDosen;
            document.getElementById("inputNip").value = data.nip || "";
            document.getElementById("inputNama").value = data.nama;
            document.getElementById("inputEmail").value = data.email || "";
            document.getElementById("inputStatus").value = data.status || "Aktif";
        }
    } else {
        document.getElementById("formModalTitle").innerHTML =
            '<i class="fas fa-plus text-emerald-600"></i> Tambah Dosen';
        document.getElementById("btnSave").innerHTML =
            '<i class="fas fa-save"></i> Simpan Data';
    }
}

// --- 3. SUBMIT FORM (JSON MODE) ---
document.getElementById("dosenForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const btn = document.getElementById("btnSave");
    const msg = document.getElementById("formMessage");

    const id = document.getElementById("inputId").value;
    const url = id ? API_URL + "/dosen/" + id : API_URL + "/dosen";
    const method = id ? "PUT" : "POST";

    const formData = new FormData(this);
    const dataObj = Object.fromEntries(formData.entries());

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
    showLoading("Menyimpan dosen...");

    fetch(url, {
        method: method,
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(dataObj),
    })
        .then((res) => res.json())
        .then((data) => {
            hideLoading();
            if (data.status === "success" || data.code === 200 || data.code === 201) {
                closeModal("formModal");
                loadDosen();
                showSuccess(
                    id
                        ? "Data dosen berhasil diperbarui!"
                        : "Data dosen baru berhasil ditambahkan!",
                );
            } else {
                throw new Error(data.message || "Gagal menyimpan data");
            }
        })
        .catch((err) => {
            hideLoading();
            msg.innerHTML = `<div class="bg-red-50 text-red-800 p-3 rounded text-sm mb-4 border border-red-200 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> ${err.message}</div>`;
            msg.classList.remove("hidden");
            showError(err.message);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
        });
});

// --- HELPER FUNCTIONS ---
function closeModal(modalId) {
    document.getElementById(modalId).classList.add("hidden");
    document.body.style.overflow = "auto";
}

function hapusDosen(id, event) {
    if (event) event.stopPropagation();
    confirmDelete(() => {
        showLoading("Menghapus data...");
        fetch(API_URL + "/dosen/" + id, { method: "DELETE" })
            .then((res) => res.json())
            .then(() => {
                hideLoading();
                loadDosen();
                showSuccess("Data dosen berhasil dihapus!");
            })
            .catch((err) => {
                hideLoading();
                showError("Gagal menghapus data");
            });
    });
}

document.onkeydown = function (evt) {
    if (evt.keyCode == 27) {
        closeModal("formModal");
    }
};
