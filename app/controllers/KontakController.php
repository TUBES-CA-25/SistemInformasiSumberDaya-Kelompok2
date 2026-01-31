<?php

// PENTING: Panggil Namespace Library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class KontakController extends Controller {

    public function send($params) {
        
        // 1. Ambil Data
        $nama    = $_POST['nama'] ?? '';
        $email   = $_POST['email'] ?? '';
        $subjek  = $_POST['subjek'] ?? '';
        $pesan   = $_POST['pesan'] ?? '';

        // Validasi input
        if (empty($nama) || empty($email) || empty($pesan)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Semua kolom wajib diisi']);
            exit;
        }

        // 2. Inisialisasi PHPMailer
        $mail = new PHPMailer(true); // true = aktifkan exception agar error terlihat

        try {
            // --- KONFIGURASI SERVER (GMAIL) ---
            $mail->isSMTP();                                            
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                                   
            
            // GANTI DENGAN EMAIL KAMU
            $mail->Username   = 'nahwakakaa@gmail.com';                 
            
            // GANTI DENGAN APP PASSWORD (16 DIGIT) - JANGAN PASSWORD LOGIN BIASA!
            $mail->Password   = 'pzwx lfbx shzm jwoo';                               
            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            
            $mail->Port       = 587;                                    

            // --- PENGIRIM & PENERIMA ---
            // Pengirim di-set email kamu (karena SMTP Gmail melarang spoofing email orang lain)
            $mail->setFrom('email.kamu@gmail.com', 'Admin Website');
            
            // Penerima: Kirim ke email kamu sendiri untuk dicek
            $mail->addAddress('email.kamu@gmail.com');     
            
            // Reply-To: Agar kalau kamu klik "Balas", masuknya ke email si Pengunjung
            $mail->addReplyTo($email, $nama);

            // --- KONTEN EMAIL ---
            $mail->isHTML(true);                                  
            $mail->Subject = "Pesan Website: " . $subjek;
            $mail->Body    = "
                <h3>Pesan Baru dari Pengunjung</h3>
                <hr>
                <p><b>Nama:</b> $nama</p>
                <p><b>Email:</b> $email</p>
                <p><b>Subjek:</b> $subjek</p>
                <br>
                <div style='background: #f3f4f6; padding: 15px; border-radius: 5px;'>
                    <b>Isi Pesan:</b><br>
                    $pesan
                </div>
            ";
            $mail->AltBody = "Nama: $nama\nEmail: $email\nPesan: $pesan";

            // Kirim!
            $mail->send();

            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Pesan BERHASIL dikirim ke email admin!']);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error', 
                'message' => 'Gagal kirim email. Error: ' . $mail->ErrorInfo
            ]);
        }
        exit;
    }
}