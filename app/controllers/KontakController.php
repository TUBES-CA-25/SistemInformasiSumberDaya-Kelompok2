<?php

// PENTING: Namespace Library PHPMailer
// (Pastikan folder 'vendor' sudah ada di project agar ini berfungsi)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class KontakController extends Controller {

    public function index($params) {
        $this->view('contact/index');
    }

    public function send($params) {
        
        // 1. Ambil Data dari Form
        $nama    = $_POST['nama'] ?? '';
        $email   = $_POST['email'] ?? '';
        $subjek  = $_POST['subjek'] ?? '';
        $pesan   = $_POST['pesan'] ?? '';

        // 2. Validasi Input
        if (empty($nama) || empty($email) || empty($pesan)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Semua kolom wajib diisi']);
            exit;
        }

        // 3. Inisialisasi PHPMailer
        // 'true' artinya kita mengaktifkan Exception agar error bisa ditangkap catch
        $mail = new PHPMailer(true); 

        try {
            // --- KONFIGURASI SERVER (Mengambil dari config.php) ---
            // Ini membuat kode aman karena password tidak tertulis disini
            $mail->isSMTP();                                            
            $mail->Host       = SMTP_HOST;                     
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = SMTP_USER;                 
            $mail->Password   = SMTP_PASS;                               
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            
            $mail->Port       = SMTP_PORT;                                    

            // --- PENGIRIM & PENERIMA ---
            
            // PENTING UNTUK HOSTING: 
            // Email Pengirim (setFrom) HARUS SAMA dengan SMTP_USER.
            // Jika beda, server hosting sering menolak (dianggap spamming).
            $mail->setFrom(SMTP_USER, 'Sistem Informasi Sumber Daya');
            
            // Penerima: Kirim notifikasi ke email Admin (Anda sendiri)
            $mail->addAddress(SMTP_USER);     
            
            // Reply-To: Ini kuncinya!
            // Saat Admin klik "Reply" di Gmail, tujuannya langsung ke email Pengunjung.
            $mail->addReplyTo($email, $nama);

            // --- KONTEN EMAIL ---
            $mail->isHTML(true);                                  
            $mail->Subject = "Pesan Baru: " . $subjek;
            
            // Styling HTML sederhana agar email terlihat rapi
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; line-height: 1.6;'>
                    <h3 style='color: #2563eb;'>Pesan Baru dari Website</h3>
                    <hr>
                    <p><b>Nama Pengunjung:</b> $nama</p>
                    <p><b>Email Pengunjung:</b> $email</p>
                    <p><b>Subjek:</b> $subjek</p>
                    <br>
                    <div style='background: #f3f4f6; padding: 15px; border-radius: 5px; border-left: 4px solid #2563eb;'>
                        <b>Isi Pesan:</b><br>
                        " . nl2br(htmlspecialchars($pesan)) . "
                    </div>
                    <br>
                    <small style='color: #888;'>Email ini dikirim otomatis oleh sistem.</small>
                </div>
            ";
            
            // Versi teks biasa untuk email client tua
            $mail->AltBody = "Nama: $nama\nEmail: $email\nPesan: $pesan";

            // Kirim!
            $mail->send();

            // Respon Sukses ke Javascript
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Pesan BERHASIL dikirim ke email admin!']);

        } catch (Exception $e) {
            // Respon Gagal (Error Handler)
            // Di Hosting, error detail ($mail->ErrorInfo) sebaiknya dicatat di log saja, 
            // tapi untuk sekarang kita tampilkan agar Anda tahu jika ada masalah.
            http_response_code(500);
            echo json_encode([
                'status' => 'error', 
                'message' => 'Gagal kirim email. Error: ' . $mail->ErrorInfo
            ]);
        }
        exit;
    }
}