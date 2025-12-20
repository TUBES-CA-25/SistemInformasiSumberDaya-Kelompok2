<section class="resource-section py-10 bg-gray-50">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb / Back Button -->
        <div class="mb-8">
            <a href="<?= BASE_URL ?>/asisten" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Asisten
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-5xl mx-auto border border-gray-100">
            <!-- Profile Header with Background -->
            <div class="h-48 bg-gradient-to-r from-blue-600 to-indigo-700 relative">
                <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-black/50 to-transparent"></div>
            </div>

            <div class="relative px-8 pb-10">
                <!-- Profile Image & Basic Info Wrapper -->
                <div class="flex flex-col md:flex-row items-start -mt-20">
                    <!-- Profile Image -->
                    <div class="w-40 h-40 rounded-full border-4 border-white shadow-lg overflow-hidden bg-white flex-shrink-0 z-10 relative group">
                        <?php
                            $fotoPath = !empty($asisten['foto']) ? ASSETS_URL . '/uploads/' . $asisten['foto'] : 'https://placehold.co/160x160/7f8c8d/white?text=' . urlencode(substr($asisten['nama'], 0, 2));
                        ?>
                        <img src="<?= $fotoPath ?>" 
                             alt="<?= htmlspecialchars($asisten['nama']) ?>" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             onerror="this.src='https://placehold.co/160x160/7f8c8d/white?text=Asisten'">
                    </div>

                    <!-- Name and Title -->
                    <div class="mt-4 md:mt-20 md:ml-6 flex-grow z-0 pt-2">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 mb-1"><?= htmlspecialchars($asisten['nama']) ?></h1>
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="text-lg text-blue-600 font-medium"><?= htmlspecialchars($asisten['jabatan'] ?? 'Asisten Laboratorium') ?></span>
                                    <?php if ($asisten['isKoordinator']): ?>
                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2.5 py-0.5 rounded-full font-bold border border-yellow-200 shadow-sm flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                                            </svg>
                                            Koordinator Lab
                                        </span>
                                    <?php endif; ?>
                                    <span class="bg-green-100 text-green-800 text-xs px-2.5 py-0.5 rounded-full font-medium border border-green-200">
                                        <?= htmlspecialchars($asisten['kategori'] ?? 'Umum') ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mt-4 md:mt-0 flex space-x-3">
                                <a href="mailto:<?= htmlspecialchars($asisten['email']) ?>" class="flex items-center justify-center px-4 py-2 border border-blue-600 text-blue-600 bg-white hover:bg-blue-50 rounded-lg shadow-sm transition-all duration-200 font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Hubungi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
                    <!-- Left Sidebar: Personal Info -->
                    <div class="md:col-span-1 space-y-6">
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Informasi Biodata</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Jurusan</p>
                                    <p class="font-medium text-gray-900"><?= htmlspecialchars($asisten['jurusan'] ?? '-') ?></p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Laboratorium</p>
                                    <div class="flex items-center">
                                        <span class="inline-block w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>
                                        <p class="font-medium text-gray-900"><?= htmlspecialchars($asisten['lab'] ?? '-') ?></p>
                                    </div>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Spesialisasi</p>
                                    <p class="font-medium text-gray-900"><?= htmlspecialchars($asisten['spesialisasi'] ?? '-') ?></p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Status</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $asisten['statusAktif'] == '1' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= $asisten['statusAktif'] == '1' ? 'Aktif' : 'Tidak Aktif' ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Skills Section -->
                        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                Keahlian
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <?php 
                                    $skills = json_decode($asisten['skills'] ?? '[]', true);
                                    if (json_last_error() !== JSON_ERROR_NONE) {
                                        // Fallback if not valid JSON
                                        if (!empty($asisten['skills'])) {
                                            $skills = explode(',', $asisten['skills']);
                                        } else {
                                            $skills = [];
                                        }
                                    }
                                    
                                    if (!empty($skills) && is_array($skills)):
                                        foreach($skills as $skill): 
                                            $skill = trim($skill);
                                            if(empty($skill)) continue;
                                ?>
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-sm font-medium border border-indigo-100 hover:bg-indigo-100 transition-colors cursor-default">
                                        <?= htmlspecialchars($skill) ?>
                                    </span>
                                <?php 
                                        endforeach;
                                    else:
                                ?>
                                    <p class="text-gray-400 text-sm italic">Belum ada data keahlian.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content: Bio -->
                    <div class="md:col-span-2">
                        <div class="bg-white rounded-xl p-8 border border-gray-100 shadow-sm h-full">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <span class="bg-blue-100 p-2 rounded-lg mr-3 text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                                Tentang Saya
                            </h3>
                            
                            <div class="prose max-w-none text-gray-600 leading-relaxed">
                                <?php if (!empty($asisten['bio'])): ?>
                                    <?= nl2br(htmlspecialchars($asisten['bio'])) ?>
                                <?php else: ?>
                                    <p class="italic text-gray-400">Belum ada biografi yang ditambahkan.</p>
                                    <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300 text-center">
                                        <p class="text-sm text-gray-500">Asisten ini berdedikasi untuk membantu kegiatan praktikum dan riset di laboratorium kami.</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mt-10 pt-6 border-t border-gray-100">
                                <h4 class="font-bold text-gray-800 mb-4">Kontribusi & Tanggung Jawab</h4>
                                <ul class="space-y-3">
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-gray-600">Membantu pelaksanaan praktikum mahasiswa</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-gray-600">Pemeliharaan dan inventarisasi peralatan laboratorium</span>
                                    </li>
                                    <?php if($asisten['isKoordinator']): ?>
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-gray-600 font-medium">Mengkoordinir jadwal dan tugas asisten lainnya</span>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
