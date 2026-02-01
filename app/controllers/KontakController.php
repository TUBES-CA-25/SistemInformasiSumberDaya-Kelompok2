<?php
// 1. Panggil Induk Controller (WAJIB ADA)
require_once __DIR__ . '/Controller.php';

// 2. Load PHPMailer via Composer
// Pastikan path vendor benar. Naik 2 tingkat dari folder controllers.
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class KontakController extends Controller {

    public function index() {
        // Tampilkan halaman kontak
        $data['judul'] = 'Hubungi Kami';
        $data['pageCss'] = 'kontak.css'; // Paksa CSS agar tidak hilang
        $this->view('kontak/index', $data);
    }

    public function send() {
        // Cek apakah request ini POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . PUBLIC_URL . '/kontak');
            exit;
        }

        // Ambil data JSON (karena SweetAlert biasanya kirim JSON)
        // Atau ambil $_POST jika form biasa. Kita support keduanya.
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $nama  = htmlspecialchars($input['nama'] ?? '');
        $email = htmlspecialchars($input['email'] ?? '');
        $pesan = htmlspecialchars($input['pesan'] ?? '');

        // Validasi Sederhana
        if (empty($nama) || empty($email) || empty($pesan)) {
            echo json_encode(['status' => 'error', 'message' => 'Semua kolom wajib diisi!']);
            exit;
        }

        $mail = new PHPMailer(true);

        try {
            // --- Konfigurasi Server ---
            // $mail->SMTPDebug = 2; // Nyalakan ini jika masih error untuk lihat log detail
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST; // Ambil dari config.php
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER; // Ambil dari config.php
            $mail->Password   = SMTP_PASS; // Ambil dari config.php
            $mail->SMTPSecure = 'tls';     // Atau PHPMailer::ENCRYPTION_STARTTLS
            $mail->Port       = 587;

            // --- [SOLUSI WINDOWS XAMPP] ---
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // --- Penerima & Konten ---
            $mail->setFrom(SMTP_USER, 'Sistem Lab (No-Reply)');
            $mail->addAddress(SMTP_USER); // Kirim ke diri sendiri (Admin)
            $mail->addReplyTo($email, $nama); // Agar admin bisa reply ke pengirim

            $mail->isHTML(true);
            $mail->Subject = "Pesan Baru dari: $nama";
            $mail->Body    = "
                <h3>Pesan Baru dari Website Lab</h3>
                <p><strong>Nama:</strong> $nama</p>
                <p><strong>Email:</strong> $email</p>
                <br>
                <p><strong>Isi Pesan:</strong></p>
                <p style='background:#f4f4f4; padding:10px;'>$pesan</p>
            ";

            $mail->send();
            
            // Respon Sukses ke JavaScript
            echo json_encode(['status' => 'success', 'message' => 'Pesan berhasil dikirim!']);

        } catch (Exception $e) {
            // Respon Gagal ke JavaScript
            http_response_code(500); // Kode error server
            echo json_encode(['status' => 'error', 'message' => 'Gagal kirim: ' . $mail->ErrorInfo]);
        }
        exit;
    }
}