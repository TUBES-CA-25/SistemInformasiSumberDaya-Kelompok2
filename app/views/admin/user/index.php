<?php
// Pastikan model dimuat jika belum ada (karena routing di index.php langsung load view)
if (!isset($users)) {
    require_once APP_PATH . '/models/UserModel.php';
    $userModel = new UserModel();
    $users = $userModel->getAllUsers();
}
?>
<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-users-cog text-blue-600"></i> 
        Manajemen User Admin
    </h1>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <button onclick="openFormModal()" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5 whitespace-nowrap">
            <i class="fas fa-plus"></i> Tambah User Admin
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Daftar Akun Pengelola</h3>
            <p class="text-xs text-gray-500">Manajemen akses sistem informasi</p>
        </div>
        <span id="totalData" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">Total: <?= count($users) ?> User</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider border-b border-gray-200">
                    <th class="px-6 py-4 font-bold text-center w-16">No</th>
                    <th class="px-6 py-4 font-bold">Informasi User</th>
                    <th class="px-6 py-4 font-bold text-center">Hak Akses</th>
                    <th class="px-6 py-4 font-bold">Aktivitas</th>
                    <th class="px-6 py-4 font-bold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-gray-100 text-gray-700 text-sm">
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <i class="fas fa-user-slash text-4xl mb-3 opacity-20"></i>
                            <p>Belum ada data user administrator.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($users as $index => $u): ?>
                <tr class="hover:bg-blue-50/50 transition-colors duration-150">
                    <td class="px-6 py-4 text-center font-medium text-gray-400"><?= $index + 1 ?></td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br <?= $u['role'] === 'super_admin' ? 'from-purple-500 to-indigo-600' : 'from-blue-500 to-cyan-600' ?> flex items-center justify-center text-white shadow-sm">
                                <i class="fas <?= $u['role'] === 'super_admin' ? 'fa-user-shield' : 'fa-user' ?>"></i>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900"><?= htmlspecialchars($u['username']) ?></div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-tight">ID: #USR-<?= str_pad($u['id'], 3, '0', STR_PAD_LEFT) ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if ($u['role'] === 'super_admin'): ?>
                            <span class="inline-flex items-center gap-1.5 bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold border border-purple-200 shadow-sm">
                                <i class="fas fa-crown text-[10px]"></i> SUPER ADMIN
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold border border-blue-200 shadow-sm">
                                <i class="fas fa-user-tag text-[10px]"></i> ADMIN
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-gray-700 font-medium whitespace-nowrap">
                                <i class="far fa-clock text-xs mr-1 text-gray-400"></i>
                                <?= $u['last_login'] ? date('d M Y, H:i', strtotime($u['last_login'])) : '<span class="text-xs italic text-gray-400">Belum login</span>' ?>
                            </span>
                            <span class="text-[10px] text-gray-400">Dibuat: <?= date('d/m/Y', strtotime($u['created_at'])) ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center items-center gap-2">
                            <button onclick="openFormModal(<?= $u['id'] ?>)" class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center shadow-sm border border-amber-100 hover:border-amber-500" title="Edit">
                                <i class="fas fa-pen text-sm"></i>
                            </button>
                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                            <button onclick="deleteUser(<?= $u['id'] ?>)" class="w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center shadow-sm border border-red-100 hover:border-red-500" title="Hapus">
                                <i class="fas fa-trash-alt text-sm"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form User -->
<div id="formModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full sm:max-w-md border border-gray-100">
            
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-user-plus text-blue-600"></i> <span>Tambah User Admin</span>
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="userForm" class="p-6 space-y-4">
                <input type="hidden" id="userId" name="id">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                    <input type="text" id="username" name="username" required 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>

                <div id="passContainer">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password <span class="text-red-500" id="passReqIcon">*</span></label>
                    <input type="password" id="password" name="password" 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    <p id="passHint" class="text-[10px] text-gray-400 mt-1 hidden italic">* Kosongkan password jika tidak ingin menggantinya</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Hak Akses (Role) <span class="text-red-500">*</span></label>
                    <select id="role" name="role" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                        <option value="admin">Admin Biasa</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                    <p class="text-[10px] text-gray-500 mt-1">Super Admin memiliki akses ke manajemen user.</p>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 font-medium transition-colors">Batal</button>
                    <button type="submit" id="btnSave" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors flex items-center gap-2 shadow-sm">
                        <i class="fas fa-save"></i> <span>Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentUsers = <?= json_encode($users) ?>;

function openFormModal(id = null) {
    const modal = document.getElementById('formModal');
    const form = document.getElementById('userForm');
    const modalTitle = document.getElementById('modalTitle');
    const passHint = document.getElementById('passHint');
    const passReqIcon = document.getElementById('passReqIcon');
    const passInput = document.getElementById('password');
    
    form.reset();
    document.getElementById('userId').value = '';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    if (id) {
        const user = currentUsers.find(u => u.id == id);
        if (user) {
            modalTitle.innerHTML = '<i class="fas fa-user-edit text-amber-500"></i> <span>Edit User Admin</span>';
            document.getElementById('userId').value = user.id;
            document.getElementById('username').value = user.username;
            document.getElementById('role').value = user.role;
            
            passHint.classList.remove('hidden');
            passReqIcon.classList.add('hidden');
            passInput.required = false;
        }
    } else {
        modalTitle.innerHTML = '<i class="fas fa-user-plus text-blue-600"></i> <span>Tambah User Admin</span>';
        passHint.classList.add('hidden');
        passReqIcon.classList.remove('hidden');
        passInput.required = true;
    }
}

function closeModal() {
    document.getElementById('formModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('userId').value;
    const url = id ? `${API_URL}/user/${id}` : `${API_URL}/user`;
    const method = id ? 'PUT' : 'POST';
    
    const formData = new FormData(this);
    const dataObj = Object.fromEntries(formData.entries());

    showLoading('Menyimpan data user...');

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dataObj)
    })
    .then(r => r.json())
    .then(res => {
        hideLoading();
        if (res.status === 'success') {
            showSuccess(res.message);
            closeModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showError(res.message);
        }
    })
    .catch(err => {
        hideLoading();
        showError('Kesalahan sistem saat menyimpan user');
    });
});

function deleteUser(id) {
    confirmDelete(() => {
        showLoading('Menghapus user...');
        fetch(`${API_URL}/user/${id}`, { method: 'DELETE' })
        .then(r => r.json())
        .then(res => {
            hideLoading();
            if (res.status === 'success') {
                showSuccess(res.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showError(res.message);
            }
        });
    }, 'Apakah Anda yakin ingin menghapus user ini? Akun ini tidak akan bisa login lagi.');
}
</script>
