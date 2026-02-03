let allAlumniData = [];

document.addEventListener("DOMContentLoaded", () => {
  loadAlumni();

  // Live Search
  document
    .getElementById("searchInput")
    .addEventListener("keyup", function (e) {
      const keyword = e.target.value.toLowerCase();
      const filtered = allAlumniData.filter(
        (item) =>
          (item.nama && item.nama.toLowerCase().includes(keyword)) ||
          (item.email && item.email.toLowerCase().includes(keyword)) ||
          (item.angkatan && item.angkatan.toString().includes(keyword)) ||
          (item.divisi && item.divisi.toLowerCase().includes(keyword)),
      );
      renderTable(filtered);
    });
});

function loadAlumni() {
  fetch("/api/alumni")
    .then((res) => res.json())
    .then((res) => {
      if ((res.status === true || res.status === "success" || res.code === 200) && res.data) {
        allAlumniData = res.data;
        renderTable(allAlumniData);
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

  if (totalEl) totalEl.innerText = `Total: ${data.length}`;

  if (data && data.length > 0) {
    let rowsHtml = "";
    data.forEach((item, index) => {
      const fotoUrl = item.foto
        ? item.foto.includes("http")
          ? item.foto
          : ASSETS_URL + "/assets/uploads/" + item.foto
        : `https://ui-avatars.com/api/?name=${encodeURIComponent(item.nama)}&background=random&color=fff`;
      rowsHtml += `
                <tr onclick="openDetailModal(${item.id})" class="hover:bg-blue-50 transition-colors duration-150 cursor-pointer group border-b border-gray-100">
                    <td class="px-6 py-4 text-center font-medium text-gray-500">${index + 1}</td>
                    <td class="px-6 py-4"><div class="flex justify-center"><img src="${fotoUrl}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-md"></div></td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-800 text-sm group-hover:text-blue-600 transition-colors">${item.nama}</span>
                            <span class="text-[10px] text-gray-500 uppercase mt-0.5">${item.divisi || "Alumni"}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center"><span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-semibold border border-blue-100">${item.angkatan || "-"}</span></td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center items-center gap-2">
                            <button onclick="openFormModal(${item.id}, event)" class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-all shadow-sm flex items-center justify-center"><i class="fas fa-pen text-xs"></i></button>
                            <button onclick="hapusAlumni(${item.id}, event)" class="w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-all shadow-sm flex items-center justify-center"><i class="fas fa-trash-alt text-xs"></i></button>
                        </div>
                    </td>
                </tr>`;
    });
    tbody.innerHTML = rowsHtml;
  } else {
    tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Data alumni tidak ditemukan</td></tr>`;
  }
}

function openDetailModal(id) {
  document.getElementById("detailModal").classList.remove("hidden");
  document.body.style.overflow = "hidden";
  document.getElementById("dNama").innerText = "Memuat...";
  fetch("/api/alumni/" + id)
    .then((res) => res.json())
    .then((res) => {
      if ((res.status === "success" || res.code === 200) && res.data) {
        const d = res.data;
        document.getElementById("dNama").innerText = d.nama;
        document.getElementById("dDivisi").innerText = d.divisi || "Alumni";
        document.getElementById("dAngkatan").innerText = d.angkatan;
        document.getElementById("dEmail").innerText = d.email || "-";
        document.getElementById("dMataKuliah").innerText = d.mata_kuliah || "-";
        document.getElementById("dKesan").innerText = d.kesan_pesan
          ? `"${d.kesan_pesan}"`
          : "-";
        const fotoUrl = d.foto
          ? d.foto.includes("http")
            ? d.foto
            : ASSETS_URL + "/assets/uploads/" + d.foto
          : `https://placehold.co/150x150?text=${d.nama.charAt(0)}`;
        document.getElementById("dFoto").src = fotoUrl;
        const sDiv = document.getElementById("dKeahlian");
        sDiv.innerHTML = "";
        let skills = d.keahlian;
        try {
          if (skills.includes("[")) skills = JSON.parse(skills);
          else skills = skills.split(",");
        } catch (e) {
          if (skills) skills = skills.split(",");
        }
        if (Array.isArray(skills)) {
          skills.forEach((s) => {
            if (s.trim())
              sDiv.innerHTML += `<span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold border border-blue-100">${s.trim()}</span>`;
          });
        } else {
          sDiv.innerHTML = "-";
        }
      }
    });
}

function openFormModal(id = null, event = null) {
  if (event) event.stopPropagation();
  document.getElementById("formModal").classList.remove("hidden");
  document.body.style.overflow = "hidden";
  document.getElementById("formMessage").classList.add("hidden");
  document.getElementById("alumniForm").reset();
  document.getElementById("inputId").value = "";
  document.getElementById("fotoPreviewInfo").classList.add("hidden");

  if (id) {
    document.getElementById("formModalTitle").innerHTML =
      '<i class="fas fa-user-edit text-blue-600"></i> Edit Alumni';
    document.getElementById("btnSave").innerHTML =
      '<i class="fas fa-save"></i> Update Data';
    // Isi Form untuk Edit
    fetch("/api/alumni/" + id)
      .then((res) => res.json())
      .then((res) => {
        if (res.data) {
          const d = res.data;
          document.getElementById("inputId").value = d.id;
          document.getElementById("inputNama").value = d.nama;
          document.getElementById("inputAngkatan").value = d.angkatan;
          document.getElementById("inputDivisi").value = d.divisi || "";

          // --- UPDATE KEAHLIAN TAGS ---
          let keahlianArray = [];
          try {
            if (d.keahlian.startsWith("[") || d.keahlian.startsWith("{")) {
              let parsed = JSON.parse(d.keahlian);
              keahlianArray = Array.isArray(parsed) ? parsed : [parsed];
            } else if (d.keahlian) {
              keahlianArray = d.keahlian.split(",").map((item) => item.trim());
            }
          } catch (e) {
            if (d.keahlian)
              keahlianArray = d.keahlian.split(",").map((item) => item.trim());
          }

          selectedKeahlian = [];
          const container = document.getElementById("keahlianTagContainer");
          container.querySelectorAll(".skill-tag").forEach((el) => el.remove());

          keahlianArray.forEach((skill) => {
            if (skill) addKeahlianTag(skill, false);
          });

          // --- UPDATE MATA KULIAH TAGS ---
          let matkulArray = [];
          try {
            if (
              d.mata_kuliah.startsWith("[") ||
              d.mata_kuliah.startsWith("{")
            ) {
              let parsed = JSON.parse(d.mata_kuliah);
              matkulArray = Array.isArray(parsed) ? parsed : [parsed];
            } else if (d.mata_kuliah) {
              matkulArray = d.mata_kuliah.split(",").map((item) => item.trim());
            }
          } catch (e) {
            if (d.mata_kuliah)
              matkulArray = d.mata_kuliah.split(",").map((item) => item.trim());
          }

          selectedMatkul = [];
          const matkulContainer = document.getElementById("matkulTagContainer");
          matkulContainer
            .querySelectorAll(".matkul-tag")
            .forEach((el) => el.remove());

          matkulArray.forEach((m) => {
            if (m) addMatkulTag(m, false);
          });

          document.getElementById("inputEmail").value = d.email || "";
          document.getElementById("inputKesanPesan").value =
            d.kesan_pesan || "";
          document.getElementById("inputMataKuliah").value =
            d.mata_kuliah || "";

          if (d.foto)
            document
              .getElementById("fotoPreviewInfo")
              .classList.remove("hidden");
        }
      });
  } else {
    document.getElementById("formModalTitle").innerHTML =
      '<i class="fas fa-user-plus text-blue-600"></i> Tambah Alumni Baru';
    document.getElementById("btnSave").innerHTML =
      '<i class="fas fa-save"></i> Simpan Data';

    // Reset Keahlian Tags
    selectedKeahlian = [];
    const container = document.getElementById("keahlianTagContainer");
    container.querySelectorAll(".skill-tag").forEach((el) => el.remove());
    document.getElementById("inputKeahlian").value = "";

    // Reset Matkul Tags
    selectedMatkul = [];
    const matkulContainer = document.getElementById("matkulTagContainer");
    matkulContainer
      .querySelectorAll(".matkul-tag")
      .forEach((el) => el.remove());
    document.getElementById("inputMataKuliah").value = "";
  }
}

document.getElementById("alumniForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const btn = document.getElementById("btnSave");
  const formMsg = document.getElementById("formMessage");
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';

  const formData = new FormData(this);
  const id = document.getElementById("inputId").value;
  const url = id ? "/api/alumni/" + id : "/api/alumni";

  fetch(url, { method: "POST", body: formData })
    .then((res) => res.json())
    .then((data) => {
      hideLoading();
      if (data.status === "success" || data.code === 201 || data.code === 200) {
        closeModal("formModal");
        loadAlumni();
        showSuccess(
          id
            ? "Data alumni berhasil diperbarui!"
            : "Alumni baru berhasil ditambahkan!",
        );
      } else {
        throw new Error(data.message || "Gagal menyimpan");
      }
    })
    .catch((err) => {
      hideLoading();
      formMsg.innerHTML = `<div class="bg-red-50 text-red-800 p-3 rounded text-sm"><i class="fas fa-exclamation-circle"></i> ${err.message}</div>`;
      formMsg.classList.remove("hidden");
      showError(err.message);
    })
    .finally(() => {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
    });
});

function closeModal(modalId) {
  document.getElementById(modalId).classList.add("hidden");
  document.body.style.overflow = "auto";
}
document.onkeydown = function (evt) {
  if (evt.keyCode == 27) {
    closeModal("detailModal");
    closeModal("formModal");
  }
};
function hapusAlumni(id, event) {
  if (event) event.stopPropagation();
  confirmDelete(() => {
    showLoading("Menghapus data...");
    fetch("/api/alumni/" + id, { method: "DELETE" })
      .then((res) => res.json())
      .then(() => {
        hideLoading();
        loadAlumni();
        showSuccess("Data alumni berhasil dihapus!");
      })
      .catch((err) => {
        hideLoading();
        showError("Gagal menghapus data");
      });
  });
}

// --- 4. SISTEM TAGGING KEAHLIAN (LinkedIn Style) ---
const IT_SKILLS_LIST = [
  "Web Development",
  "PHP",
  "Laravel",
  "Javascript",
  "React",
  "Vue",
  "Node.js",
  "Express",
  "Tailwind CSS",
  "Bootstrap",
  "Database Management",
  "SQL",
  "MySQL",
  "PostgreSQL",
  "MongoDB",
  "Firebase",
  "Mobile Development",
  "Flutter",
  "React Native",
  "Android Studio",
  "iOS Development",
  "Artificial Intelligence",
  "Machine Learning",
  "Python",
  "Data Science",
  "R Programming",
  "Cybersecurity",
  "Networking",
  "Cisco",
  "Linux Administration",
  "Cloud Computing",
  "AWS",
  "Google Cloud",
  "UI/UX Design",
  "Figma",
  "Adobe XD",
  "Internet of Things (IoT)",
  "Arduino",
  "Raspberry Pi",
  "Game Development",
  "Unity",
  "C#",
  "C++",
];

const COURSE_LIST = [
  "Pemrograman Dasar",
  "Basis Data",
  "Pemrograman Web",
  "Pemrograman Berorientasi Objek",
  "Struktur Data",
  "Algoritma & Pemrograman",
  "Sistem Operasi",
  "Jaringan Komputer",
  "Analisis & Desain Sistem",
  "Rekayasa Perangkat Lunak",
  "Kecerdasan Buatan",
  "Mobile Programming",
  "Cloud Computing",
  "Keamanan Informasi",
  "Interaksi Manusia & Komputer",
];

let selectedKeahlian = [];
let selectedMatkul = [];

function initKeahlianTagging() {
  const container = document.getElementById("keahlianTagContainer");
  const tagInput = document.getElementById("tagInputKeahlian");
  const suggestionBox = document.getElementById("keahlianSuggestions");

  if (!container || !tagInput) return;

  container.addEventListener("click", () => tagInput.focus());

  tagInput.addEventListener("input", (e) => {
    const val = e.target.value.toLowerCase();
    if (!val) {
      suggestionBox.classList.add("hidden");
      return;
    }

    const filtered = IT_SKILLS_LIST.filter(
      (s) => s.toLowerCase().includes(val) && !selectedKeahlian.includes(s),
    ).slice(0, 5);

    if (filtered.length > 0) {
      suggestionBox.innerHTML = filtered
        .map(
          (s) => `
                <div class="px-4 py-2 hover:bg-blue-50 cursor-pointer flex items-center gap-2 group" onclick="addKeahlianTag('${s.replace(/'/g, "\\'")}')">
                    <i class="fas fa-plus-circle text-gray-300 group-hover:text-blue-500 transition-colors"></i>
                    <span class="text-sm font-medium text-gray-700">${s}</span>
                </div>
            `,
        )
        .join("");
      suggestionBox.classList.remove("hidden");
    } else {
      suggestionBox.innerHTML = `
                <div class="px-4 py-2 hover:bg-blue-50 cursor-pointer flex items-center gap-2 group" onclick="addKeahlianTag('${tagInput.value.replace(/'/g, "\\'")}')">
                    <i class="fas fa-plus-circle text-blue-500"></i>
                    <span class="text-sm font-medium text-gray-700">Tambahkan "${tagInput.value}"</span>
                </div>`;
      suggestionBox.classList.remove("hidden");
    }
  });

  tagInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      const val = tagInput.value.trim();
      if (val) addKeahlianTag(val);
    } else if (
      e.key === "Backspace" &&
      !tagInput.value &&
      selectedKeahlian.length > 0
    ) {
      removeKeahlianTag(selectedKeahlian[selectedKeahlian.length - 1]);
    }
  });

  document.addEventListener("click", (e) => {
    if (!container.contains(e.target) && !suggestionBox.contains(e.target)) {
      suggestionBox.classList.add("hidden");
    }
  });
}

function initMatkulTagging() {
  const container = document.getElementById("matkulTagContainer");
  const tagInput = document.getElementById("tagInputMatkul");
  const suggestionBox = document.getElementById("matkulSuggestions");

  if (!container || !tagInput) return;

  container.addEventListener("click", () => tagInput.focus());

  tagInput.addEventListener("input", (e) => {
    const val = e.target.value.toLowerCase();
    if (!val) {
      suggestionBox.classList.add("hidden");
      return;
    }

    const filtered = COURSE_LIST.filter(
      (s) => s.toLowerCase().includes(val) && !selectedMatkul.includes(s),
    ).slice(0, 5);

    if (filtered.length > 0) {
      suggestionBox.innerHTML = filtered
        .map(
          (s) => `
                <div class="px-4 py-2 hover:bg-emerald-50 cursor-pointer flex items-center gap-2 group" onclick="addMatkulTag('${s.replace(/'/g, "\\'")}')">
                    <i class="fas fa-plus-circle text-gray-300 group-hover:text-emerald-500 transition-colors"></i>
                    <span class="text-sm font-medium text-gray-700">${s}</span>
                </div>
            `,
        )
        .join("");
      suggestionBox.classList.remove("hidden");
    } else {
      suggestionBox.innerHTML = `
                <div class="px-4 py-2 hover:bg-emerald-50 cursor-pointer flex items-center gap-2 group" onclick="addMatkulTag('${tagInput.value.replace(/'/g, "\\'")}')">
                    <i class="fas fa-plus-circle text-emerald-500"></i>
                    <span class="text-sm font-medium text-gray-700">Tambahkan "${tagInput.value}"</span>
                </div>`;
      suggestionBox.classList.remove("hidden");
    }
  });

  tagInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      const val = tagInput.value.trim();
      if (val) addMatkulTag(val);
    } else if (
      e.key === "Backspace" &&
      !tagInput.value &&
      selectedMatkul.length > 0
    ) {
      removeMatkulTag(selectedMatkul[selectedMatkul.length - 1]);
    }
  });

  document.addEventListener("click", (e) => {
    if (!container.contains(e.target) && !suggestionBox.contains(e.target)) {
      suggestionBox.classList.add("hidden");
    }
  });
}

function addKeahlianTag(skill, focus = true) {
  if (!skill || selectedKeahlian.includes(skill)) {
    document.getElementById("tagInputKeahlian").value = "";
    document.getElementById("keahlianSuggestions").classList.add("hidden");
    return;
  }

  selectedKeahlian.push(skill);
  updateHiddenInputKeahlian();

  const container = document.getElementById("keahlianTagContainer");
  const tagInput = document.getElementById("tagInputKeahlian");

  const tagEl = document.createElement("div");
  tagEl.className =
    "skill-tag inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold border border-blue-100 animate-in fade-in zoom-in duration-200";
  tagEl.dataset.skill = skill;
  tagEl.innerHTML = `
        <span>${skill}</span>
        <button type="button" onclick="event.stopPropagation(); removeKeahlianTag('${skill.replace(/'/g, "\\'")}')" class="hover:text-blue-900 transition-colors">
            <i class="fas fa-times-circle"></i>
        </button>
    `;

  container.insertBefore(tagEl, tagInput);
  tagInput.value = "";
  document.getElementById("keahlianSuggestions").classList.add("hidden");
  if (focus) tagInput.focus();
}

function removeKeahlianTag(skill) {
  selectedKeahlian = selectedKeahlian.filter((s) => s !== skill);
  updateHiddenInputKeahlian();
  const tagEl = document.querySelector(`.skill-tag[data-skill="${skill}"]`);
  if (tagEl) tagEl.remove();
}

function updateHiddenInputKeahlian() {
  document.getElementById("inputKeahlian").value =
    JSON.stringify(selectedKeahlian);
}

function addMatkulTag(matkul, focus = true) {
  if (!matkul || selectedMatkul.includes(matkul)) {
    document.getElementById("tagInputMatkul").value = "";
    document.getElementById("matkulSuggestions").classList.add("hidden");
    return;
  }

  selectedMatkul.push(matkul);
  updateHiddenInputMatkul();

  const container = document.getElementById("matkulTagContainer");
  const tagInput = document.getElementById("tagInputMatkul");

  const tagEl = document.createElement("div");
  tagEl.className =
    "matkul-tag inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-100 animate-in fade-in zoom-in duration-200";
  tagEl.dataset.matkul = matkul;
  tagEl.innerHTML = `
        <span>${matkul}</span>
        <button type="button" onclick="event.stopPropagation(); removeMatkulTag('${matkul.replace(/'/g, "\\'")}')" class="hover:text-emerald-900 transition-colors">
            <i class="fas fa-times-circle"></i>
        </button>
    `;

  container.insertBefore(tagEl, tagInput);
  tagInput.value = "";
  document.getElementById("matkulSuggestions").classList.add("hidden");
  if (focus) tagInput.focus();
}

function removeMatkulTag(matkul) {
  selectedMatkul = selectedMatkul.filter((s) => s !== matkul);
  updateHiddenInputMatkul();
  const tagEl = document.querySelector(`.matkul-tag[data-matkul="${matkul}"]`);
  if (tagEl) tagEl.remove();
}

function updateHiddenInputMatkul() {
  document.getElementById("inputMataKuliah").value =
    JSON.stringify(selectedMatkul);
}

document.addEventListener("DOMContentLoaded", () => {
  initKeahlianTagging();
  initMatkulTagging();
});
