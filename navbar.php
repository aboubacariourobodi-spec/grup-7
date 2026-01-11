<?php
// Ensure session is started (in case navbar.php is included before session_start())
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Safely get the user's name from session, fallback to "Kullanıcı" if not set
$adiSoyadi = $_SESSION['adiSoyadi'] ?? 'Kullanıcı';

// Safely get first letter for avatar (handles Turkish characters properly)
$ilkHarf = '';
if (!empty($adiSoyadi)) {
    $ilkHarf = strtoupper(mb_substr($adiSoyadi, 0, 1, "UTF-8"));
}
?>

<header class="flex-none px-6 pt-12 pb-4 bg-surface-light dark:bg-surface-dark border-b border-slate-100 dark:border-slate-800 z-20 flex justify-between items-center shadow-sm">
    <div>
        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
            Yönetici Paneli
        </p>

        <h1 class="text-xl font-bold text-slate-900 dark:text-white">
            Hoş geldin, <?= htmlspecialchars($adiSoyadi) ?> 👋
        </h1>
    </div>
    
    <div>
        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Yönetici Paneli</p>
        <h1 class="text-xl font-bold text-slate-900 dark:text-white">ÖPSP Kontrol</h1>
    </div>

    <div class="flex flex-row items-center gap-4">
        <button class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-primary-text font-bold hover:brightness-95 transition-all relative">
            <?= htmlspecialchars($ilkHarf) ?>
            <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-white dark:border-surface-dark"></span>
        </button>
        
        <a href="logout.php" class="text-sm font-medium text-red-600 hover:text-red-700 transition">
            Çıkış Yap
        </a>
    </div>
</header>