<?php
session_start();
include "connection.php";

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION['kullanici_id'];
$adiSoyadi = $_SESSION['adiSoyadi'] ?? 'Kullanıcı';

// User's own presentation count
$user_presentations = 0;
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM sunumlar WHERE kullanici_id = ?");
$stmt->bind_param("i", $kullanici_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $user_presentations = $row['count'];
}
$stmt->close();

// User's own records in ogrenci_detaylar
$user_count = 0;
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM ogrenci_detaylar WHERE kullanici_id = ?");
$stmt->bind_param("i", $kullanici_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $user_count = $row['count'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html class="light" lang="tr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>ÖPSP - Yönetici Paneli</title>

    <!-- Fixed Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700;FILL@0..1" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#fef3c7",
                        "primary-dark": "#d97706",
                        "primary-text": "#78350f",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1e293b",
                    },
                    fontFamily: {
                        display: ["Lexend", "Noto Sans", "sans-serif"],
                        body: ["Noto Sans", "sans-serif"],
                    },
                },
            },
        }
    </script>
    <style>
        body { min-height: 100dvh; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-display antialiased text-slate-900 dark:text-white bg-background-light dark:bg-background-dark flex flex-col h-screen overflow-hidden">
    <?php include "sidebar.php" ?>
    <div class="flex-1 flex flex-col md:ml-64">
        <?php include "navbar.php" ?>

        <main class="flex-1 overflow-y-auto hide-scrollbar pb-20 px-4 pt-6 md:px-6">
            <div class="max-w-7xl mx-auto">

                <!-- Stats Cards - Responsive Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                    <div class="bg-surface-light dark:bg-surface-dark p-5 md:p-6 rounded-2xl shadow-md border border-slate-100 dark:border-slate-700 flex items-center gap-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                            <span class="material-symbols-outlined text-3xl md:text-4xl text-blue-600 dark:text-blue-400">group</span>
                        </div>
                        <div>
                            <span class="block text-3xl md:text-4xl font-bold"><?= $user_count ?></span>
                            <span class="text-sm md:text-base text-slate-600 dark:text-slate-400 font-medium">Kayıtlı Bilgim</span>
                        </div>
                    </div>

                    <div class="bg-surface-light dark:bg-surface-dark p-5 md:p-6 rounded-2xl shadow-md border border-slate-100 dark:border-slate-700 flex items-center gap-4">
                        <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-xl">
                            <span class="material-symbols-outlined text-3xl md:text-4xl text-amber-600 dark:text-amber-400">slideshow</span>
                        </div>
                        <div>
                            <span class="block text-3xl md:text-4xl font-bold"><?= $user_presentations ?></span>
                            <span class="text-sm md:text-base text-slate-600 dark:text-slate-400 font-medium">Yüklediğim Sunum</span>
                        </div>
                    </div>
                </div>

                <!-- User Management -->
                <section class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg md:text-xl font-bold">Kişisel İşlemler</h2>
                        <a href="kullanicilar.php" class="text-sm font-medium text-amber-600 dark:text-amber-400 hover:underline">Detaylar →</a>
                    </div>
                    <div class="bg-surface-light dark:bg-surface-dark rounded-2xl shadow-md border border-slate-100 dark:border-slate-700 overflow-hidden">
                        <a href="ProfiliDuzenle.php" class="w-full flex items-center justify-between p-4 md:p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/30 dark:to-purple-800/30 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-2xl">edit</span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-sm md:text-base">Profili Düzenle</h3>
                                    <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400">Ad, e-posta ve şifre güncelle</p>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-slate-400">chevron_right</span>
                        </a>
                    </div>
                </section>

                <!-- Presentation Management -->
                <section class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg md:text-xl font-bold">Sunumlarım</h2>
                        <a href="sunum.php" class="text-sm font-medium text-amber-600 dark:text-amber-400 hover:underline">Tümünü Gör →</a>
                    </div>
                    <div class="space-y-4">
                        <a href="sunum.php" class="block bg-surface-light dark:bg-surface-dark p-5 rounded-2xl shadow-md border border-slate-100 dark:border-slate-700 hover:shadow-lg transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-xl">
                                        <span class="material-symbols-outlined text-3xl text-amber-600 dark:text-amber-400">cloud_upload</span>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-sm md:text-base">Yeni Sunum Yükle</h3>
                                        <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">PDF veya PPTX yükleyin</p>
                                    </div>
                                </div>
                                <span class="text-2xl font-bold text-amber-600 dark:text-amber-400"><?= $user_presentations ?></span>
                            </div>
                        </a>
                    </div>
                </section>

                <!-- System Status -->
                <section class="mb-20">
                    <h2 class="text-lg md:text-xl font-bold mb-4">Durum</h2>
                    <div class="bg-emerald-50 dark:bg-emerald-900/30 p-5 md:p-6 rounded-2xl border border-emerald-200 dark:border-emerald-800 flex items-center gap-4">
                        <span class="material-symbols-outlined text-4xl md:text-5xl text-emerald-600 dark:text-emerald-400">check_circle</span>
                        <div>
                            <h3 class="font-bold text-emerald-800 dark:text-emerald-300 text-base md:text-lg">Hesabınız Aktif</h3>
                            <p class="text-sm text-emerald-700 dark:text-emerald-400 mt-1">Tüm özelliklere erişiminiz var.</p>
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <!-- Mobile Bottom Navigation -->
        <nav class="flex-none md:hidden bg-surface-light dark:bg-surface-dark border-t border-slate-200 dark:border-slate-800 px-4 py-3 flex justify-around items-center z-30 shadow-lg">
            <a href="adminPage.php" class="flex flex-col items-center gap-1 py-2 px-3 rounded-xl bg-primary/20">
                <span class="material-symbols-outlined text-amber-600 text-2xl">dashboard</span>
                <span class="text-xs font-bold text-amber-600">Ana Sayfa</span>
            </a>
            <a href="kullanicilar.php" class="flex flex-col items-center gap-1 py-2 px-3">
                <span class="material-symbols-outlined text-slate-500 text-2xl">person</span>
                <span class="text-xs font-medium text-slate-500">Bilgilerim</span>
            </a>
            <a href="sunum.php" class="flex flex-col items-center gap-1 py-2 px-3">
                <span class="material-symbols-outlined text-slate-500 text-2xl">present_to_all</span>
                <span class="text-xs font-medium text-slate-500">Sunumlar</span>
            </a>
            <a href="ProfiliDuzenle.php" class="flex flex-col items-center gap-1 py-2 px-3">
                <span class="material-symbols-outlined text-slate-500 text-2xl">settings</span>
                <span class="text-xs font-medium text-slate-500">Ayarlar</span>
            </a>
        </nav>

        <!-- Desktop Bottom Navigation (Hidden on Mobile) -->
        <nav class="hidden md:flex flex-none bg-surface-light dark:bg-surface-dark border-t border-slate-200 dark:border-slate-800 px-6 py-4 justify-around z-20">
            <a href="adminPage.php" class="flex flex-col items-center gap-2 group">
                <span class="material-symbols-outlined text-amber-600 text-3xl">dashboard</span>
                <span class="text-sm font-bold text-amber-600">Ana Sayfa</span>
            </a>
            <a href="kullanicilar.php" class="flex flex-col items-center gap-2 group">
                <span class="material-symbols-outlined text-slate-500 group-hover:text-amber-600 text-3xl transition">person</span>
                <span class="text-sm font-medium text-slate-500 group-hover:text-amber-600 transition">Bilgilerim</span>
            </a>
            <a href="sunum.php" class="flex flex-col items-center gap-2 group">
                <span class="material-symbols-outlined text-slate-500 group-hover:text-amber-600 text-3xl transition">present_to_all</span>
                <span class="text-sm font-medium text-slate-500 group-hover:text-amber-600 transition">Sunumlar</span>
            </a>
            <a href="ProfiliDuzenle.php" class="flex flex-col items-center gap-2 group">
                <span class="material-symbols-outlined text-slate-500 group-hover:text-amber-600 text-3xl transition">settings</span>
                <span class="text-sm font-medium text-slate-500 group-hover:text-amber-600 transition">Ayarlar</span>
            </a>
        </nav>
    </div>
</body>
</html>