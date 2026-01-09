/**
 * Session Timeout Handler
 * Auto logout user setelah tidak aktif
 * Dengan warning modal sebelum logout
 */
(function() {
    // ============ KONFIGURASI PRODUCTION ============
    const SESSION_TIMEOUT = 30 * 60 * 1000;  // 30 menit dalam milidetik
    const WARNING_BEFORE = 5 * 60 * 1000;    // Warning 5 menit sebelum logout
    // =================================================
    
    let timeoutTimer;
    let warningTimer;
    let countdownInterval;
    let warningModal = null;
    
    console.log('🔐 Session Timeout Script Loaded!');
    console.log('⏱️ Timeout: ' + (SESSION_TIMEOUT/1000) + ' detik');
    console.log('⚠️ Warning muncul: ' + (WARNING_BEFORE/1000) + ' detik sebelum timeout');
    
    // Dapatkan PUBLIC_URL dari global variable atau generate dari path
    function getPublicUrl() {
        if (typeof PUBLIC_URL !== 'undefined') return PUBLIC_URL;
        const path = window.location.pathname;
        if (path.includes('SistemInformasiSumberDaya-Kelompok2')) {
            return '/SistemInformasiSumberDaya-Kelompok2/public';
        }
        return '';
    }
    
    // Reset timer setiap ada aktivitas user
    function resetTimer() {
        console.log('🔄 Timer reset!');
        clearTimeout(timeoutTimer);
        clearTimeout(warningTimer);
        clearInterval(countdownInterval);
        hideWarningModal();
        
        // Set warning timer (muncul sebelum timeout)
        warningTimer = setTimeout(function() {
            console.log('⚠️ Warning modal muncul!');
            showWarningModal();
        }, SESSION_TIMEOUT - WARNING_BEFORE);
        
        // Set logout timer
        timeoutTimer = setTimeout(function() {
            console.log('🚪 Auto logout!');
            forceLogout();
        }, SESSION_TIMEOUT);
    }
    
    // Tampilkan modal peringatan
    function showWarningModal() {
        if (warningModal) return;
        
        warningModal = document.createElement('div');
        warningModal.id = 'session-warning-modal';
        warningModal.innerHTML = '<div style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 99999; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">' +
            '<div style="background: white; padding: 32px; border-radius: 16px; max-width: 420px; text-align: center; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modalFadeIn 0.3s ease-out;">' +
                '<div style="width: 80px; height: 80px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">' +
                    '<i class="fas fa-clock" style="font-size: 36px; color: white;"></i>' +
                '</div>' +
                '<h3 style="font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 12px;">⚠️ Sesi Akan Berakhir</h3>' +
                '<p style="color: #64748b; margin-bottom: 8px; font-size: 15px;">' +
                    'Demi keamanan, sesi Anda akan berakhir dalam:' +
                '</p>' +
                '<div id="countdown-timer" style="font-size: 48px; font-weight: 700; color: #ef4444; margin: 16px 0; font-family: monospace;">' +
                    '5:00' +
                '</div>' +
                '<p style="color: #94a3b8; font-size: 13px; margin-bottom: 24px;">' +
                    'Klik "Perpanjang Sesi" untuk tetap login' +
                '</p>' +
                '<div style="display: flex; gap: 12px; justify-content: center;">' +
                    '<button id="extend-session-btn" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 14px 28px; border-radius: 10px; border: none; cursor: pointer; font-weight: 600; font-size: 15px; display: flex; align-items: center; gap: 8px; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 14px rgba(59, 130, 246, 0.4);">' +
                        '<i class="fas fa-rotate-right"></i> Perpanjang Sesi' +
                    '</button>' +
                    '<button id="logout-now-btn" style="background: #f1f5f9; color: #64748b; padding: 14px 28px; border-radius: 10px; border: 1px solid #e2e8f0; cursor: pointer; font-weight: 600; font-size: 15px; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">' +
                        '<i class="fas fa-sign-out-alt"></i> Logout' +
                    '</button>' +
                '</div>' +
            '</div>' +
        '</div>' +
        '<style>' +
            '@keyframes modalFadeIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }' +
            '#extend-session-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5); }' +
            '#logout-now-btn:hover { background: #fee2e2; color: #ef4444; border-color: #fecaca; }' +
        '</style>';
        
        document.body.appendChild(warningModal);
        
        // Event listeners
        document.getElementById('extend-session-btn').addEventListener('click', extendSession);
        document.getElementById('logout-now-btn').addEventListener('click', forceLogout);
        
        // Start countdown
        var remainingSeconds = Math.floor(WARNING_BEFORE / 1000);
        updateCountdown(remainingSeconds);
        
        countdownInterval = setInterval(function() {
            remainingSeconds--;
            updateCountdown(remainingSeconds);
            console.log('⏱️ Countdown: ' + remainingSeconds + ' detik');
            
            if (remainingSeconds <= 0) {
                clearInterval(countdownInterval);
            }
        }, 1000);
    }
    
    function updateCountdown(seconds) {
        var timerElement = document.getElementById('countdown-timer');
        if (timerElement) {
            var minutes = Math.floor(seconds / 60);
            var secs = seconds % 60;
            timerElement.textContent = minutes + ':' + (secs < 10 ? '0' : '') + secs;
            
            // Warna merah berkedip saat < 10 detik
            if (seconds <= 10) {
                timerElement.style.animation = 'pulse 0.5s infinite';
            }
        }
    }
    
    function hideWarningModal() {
        if (warningModal) {
            warningModal.remove();
            warningModal = null;
        }
    }
    
    // Perpanjang session dengan AJAX call ke server
    function extendSession() {
        console.log('🔄 Extending session...');
        var btn = document.getElementById('extend-session-btn');
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            btn.disabled = true;
        }
        
        // Ping server untuk refresh session
        fetch(getPublicUrl() + '/api/session-refresh', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            console.log('✅ Server response:', data);
            if (data.status === 'success') {
                resetTimer();
                showToast('✅ Sesi berhasil diperpanjang!', 'success');
            } else {
                forceLogout();
            }
        })
        .catch(function(err) {
            console.log('❌ Error:', err);
            // Jika gagal koneksi, tetap reset timer di client
            resetTimer();
            showToast('✅ Sesi diperpanjang', 'success');
        });
    }
    
    // Tampilkan toast notification
    function showToast(message, type) {
        var toast = document.createElement('div');
        var bgColor = type === 'success' ? '#10b981' : '#ef4444';
        toast.innerHTML = 
            '<div style="position: fixed; top: 20px; right: 20px; background: ' + bgColor + '; color: white; padding: 16px 24px; border-radius: 10px; z-index: 999999; font-weight: 500; box-shadow: 0 10px 40px rgba(0,0,0,0.2); animation: slideIn 0.3s ease-out;">' +
                message +
            '</div>' +
            '<style>' +
                '@keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }' +
            '</style>';
        document.body.appendChild(toast);
        
        setTimeout(function() {
            toast.remove();
        }, 3000);
    }
    
    // Force logout - redirect ke halaman logout
    function forceLogout() {
        console.log('🚪 Redirecting to logout...');
        window.location.href = getPublicUrl() + '/logout';
    }
    
    // Inisialisasi - mulai timer saat halaman dimuat
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            console.log('📄 DOM Ready - Starting timer');
            resetTimer();
        });
    } else {
        console.log('📄 DOM Already Ready - Starting timer');
        resetTimer();
    }
    
    // Tambahkan style untuk pulse animation
    var style = document.createElement('style');
    style.textContent = '@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }';
    document.head.appendChild(style);
})();
