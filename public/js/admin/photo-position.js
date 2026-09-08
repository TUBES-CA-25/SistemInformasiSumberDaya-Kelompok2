/**
 * PhotoPositioner
 * 
 * Widget "Atur, Pan & Zoom Posisi Foto Profil" di Form Admin.
 * - Geser foto 4 arah (Kiri, Kanan, Atas, Bawah) via mouse drag atau tombol nav.
 * - Zoom In / Zoom Out via mouse wheel scroll atau zoom slider.
 * - Overlay Grid 3x3 Rule-of-Thirds untuk bantuan visual cropping.
 * - Otomatis menyimpan koordinat persentase (0-100%) ke hidden inputs (foto_pos_x & foto_pos_y).
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

    function applyPosition(box, x, y, zoom, markDirty = true) {
        x = clamp(x, 0, 100);
        y = clamp(y, 0, 100);

        if (zoom === undefined || zoom === null) {
            zoom = parseFloat(box.dataset.zoomLevel) || 1;
        }
        zoom = clamp(zoom, 1, 2.5);
        box.dataset.zoomLevel = zoom.toFixed(2);
        if (markDirty) {
            box.dataset.isDirty = '1';
        }

        const img = box.querySelector('.photo-position-img');
        if (img) {
            img.style.objectPosition = x.toFixed(1) + '% ' + y.toFixed(1) + '%';
            img.style.transform = `scale(${zoom})`;
            img.style.transformOrigin = `${x.toFixed(1)}% ${y.toFixed(1)}%`;
        }

        const inputs = getInputs(box);
        if (inputs.x) inputs.x.value = x.toFixed(1);
        if (inputs.y) inputs.y.value = y.toFixed(1);

        // Update control elements if present
        const parent = box.parentElement;
        if (parent) {
            const slider = parent.querySelector('.photo-zoom-slider');
            if (slider && parseFloat(slider.value) !== zoom) {
                slider.value = zoom;
            }
            const infoBadge = parent.querySelector('.photo-pos-badge');
            if (infoBadge) {
                infoBadge.textContent = `X: ${x.toFixed(0)}% | Y: ${y.toFixed(0)}% | ${zoom.toFixed(1)}x`;
            }
        }
    }

    function ensureGridAndControls(box) {
        // 1. Ensure Grid 3x3 Overlay
        if (!box.querySelector('.photo-crop-grid')) {
            const grid = document.createElement('div');
            grid.className = 'photo-crop-grid absolute inset-0 pointer-events-none grid grid-cols-3 grid-rows-3 border border-white/40 opacity-50 transition-opacity duration-200 z-10';
            grid.innerHTML = `
                <div class="border-r border-b border-white/30"></div>
                <div class="border-r border-b border-white/30"></div>
                <div class="border-b border-white/30"></div>
                <div class="border-r border-b border-white/30"></div>
                <div class="border-r border-b border-white/30"></div>
                <div class="border-b border-white/30"></div>
                <div class="border-r border-white/30"></div>
                <div class="border-r border-white/30"></div>
                <div></div>
            `;
            box.appendChild(grid);
        }

        // 2. Ensure Control Toolbar below box
        const parent = box.parentElement;
        if (parent) {
            let controls = parent.querySelector('.photo-position-controls');
            if (!controls) {
                controls = document.createElement('div');
                controls.className = 'photo-position-controls mt-2.5 flex flex-col gap-2 p-2.5 bg-gray-50 rounded-xl border border-gray-200 text-gray-700 shadow-sm';
                controls.innerHTML = `
                    <!-- Header Status & Zoom Slider -->
                    <div class="flex items-center justify-between gap-2 text-xs">
                        <span class="photo-pos-badge font-mono text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">X: 50% | Y: 50% | 1.0x</span>
                        <div class="flex items-center gap-1.5 flex-1 justify-end">
                            <button type="button" class="btn-zoom-out w-6 h-6 bg-white rounded border border-gray-300 text-gray-700 font-bold hover:bg-blue-50 hover:text-blue-600 flex items-center justify-center transition-colors" title="Zoom Out"><i class="fas fa-minus text-[10px]"></i></button>
                            <input type="range" class="photo-zoom-slider w-20 accent-blue-600 h-1.5 bg-gray-200 rounded-lg cursor-pointer" min="1" max="2.5" step="0.05" value="1">
                            <button type="button" class="btn-zoom-in w-6 h-6 bg-white rounded border border-gray-300 text-gray-700 font-bold hover:bg-blue-50 hover:text-blue-600 flex items-center justify-center transition-colors" title="Zoom In"><i class="fas fa-plus text-[10px]"></i></button>
                            <button type="button" class="btn-reset-pos px-2 py-0.5 bg-white border border-gray-300 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded font-bold text-[10px] flex items-center gap-1 transition-colors ml-1" title="Reset ke tengah"><i class="fas fa-undo"></i> Reset</button>
                        </div>
                    </div>

                    <!-- Directional Arrow Buttons -->
                    <div class="flex items-center justify-between gap-1 text-[11px] font-semibold text-gray-600 border-t border-gray-200/80 pt-2">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider flex items-center gap-1"><i class="fas fa-arrows-alt text-blue-500"></i> Geser Foto:</span>
                        <div class="flex items-center gap-1">
                            <button type="button" class="btn-step-left px-2 py-0.5 bg-white rounded border border-gray-300 hover:bg-blue-50 hover:text-blue-600 flex items-center gap-1 transition-colors text-[10px]" title="Geser Kiri"><i class="fas fa-arrow-left"></i> Kiri</button>
                            <button type="button" class="btn-step-right px-2 py-0.5 bg-white rounded border border-gray-300 hover:bg-blue-50 hover:text-blue-600 flex items-center gap-1 transition-colors text-[10px]" title="Geser Kanan">Kanan <i class="fas fa-arrow-right"></i></button>
                            <button type="button" class="btn-step-up px-2 py-0.5 bg-white rounded border border-gray-300 hover:bg-blue-50 hover:text-blue-600 flex items-center gap-1 transition-colors text-[10px]" title="Geser Atas"><i class="fas fa-arrow-up"></i> Atas</button>
                            <button type="button" class="btn-step-down px-2 py-0.5 bg-white rounded border border-gray-300 hover:bg-blue-50 hover:text-blue-600 flex items-center gap-1 transition-colors text-[10px]" title="Geser Bawah">Bawah <i class="fas fa-arrow-down"></i></button>
                        </div>
                    </div>
                `;
                box.after(controls);

                // Bind Toolbar Events
                const slider = controls.querySelector('.photo-zoom-slider');
                const btnZoomIn = controls.querySelector('.btn-zoom-in');
                const btnZoomOut = controls.querySelector('.btn-zoom-out');
                const btnReset = controls.querySelector('.btn-reset-pos');
                const btnLeft = controls.querySelector('.btn-step-left');
                const btnRight = controls.querySelector('.btn-step-right');
                const btnUp = controls.querySelector('.btn-step-up');
                const btnDown = controls.querySelector('.btn-step-down');

                const getXY = () => {
                    const inputs = getInputs(box);
                    return {
                        x: inputs.x ? parseFloat(inputs.x.value) || 50 : 50,
                        y: inputs.y ? parseFloat(inputs.y.value) || 50 : 50
                    };
                };

                if (slider) {
                    slider.addEventListener('input', (e) => {
                        const xy = getXY();
                        applyPosition(box, xy.x, xy.y, parseFloat(e.target.value), true);
                    });
                }

                if (btnZoomIn) {
                    btnZoomIn.addEventListener('click', () => {
                        const xy = getXY();
                        let z = (parseFloat(box.dataset.zoomLevel) || 1) + 0.15;
                        applyPosition(box, xy.x, xy.y, z, true);
                    });
                }

                if (btnZoomOut) {
                    btnZoomOut.addEventListener('click', () => {
                        const xy = getXY();
                        let z = (parseFloat(box.dataset.zoomLevel) || 1) - 0.15;
                        applyPosition(box, xy.x, xy.y, z, true);
                    });
                }

                if (btnReset) {
                    btnReset.addEventListener('click', () => {
                        applyPosition(box, 50, 50, 1, true);
                    });
                }

                if (btnLeft) {
                    btnLeft.addEventListener('click', () => {
                        const xy = getXY();
                        applyPosition(box, xy.x - 5, xy.y, null, true);
                    });
                }

                if (btnRight) {
                    btnRight.addEventListener('click', () => {
                        const xy = getXY();
                        applyPosition(box, xy.x + 5, xy.y, null, true);
                    });
                }

                if (btnUp) {
                    btnUp.addEventListener('click', () => {
                        const xy = getXY();
                        applyPosition(box, xy.x, xy.y - 5, null, true);
                    });
                }

                if (btnDown) {
                    btnDown.addEventListener('click', () => {
                        const xy = getXY();
                        applyPosition(box, xy.x, xy.y + 5, null, true);
                    });
                }
            } else {
                controls.classList.remove('hidden');
            }
        }
    }

    function attach(box) {
        if (box.dataset.positionerAttached === '1') return;
        box.dataset.positionerAttached = '1';

        ensureGridAndControls(box);

        let dragging = false;
        let startClientX = 0;
        let startClientY = 0;
        let startX = 50;
        let startY = 50;

        box.style.touchAction = 'none';
        box.style.cursor = 'grab';

        // Mouse Wheel Zoom
        box.addEventListener('wheel', function (e) {
            const img = box.querySelector('.photo-position-img');
            if (!img || !img.src) return;
            e.preventDefault();

            let currentZoom = parseFloat(box.dataset.zoomLevel) || 1;
            const delta = e.deltaY < 0 ? 0.1 : -0.1;
            let newZoom = clamp(currentZoom + delta, 1, 2.5);

            const inputs = getInputs(box);
            const x = inputs.x ? parseFloat(inputs.x.value) || 50 : 50;
            const y = inputs.y ? parseFloat(inputs.y.value) || 50 : 50;

            applyPosition(box, x, y, newZoom, true);
        }, { passive: false });

        // Pointer Drag (Pan Kiri, Kanan, Atas, Bawah)
        box.addEventListener('pointerdown', function (e) {
            const img = box.querySelector('.photo-position-img');
            if (!img || !img.src) return;

            dragging = true;
            box.classList.add('is-dragging');
            box.style.cursor = 'grabbing';
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

            const zoom = parseFloat(box.dataset.zoomLevel) || 1;
            const scale = Math.max(rect.width / img.naturalWidth, rect.height / img.naturalHeight) * zoom;
            const renderedW = img.naturalWidth * scale;
            const renderedH = img.naturalHeight * scale;
            const rangeX = renderedW - rect.width;
            const rangeY = renderedH - rect.height;

            const deltaX = e.clientX - startClientX;
            const deltaY = e.clientY - startClientY;

            const MIN_RANGE = 1;

            const newX = rangeX > MIN_RANGE ? startX - (deltaX / rangeX) * 100 : startX - (deltaX / rect.width) * 50;
            const newY = rangeY > MIN_RANGE ? startY - (deltaY / rangeY) * 100 : startY - (deltaY / rect.height) * 50;

            applyPosition(box, newX, newY, null, true);
        });

        function endDrag() {
            if (!dragging) return;
            dragging = false;
            box.classList.remove('is-dragging');
            box.style.cursor = 'grab';
        }

        box.addEventListener('pointerup', endDrag);
        box.addEventListener('pointercancel', endDrag);
        box.addEventListener('pointerleave', function (e) {
            if (dragging && e.buttons === 0) endDrag();
        });
    }

    function setImage(boxId, url, x, y) {
        const box = document.getElementById(boxId);
        if (!box || !url) return;

        const img = box.querySelector('.photo-position-img');
        if (!img) return;

        const posX = (x === null || x === undefined || x === '') ? 50 : parseFloat(x);
        const posY = (y === null || y === undefined || y === '') ? 50 : parseFloat(y);

        img.crossOrigin = 'anonymous';
        img.onload = function () {
            applyPosition(box, isNaN(posX) ? 50 : posX, isNaN(posY) ? 50 : posY, 1, false);
            box.dataset.isDirty = '0';
        };
        img.src = url;

        box.classList.remove('hidden');
        attach(box);

        const parent = box.parentElement;
        if (parent) {
            const controls = parent.querySelector('.photo-position-controls');
            if (controls) controls.classList.remove('hidden');
        }
    }

    function reset(boxId) {
        const box = document.getElementById(boxId);
        if (!box) return;

        const parent = box.parentElement;
        if (parent) {
            const controls = parent.querySelector('.photo-position-controls');
            if (controls) controls.classList.add('hidden');
        }

        const img = box.querySelector('.photo-position-img');
        if (img) img.src = '';

        applyPosition(box, 50, 50, 1, false);
        box.dataset.isDirty = '0';
        box.classList.add('hidden');
    }

    function isDirty(boxId) {
        const box = typeof boxId === 'string' ? document.getElementById(boxId) : boxId;
        return box ? box.dataset.isDirty === '1' : false;
    }

    function resetDirty(boxId) {
        const box = typeof boxId === 'string' ? document.getElementById(boxId) : boxId;
        if (box) box.dataset.isDirty = '0';
    }

    function getCroppedBlob(boxId, targetWidth = 600, targetHeight = 600) {
        return new Promise((resolve) => {
            const box = typeof boxId === 'string' ? document.getElementById(boxId) : boxId;
            if (!box) return resolve(null);

            const img = box.querySelector('.photo-position-img');
            if (!img || !img.src || !img.naturalWidth || !img.naturalHeight) return resolve(null);
            if (img.naturalWidth === 0 || img.naturalHeight === 0) return resolve(null);

            const rect = box.getBoundingClientRect();
            const W_box = rect.width > 0 ? rect.width : 220;
            const H_box = rect.height > 0 ? rect.height : 220;

            const W_img = img.naturalWidth;
            const H_img = img.naturalHeight;

            const zoom = parseFloat(box.dataset.zoomLevel) || 1;

            const inputs = getInputs(box);
            const X_pct = inputs.x ? parseFloat(inputs.x.value) || 50 : 50;
            const Y_pct = inputs.y ? parseFloat(inputs.y.value) || 50 : 50;

            const S_base = Math.max(W_box / W_img, H_box / H_img);
            const S_total = S_base * zoom;

            const srcW = Math.min(W_img, W_box / S_total);
            const srcH = Math.min(H_img, H_box / S_total);

            let srcX = (W_img - srcW) * (X_pct / 100);
            let srcY = (H_img - srcH) * (Y_pct / 100);

            srcX = Math.max(0, Math.min(W_img - srcW, srcX));
            srcY = Math.max(0, Math.min(H_img - srcH, srcY));

            try {
                const canvas = document.createElement('canvas');
                canvas.width = targetWidth;
                canvas.height = targetHeight;
                const ctx = canvas.getContext('2d');

                ctx.imageSmoothingEnabled = true;
                ctx.imageSmoothingQuality = 'high';

                ctx.drawImage(img, srcX, srcY, srcW, srcH, 0, 0, targetWidth, targetHeight);

                canvas.toBlob((blob) => {
                    resolve(blob);
                }, 'image/jpeg', 0.92);
            } catch (err) {
                console.warn('Canvas cropping failed:', err);
                resolve(null);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.photo-position-box').forEach(attach);
    });

    return {
        attach: attach,
        setImage: setImage,
        reset: reset,
        isDirty: isDirty,
        resetDirty: resetDirty,
        getCroppedBlob: getCroppedBlob
    };
})();
