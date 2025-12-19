<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 max-w-7xl">
        <div class="page-header">
            <h1>Kepala Laboratorium</h1>
            <p>Tim kepemimpinan yang bertanggung jawab atas operasional dan pengembangan laboratorium.</p>
        </div>

        <div class="mt-10 mb-12 flex flex-col sm:flex-row gap-8 justify-between items-stretch">
            <?php if (!empty($manajemenList)): ?>
                <?php foreach ($manajemenList as $index => $manajemen): ?>
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 p-8 text-center flex-1 min-w-0">
                        <div class="w-40 h-48 mx-auto mb-6">
                            <img class="w-full h-full object-cover rounded-xl shadow-md" 
                                 src="<?php echo BASE_URL; ?>/storage/uploads/<?php echo htmlspecialchars($manajemen['foto']); ?>" 
                                 alt="<?php echo htmlspecialchars($manajemen['nama']); ?>" 
                                 onerror="this.src='https://placehold.co/300x400/<?php echo $index == 0 ? '34495e' : ($index == 1 ? '2c3e50' : '27ae60'); ?>/white?text=<?php echo urlencode($manajemen['nama']); ?>'">
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 leading-tight"><?php echo htmlspecialchars($manajemen['nama']); ?></h3>
                            <p class="text-gray-600 text-sm font-medium leading-relaxed"><?php echo htmlspecialchars($manajemen['jabatan']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-10">
                    <p class="text-gray-500">Belum ada data manajemen laboratorium. Silahkan hubungi administrator.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="vision-message">
            <h3>Pesan Tim Kepemimpinan</h3>
            <blockquote>
                "Kami berkomitmen untuk menciptakan lingkungan laboratorium yang kondusif untuk pembelajaran dan penelitian. 
                Melalui kerjasama tim yang solid, kami berusaha memberikan fasilitas dan bimbingan terbaik 
                untuk mengembangkan potensi mahasiswa di bidang teknologi informasi."
            </blockquote>
        </div>
    </div>
</section>