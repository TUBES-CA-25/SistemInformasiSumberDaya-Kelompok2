<link rel="stylesheet" href="<?= ASSETS_URL ?>/css/fasilitas.css">
<link rel="stylesheet" href="<?= ASSETS_URL ?>/css/denah-interaktif.css"> 

<section class="fasilitas-section">
    <div class="container">
        
        <header class="page-header denah-header">
            <span class="header-badge">Floor Plan</span>
            <h1>Denah Lantai 2</h1>
            <p>Tata letak Laboratorium, Ruang Riset, dan Fasilitas Pendukung.</p>
        </header>

        <div class="denah-layout">
            
            <div class="denah-card map-box">
                <div class="card-title">
                    <i class="ri-map-2-line"></i>
                    <h3>Layout Ruangan (Interaktif)</h3>
                </div>
                
                <div class="image-wrapper interactive-map-container">
                    
                    <img src="<?= PUBLIC_URL ?>/images/denah-lantai-2.png" alt="Denah Lantai 2" class="map-base">

                    <svg class="map-svg" viewBox="0 0 1920 1080" preserveAspectRatio="xMidYMid meet">
        
                        <path d="M1163.6 469.5V595.002H1385.5V869.5H1089.5V469.5H1163.6Z" 
                            transform="translate(5, 5)"
                            class="room-poly" 
                            data-id="L1" 
                            fill="transparent">
                        </path>
                        
                        <path d="M1383.5 956.5 H1748.5 V594.5 H1383.5 Z" 
                            transform="translate(10, 0)"
                            class="room-poly" 
                            data-id="L2" 
                            fill="transparent">
                        </path>

                        <path d="M1744.49 59.52V594.176H1670.94V468.85H1386.12V146.271H1468.98V59.52H1744.49Z" 
                            transform="translate(10, -5)"
                            class="room-poly" 
                            data-id="L3" 
                            fill="transparent">
                        </path>

                        <path d="M1088.4 471.737H1386.11V239.337H1088.4V471.737Z" 
                            transform="translate(5, -5)"
                            class="room-poly" 
                            data-id="L4" 
                            fill="transparent">
                        </path>

                        <path d="M555.782 56.999L555.499 292.999V293.5H783.499V56.5H555.782V56.999Z" 
                            transform="translate(-6, -5)"
                            class="room-poly" 
                            data-id="L5" 
                            fill="transparent">
                        </path>

                        <path d="M555.499 57.5V292.5H328.5L328.782 57.5H555.499Z" 
                            transform="translate(-11, -8)"
                            class="room-poly" 
                            data-id="L6" 
                            fill="transparent">
                        </path>

                        <path d="M174.5 628.018H327.628V361.266H174.5V628.018Z" 
                            transform="translate(-13, -1)"
                            class="room-poly" 
                            data-id="L7" 
                            fill="transparent">
                        </path>

                        <path d="M430 362H531L530.246 573H430V362Z" 
                            transform="translate(-8, -2)"
                            class="room-poly" 
                            data-id="R1" 
                            fill="transparent">
                        </path>

                        <path d="M329 362H431L430.239 573H329V362Z" 
                            transform="translate(-12, -2)"
                            class="room-poly" 
                            data-id="R2" 
                            fill="transparent">
                        </path>

                        <path d="M230.492 116.021H327.129V293.063H230.492V116.021Z" 
                            transform="translate(-12, -7)"
                            class="room-poly" 
                            data-id="R3" 
                            fill="transparent">
                        </path>

                    </svg>

                    </div>
            </div>

            <div class="denah-card legend-box">
                <div class="card-title">
                    <i class="ri-list-check"></i>
                    <h3>Legend</h3>
                </div>
                
                <div class="legend-grid-layout">
                    <div class="legend-column">
                        <div class="legend-item"><span class="box blue"></span> <span class="code">L1</span> <span class="desc">: Internet of Things Laboratory</span></div>
                        <div class="legend-item"><span class="box blue"></span> <span class="code">L2</span> <span class="desc">: StartUp Laboratory</span></div>
                        <div class="legend-item"><span class="box blue"></span> <span class="code">L3</span> <span class="desc">: Multimedia Laboratory</span></div>
                        <div class="legend-item"><span class="box blue"></span> <span class="code">L4</span> <span class="desc">: Computer Network Laboratory</span></div>
                        <div class="legend-item"><span class="box blue"></span> <span class="code">L5</span> <span class="desc">: Data Science Laboratory</span></div>
                        <div class="legend-item"><span class="box blue"></span> <span class="code">L6</span> <span class="desc">: Computer Vision Laboratory</span></div>
                        <div class="legend-item"><span class="box blue"></span> <span class="code">L7</span> <span class="desc">: Microcontroller Laboratory</span></div>
                        
                        <div class="gap-small"></div>
                        
                        <div class="legend-item"><span class="box grey"></span> <span class="code">HN</span> <span class="desc">: Head of Networking and<br>Programming Laboratory</span></div>
                        <div class="legend-item"><span class="box grey"></span> <span class="code">HB</span> <span class="desc">: Head of Basic Laboratory</span></div>
                        <div class="legend-item"><span class="box grey"></span> <span class="code">PR</span> <span class="desc">: PC Room</span></div>
                    </div>

                    <div class="legend-column">
                        <div class="legend-item"><span class="box grey"></span> <span class="code">LR</span> <span class="desc">: Laboratory Services Room</span></div>
                        <div class="legend-item"><span class="box grey"></span> <span class="code">AR</span> <span class="desc">: Assistant Room</span></div>
                        <div class="legend-item"><span class="box grey"></span> <span class="code">WH</span> <span class="desc">: WareHouse</span></div>
                        <div class="legend-item"><span class="box grey"></span> <span class="code">SI</span> <span class="desc">: Studi Informatika</span></div>
                        <div class="legend-item"><span class="box grey"></span> <span class="code">SR</span> <span class="desc">: Server Room</span></div>
                        
                        <div class="gap-small"></div>

                        <div class="legend-item"><span class="box orange"></span> <span class="code">R1</span> <span class="desc">: Research Room 1</span></div>
                        <div class="legend-item"><span class="box orange"></span> <span class="code">R2</span> <span class="desc">: Research Room 2</span></div>
                        <div class="legend-item"><span class="box orange"></span> <span class="code">R3</span> <span class="desc">: Research Room 3</span></div>
                        
                        <div class="gap-small"></div>

                        <div class="legend-item"><span class="box white border"></span> <span class="code">WS</span> <span class="desc">: Working Space</span></div>
                        <div class="legend-item"><span class="box yellow"></span> <span class="code">TL</span> <span class="desc">: Toilet</span></div>
                        <div class="legend-item"><span class="box stairs-icon"></span> <span class="code">ST</span> <span class="desc">: Stairs</span></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<div id="room-tooltip" class="room-tooltip">
    <div class="tooltip-img">
        <img id="tt-image" src="" alt="Room Preview">
    </div>
    <div class="tooltip-content">
        <h4 id="tt-title">Nama Ruangan</h4>
        <p id="tt-desc">Deskripsi singkat...</p>
        <span id="tt-status" class="status-badge">Available</span>
    </div>
</div>

<script src="<?= PUBLIC_URL ?>/js/denah.js"></script>