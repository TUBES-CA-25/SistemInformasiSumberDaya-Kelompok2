document.addEventListener("DOMContentLoaded", () => {
  const tooltip = document.getElementById("room-tooltip");
  const ttImg = document.getElementById("tt-image");
  const ttTitle = document.getElementById("tt-title");
  const ttDesc = document.getElementById("tt-desc");
  const ttStatus = document.getElementById("tt-status");

  // DATA RUANGAN (Database Sementara)
  const roomData = {
    // --- LABORATORIUM UTAMA (L1 - L7) ---
    L1: {
      title: "L1 - IoT Laboratory",
      desc: "Laboratorium Internet of Things. Praktikum sensor, mikrokontroler, dan sistem tertanam.",
      img: "/images/l1.jpg",
      status: "Available",
    },
    L2: {
      title: "L2 - StartUp Laboratory",
      desc: "Inkubator bisnis digital dan tempat pengembangan rintisan teknologi mahasiswa.",
      img: "/images/l2.jpg",
      status: "Used",
    },
    L3: {
      title: "L3 - Multimedia Laboratory",
      desc: "Fasilitas komputer spesifikasi tinggi untuk rendering, editing video, dan desain grafis.",
      img: "/images/l3.jpg",
      status: "Available",
    },
    L4: {
      title: "L4 - Computer Network Lab",
      desc: "Praktikum Jaringan Komputer. Dilengkapi perangkat Cisco Router, Switch, dan Mikrotik.",
      img: "/images/l4.jpg",
      status: "Available",
    },
    L5: {
      title: "L5 - Data Science Laboratory",
      desc: "Laboratorium untuk pengolahan Big Data, Machine Learning, dan Statistik Komputasi.",
      img: "/images/l5.jpg",
      status: "Maintenance",
    },
    L6: {
      title: "L6 - Computer Vision Lab",
      desc: "Riset dan praktikum pengolahan citra digital (Image Processing) serta AI Visual.",
      img: "/images/l6.jpg",
      status: "Available",
    },
    L7: {
      title: "L7 - Microcontroller Lab",
      desc: "Praktikum perangkat keras (hardware), elektronika dasar, dan robotika.",
      img: "/images/l7.jpg",
      status: "Available",
    },

    // --- RUANG RISET (R1 - R3) ---
    R1: {
      title: "R1 - Research Room 1",
      desc: "Ruang penelitian khusus untuk Dosen dan Mahasiswa tingkat akhir.",
      img: "/images/r1.jpg",
      status: "Available",
    },
    R2: {
      title: "R2 - Research Room 2",
      desc: "Fasilitas riset kolaboratif dan pengembangan proyek skripsi.",
      img: "/images/r2.jpg",
      status: "Used",
    },
    R3: {
      title: "R3 - Research Room 3",
      desc: "Ruang fokus grup riset (Research Group) dan inovasi teknologi.",
      img: "/images/r3.jpg",
      status: "Available",
    },

    // --- RUANG PENUNJANG & KANTOR ---
    HN: {
      title: "HN - Head of Networking Lab",
      desc: "Ruang Kepala Laboratorium Jaringan dan Pemrograman.",
      img: "/images/labs/office.jpg",
      status: "Restricted",
    },
    HB: {
      title: "HB - Head of Basic Lab",
      desc: "Ruang Kepala Laboratorium Dasar.",
      img: "/images/labs/office.jpg",
      status: "Restricted",
    },
    PR: {
      title: "PR - PC Room",
      desc: "Ruang komputer umum (Personal Computer Room) untuk akses mandiri.",
      img: "/images/labs/pr.jpg",
      status: "Available",
    },
    LR: {
      title: "LR - Lab Services Room",
      desc: "Pusat layanan administrasi, peminjaman alat, dan teknisi laboratorium.",
      img: "/images/labs/admin.jpg",
      status: "Open",
    },
    AR: {
      title: "AR - Assistant Room",
      desc: "Ruang kerja, istirahat, dan koordinasi Asisten Laboratorium.",
      img: "/images/labs/asisten.jpg",
      status: "Restricted",
    },
    WH: {
      title: "WH - Warehouse",
      desc: "Gudang penyimpanan inventaris, modul, dan peralatan praktikum cadangan.",
      img: "/images/labs/gudang.jpg",
      status: "Locked",
    },
    SI: {
      title: "SI - Studi Informatika",
      desc: "Ruang himpunan atau pusat studi mahasiswa informatika.",
      img: "/images/labs/si.jpg",
      status: "Open",
    },
    SR: {
      title: "SR - Server Room",
      desc: "Pusat data center dan infrastruktur utama jaringan kampus.",
      img: "/images/labs/server.jpg",
      status: "Restricted",
    },
    WS: {
      title: "WS - Working Space",
      desc: "Area terbuka (Co-working space) untuk diskusi dan kerja kelompok.",
      img: "/images/labs/ws.jpg",
      status: "Open",
    },
    TL: {
      title: "TL - Toilet",
      desc: "Fasilitas toilet.",
      img: "/images/labs/toilet.jpg",
      status: "Open",
    },
    ST: {
      title: "ST - Stairs",
      desc: "Tangga akses antar lantai.",
      img: "/images/labs/stairs.jpg",
      status: "Open",
    },
  };

  // Event Listener untuk setiap Path Ruangan
  document.querySelectorAll(".room-poly").forEach((poly) => {
    // 1. Mouse Masuk (Show Tooltip)
    poly.addEventListener("mouseenter", (e) => {
      const id = poly.getAttribute("data-id");
      const data = roomData[id];

      if (data) {
        ttTitle.innerText = data.title;
        ttDesc.innerText = data.desc;
        ttImg.src = data.img;
        ttStatus.innerText = data.status;

        // Update warna badge status
        if (data.status === "Maintenance") {
          ttStatus.style.background = "#fee2e2";
          ttStatus.style.color = "#991b1b";
        } else {
          ttStatus.style.background = "#dcfce7";
          ttStatus.style.color = "#166534";
        }

        tooltip.classList.add("show");
      }
    });

    // 2. Mouse Bergerak (Follow Cursor)
    poly.addEventListener("mousemove", (e) => {
      // Posisi tooltip mengikuti mouse
      const offsetX = 20;
      const offsetY = 20;

      // Logika agar tidak keluar layar (opsional, tahap lanjut)
      tooltip.style.left = e.clientX + offsetX + "px";
      tooltip.style.top = e.clientY + offsetY + "px";
      if (window.innerWidth > 991) {
        const offsetX = 15;
        const offsetY = 15;

        tooltip.style.left = e.clientX + offsetX + "px";
        tooltip.style.top = e.clientY + offsetY + "px";
      }
    });

    // 3. Mouse Keluar (Hide Tooltip)
    poly.addEventListener("mouseleave", () => {
      tooltip.classList.remove("show");
    });
  });
});
