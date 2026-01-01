<aside class="w-64 bg-gray-900 flex-shrink-0 flex flex-col transition-all duration-300">
    <div class="flex items-center justify-center h-16 bg-gray-800 shadow-md border-b border-gray-700">
        <h2 class="text-xl font-bold text-white tracking-wider">
            <i class="fas fa-flask text-blue-500 mr-2"></i>SISTEM LAB
        </h2>
    </div>

    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1">
            <li>
                <a href="javascript:void(0)" onclick="navigate('admin')" class="<?= getMenuClass($uri, 'admin') ?>">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>
            
            <li>
                <a href="javascript:void(0)" onclick="navigate('admin/asisten')" class="<?= getMenuClass($uri, 'admin/asisten') ?>">
                    <i class="fas fa-users w-6"></i>
                    <span class="font-medium">Data Asisten</span>
                </a>
            </li>
            
            <li>
                <a href="javascript:void(0)" onclick="navigate('admin/alumni')" class="<?= getMenuClass($uri, 'admin/alumni') ?>">
                    <i class="fas fa-user-graduate w-6"></i>
                    <span class="font-medium">Data Alumni</span>
                </a>
            </li>
            
            <li>
                <a href="javascript:void(0)" onclick="navigate('admin/laboratorium')" class="<?= getMenuClass($uri, 'admin/laboratorium') ?>">
                    <i class="fas fa-desktop w-6"></i>
                    <span class="font-medium">Data Fasilitas</span>
                </a>
            </li>
            
            <li>
                <a href="javascript:void(0)" onclick="navigate('admin/matakuliah')" class="<?= getMenuClass($uri, 'admin/matakuliah') ?>">
                    <i class="fas fa-book w-6"></i>
                    <span class="font-medium">Data Mata Kuliah</span>
                </a>
            </li>
            
            <li>
                <a href="javascript:void(0)" onclick="navigate('admin/jadwal')" class="<?= getMenuClass($uri, 'admin/jadwal') ?>">
                    <i class="fas fa-calendar-alt w-6"></i>
                    <span class="font-medium">Jadwal Praktikum</span>
                </a>
            </li>

            <li>
                <a href="javascript:void(0)" onclick="navigate('admin/peraturan')" class="<?= getMenuClass($uri, 'admin/peraturan') ?>">
                    <i class="fas fa-gavel w-6"></i>
                    <span class="font-medium">Peraturan Lab</span>
                </a>
            </li>

            <li>
                <a href="javascript:void(0)" onclick="navigate('admin/sanksi')" class="<?= getMenuClass($uri, 'admin/sanksi') ?>">
                    <i class="fas fa-exclamation-triangle w-6"></i>
                    <span class="font-medium">Sanksi Lab</span>
                </a>
            </li>

            <li>
                <a href="javascript:void(0)" onclick="navigate('admin/manajemen')" class="<?= getMenuClass($uri, 'admin/manajemen') ?>">
                    <i class="fas fa-user-tie w-6"></i>
                    <span class="font-medium">Manajemen Lab</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="p-4 border-t border-gray-800 bg-gray-900">
        <a href="<?= PUBLIC_URL ?>/logout" class="flex items-center w-full px-4 py-2 text-sm text-red-400 bg-gray-800 rounded-lg hover:bg-red-600 hover:text-white transition-colors duration-200">
            <i class="fas fa-sign-out-alt w-6"></i>
            <span class="font-semibold">Logout</span>
        </a>
    </div>
</aside>