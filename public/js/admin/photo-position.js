/**
 * PhotoPositioner
 *
 * Widget "geser untuk atur posisi tampil" foto profil di form admin.
 * Alih-alih server memotong foto secara paksa, foto disimpan utuh dan
 * posisi crop-nya (object-position CSS) diatur manual lewat drag,
 * lalu disimpan sebagai persentase (0-100) di dua hidden input.
 *
 * Pemakaian di HTML:
 *   <div id="fotoPositionBox" class="photo-position-box hidden"
 *        data-target-x="inputFotoPosX" data-target-y="inputFotoPosY">
 *     <img class="photo-position-img" draggable="false">
 *   </div>
 *   <input type="hidden" id="inputFotoPosX" name="foto_pos_x" value="50">
 *   <input type="hidden" id="inputFotoPosY" name="foto_pos_y" value="50">
 *
 * Pemakaian di JS:
 *   PhotoPositioner.setImage('fotoPositionBox', url, posX, posY); // tampilkan + set posisi
 *   PhotoPositioner.reset('fotoPositionBox');                     // sembunyikan (tidak ada foto)
 */
const PhotoPositioner = (function () {
    function clamp(v, min, max) {
        return Math.max(min, Math.min(max, v));
    }

    function getInputs(box) {
        return {
            x: document.getElementById(box.dataset.targetX),
            y: document.getElementById(box.dataset.targetY)
        };
    }

    function applyPosition(box, x, y) {
        x = clamp(x, 0, 100);
        y = clamp(y, 0, 100);

        const img = box.querySelector('.photo-position-img');
        if (img) img.style.objectPosition = x.toFixed(1) + '% ' + y.toFixed(1) + '%';

        const inputs = getInputs(box);
        if (inputs.x) inputs.x.value = x.toFixed(1);
        if (inputs.y) inputs.y.value = y.toFixed(1);
    }

    function attach(box) {
        if (box.dataset.positionerAttached === '1') return;
        box.dataset.positionerAttached = '1';

        let dragging = false;
        let startClientX = 0;
        let startClientY = 0;
        let startX = 50;
        let startY = 50;

        box.style.touchAction = 'none';

        box.addEventListener('pointerdown', function (e) {
            const img = box.querySelector('.photo-position-img');
            if (!img || !img.src) return;

            dragging = true;
            box.classList.add('is-dragging');
            try { box.setPointerCapture(e.pointerId); } catch (err) { /* noop */ }

            startClientX = e.clientX;
            startClientY = e.clientY;

            const inputs = getInputs(box);
            startX = inputs.x ? parseFloat(inputs.x.value) || 50 : 50;
            startY = inputs.y ? parseFloat(inputs.y.value) || 50 : 50;

            e.preventDefault();
        });

        box.addEventListener('pointermove', function (e) {
            if (!dragging) return;

            const img = box.querySelector('.photo-position-img');
            if (!img || !img.naturalWidth || !img.naturalHeight) return;

            const rect = box.getBoundingClientRect();
            if (rect.width === 0 || rect.height === 0) return;

            // object-fit: cover -> gambar diskalakan agar menutupi seluruh kotak
            const scale = Math.max(rect.width / img.naturalWidth, rect.height / img.naturalHeight);
            const renderedW = img.naturalWidth * scale;
            const renderedH = img.naturalHeight * scale;
            const rangeX = renderedW - rect.width;
            const rangeY = renderedH - rect.height;

            const deltaX = e.clientX - startClientX;
            const deltaY = e.clientY - startClientY;

            // Pakai ambang batas 1px, bukan > 0, supaya sisa desimal hasil pembulatan
            // scale (mis. gambar lama yang sudah persis persegi) tidak memicu pembagian
            // dengan bilangan mendekati nol yang melontarkan posisi ke ujung ekstrem.
            const MIN_RANGE = 1;

            // Geser ke kanan = ingin lihat bagian kiri foto -> persentase turun
            const newX = rangeX > MIN_RANGE ? startX - (deltaX / rangeX) * 100 : 50;
            const newY = rangeY > MIN_RANGE ? startY - (deltaY / rangeY) * 100 : 50;

            applyPosition(box, newX, newY);
        });

        function endDrag() {
            if (!dragging) return;
            dragging = false;
            box.classList.remove('is-dragging');
        }

        box.addEventListener('pointerup', endDrag);
        box.addEventListener('pointercancel', endDrag);
        box.addEventListener('pointerleave', function (e) {
            if (dragging && e.buttons === 0) endDrag();
        });
    }

    /**
     * Tampilkan box dengan gambar tertentu di posisi (x, y) persen.
     * Dipanggil saat: pilih file baru (posisi direset ke tengah), atau saat edit data lama.
     */
    function setImage(boxId, url, x, y) {
        const box = document.getElementById(boxId);
        if (!box || !url) return;

        const img = box.querySelector('.photo-position-img');
        if (!img) return;

        const posX = (x === null || x === undefined || x === '') ? 50 : parseFloat(x);
        const posY = (y === null || y === undefined || y === '') ? 50 : parseFloat(y);

        img.onload = function () {
            applyPosition(box, isNaN(posX) ? 50 : posX, isNaN(posY) ? 50 : posY);
        };
        img.src = url;

        box.classList.remove('hidden');
        attach(box);
    }

    /**
     * Sembunyikan box & kembalikan posisi ke tengah (dipanggil saat tidak ada foto / form direset).
     */
    function reset(boxId) {
        const box = document.getElementById(boxId);
        if (!box) return;

        const img = box.querySelector('.photo-position-img');
        if (img) img.src = '';

        applyPosition(box, 50, 50);
        box.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.photo-position-box').forEach(attach);
    });

    return { attach: attach, setImage: setImage, reset: reset };
})();
