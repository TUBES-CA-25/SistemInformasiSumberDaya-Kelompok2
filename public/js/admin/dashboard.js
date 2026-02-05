document.addEventListener("DOMContentLoaded", function () {
  // 1. Tampilkan Tanggal Hari Ini (Format Indonesia)
  const options = {
    weekday: "long",
    day: "numeric",
    month: "long",
    year: "numeric",
  };
  document.getElementById("currentDate").innerText =
    new Date().toLocaleDateString("id-ID", options);

  // 2. Load Data dari Database pertama kali
  loadDashboardStats();

  // 4. Refresh jadwal setiap menit (opsional untuk filter jam)
  setInterval(loadDashboardStats, 60000); // Refresh setiap 60 detik
});

function loadDashboardStats() {
  // Panggil API yang baru kita buat
  fetch(API_URL + "/dashboard/stats")
    .then((res) => res.json())
    .then((res) => {
      if (res.status === "success" || res.code === 200) {
        const data = res.data;

        // A. Isi Kartu Statistik
        document.getElementById("statJadwal").innerText =
          data.total_jadwal_hari_ini || 0;
        document.getElementById("statAsisten").innerText =
          data.total_asisten || 0;
        document.getElementById("statLab").innerText = data.total_lab || 0;
        document.getElementById("statAlumni").innerText =
          data.total_alumni || 0;

        // B. Render Card Jadwal
        renderJadwalCards(data.jadwal_hari_ini);
      }
    })
    .catch((err) => {
      console.error("Error loading dashboard:", err);
      // Tampilkan pesan error di container jika gagal
      document.getElementById("jadwalCardContainer").innerHTML = `
            <div class="text-center py-10 text-red-400">
                <p>Gagal memuat data dari server.</p>
            </div>`;
    });
}

function renderJadwalCards(data) {
  const container = document.getElementById("jadwalCardContainer");
  container.innerHTML = "";

  // Cek jika data kosong (Misal hari Minggu tidak ada jadwal)
  if (!data || data.length === 0) {
    container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-4 border border-gray-100">
                    <i class="fas fa-clock text-3xl"></i>
                </div>
                <h3 class="text-gray-600 font-bold text-lg">Tidak Ada Sesi Aktif</h3>
                <p class="text-gray-400 text-sm mt-1">Saat ini tidak ada jadwal praktikum yang sedang berlangsung.</p>
            </div>`;
    return;
  }

  // Render Data Asli
  let cardsHtml = "";
  data.forEach((item) => {
    // Format Waktu (Hapus detik 00)
    const mulai = item.waktuMulai.substring(0, 5);
    const selesai = item.waktuSelesai.substring(0, 5);

    // Styling Card
    const statusColor = "border-l-blue-500";
    const timeBg = "bg-blue-50 text-blue-700";

    const card = `
            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 border-l-4 ${statusColor} group relative overflow-hidden">
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    
                    <div class="flex items-center gap-5">
                        <div class="flex flex-col items-center justify-center w-16 h-16 rounded-xl ${timeBg} border border-blue-100 flex-shrink-0 shadow-inner">
                            <span class="text-sm font-bold">${mulai}</span>
                            <div class="h-px w-8 bg-blue-200 my-0.5"></div>
                            <span class="text-xs text-blue-500 opacity-80">${selesai}</span>
                        </div>
                        
                        <div>
                            <h4 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition-colors line-clamp-1">
                                ${item.namaMatakuliah}
                            </h4>
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <span class="flex items-center gap-1.5 bg-purple-50 px-2.5 py-1 rounded text-[11px] font-semibold text-purple-700 border border-purple-100">
                                    <i class="fas fa-flask"></i> ${item.namaLab}
                                </span>
                                <span class="flex items-center gap-1.5 bg-gray-100 px-2.5 py-1 rounded text-[11px] font-semibold text-gray-600 border border-gray-200">
                                    <i class="fas fa-code"></i> ${item.kodeMatakuliah || "-"}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-6 sm:border-l sm:border-gray-100 sm:pl-6 min-w-[100px]">
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-0.5">Kelas</p>
                            <span class="text-2xl font-bold text-gray-700 font-mono">${item.kelas}</span>
                        </div>
                        
                        <div class="relative w-10 h-10 flex items-center justify-center" title="Status: Aktif">
                            <span class="absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-20 animate-ping"></span>
                            <div class="relative inline-flex rounded-full h-10 w-10 bg-emerald-50 text-emerald-500 items-center justify-center border border-emerald-100">
                                <i class="fas fa-check text-lg"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        `;
    cardsHtml += card;
  });
  container.innerHTML = cardsHtml;
}
