<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../models/KontakModel.php';

class KontakController extends Controller {

    /** @var KontakModel */
    private $model;

    public function __construct() {
        $this->model = new KontakModel();
    }

    /**
     * Helper pembersih buffer sebelum merespons JSON API
     */
    private function cleanBuffers(): void {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
    }

    /**
     * Tampilan Publik: Halaman Hubungi Kami
     */
    public function index($params = []) {
        $data = [
            'judul'    => 'Hubungi Kami',
            'kontak'   => $this->model->getActivePublic(),
            'maps_url' => $this->model->getActiveMapsUrl()
        ];
        $this->view('contact/index', $data);
    }

    /**
     * Tampilan Admin: Kelola Saluran Kontak & Kotak Masuk Pesan
     */
    public function adminIndex($params = []) {
        $data = [
            'judul'           => 'Manajemen Kontak & Kotak Masuk',
            'unread_count'    => $this->model->countUnreadPesan(),
            'recipient_email' => $this->model->getRecipientEmail(),
            'email_source'    => defined('CONTACT_EMAIL_SOURCE') ? CONTACT_EMAIL_SOURCE : 'kontak'
        ];
        $this->view('admin/kontak/index', $data);
    }

    /**
     * Kirim Pesan dari Formulir Kontak Pengunjung
     * Menyimpan ke database (pesan_kontak) dan mengirim notifikasi email jika SMTP aktif
     */
    public function send($params = []) {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        // Ambil Data dari Form POST
        $nama    = trim($_POST['nama'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $subjek  = trim($_POST['subjek'] ?? '');
        $pesan   = trim($_POST['pesan'] ?? '');

        // Validasi Input
        if (empty($nama) || empty($email) || empty($pesan)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nama, email, dan pesan wajib diisi.']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Format email tidak valid.']);
            exit;
        }

        // 1. Simpan pesan ke Database Kotak Masuk
        $saved = $this->model->simpanPesan($nama, $email, $subjek, $pesan);
        if (!$saved) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan pesan ke sistem. Silakan coba lagi.']);
            exit;
        }

        // 2. Kirim Email Notifikasi via PHPMailer jika konfigurasi SMTP tersedia
        $emailTerkirim = false;
        if (defined('SMTP_HOST') && !empty(SMTP_HOST) && defined('SMTP_USER') && !empty(SMTP_USER) && defined('SMTP_PASS') && !empty(SMTP_PASS)) {
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_USER;
                $mail->Password   = SMTP_PASS;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = defined('SMTP_PORT') ? SMTP_PORT : 587;
                $mail->Timeout    = 5; // Timeout cepat agar UX responsif

                $mail->setFrom(SMTP_USER, 'Sistem Informasi Sumber Daya');

                // Ambil target email dinamis sesuai settingan .env / Saluran Kontak
                $targetAdmin = $this->model->getRecipientEmail();
                $mail->addAddress($targetAdmin);
                $mail->addReplyTo($email, $nama);

                $mail->isHTML(true);
                $mail->Subject = "Pesan Baru: " . (!empty($subjek) ? $subjek : 'Pesan Pengunjung');
                $mail->Body    = "
                    <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #1e293b;'>
                        <h3 style='color: #2563eb;'>Pesan Baru dari Website IC-Labs</h3>
                        <hr style='border: 0; border-top: 1px solid #e2e8f0;'>
                        <p><b>Nama:</b> " . htmlspecialchars($nama) . "</p>
                        <p><b>Email:</b> " . htmlspecialchars($email) . "</p>
                        <p><b>Subjek:</b> " . htmlspecialchars($subjek) . "</p>
                        <br>
                        <div style='background: #f8fafc; padding: 15px; border-radius: 8px; border-left: 4px solid #2563eb;'>
                            <b>Isi Pesan:</b><br>" . nl2br(htmlspecialchars($pesan)) . "
                        </div>
                        <br>
                        <small style='color: #64748b;'>Pesan ini juga telah otomatis dicatat di Kotak Masuk Panel Admin.</small>
                    </div>
                ";
                $mail->AltBody = "Nama: $nama\nEmail: $email\nSubjek: $subjek\nPesan: $pesan";
                $mail->send();
                $emailTerkirim = true;
            } catch (\Throwable $e) {
                // Log atau abaikan jika SMTP hosting bermasalah, karena pesan sudah tersimpan di DB
                error_log("PHPMailer Notice: " . $e->getMessage());
            }
        }

        echo json_encode([
            'status'  => 'success',
            'message' => 'Pesan Anda berhasil dikirim dan tersimpan di sistem!'
        ]);
        exit;
    }

    // ==========================================
    // API: CRUD SALURAN KONTAK
    // ==========================================

    /**
     * API: Ambil semua saluran kontak
     */
    public function apiIndex(): void {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        $kontak = $this->model->getAllOrdered();
        $unreadCount = $this->model->countUnreadPesan();
        $recipientEmail = $this->model->getRecipientEmail();
        $emailSource = defined('CONTACT_EMAIL_SOURCE') ? CONTACT_EMAIL_SOURCE : 'kontak';

        echo json_encode([
            'status' => 'success',
            'data'   => $kontak,
            'meta'   => [
                'total'           => count($kontak),
                'unread_pesan'    => $unreadCount,
                'recipient_email' => $recipientEmail,
                'email_source'    => $emailSource
            ]
        ]);
        exit;
    }

    /**
     * API: Ambil satu detail kontak
     */
    public function apiShow($params = []): void {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        $id = $params['id'] ?? $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID kontak wajib disertakan.']);
            exit;
        }

        $data = $this->model->getById($id);
        if (!$data) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Data kontak tidak ditemukan.']);
            exit;
        }

        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    /**
     * API: Tambah saluran kontak baru
     */
    public function apiStore(): void {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        $input = $_POST;
        if (empty($input)) {
            $input = json_decode(file_get_contents('php://input'), true) ?? [];
        }

        $nama   = trim($input['nama'] ?? '');
        $nilai  = trim($input['nilai'] ?? '');
        $tautan = trim($input['tautan'] ?? '');

        if (empty($nama) || empty($nilai)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nama kontak dan nilai/informasi wajib diisi.']);
            exit;
        }

        // Tentukan ikon & tipe secara statik otomatis berdasarkan nama / konten
        $namaLower  = strtolower($nama);
        $nilaiLower = strtolower($nilai);
        $ikon = 'ri-information-line';
        $tipe = 'lainnya';

        if (strpos($namaLower, 'map') !== false || strpos($namaLower, 'peta') !== false || strpos($nilaiLower, 'google.com/maps') !== false) {
            $ikon = 'ri-map-pin-line';
            $tipe = 'maps';
        } elseif (strpos($namaLower, 'lokasi') !== false || strpos($namaLower, 'alamat') !== false || strpos($namaLower, 'gedung') !== false) {
            $ikon = 'ri-map-pin-2-line';
            $tipe = 'lokasi';
        } elseif (strpos($namaLower, 'email') !== false || filter_var($nilai, FILTER_VALIDATE_EMAIL)) {
            $ikon = 'ri-mail-line';
            $tipe = 'email';
        } elseif (strpos($namaLower, 'whatsapp') !== false || strpos($namaLower, 'wa') !== false) {
            $ikon = 'ri-whatsapp-line';
            $tipe = 'whatsapp';
        } elseif (strpos($namaLower, 'telp') !== false || strpos($namaLower, 'telepon') !== false || strpos($namaLower, 'phone') !== false) {
            $ikon = 'ri-phone-line';
            $tipe = 'telepon';
        } elseif (strpos($namaLower, 'instagram') !== false || strpos($namaLower, 'ig') !== false) {
            $ikon = 'ri-instagram-line';
            $tipe = 'sosial_media';
        }

        // Jika maps, ekstrak URL src jika diisi dengan tag iframe
        if ($tipe === 'maps') {
            if (preg_match('/src="([^"]+)"/', $nilai, $matches)) {
                $nilai = $matches[1];
            }
        }

        // Auto-format tautan jika kosong sesuai tipe
        if (empty($tautan)) {
            if ($tipe === 'email' && filter_var($nilai, FILTER_VALIDATE_EMAIL)) {
                $tautan = 'mailto:' . $nilai;
            } elseif ($tipe === 'whatsapp') {
                $cleanPhone = preg_replace('/[^0-9]/', '', $nilai);
                if (!empty($cleanPhone)) {
                    $tautan = 'https://wa.me/' . $cleanPhone;
                }
            }
        }

        $allExisting = $this->model->getAllOrdered();
        $urutan = count($allExisting) + 1;

        $inserted = $this->model->insert([
            'nama'      => $nama,
            'tipe'      => $tipe,
            'ikon'      => $ikon,
            'nilai'     => $nilai,
            'tautan'    => $tautan,
            'urutan'    => $urutan,
            'is_active' => 1
        ]);

        if ($inserted) {
            echo json_encode([
                'status'  => 'success',
                'message' => 'Saluran kontak baru berhasil ditambahkan.',
                'id'      => $this->model->getLastInsertId()
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data kontak.']);
        }
        exit;
    }

    /**
     * API: Update saluran kontak
     */
    public function apiUpdate($params = []): void {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        $id = $params['id'] ?? $_POST['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID kontak wajib disertakan.']);
            exit;
        }

        $existing = $this->model->getById($id);
        if (!$existing) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Data kontak tidak ditemukan.']);
            exit;
        }

        $input = $_POST;
        if (empty($input)) {
            $input = json_decode(file_get_contents('php://input'), true) ?? [];
        }

        $nama   = trim($input['nama'] ?? $existing['nama']);
        $nilai  = trim($input['nilai'] ?? $existing['nilai']);
        $tautan = trim($input['tautan'] ?? $existing['tautan']);

        if (empty($nama) || empty($nilai)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nama kontak dan nilai wajib diisi.']);
            exit;
        }

        // Tentukan tipe & ikon statik
        $tipe = $existing['tipe'];
        $ikon = $existing['ikon'];
        $namaLower = strtolower($nama);
        $nilaiLower = strtolower($nilai);

        if (strpos($namaLower, 'map') !== false || strpos($namaLower, 'peta') !== false || strpos($nilaiLower, 'google.com/maps') !== false) {
            $tipe = 'maps';
            $ikon = 'ri-map-pin-line';
        } elseif (empty($ikon) || $ikon === 'ri-information-line') {
            if (strpos($namaLower, 'email') !== false || filter_var($nilai, FILTER_VALIDATE_EMAIL)) {
                $ikon = 'ri-mail-line';
                $tipe = 'email';
            } elseif (strpos($namaLower, 'whatsapp') !== false || strpos($namaLower, 'wa') !== false) {
                $ikon = 'ri-whatsapp-line';
                $tipe = 'whatsapp';
            } elseif (strpos($namaLower, 'lokasi') !== false || strpos($namaLower, 'alamat') !== false) {
                $ikon = 'ri-map-pin-2-line';
                $tipe = 'lokasi';
            }
        }

        if ($tipe === 'maps') {
            if (preg_match('/src="([^"]+)"/', $nilai, $matches)) {
                $nilai = $matches[1];
            }
        }

        $updated = $this->model->update($id, [
            'nama'      => $nama,
            'tipe'      => $tipe,
            'ikon'      => $ikon,
            'nilai'     => $nilai,
            'tautan'    => $tautan,
            'urutan'    => $existing['urutan'],
            'is_active' => 1
        ]);

        if ($updated) {
            echo json_encode(['status' => 'success', 'message' => 'Data kontak berhasil diperbarui.']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data kontak.']);
        }
        exit;
    }

    /**
     * API: Toggle status aktif/nonaktif saluran kontak
     */
    public function apiToggle($params = []): void {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        $id = $params['id'] ?? $_POST['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID kontak wajib disertakan.']);
            exit;
        }

        $toggled = $this->model->toggleStatus((int)$id);
        if ($toggled) {
            $updated = $this->model->getById($id);
            $statusLabel = ((int)$updated['is_active'] === 1) ? 'diaktifkan' : 'dinonaktifkan';
            echo json_encode([
                'status'    => 'success',
                'message'   => "Saluran kontak berhasil {$statusLabel}.",
                'is_active' => (int)$updated['is_active']
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengubah status kontak.']);
        }
        exit;
    }

    /**
     * API: Hapus saluran kontak
     */
    public function apiDelete($params = []): void {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        $id = $params['id'] ?? $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID kontak wajib disertakan.']);
            exit;
        }

        $deleted = $this->model->delete($id);
        if ($deleted) {
            echo json_encode(['status' => 'success', 'message' => 'Saluran kontak berhasil dihapus.']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus saluran kontak.']);
        }
        exit;
    }

    // ==========================================
    // API: KOTAK MASUK PESAN (INBOX)
    // ==========================================

    /**
     * API: Ambil semua pesan masuk
     */
    public function apiMessages(): void {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        $messages = $this->model->getAllPesan();
        $unreadCount = $this->model->countUnreadPesan();

        echo json_encode([
            'status' => 'success',
            'data'   => $messages,
            'meta'   => [
                'total'        => count($messages),
                'unread_count' => $unreadCount
            ]
        ]);
        exit;
    }

    /**
     * API: Ambil detail pesan dan otomatis tandai dibaca
     */
    public function apiMessageDetail($params = []): void {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        $id = $params['id'] ?? $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID pesan wajib disertakan.']);
            exit;
        }

        $pesan = $this->model->getPesanById((int)$id);
        if (!$pesan) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Pesan tidak ditemukan.']);
            exit;
        }

        // Tandai sudah dibaca jika sebelumnya belum dibaca
        if ($pesan['status'] === 'belum_dibaca') {
            $this->model->tandaiDibaca((int)$id);
            $pesan['status'] = 'dibaca';
        }

        echo json_encode([
            'status' => 'success',
            'data'   => $pesan,
            'unread' => $this->model->countUnreadPesan()
        ]);
        exit;
    }

    /**
     * API: Tandai pesan sudah dibaca
     */
    public function apiMarkRead($params = []): void {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        $id = $params['id'] ?? $_POST['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID pesan wajib disertakan.']);
            exit;
        }

        $res = $this->model->tandaiDibaca((int)$id);
        if ($res) {
            echo json_encode([
                'status'  => 'success',
                'message' => 'Pesan ditandai sebagai telah dibaca.',
                'unread'  => $this->model->countUnreadPesan()
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengubah status pesan.']);
        }
        exit;
    }

    /**
     * API: Hapus pesan dari kotak masuk
     */
    public function apiDeleteMessage($params = []): void {
        $this->cleanBuffers();
        header('Content-Type: application/json; charset=utf-8');

        $id = $params['id'] ?? $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID pesan wajib disertakan.']);
            exit;
        }

        $deleted = $this->model->hapusPesan((int)$id);
        if ($deleted) {
            echo json_encode([
                'status'  => 'success',
                'message' => 'Pesan berhasil dihapus dari kotak masuk.',
                'unread'  => $this->model->countUnreadPesan()
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus pesan.']);
        }
        exit;
    }
}