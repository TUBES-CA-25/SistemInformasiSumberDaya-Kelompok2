<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #e8f6f3; color: #27ae60;">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3 id="count-asisten">-</h3>
            <p>Total Asisten</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef5e7; color: #f39c12;">
            <i class="fas fa-desktop"></i>
        </div>
        <div class="stat-info">
            <h3 id="count-lab">-</h3>
            <p>Laboratorium</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #f4ecf7; color: #8e44ad;">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-info">
            <h3 id="count-alumni">-</h3>
            <p>Alumni Terdaftar</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #ebf5fb; color: #3498db;">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-info">
            <h3 id="count-mk">-</h3>
            <p>Mata Kuliah</p>
        </div>
    </div>
</div>

<div style="margin-top: 40px;">
    <h2><i class="fas fa-history"></i> Aktivitas Terbaru</h2>
    <div class="card">
        <p style="color: #777; text-align: center; padding: 20px;">
            <i class="fas fa-info-circle"></i> Belum ada aktivitas yang tercatat hari ini.
        </p>
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