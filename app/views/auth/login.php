<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SISDA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100">
        
        <div class="bg-blue-600 p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/20 mb-4 backdrop-blur-sm">
                <i class="fas fa-user-shield text-3xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-white tracking-wide">ADMIN SISDA</h2>
            <p class="text-blue-100 text-sm mt-1">Silakan login untuk melanjutkan</p>
        </div>

        <div class="p-8">
            <?php 
            $flashError = $_SESSION['flash']['error'] ?? null;
            if ($flashError): 
                unset($_SESSION['flash']['error']);
            ?>
                <div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-200 rounded-lg bg-red-50" role="alert">
                    <i class="fas fa-circle-exclamation mr-2"></i>
                    <div><?= $flashError; ?></div>
                </div>
            <?php endif; ?>
            
            <?php 
            $flashSuccess = $_SESSION['flash']['success'] ?? null;
            if ($flashSuccess): 
                unset($_SESSION['flash']['success']);
            ?>
                <div class="flex items-center p-4 mb-4 text-sm text-emerald-800 border border-emerald-200 rounded-lg bg-emerald-50" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    <div><?= $flashSuccess; ?></div>
                </div>
            <?php endif; ?>

            <form action="<?= PUBLIC_URL ?>/auth" method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-slate-400"></i>
                        </div>
                        <input type="text" name="username" required autofocus autocomplete="off" 
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            placeholder="Masukkan username"
                            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-slate-400"></i>
                        </div>
                        <input type="password" name="password" required
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            placeholder="Masukkan password">
                    </div>
                </div>
                
                <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-semibold rounded-lg text-sm px-5 py-3 transition-colors duration-200 shadow-lg shadow-blue-500/30">
                    LOGIN <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="<?= rtrim(PUBLIC_URL, '/') . '/home' ?>" class="text-sm text-slate-500 hover:text-blue-600 font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Home
                </a>
            </div>
        </div>
    </div>

</body>
</html>