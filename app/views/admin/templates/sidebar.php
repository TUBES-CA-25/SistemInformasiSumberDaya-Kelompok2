<aside class="w-64 bg-slate-900 flex-shrink-0 flex flex-col h-screen font-sans border-r border-slate-800 transition-all duration-300">
    
    <div class="h-20 flex items-center px-6 border-b border-slate-800/50 bg-slate-900/50 backdrop-blur-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-cyan-500 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="fas fa-flask text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-white font-bold text-lg tracking-wide">SISTEM LAB</h1>
                <p class="text-xs text-slate-400 font-medium">Administrator</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-2 custom-scrollbar">
        
        <div>
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Utama</p>
            <ul class="space-y-1">
                <li>
                    <a href="javascript:void(0)" onclick="navigate('admin')" 
                       class="<?= $uri == 'admin' ? 'bg-blue-600/10 text-blue-400 border-blue-500' : 'text-slate-400 border-transparent hover:text-white hover:bg-slate-800/50' ?> group flex items-center px-4 py-3 text-sm font-medium rounded-xl border-l-[3px] transition-all duration-200">
                        <i class="fas fa-tachometer-alt w-5 text-center <?= $uri == 'admin' ? 'text-blue-400' : 'text-slate-500 group-hover:text-white' ?> transition-colors"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>

        <div id="groupMaster" class="mt-4">
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Database</p>
            
            <button onclick="toggleMenu('menuMaster', 'arrowMaster')" 
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-slate-400 rounded-xl hover:bg-slate-800/50 hover:text-white transition-all duration-200 group focus:outline-none">
                <div class="flex items-center">
                    <i class="fas fa-database w-5 text-center text-slate-500 group-hover:text-emerald-400 transition-colors"></i>
                    <span class="ml-3">Master Data</span>
                </div>
                <i id="arrowMaster" class="fas fa-chevron-right text-[10px] transition-transform duration-300"></i>
            </button>

            <ul id="menuMaster" class="hidden mt-1 space-y-1 pl-11 relative">
                <div class="absolute left-6 top-0 bottom-0 w-px bg-slate-800"></div>

                <?php $active = strpos($uri, 'asisten') !== false; ?>
                <li>
                    <a href="javascript:void(0)" onclick="navigate('admin/asisten')" 
                       class="<?= $active ? 'text-emerald-400 font-semibold' : 'text-slate-400 hover:text-white' ?> block py-2 pl-4 text-sm transition-colors relative"
                       data-active="<?= $active ? 'true' : 'false' ?>"> <?php if($active): ?><span class="absolute -left-[21px] top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]"></span><?php endif; ?>
                       Data Asisten
                    </a>
                </li>

                 <?php $active = strpos($uri, 'alumni') !== false; ?>
                 <li>
                    <a href="javascript:void(0)" onclick="navigate('admin/alumni')" 
                       class="<?= $active ? 'text-emerald-400 font-semibold' : 'text-slate-400 hover:text-white' ?> block py-2 pl-4 text-sm transition-colors relative"
                       data-active="<?= $active ? 'true' : 'false' ?>">
                       <?php if($active): ?><span class="absolute -left-[21px] top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]"></span><?php endif; ?>
                       Data Alumni
                    </a>
                </li>

                <?php $active = strpos($uri, 'manajemen') !== false; ?>
                <li>
                    <a href="javascript:void(0)" onclick="navigate('admin/manajemen')" 
                       class="<?= $active ? 'text-emerald-400 font-semibold' : 'text-slate-400 hover:text-white' ?> block py-2 pl-4 text-sm transition-colors relative"
                       data-active="<?= $active ? 'true' : 'false' ?>">
                       <?php if($active): ?><span class="absolute -left-[21px] top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]"></span><?php endif; ?>
                       Struktur Organisasi
                    </a>
                </li>

                <?php $active = strpos($uri, 'laboratorium') !== false; ?>
                <li>
                    <a href="javascript:void(0)" onclick="navigate('admin/laboratorium')" 
                       class="<?= $active ? 'text-emerald-400 font-semibold' : 'text-slate-400 hover:text-white' ?> block py-2 pl-4 text-sm transition-colors relative"
                       data-active="<?= $active ? 'true' : 'false' ?>">
                       <?php if($active): ?><span class="absolute -left-[21px] top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]"></span><?php endif; ?>
                       Fasilitas Lab
                    </a>
                </li>
                 
                <?php $active = strpos($uri, 'matakuliah') !== false; ?>
                <li>
                    <a href="javascript:void(0)" onclick="navigate('admin/matakuliah')" 
                       class="<?= $active ? 'text-emerald-400 font-semibold' : 'text-slate-400 hover:text-white' ?> block py-2 pl-4 text-sm transition-colors relative"
                       data-active="<?= $active ? 'true' : 'false' ?>">
                       <?php if($active): ?><span class="absolute -left-[21px] top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]"></span><?php endif; ?>
                       Mata Kuliah
                    </a>
                </li>
            </ul>
        </div>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin'): ?>
        <div id="groupSystem" class="mt-4">
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Sistem</p>
            <ul class="space-y-1">
                <li>
                    <a href="javascript:void(0)" onclick="navigate('admin/user')" 
                       class="<?= $uri == 'admin/user' ? 'bg-purple-600/10 text-purple-400 border-purple-500' : 'text-slate-400 border-transparent hover:text-white hover:bg-slate-800/50' ?> group flex items-center px-4 py-3 text-sm font-medium rounded-xl border-l-[3px] transition-all duration-200">
                        <i class="fas fa-users-cog w-5 text-center <?= $uri == 'admin/user' ? 'text-purple-400' : 'text-slate-500 group-hover:text-white' ?> transition-colors"></i>
                        <span class="ml-3">Manajemen User</span>
                    </a>
                </li>
            </ul>
        </div>
        <?php endif; ?>

        <div id="groupOps" class="mt-4">
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Operasional</p>
            
            <button onclick="toggleMenu('menuOps', 'arrowOps')" 
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-slate-400 rounded-xl hover:bg-slate-800/50 hover:text-white transition-all duration-200 group focus:outline-none">
                <div class="flex items-center">
                    <i class="fas fa-layer-group w-5 text-center text-slate-500 group-hover:text-amber-400 transition-colors"></i>
                    <span class="ml-3">Aktivitas Lab</span>
                </div>
                <i id="arrowOps" class="fas fa-chevron-right text-[10px] transition-transform duration-300"></i>
            </button>

            <ul id="menuOps" class="hidden mt-1 space-y-1 pl-11 relative">
                <div class="absolute left-6 top-0 bottom-0 w-px bg-slate-800"></div>

                <?php $active = strpos($uri, 'jadwal') !== false && strpos($uri, 'jadwalupk') === false; ?>
                <li>
                    <a href="javascript:void(0)" onclick="navigate('admin/jadwal')" 
                       class="<?= $active ? 'text-amber-400 font-semibold' : 'text-slate-400 hover:text-white' ?> block py-2 pl-4 text-sm transition-colors relative"
                       data-active="<?= $active ? 'true' : 'false' ?>">
                       <?php if($active): ?><span class="absolute -left-[21px] top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-amber-400 shadow-[0_0_8px_rgba(251,191,36,0.5)]"></span><?php endif; ?>
                       Jadwal Praktikum
                    </a>
                </li>

                <?php $active = strpos($uri, 'jadwalupk') !== false; ?>
                <li>
                    <a href="javascript:void(0)" onclick="navigate('admin/jadwalupk')" 
                        class="<?= $active ? 'text-amber-400 font-semibold' : 'text-slate-400 hover:text-white' ?> block py-2 pl-4 text-sm transition-colors relative"
                        data-active="<?= $active ? 'true' : 'false' ?>">
                        <?php if($active): ?><span class="absolute -left-[21px] top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-amber-400 shadow-[0_0_8px_rgba(251,191,36,0.5)]"></span><?php endif; ?>
                        Jadwal UPK
                    </a>
                </li>

                <?php $active = strpos($uri, 'peraturan') !== false || strpos($uri, 'sanksi') !== false; ?>
                <li>
                    <a href="javascript:void(0)" onclick="navigate('admin/peraturan')" 
                       class="<?= $active ? 'text-amber-400 font-semibold' : 'text-slate-400 hover:text-white' ?> block py-2 pl-4 text-sm transition-colors relative"
                       data-active="<?= $active ? 'true' : 'false' ?>">
                       <?php if($active): ?><span class="absolute -left-[21px] top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-amber-400 shadow-[0_0_8px_rgba(251,191,36,0.5)]"></span><?php endif; ?>
                       Peraturan & Sanksi
                    </a>
                </li>

                <?php $active = strpos($uri, 'sop') !== false; ?>
                <li>
                    <a href="javascript:void(0)" onclick="navigate('admin/sop')" 
                       class="<?= $active ? 'text-amber-400 font-semibold' : 'text-slate-400 hover:text-white' ?> block py-2 pl-4 text-sm transition-colors relative"
                       data-active="<?= $active ? 'true' : 'false' ?>">
                       <?php if($active): ?><span class="absolute -left-[21px] top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-amber-400 shadow-[0_0_8px_rgba(251,191,36,0.5)]"></span><?php endif; ?>
                       SOP Laboratorium
                    </a>
                </li>

                <?php $active = strpos($uri, 'formatpenulisan') !== false; ?>
                <li>
                    <a href="javascript:void(0)" onclick="navigate('admin/formatpenulisan')" 
                       class="<?= $active ? 'text-amber-400 font-semibold' : 'text-slate-400 hover:text-white' ?> block py-2 pl-4 text-sm transition-colors relative"
                       data-active="<?= $active ? 'true' : 'false' ?>">
                       <?php if($active): ?><span class="absolute -left-[21px] top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-amber-400 shadow-[0_0_8px_rgba(251,191,36,0.5)]"></span><?php endif; ?>
                       Format Penulisan
                    </a>
                </li>
            </ul>
        </div>

    </nav>

    <div class="p-4 border-t border-slate-800 bg-slate-900">
        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-800/50 border border-slate-700/50">
            <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-slate-300">
                <i class="fas fa-user"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">Admin Lab</p>
                <p class="text-xs text-slate-400 truncate">Online</p>
            </div>
            <a href="<?= PUBLIC_URL ?>/logout" class="p-2 text-slate-400 hover:text-red-400 transition-colors" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>

</aside>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
</style>

<script>
function toggleMenu(menuId, arrowId) {
    const menu = document.getElementById(menuId);
    const arrow = document.getElementById(arrowId);
    
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        menu.classList.add('block');
        if(arrow) {
            arrow.classList.add('rotate-90');
            arrow.classList.remove('text-slate-600');
            arrow.classList.add('text-white');
        }
    } else {
        menu.classList.add('hidden');
        menu.classList.remove('block');
        if(arrow) {
            arrow.classList.remove('rotate-90');
            arrow.classList.add('text-slate-600');
            arrow.classList.remove('text-white');
        }
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const activeLinks = document.querySelectorAll('a[data-active="true"]');
    
    activeLinks.forEach(link => {
        const parentMenu = link.closest('ul');
        if (parentMenu) {
            parentMenu.classList.remove('hidden');
            parentMenu.classList.add('block');
            
            let arrowId = '';
            if (parentMenu.id === 'menuMaster') arrowId = 'arrowMaster';
            if (parentMenu.id === 'menuOps') arrowId = 'arrowOps';
            
            const arrow = document.getElementById(arrowId);
            if (arrow) {
                arrow.classList.add('rotate-90');
                arrow.classList.add('text-white');
                arrow.classList.remove('text-slate-600');
            }
        }
    });
});
</script>