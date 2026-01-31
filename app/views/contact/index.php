<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<section class="contact-section">
    <div class="container">
        
        <div class="contact-wrapper">
            <div class="contact-info-side">
                <h2>Hubungi Kami</h2>
                <p>Punya pertanyaan seputar praktikum atau penelitian? Kami siap membantu Anda. Silakan hubungi melalui saluran di bawah atau isi formulir pesan.</p>
                
                <div class="contact-methods">
                    <div class="method-item">
                        <div class="method-icon"><i class="ri-map-pin-2-line"></i></div>
                        <div class="method-text">
                            <h4>Lokasi Lab</h4>
                            <span>Kampus II UMI, Gedung FIKOM Lt. 2 & 3<br>Jl. Urip Sumoharjo No. Km. 5, Makassar</span>
                        </div>
                    </div>

                    <div class="method-item">
                        <div class="method-icon"><i class="ri-mail-line"></i></div>
                        <div class="method-text">
                            <h4>Email Resmi</h4>
                            <span>fikom.iclabs@umi.ac.id</span>
                        </div>
                    </div>

                    <div class="method-item">
                        <div class="method-icon"><i class="ri-whatsapp-line"></i></div>
                        <div class="method-text">
                            <h4>WhatsApp Support</h4>
                            <span>+62 411 455666</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contact-form-card">
                <form id="contactForm">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" placeholder="Masukkan nama Anda..." required class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="example@domain.com" required class="w-full px-4 py-2 border rounded-lg">
                    </div>

                    <div class="form-group">
                        <label>Subjek</label>
                        <input type="text" name="subjek" placeholder="Tujuan pesan..." required class="w-full px-4 py-2 border rounded-lg">
                    </div>

                    <div class="form-group">
                        <label>Pesan Anda</label>
                        <textarea name="pesan" rows="5" placeholder="Tuliskan pesan..." required class="w-full px-4 py-2 border rounded-lg"></textarea>
                    </div>

                    <button type="submit" id="btnKirim" class="btn-submit w-full bg-blue-600 text-white py-2 rounded-lg mt-4">
                        Kirim Pesan <i class="ri-send-plane-fill"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="map-container">
            <div class="map-header">
                <h3><i class="ri-map-pin-line"></i> Lokasi Laboratorium</h3>
            </div>
            <div class="map-iframe-wrapper">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3973.791123008261!2d119.448235!3d-5.137305!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbefd3165008369%3A0x7af75b8baf265f2b!2sFakultas%20Ilmu%20Komputer%20UMI!5e0!3m2!1sid!2sus!4v1766106276722!5m2!1sid!2sus" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

    </div>
</section>

<script src="<?= defined('ASSETS_URL') ? ASSETS_URL : PUBLIC_URL ?>/js/kontak.js"></script>