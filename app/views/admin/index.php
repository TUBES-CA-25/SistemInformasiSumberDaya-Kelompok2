<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-5">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div class="w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="text-right">
                <p class="text-sm font-medium text-gray-500">Total Asisten</p>
                <h3 id="count-asisten" class="text-2xl font-bold text-gray-800">-</h3>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-blue-600 font-semibold uppercase tracking-wider">
            <i class="fas fa-arrow-up mr-1"></i> Data Aktif
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div class="w-14 h-14 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                <i class="fas fa-desktop text-2xl"></i>
            </div>
            <div class="text-right">
                <p class="text-sm font-medium text-gray-500">Laboratorium</p>
                <h3 id="count-lab" class="text-2xl font-bold text-gray-800">-</h3>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-emerald-600 font-semibold uppercase tracking-wider">
            <i class="fas fa-check-circle mr-1"></i> Fasilitas Terdata
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div class="w-14 h-14 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                <i class="fas fa-user-graduate text-2xl"></i>
            </div>
            <div class="text-right">
                <p class="text-sm font-medium text-gray-500">Alumni</p>
                <h3 id="count-alumni" class="text-2xl font-bold text-gray-800">-</h3>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-purple-600 font-semibold uppercase tracking-wider">
            <i class="fas fa-graduation-cap mr-1"></i> Terdaftar
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
        <div class="flex items-center justify-between">
            <div class="w-14 h-14 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300">
                <i class="fas fa-book text-2xl"></i>
            </div>
            <div class="text-right">
                <p class="text-sm font-medium text-gray-500">Mata Kuliah</p>
                <h3 id="count-mk" class="text-2xl font-bold text-gray-800">-</h3>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-amber-600 font-semibold uppercase tracking-wider">
            <i class="fas fa-calendar-alt mr-1"></i> Praktikum
        </div>
    </div>
</div>

<div class="mt-10">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-history text-blue-600"></i> Aktivitas Terbaru
        </h2>
    </div>
    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden min-h-[200px] flex items-center justify-center">
        <div class="text-center group p-8">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-info-circle text-3xl text-gray-300 group-hover:text-blue-400 transition-colors"></i>
            </div>
            <p class="text-gray-400 font-medium">Belum ada aktivitas yang tercatat hari ini.</p>
            <p class="text-xs text-gray-300 mt-2">Log aktivitas sistem akan muncul di sini.</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetchStats();
});

function fetchStats() {
    fetch(API_URL + '/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const stats = data.data;
                document.getElementById('count-asisten').textContent = stats.asisten;
                document.getElementById('count-lab').textContent = stats.laboratorium;
                document.getElementById('count-alumni').textContent = stats.alumni;
                document.getElementById('count-mk').textContent = stats.matakuliah;
            }
        })
        .catch(error => console.error('Error fetching stats:', error));
}

</script>