// ============================================
// ROOM FACILITY POPUP ENGINE (DENAH LANTAI 2)
// ============================================
const getBaseUrl = () => typeof PUBLIC_BASE_URL !== 'undefined' ? PUBLIC_BASE_URL : '';

const roomFacilityData = {
  'L1': {
    code: 'L1',
    name: 'Internet of Things Laboratory',
    category: 'Laboratorium Praktikum & Riset',
    capacity: '24 Mahasiswa',
    badgeColor: '#2563eb',
    targetUrl: '/laboratorium/24',
    desc: 'Laboratorium IOT adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 24 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.',
    facilities: [
      '24 Unit High-Performance Workstation PC',
      'Development Kit: ESP32, Arduino Uno/Mega & Raspberry Pi 4',
      'Sensor Modules & Wireless Subnet Gateway'
    ]
  },
  'L2': {
    code: 'L2',
    name: 'StartUp Laboratory',
    category: 'Inkubator & Kolaborasi Digital',
    capacity: '36 Mahasiswa',
    badgeColor: '#2563eb',
    targetUrl: '/laboratorium/23',
    desc: 'Laboratorium Startup adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 36 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.',
    facilities: [
      '36 Unit Computer Workstation',
      'Smartboard Digital & Inkubasi Produk Digital'
    ]
  },
  'L3': {
    code: 'L3',
    name: 'Multimedia Laboratory',
    category: 'Desain, Animasi & Processing',
    capacity: '30 Mahasiswa',
    badgeColor: '#2563eb',
    targetUrl: '/laboratorium/28',
    desc: 'Laboratorium Multimedia adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 30 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.',
    facilities: [
      '30 Unit Graphic Workstation (GPU RTX Series)',
      'Digital Drawing Pen Tablets & Color-Accurate LED'
    ]
  },
  'L4': {
    code: 'L4',
    name: 'Computer Network Laboratory',
    category: 'Jaringan & Keamanan Siber',
    capacity: '24 Mahasiswa',
    badgeColor: '#2563eb',
    targetUrl: '/laboratorium/25',
    desc: 'Laboratorium Computer Network adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 15 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.',
    facilities: [
      'Server Rack, Cisco Enterprise Routers & Switches',
      'MikroTik Training Equipment & Network Tester Kit'
    ]
  },
  'L5': {
    code: 'L5',
    name: 'Data Science Laboratory',
    category: 'Komputasi Cerdas & Big Data',
    capacity: '26 Mahasiswa',
    badgeColor: '#2563eb',
    targetUrl: '/laboratorium/26',
    desc: 'Laboratorium Data Science adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 25 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.',
    facilities: [
      '25 Unit High-RAM Workstation PC',
      'Akses Cluster GPU Deep Learning & Python AI Suite'
    ]
  },
  'L6': {
    code: 'L6',
    name: 'Computer Vision Laboratory',
    category: 'Visi Komputer & Edge AI',
    capacity: '26 Mahasiswa',
    badgeColor: '#2563eb',
    targetUrl: '/laboratorium/27',
    desc: 'Laboratorium Computer Vision adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 25 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.',
    facilities: [
      '25 Unit High-Performance Workstation PC',
      'Industrial RGB-D Cameras & Edge AI Jetson Units'
    ]
  },
  'L7': {
    code: 'L7',
    name: 'Microcontroller Laboratory',
    category: 'Sistem Tertanam & Hardware',
    capacity: '25 Mahasiswa',
    badgeColor: '#2563eb',
    targetUrl: '/laboratorium/29',
    desc: 'Laboratorium Microcontroller adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 25 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.',
    facilities: [
      '25 Unit Computer Workstation & Circuit Trainer Kit',
      'Digital Oscilloscopes & Soldering Station'
    ]
  },
  'HN': {
    code: 'HN',
    name: 'Head of Networking & Programming Lab',
    category: 'Ruang Pimpinan Laboratorium',
    capacity: 'Ir. A. Rachman Manga\', S.Kom., M.T.',
    badgeColor: '#64748b',
    targetUrl: '/kepala',
    desc: 'Ruang kerja dan pusat koordinasi Kepala Laboratorium Jaringan Komputer & Pemrograman, Ir. A. Rachman Manga\', S.Kom., M.T.',
    facilities: ['Meja Kerja Pimpinan & Fasilitas Konsultasi Mahasiswa']
  },
  'HB': {
    code: 'HB',
    name: 'Head of Basic Laboratory',
    category: 'Ruang Pimpinan Laboratorium',
    capacity: 'Ir. Huzain Azis, S.Kom., M.Cs.',
    badgeColor: '#64748b',
    targetUrl: '/kepala',
    desc: 'Ruang kerja dan pusat koordinasi Kepala Laboratorium Dasar Komputer FIKOM UMI, Ir. Huzain Azis, S.Kom., M.Cs.',
    facilities: ['Meja Kerja Pimpinan & Fasilitas Monitoring Laboratorium']
  },
  'PR': {
    code: 'PR',
    name: 'PC Room',
    category: 'Fasilitas Komputasi Terpusat',
    capacity: null,
    badgeColor: '#64748b',
    targetUrl: null,
    desc: 'Ruang penyimpanan dan pengelolaan unit PC cadangan serta komponen hardware pendukung praktikum.',
    facilities: ['Penyimpanan Unit PC & Stasiun Maintenance']
  },
  'LR': {
    code: 'LR',
    name: 'Laboratory Services Room',
    category: 'Layanan & Administrasi Lab',
    capacity: null,
    badgeColor: '#64748b',
    targetUrl: null,
    desc: 'Pusat pelayanan administrasi praktikum, peminjaman alat lab, dan informasi operasional laboratorium.',
    facilities: ['Loket Pelayanan Administrasi & Peminjaman Alat']
  },
  'AR': {
    code: 'AR',
    name: 'Assistant Room',
    category: 'Ruang Asisten Laboratorium',
    capacity: null,
    badgeColor: '#64748b',
    targetUrl: '/asisten',
    desc: 'Ruang kerja, persiapan modul praktikum, dan piket harian seluruh Asisten Laboratorium FIKOM UMI.',
    facilities: ['Workstation PC Asisten & Area Briefing']
  },
  'WH': {
    code: 'WH',
    name: 'WareHouse / Gudang Lab',
    category: 'Penyimpanan & Logistik',
    capacity: null,
    badgeColor: '#64748b',
    targetUrl: null,
    desc: 'Ruang penyimpanan terpusat inventaris perangkat keras, kabel, suku cadang, dan perlengkapan cadangan lab.',
    facilities: ['Rak Inventaris Logistik & Komponen Hardware']
  },
  'SI': {
    code: 'SI',
    name: 'Studio Informatika',
    category: 'Fasilitas Media & Staff',
    capacity: null,
    badgeColor: '#64748b',
    targetUrl: null,
    desc: 'Studio Informatika adalah fasilitas khusus Staff untuk pengelolaan media digital, proses editing video/grafis, serta sesi fotografi dan produksi konten kreatif FIKOM UMI.',
    facilities: ['Studio Fotografi & Video Production Staff', 'Workstation Media & Editing Software Staff']
  },
  'SR': {
    code: 'SR',
    name: 'Server Room',
    category: 'Infrastruktur Data Center',
    capacity: null,
    badgeColor: '#64748b',
    targetUrl: null,
    desc: 'Pusat server terintegrasi yang melayani infrastruktur jaringan, database, dan cloud komputasi laboratorium.',
    facilities: ['Server Rack Enclosures & Precision AC']
  },
  'R1': {
    code: 'R1',
    name: 'Research Room 1',
    category: 'Ruang Riset Mahasiswa & Dosen',
    capacity: '12 Peneliti',
    badgeColor: '#f97316',
    targetUrl: '/laboratorium/30',
    desc: 'Research Room 1 adalah laboratorium yang memberikan pelayanan kepada dosen dan mahasiswa dalam melakukan proses kegiatan penelitian. Ruangan ini memiliki daya tampung maksimal 12 orang.',
    facilities: ['Meja Riset Ergonomis & High-Speed LAN Port']
  },
  'R2': {
    code: 'R2',
    name: 'Research Room 2',
    category: 'Ruang Riset Mahasiswa & Dosen',
    capacity: '12 Peneliti',
    badgeColor: '#f97316',
    targetUrl: '/laboratorium/31',
    desc: 'Research Room 2 adalah laboratorium yang memberikan pelayanan kepada dosen dan mahasiswa dalam melakukan proses kegiatan penelitian. Ruangan ini memiliki daya tampung maksimal 12 orang.',
    facilities: ['Akses Komputasi Terdedikasi & Jurnal Digital']
  },
  'R3': {
    code: 'R3',
    name: 'Research Room 3',
    category: 'Ruang Riset Mahasiswa & Dosen',
    capacity: '12 Peneliti',
    badgeColor: '#f97316',
    targetUrl: '/laboratorium/32',
    desc: 'Research Room 3 adalah laboratorium yang memberikan pelayanan kepada dosen dan mahasiswa dalam melakukan proses kegiatan penelitian. Ruangan ini memiliki daya tampung maksimal 12 orang.',
    facilities: ['Quiet Work Zone & High Speed Wi-Fi Connection']
  },
  'WS': {
    code: 'WS',
    name: 'Working Space',
    category: 'Area Kerja Terbuka',
    capacity: null,
    badgeColor: '#3b82f6',
    targetUrl: null,
    desc: 'Area kerja mandiri untuk belajar, mengerjakan tugas kelompok, dan berdiskusi antar mahasiswa FIKOM UMI.',
    facilities: ['Meja & Kursi Kerja Santai Kolaboratif']
  }
};

document.addEventListener('DOMContentLoaded', () => {
  const popup = document.getElementById('roomFacilityPopup');
  if (!popup) return;

  const popupCode = document.getElementById('popupRoomCode');
  const popupName = document.getElementById('popupRoomName');
  const popupCategory = document.getElementById('popupRoomCategory');
  const popupCapacity = document.getElementById('popupRoomCapacity');
  const popupDesc = document.getElementById('popupRoomDesc');
  const popupFacilityList = document.getElementById('popupFacilityList');
  const popupRoomLink = document.getElementById('popupRoomLink');

  const targets = document.querySelectorAll('[data-room]');

  let activeTarget = null;
  let isOverPopup = false;
  let hideTimer = null;

  popup.addEventListener('mouseenter', () => {
    isOverPopup = true;
    if (hideTimer) clearTimeout(hideTimer);
  });

  popup.addEventListener('mouseleave', () => {
    isOverPopup = false;
    hidePopup();
  });

  const showPopup = (roomCode, event) => {
    const data = roomFacilityData[roomCode];
    if (!data) return;

    if (hideTimer) clearTimeout(hideTimer);

    // Render Popup Contents
    if (popupCode) {
      popupCode.textContent = data.code;
      popupCode.style.backgroundColor = data.badgeColor || '#2563eb';
    }
    if (popupName) popupName.textContent = data.name;
    if (popupCategory) popupCategory.textContent = data.category;
    
    if (popupCapacity) {
      if (data.capacity) {
        popupCapacity.style.display = 'inline-flex';
        const isPerson = data.capacity.includes('Ir.') || data.capacity.includes('S.Kom');
        const icon = isPerson ? 'ri-user-star-line' : 'ri-user-group-line';
        const text = isPerson ? data.capacity : `Kapasitas: ${data.capacity}`;
        popupCapacity.innerHTML = `<i class="${icon}"></i> <span>${text}</span>`;
      } else {
        popupCapacity.style.display = 'none';
      }
    }

    if (popupDesc) popupDesc.textContent = data.desc;

    // Update Visit Link
    if (popupRoomLink) {
      popupRoomLink.href = getBaseUrl() + (data.targetUrl || '/laboratorium');
    }

    // Highlight all matching targets (legend + hotspot)
    targets.forEach(t => {
      if (t.getAttribute('data-room') === roomCode) {
        t.classList.add('active-room-highlight');
      } else {
        t.classList.remove('active-room-highlight');
      }
    });

    // Position Popup
    updatePopupPosition(event);
    popup.classList.add('visible');
    popup.setAttribute('aria-hidden', 'false');
  };

  const popupCloseBtn = document.getElementById('popupCloseBtn');
  if (popupCloseBtn) {
    popupCloseBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      forceHidePopup();
    });
  }

  const forceHidePopup = () => {
    isOverPopup = false;
    popup.classList.remove('visible');
    popup.setAttribute('aria-hidden', 'true');
    targets.forEach(t => t.classList.remove('active-room-highlight'));
    activeTarget = null;
  };

  const hidePopup = () => {
    if (isOverPopup) return;
    forceHidePopup();
  };

  const updatePopupPosition = (e) => {
    if (!popup.classList.contains('visible')) return;
    if (isOverPopup) return;

    // Mobile / Tablet Responsive Floating Bottom Sheet Position
    if (window.innerWidth <= 768) {
      popup.style.left = '';
      popup.style.top = '';
      return;
    }

    const popupWidth = popup.offsetWidth || 340;
    const popupHeight = popup.offsetHeight || 300;
    const padding = 20;

    let x = e ? e.clientX + 18 : window.innerWidth / 2;
    let y = e ? e.clientY + 18 : window.innerHeight / 2;

    // Boundary detection (keep inside screen viewport)
    if (x + popupWidth > window.innerWidth - padding) {
      x = e.clientX - popupWidth - 15;
    }
    if (y + popupHeight > window.innerHeight - padding) {
      y = e.clientY - popupHeight - 15;
    }

    if (x < padding) x = padding;
    if (y < padding) y = padding;

    popup.style.left = `${x}px`;
    popup.style.top = `${y}px`;
  };

  const navigableRooms = ['L1', 'L2', 'L3', 'L4', 'L5', 'L6', 'L7', 'R1', 'R2', 'R3'];

  // Event Listeners for Legend Items & Spatial Hotspots
  targets.forEach(target => {
    const roomCode = target.getAttribute('data-room');
    let lastTapTime = 0;

    target.addEventListener('mouseenter', (e) => {
      activeTarget = target;
      showPopup(roomCode, e);
    });

    target.addEventListener('mousemove', (e) => {
      if (activeTarget === target && !isOverPopup && window.innerWidth > 768) {
        updatePopupPosition(e);
      }
    });

    target.addEventListener('mouseleave', () => {
      hideTimer = setTimeout(() => {
        if (!isOverPopup) {
          hidePopup();
        }
      }, 150);
    });

    // Double-Click (Dblclick) to enter Laboratory & Research facilities
    target.addEventListener('dblclick', (e) => {
      e.preventDefault();
      e.stopPropagation();
      const data = roomFacilityData[roomCode];
      if (data && navigableRooms.includes(roomCode) && data.targetUrl) {
        window.location.href = getBaseUrl() + data.targetUrl;
      }
    });

    // Mobile Touch Toggle & Double-Tap Navigation Support
    target.addEventListener('touchend', (e) => {
      const currentTime = new Date().getTime();
      const tapLength = currentTime - lastTapTime;

      // Double-Tap detected (less than 350ms between taps)
      if (tapLength < 350 && tapLength > 0) {
        const data = roomFacilityData[roomCode];
        if (data && navigableRooms.includes(roomCode) && data.targetUrl) {
          e.preventDefault();
          window.location.href = getBaseUrl() + data.targetUrl;
          return;
        }
      } else {
        // Single tap: toggle preview popup on mobile
        if (window.innerWidth <= 768) {
          if (popup.classList.contains('visible') && activeTarget === target) {
            forceHidePopup();
          } else {
            activeTarget = target;
            showPopup(roomCode, e);
          }
        }
      }
      lastTapTime = currentTime;
    });
  });

  // Close popup when touching elsewhere on mobile
  document.addEventListener('touchstart', (e) => {
    if (!e.target.closest('[data-room]') && !e.target.closest('#roomFacilityPopup')) {
      forceHidePopup();
    }
  }, { passive: true });
});

// ============================================
// FACILITY DETAIL IMAGE SLIDER ENGINE
// ============================================
(function initFacilityDetailSlider() {
  let currentIndex = 0;
  let autoSlideTimer = null;

  const getSliderTrack = () => document.getElementById('sliderTrack');
  const getSlides = () => document.querySelectorAll('#sliderTrack .slide-item');
  const getDots = () => document.querySelectorAll('#labSlider .dot');

  const updateSlider = (index) => {
    const track = getSliderTrack();
    const slides = getSlides();
    const dots = getDots();
    if (!track || slides.length === 0) return;

    if (index >= slides.length) currentIndex = 0;
    else if (index < 0) currentIndex = slides.length - 1;
    else currentIndex = index;

    track.style.transform = `translateX(-${currentIndex * 100}%)`;

    dots.forEach((dot, idx) => {
      if (idx === currentIndex) {
        dot.classList.add('active');
      } else {
        dot.classList.remove('active');
      }
    });
  };

  // Expose global functions for inline onclick attributes in view
  window.moveSlide = function(direction) {
    updateSlider(currentIndex + direction);
    resetAutoSlide();
  };

  window.goToSlide = function(index) {
    updateSlider(index);
    resetAutoSlide();
  };

  const resetAutoSlide = () => {
    if (autoSlideTimer) clearInterval(autoSlideTimer);
    const slides = getSlides();
    if (slides.length > 1) {
      autoSlideTimer = setInterval(() => {
        updateSlider(currentIndex + 1);
      }, 5000);
    }
  };

  // Setup Touch Swipe, Mouse Drag, & Keyboard Navigation
  document.addEventListener('DOMContentLoaded', () => {
    const sliderContainer = document.getElementById('labSlider');
    if (!sliderContainer) return;

    updateSlider(0);
    resetAutoSlide();

    // Pause auto-slide on hover
    sliderContainer.addEventListener('mouseenter', () => {
      if (autoSlideTimer) clearInterval(autoSlideTimer);
    });
    sliderContainer.addEventListener('mouseleave', () => {
      resetAutoSlide();
    });

    // Touch Swipe Support
    let startX = 0;
    let isDragging = false;

    sliderContainer.addEventListener('touchstart', (e) => {
      if (e.touches && e.touches.length > 0) {
        startX = e.touches[0].clientX;
      }
    }, { passive: true });

    sliderContainer.addEventListener('touchend', (e) => {
      if (e.changedTouches && e.changedTouches.length > 0) {
        const endX = e.changedTouches[0].clientX;
        const diffX = startX - endX;
        if (Math.abs(diffX) > 40) {
          if (diffX > 0) window.moveSlide(1); // Swipe left -> Next photo
          else window.moveSlide(-1); // Swipe right -> Prev photo
        }
      }
    }, { passive: true });

    // Mouse Drag Support
    sliderContainer.addEventListener('mousedown', (e) => {
      startX = e.clientX;
      isDragging = true;
    });

    sliderContainer.addEventListener('mouseup', (e) => {
      if (!isDragging) return;
      isDragging = false;
      const endX = e.clientX;
      const diffX = startX - endX;
      if (Math.abs(diffX) > 40) {
        if (diffX > 0) window.moveSlide(1);
        else window.moveSlide(-1);
      }
    });

    sliderContainer.addEventListener('mouseleave', () => {
      isDragging = false;
    });

    // Keyboard Arrow Keys Navigation
    document.addEventListener('keydown', (e) => {
      if (!document.getElementById('labSlider')) return;
      if (e.key === 'ArrowLeft') window.moveSlide(-1);
      else if (e.key === 'ArrowRight') window.moveSlide(1);
    });
  });
})();

// ============================================
// LIGHTBOX FULLSCREEN MODAL ENGINE
// ============================================
(function initLightboxModal() {
  let currentLightboxIndex = 0;

  window.openLightbox = function(index) {
    const images = window.galleryImages || [];
    if (!images || images.length === 0) return;

    if (index < 0) currentLightboxIndex = images.length - 1;
    else if (index >= images.length) currentLightboxIndex = 0;
    else currentLightboxIndex = index;

    const modal = document.getElementById('photoLightboxModal');
    const imgEl = document.getElementById('lightboxImg');
    const captionEl = document.getElementById('lightboxCaption');
    const item = images[currentLightboxIndex];

    if (modal && imgEl && item) {
      imgEl.src = item.src;
      if (captionEl) captionEl.textContent = item.desc || '';
      modal.classList.add('active');
      modal.setAttribute('aria-hidden', 'false');
    }
  };

  window.closeLightbox = function() {
    const modal = document.getElementById('photoLightboxModal');
    if (modal) {
      modal.classList.remove('active');
      modal.setAttribute('aria-hidden', 'true');
    }
  };

  window.navLightbox = function(direction) {
    window.openLightbox(currentLightboxIndex + direction);
  };

  document.addEventListener('DOMContentLoaded', () => {
    const closeBtn = document.getElementById('lightboxCloseBtn');
    const prevBtn = document.getElementById('lightboxPrevBtn');
    const nextBtn = document.getElementById('lightboxNextBtn');
    const modal = document.getElementById('photoLightboxModal');

    if (closeBtn) closeBtn.addEventListener('click', window.closeLightbox);
    if (prevBtn) prevBtn.addEventListener('click', () => window.navLightbox(-1));
    if (nextBtn) nextBtn.addEventListener('click', () => window.navLightbox(1));

    if (modal) {
      modal.addEventListener('click', (e) => {
        if (e.target === modal) window.closeLightbox();
      });
    }

    document.addEventListener('keydown', (e) => {
      if (modal && modal.classList.contains('active')) {
        if (e.key === 'Escape') window.closeLightbox();
        else if (e.key === 'ArrowLeft') window.navLightbox(-1);
        else if (e.key === 'ArrowRight') window.navLightbox(1);
      }
    });
  });
})();







