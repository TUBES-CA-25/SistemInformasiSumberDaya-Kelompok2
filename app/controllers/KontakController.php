<?php

// PENTING: Namespace Library PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class KontakController extends Controller {

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
        $mail = new PHPMailer(true); 

        $mail->SMTPDebug = 2; // Level 2 akan menampilkan percakapan server
        $mail->Debugoutput = function($str, $level) {
            // Tulis log ke error log server (bukan ke layar agar JSON tidak rusak)
            error_log("SMTP DEBUG: $str");
        };

        try {
            // --- KONFIGURASI SERVER ---
            $mail->isSMTP();                                            
            $mail->Host       = SMTP_HOST;                     
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = SMTP_USER;                 
            $mail->Password   = SMTP_PASS;                               
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            
            $mail->Port       = SMTP_PORT;

            // [TAMBAHAN BARU] Bypass SSL Check
            // Ini agar teman Anda tidak terkena error SSL Certificate di Localhost
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true
                )
            );

            // --- PENGIRIM & PENERIMA ---
            $mail->setFrom(SMTP_USER, 'Sistem Informasi Sumber Daya');
            
            // Penerima: Admin
            $mail->addAddress(SMTP_USER);     
            
            // Reply-To: Balas langsung ke pengunjung
            $mail->addReplyTo($email, $nama);

            // --- KONTEN EMAIL ---
            $mail->isHTML(true);                                  
            $mail->Subject = "Pesan Baru: " . $subjek;
            
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
            
            $mail->AltBody = "Nama: $nama\nEmail: $email\nPesan: $pesan";

            // Kirim!
            $mail->send();

            // Respon Sukses
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Pesan BERHASIL dikirim ke email admin!']);

        } catch (Exception $e) {
            // Respon Gagal
            http_response_code(500);
            echo json_encode([
                'status' => 'error', 
                'message' => 'Gagal kirim email. Error: ' . $mail->ErrorInfo
            ]);
        }
        exit;
    }
}