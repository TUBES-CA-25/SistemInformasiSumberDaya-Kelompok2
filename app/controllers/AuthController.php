<?php

/**
 * AuthController - Kelola Autentikasi & Otorisasi Pengguna
 * 
 * Menangani:
 * - Halaman login publik
 * - Proses autentikasi username/password dengan password hashing
 * - Validasi kredensial dengan proteksi user enumeration
 * - Manajemen session user
 * - Update last login tracking
 * - Proses logout dengan pembersihan session
 * 
 * Tabel Database: user
 * Kunci Utama: id
 * Session Fields: user_id, username, role, status
 */

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/UserModel.php';

class AuthController extends Controller {
    // =========================================================================
    // BAGIAN 1: PROPERTI
    // =========================================================================
    
    /** @var UserModel Instance model untuk operasi data user */
    private $userModel;

    
    // =========================================================================
    // BAGIAN 2: KONSTRUKTOR
    // =========================================================================
    
    /**
     * Inisialisasi AuthController dengan UserModel
     */
    public function __construct() {
        $this->userModel = new UserModel();
    }

    
    // =========================================================================
    // BAGIAN 3: RUTE PUBLIK
    // =========================================================================
    
    /**
     * Login Form - Tampilkan halaman formulir login
     * 
     * Memeriksa status login pengguna sebelum menampilkan form.
     * Jika sudah login (session lengkap), redirect ke dashboard admin.
     * Membersihkan session tidak lengkap atau error sebelum menampilkan form.
     * 
     * @return void Menampilkan partial auth/login atau redirect
     */
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Cek status login lengkap
        if (isset($_SESSION['status']) && $_SESSION['status'] == 'login' &&
            isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $this->redirect(PUBLIC_URL . '/admin');
            return;
        }

        // Rate limit check untuk IP saat mengakses halaman login
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $ipKey = 'rate_limit_ip_' . md5($ip);
        if (class_exists('Cache')) {
            $ipData = Cache::get($ipKey, ['count' => 0, 'lockout_until' => 0]);
            $now = time();
            if ($ipData['count'] >= 5 && $ipData['lockout_until'] > $now) {
                $remainingMinutes = ceil(($ipData['lockout_until'] - $now) / 60);
                $this->setFlash('error', "Terlalu banyak percobaan login gagal. Akses IP Anda diblokir sementara selama {$remainingMinutes} menit.");
            }
        }
        
        $this->partial('auth/login');
    }

    /**
     * Autentikasi - Proses login dengan validasi kredensial & Rate Limiting
     * 
     * Validasi langkah:
     * 1. Periksa pembatasan percobaan login (Max 5x dalam 15 menit)
     * 2. Periksa input email dan password tidak kosong
     * 3. Cari user berdasarkan email di database
     * 4. Verifikasi password menggunakan password_verify()
     * 5. Jika valid, bersihkan rate limit counter, set session dan update last login
     * 6. Jika tidak valid, catat percobaan gagal dan informasikan sisa kesempatan
     * 
     * @return void Redirect ke admin dashboard atau login page dengan flash message
     */
    public function authenticate() {
        // Kumpulkan input dan trim spasi
        $email = isset($_POST['email']) ? trim($_POST['email']) : (isset($_POST['username']) ? trim($_POST['username']) : '');
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        // Konfigurasi Rate Limiting
        $maxAttempts  = 5;      // Maksimal 5x kesalahan
        $decaySeconds = 900;    // Blokir 15 menit (900 detik)
        $now          = time();

        $ipKey    = 'rate_limit_ip_' . md5($ip);
        $emailKey = !empty($email) ? 'rate_limit_email_' . md5(strtolower($email)) : null;

        // Ambil data attempt dari Cache
        $ipData    = class_exists('Cache') ? Cache::get($ipKey, ['count' => 0, 'lockout_until' => 0]) : ['count' => 0, 'lockout_until' => 0];
        $emailData = ($emailKey && class_exists('Cache')) ? Cache::get($emailKey, ['count' => 0, 'lockout_until' => 0]) : ['count' => 0, 'lockout_until' => 0];

        // 1. Cek apakah IP atau Email sedang terblokir
        if ($ipData['count'] >= $maxAttempts && $ipData['lockout_until'] > $now) {
            $remainingMinutes = ceil(($ipData['lockout_until'] - $now) / 60);
            $this->setFlash('error', "Terlalu banyak percobaan login gagal. Akses IP Anda diblokir sementara selama {$remainingMinutes} menit.");
            $this->redirect(PUBLIC_URL . '/iclabs-login');
            return;
        }

        if ($emailData['count'] >= $maxAttempts && $emailData['lockout_until'] > $now) {
            $remainingMinutes = ceil(($emailData['lockout_until'] - $now) / 60);
            $this->setFlash('error', "Terlalu banyak percobaan login gagal untuk akun ini. Diblokir sementara selama {$remainingMinutes} menit.");
            $this->redirect(PUBLIC_URL . '/iclabs-login');
            return;
        }

        // Helper untuk mencatat kegagalan
        $recordFailure = function() use ($ipKey, $emailKey, $ipData, $emailData, $maxAttempts, $decaySeconds, $now) {
            if (!class_exists('Cache')) return $maxAttempts;

            $newIpCount = $ipData['count'] + 1;
            $ipLockout  = ($newIpCount >= $maxAttempts) ? ($now + $decaySeconds) : 0;
            Cache::set($ipKey, ['count' => $newIpCount, 'lockout_until' => $ipLockout], $decaySeconds);

            if ($emailKey) {
                $newEmailCount = $emailData['count'] + 1;
                $emailLockout  = ($newEmailCount >= $maxAttempts) ? ($now + $decaySeconds) : 0;
                Cache::set($emailKey, ['count' => $newEmailCount, 'lockout_until' => $emailLockout], $decaySeconds);
            }

            return max(0, $maxAttempts - $newIpCount);
        };

        // 2. Validasi input wajib
        if (empty($email) || empty($password)) {
            $remaining = $recordFailure();
            $msg = ($remaining <= 0) 
                ? 'Terlalu banyak percobaan login gagal. Akses diblokir sementara selama 15 menit.' 
                : 'Email dan Password wajib diisi.';
            $this->setFlash('error', $msg);
            $this->redirect(PUBLIC_URL . '/iclabs-login');
            return;
        }

        // 3. Validasi format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $remaining = $recordFailure();
            $msg = ($remaining <= 0) 
                ? 'Terlalu banyak percobaan login gagal. Akses diblokir sementara selama 15 menit.' 
                : 'Format Email tidak valid (contoh: user@domain.com).';
            $this->setFlash('error', $msg);
            $this->redirect(PUBLIC_URL . '/iclabs-login');
            return;
        }

        // 4. Cari user berdasarkan email
        $user = $this->userModel->getByEmail($email) ?? $this->userModel->getByUsername($email);

        // 5. Verifikasi password
        if (!$user || !password_verify($password, $user['password'])) {
            $remaining = $recordFailure();
            $msg = ($remaining <= 0) 
                ? 'Terlalu banyak percobaan login gagal. Akun/IP Anda diblokir sementara selama 15 menit.' 
                : 'Email atau Password yang Anda masukkan salah.';
            $this->setFlash('error', $msg);
            $this->redirect(PUBLIC_URL . '/iclabs-login');
            return;
        }

        // 6. LOGIN BERHASIL: Reset Rate Limit Counter
        if (class_exists('Cache')) {
            Cache::forget($ipKey);
            if ($emailKey) Cache::forget($emailKey);
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['status'] = "login";
        
        $this->userModel->updateLastLogin($user['id']);
        $this->setFlash('success', 'Selamat datang kembali, ' . htmlspecialchars($user['username']) . '.');
        $this->redirect(PUBLIC_URL . '/admin');
    }

    /**
     * Logout - Proses logout pengguna
     * 
     * Langkah logout:
     * 1. Simpan username sebelum menghapus session
     * 2. Hapus semua data session
     * 3. Destroy session di server
     * 4. Mulai session baru untuk flash message (opsional)
     * 5. Redirect ke halaman login dengan pesan sukses
     * 
     * Session Cleanup:
     * - session_unset(): Hapus semua variabel session
     * - session_destroy(): Hapus file session dari server
     * - session_start(): Mulai session baru untuk flash message
     * 
     * @return void Redirect ke halaman login dengan pesan logout sukses
     */
    public function logout() {
        // Simpan username sebelum session dihapus (untuk greeting/audit)
        $username = $_SESSION['username'] ?? 'User';
        
        // Hapus semua data session
        session_unset();
        session_destroy();
        
        // Mulai session baru untuk flash message
        session_start();
        
        // Tampilkan pesan logout sukses yang elegan
        $this->setFlash('success', 'Anda telah berhasil logout dari sistem.');
        
        header('Location: ' . PUBLIC_URL . '/iclabs-login');
        exit;
    }
}