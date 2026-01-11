<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Safely get user's name (fallback to "Kullanıcı")
$adiSoyadi = $_SESSION['adiSoyadi'] ?? 'Kullanıcı';

// Get first letter for avatar (supports Turkish characters)
$ilkHarf = '';
if (!empty($adiSoyadi)) {
    $ilkHarf = strtoupper(mb_substr($adiSoyadi, 0, 1, 'UTF-8'));
}
?>

<aside class="fixed inset-y-0 left-0 z-30 w-64 bg-surface-light dark:bg-surface-dark border-r border-slate-200 dark:border-slate-800 flex flex-col transform transition-transform duration-300 md:translate-x-0" id="sidebar">
    <!-- Logo / Title -->
    <div class="px-6 py-6 border-b border-slate-200 dark:border-slate-800">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">ÖPSP</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Yönetici Paneli</p>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <a href="adminPage.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary text-primary-text font-medium hover:bg-amber-300 transition">
            <span class="material-symbols-outlined text-2xl">dashboard</span>
            Ana Sayfa
        </a>

        <a href="kullanicilar.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
            <span class="material-symbols-outlined text-2xl">group</span>
            Kullanıcılar
        </a>

        <a href="sunum.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
            <span class="material-symbols-outlined text-2xl">present_to_all</span>
            Sunumlar
        </a>

        <a href="ProfiliDuzenle.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
            <span class="material-symbols-outlined text-2xl">person</span>
            Profil
        </a>

        <a href="Hesabim.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
            <span class="material-symbols-outlined text-2xl">settings</span>
            Ayarlar
        </a>
    </nav>

    <!-- User Info & Logout -->
    <div class="px-6 py-5 border-t border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-4">
            <!-- Avatar -->
            <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center text-primary-text font-bold text-xl shadow-md">
                <?= htmlspecialchars($ilkHarf) ?>
            </div>

            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">
                    <?= htmlspecialchars($adiSoyadi) ?>
                </p>
                <a href="logout.php" class="text-xs text-red-600 dark:text-red-400 hover:underline">
                    Çıkış Yap
                </a>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar Overlay / Toggle (only on mobile) -->
<div class="md:hidden fixed inset-0 bg-black/50 z-20 transition-opacity duration-300" id="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- Mobile Menu Button (top bar) -->
<button class="md:hidden fixed top-4 left-4 z-40 w-12 h-12 bg-white dark:bg-slate-800 rounded-full shadow-lg flex items-center justify-center" onclick="toggleSidebar()">
    <span class="material-symbols-outlined text-2xl">menu</span>
</button>

<script>
// Mobile sidebar toggle
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('opacity-0');
    overlay.classList.toggle('pointer-events-none');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(e) {
    if (window.innerWidth < 768) { // md breakpoint
        const sidebar = document.getElementById('sidebar');
        if (!sidebar.contains(e.target) && !e.target.closest('button[onclick="toggleSidebar()"]')) {
            sidebar.classList.add('-translate-x-full');
            document.getElementById('sidebar-overlay').classList.add('opacity-0', 'pointer-events-none');
        }
    }
});
</script>