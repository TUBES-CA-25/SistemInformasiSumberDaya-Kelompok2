document.addEventListener("DOMContentLoaded", function () {
  const contactForm = document.getElementById("contactForm");

  if (!contactForm) return;

  contactForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const btn = document.getElementById("btnKirim");
    const originalText = btn.innerHTML;

    // Ubah tombol jadi loading
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';

    try {
      const formData = new FormData(this);
      // Sesuaikan port jika perlu
      const apiUrl = "http://127.0.0.1:8000/api.php/kontak";

      const response = await fetch(apiUrl, {
        method: "POST",
        body: formData,
      });

      const text = await response.text();
      let result;

      try {
        result = JSON.parse(text);
      } catch (err) {
        console.error("Respon Server:", text);
        throw new Error("Respon server tidak valid.");
      }

      if (result.status === "success") {
        // NOTIFIKASI SUKSES (CANTIK)
        Swal.fire({
          title: "Terkirim!",
          text: result.message,
          icon: "success",
          confirmButtonText: "Oke",
          confirmButtonColor: "#2563eb", // Warna biru sesuai tema
        });
        contactForm.reset();
      } else {
        // NOTIFIKASI GAGAL
        Swal.fire({
          title: "Gagal",
          text: result.message || "Terjadi kesalahan sistem",
          icon: "error",
          confirmButtonText: "Coba Lagi",
          confirmButtonColor: "#ef4444", // Warna merah
        });
      }
    } catch (error) {
      Swal.fire({
        title: "Error Koneksi",
        text: "Gagal menghubungi server. Pastikan internet lancar.",
        icon: "warning",
        confirmButtonColor: "#f59e0b",
      });
    } finally {
      btn.disabled = false;
      btn.innerHTML = originalText;
    }
  });
});
