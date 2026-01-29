/**
 * Feedback & Interaction System
 * Menggunakan SweetAlert2 untuk notifikasi dan konfirmasi yang menarik
 */

// Konfigurasi Toast yang lebih menarik
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    showClass: {
        popup: 'animate__animated animate__fadeInRight animate__faster'
    },
    hideClass: {
        popup: 'animate__animated animate__fadeOutRight animate__faster'
    },
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

// Fungsi Global untuk Notifikasi Toast
window.showSuccess = function(message) {
    Toast.fire({
        icon: 'success',
        title: message,
        background: '#f0fdf4',
        iconColor: '#16a34a',
        color: '#166534'
    });
};

window.showError = function(message) {
    Toast.fire({
        icon: 'error',
        title: message,
        background: '#fef2f2',
        iconColor: '#dc2626',
        color: '#991b1b'
    });
};

window.showWarning = function(message) {
    Toast.fire({
        icon: 'warning',
        title: message,
        background: '#fffbeb',
        iconColor: '#d97706',
        color: '#92400e'
    });
};

window.showInfo = function(message) {
    Toast.fire({
        icon: 'info',
        title: message,
        background: '#eff6ff',
        iconColor: '#2563eb',
        color: '#1e40af'
    });
};

// Fungsi untuk Loading State
window.showLoading = function(title = 'Sedang memproses...') {
    Swal.fire({
        title: title,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        },
        customClass: {
            popup: 'rounded-2xl'
        }
    });
};

window.hideLoading = function() {
    Swal.close();
};

// Fungsi Global untuk Konfirmasi Delete yang lebih menarik
window.confirmDelete = function(callback, message = 'Data yang dihapus tidak dapat dikembalikan!') {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444', // Red-500
        cancelButtonColor: '#64748b', // Slate-500
        confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i>Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        focusCancel: true,
        showClass: {
            popup: 'animate__animated animate__zoomIn animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__zoomOut animate__faster'
        },
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg px-6 py-2.5 font-medium shadow-lg shadow-red-500/30',
            cancelButton: 'rounded-lg px-6 py-2.5 font-medium'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
};

// Fungsi Konfirmasi Umum
window.confirmAction = function(callback, title = 'Konfirmasi', message = 'Apakah Anda yakin ingin melanjutkan?') {
    Swal.fire({
        title: title,
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb', // Blue-600
        cancelButtonColor: '#64748b', // Slate-500
        confirmButtonText: 'Ya, Lanjutkan',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        showClass: {
            popup: 'animate__animated animate__fadeInDown animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp animate__faster'
        },
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg px-6 py-2.5 font-medium shadow-lg shadow-blue-500/30',
            cancelButton: 'rounded-lg px-6 py-2.5 font-medium'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
};

// Cek Flash Messages dari PHP (yang di-pass via variabel global JS)
document.addEventListener('DOMContentLoaded', function() {
    if (typeof FLASH_SUCCESS !== 'undefined' && FLASH_SUCCESS) {
        showSuccess(FLASH_SUCCESS);
    }
    if (typeof FLASH_ERROR !== 'undefined' && FLASH_ERROR) {
        showError(FLASH_ERROR);
    }
    if (typeof FLASH_WARNING !== 'undefined' && FLASH_WARNING) {
        showWarning(FLASH_WARNING);
    }
});
