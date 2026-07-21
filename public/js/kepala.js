/**
 * KEPALA.JS - FIXED VERSION
 * Mendukung pencarian untuk Staff Card (.staff-role) dan Exec Card (.exec-role)
 * Serta mendukung detail profile dalam bentuk Modal
 */

document.addEventListener('DOMContentLoaded', function() {
    // ============================================
    // 1. FITUR SEARCH / PENCARIAN STAFF
    // ============================================
    const searchInput = document.getElementById('searchStaff');
    const cards = document.querySelectorAll('.card-link'); // Target pembungkus kartu

    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            const term = e.target.value.toLowerCase();

            cards.forEach(card => {
                // 1. Ambil Nama (Classnya sama: .staff-name)
                const nameEl = card.querySelector('.staff-name');
                
                // 2. Ambil Jabatan (Cek apakah dia Staff atau Exec/Kepala)
                const roleEl = card.querySelector('.staff-role, .exec-role');

                if (nameEl && roleEl) {
                    const name = nameEl.textContent.toLowerCase();
                    const role = roleEl.textContent.toLowerCase();

                    // Filter Logic
                    if (name.includes(term) || role.includes(term)) {
                        card.style.display = ''; // Tampilkan
                        card.style.opacity = '1'; 
                    } else {
                        card.style.display = 'none'; // Sembunyikan
                        card.style.opacity = '0';
                    }
                }
            });
        });
    }

    // ============================================
    // 2. MODAL SYSTEM UNTUK KEPALA & STAFF
    // ============================================
    const modal = document.getElementById("profileModal");
    const modalLoading = document.getElementById("modalLoading");
    const modalError = document.getElementById("modalError");
    const modalBody = document.getElementById("modalBody");

    const modalImg = document.getElementById("modalImg");
    const modalCategory = document.getElementById("modalCategory");
    const modalName = document.getElementById("modalName");
    const modalRole = document.getElementById("modalRole");
    const modalSubInfo = document.querySelector("#modalSubInfo span");
    const modalSubIcon = document.querySelector("#modalSubInfo i");
    const modalEmailBox = document.getElementById("modalEmailBox");
    const modalEmail = document.getElementById("modalEmail");
    const modalBio = document.getElementById("modalBio");
    const modalSkillsSection = document.getElementById("modalSkillsSection");
    const modalSkillsContainer = document.getElementById("modalSkillsContainer");
    const modalMailBtn = document.getElementById("modalMailBtn");
    const modalMailDisabled = document.getElementById("modalMailDisabled");

    const openModal = () => {
        if (!modal) return;
        modal.classList.add("active");
        document.body.style.overflow = "hidden"; // Prevent scrolling behind
    };

    const closeModal = () => {
        if (!modal) return;
        modal.classList.remove("active");
        document.body.style.overflow = ""; // Restore scrolling
    };

    const closeBtn = document.getElementById("closeProfileModal");
    if (closeBtn) {
        closeBtn.addEventListener("click", closeModal);
    }

    if (modal) {
        // Close modal on click outside container
        modal.addEventListener("click", function (e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Close modal on ESC key
        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape" && modal.classList.contains("active")) {
                closeModal();
            }
        });
    }

    document.addEventListener(
        "click",
        function (e) {
            const detailLink = e.target.closest(".staff-detail-link");
            if (detailLink) {
                e.preventDefault();
                
                const id = detailLink.getAttribute("data-id");
                const type = detailLink.getAttribute("data-type") || "manajemen";

                if (id) {
                    // Reset modal state
                    modalLoading.style.display = "flex";
                    modalError.style.display = "none";
                    modalBody.style.display = "none";
                    openModal();

                    const base = window.PUBLIC_URL || '';
                    const apiUrl = `${base}/api/sumberdaya/detail/${id}?type=${type}`;

                    fetch(apiUrl)
                        .then(res => {
                            if (!res.ok) throw new Error("Gagal mengambil data");
                            return res.json();
                        })
                        .then(res => {
                            if (!res.status || !res.data) throw new Error(res.message || "Data kosong");
                            const d = res.data;

                            // Populate Modal Content
                            modalImg.src = d.foto_url;
                            modalImg.alt = d.nama;
                            modalImg.style.objectPosition = `${d.foto_pos_x}% ${d.foto_pos_y}%`;
                            
                            modalCategory.textContent = d.kategori;
                            modalCategory.className = `category-badge ${d.badge_style}`;
                            
                            modalName.textContent = d.nama;
                            modalRole.textContent = d.jabatan;
                            modalSubInfo.textContent = d.sub_info;

                            // Dynamic icon selection
                            if (modalSubIcon) {
                                modalSubIcon.className = d.sub_icon || 'ri-id-card-line';
                            }

                            // Email
                            if (d.email && d.email !== "-") {
                                modalEmail.textContent = d.email;
                                modalEmailBox.style.display = "flex";
                                modalMailBtn.href = `mailto:${d.email}`;
                                modalMailBtn.style.display = "inline-flex";
                                modalMailDisabled.style.display = "none";
                            } else {
                                modalEmailBox.style.display = "none";
                                modalMailBtn.style.display = "none";
                                modalMailDisabled.style.display = "inline-flex";
                            }

                            // Bio
                            modalBio.innerHTML = d.bio.replace(/\n/g, "<br>");

                            // Skills (Manajemen doesn't usually have skills, but handle just in case)
                            modalSkillsContainer.innerHTML = "";
                            if (d.skills && d.skills.length > 0) {
                                d.skills.forEach(skill => {
                                    const tag = document.createElement("span");
                                    tag.className = "skill-tag";
                                    tag.textContent = skill;
                                    modalSkillsContainer.appendChild(tag);
                                });
                                modalSkillsSection.style.display = "block";
                            } else {
                                modalSkillsSection.style.display = "none";
                            }

                            // Show content
                            modalLoading.style.display = "none";
                            modalBody.style.display = "block";
                        })
                        .catch(err => {
                            console.error(err);
                            modalLoading.style.display = "none";
                            modalError.style.display = "flex";
                        });
                }
            }
        },
        { passive: false } // Crucial so e.preventDefault() is respected
    );
});