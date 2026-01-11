<?php
session_start();
include 'connection.php';


if (!isset($_SESSION['adiSoyadi'])) {
    header("Location: login.php");
    exit;
}

$adiSoyadi = $_SESSION['adiSoyadi'];
$e_posta = $_SESSION['e_posta'];
?>



<!DOCTYPE html>
<html class="light" lang="tr"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>ÖPSP - Hesabım Paneli</title>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;700&amp;family=Noto+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#fef3c7", // amber-100
                        "primary-dark": "#d97706", // amber-600 for text contrast
                        "primary-text": "#78350f", // amber-900
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1e293b",
                    },
                    fontFamily: {
                        "display": ["Lexend", "Noto Sans", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"],
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
<style>
        body {
          min-height: 100vh;padding-bottom: env(safe-area-inset-bottom);
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
  </head>
<body class="font-display antialiased text-slate-900 dark:text-white bg-background-light dark:bg-background-dark flex flex-col h-screen overflow-hidden">
<!-- Sidebar -->
    <?php include "sidebar.php" ?>
        <div class="flex-1 flex flex-col md:ml-64">
            <?php include "navbar.php" ?>
            <div class="relative flex h-full min-h-screen w-full flex-col pb-24">
                <!-- Top App Bar -->
                <div class="sticky top-0 z-10 flex items-center bg-background-light/95 dark:bg-background-dark/95 backdrop-blur-md p-4 pb-2 justify-center border-b border-transparent dark:border-white/5">
                    <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">Hesabım</h2>
                </div>
                <!-- Profile Header Section -->
                <div class="flex flex-col items-center pt-6 pb-8 px-4">
                    <div class="relative group cursor-pointer">
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-28 w-28 ring-4 ring-white dark:ring-white/10 shadow-lg object-cover" data-alt="Portrait of a smiling male student with a blurred university campus background" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBPQdSANvdi7I-m8NL37WHhbIOL-IBvhRGYfemR201JAsGqFmt2X3yxM9fDyboDz6MQC71EKhS4fxAWTIkF2qZuyaLI3e7CpE2nzv6khZmhF0NWFak9ffWzacEbSViBaepHv_HRlRsDX_gV_2Otk1ddam0JMOypTv5pTsm1OOHXY6AHE9fuvHPr1jsSjAAqjIJhkY6wGzlkGigDdyEUkNBYvBZa6DdNcauFGghqUgsyfyYXPjkZ1Jre2D0BJpuEHOSajto10-HuzXJ8");'>
                        </div>
                        <div class="absolute bottom-0 right-0 bg-primary text-white p-1.5 rounded-full shadow-md">
                            <a href="ProfiliDuzenle.php">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                            
                        </div>
                    </div>
                    <div class="flex flex-col items-center justify-center mt-4 gap-1">
                        <h1 class="text-2xl font-bold leading-tight tracking-[-0.015em] text-center text-[#181611] dark:text-white"><?= htmlspecialchars($adiSoyadi) ?></h1>
                        <p class="text-[#897c61] dark:text-[#b0a695] text-sm font-normal text-center"><?= htmlspecialchars($e_posta) ?></p>
                    </div>
                </div>
                <!-- Action Menu List -->
                <div class="px-4 w-full max-w-md mx-auto flex flex-col gap-4">
                <!-- Menu Group -->
                    <div class="flex flex-col bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-white/5">
                <!-- Edit Profile -->
                        <button class="flex items-center gap-4 px-4 py-4 w-full hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group text-left">
                            <div class="flex items-center justify-center rounded-lg bg-[#fff8e1] text-primary dark:bg-primary/20 dark:text-primary shrink-0 size-10 group-hover:scale-105 transition-transform">
                                <span class="material-symbols-outlined text-[24px]">edit </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="ProfiliDuzenle.php">
                                    <p class="text-base font-medium leading-normal truncate text-[#181611] dark:text-white">Profili Düzenle</p>
                                </a>
                            </div>
                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-500">chevron_right</span>
                        </button>
                    <div class="h-px w-full bg-gray-100 dark:bg-white/5 mx-4"></div>
                        <!-- My Presentations -->
                        <button class="flex items-center gap-4 px-4 py-4 w-full hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group text-left">
                            <div class="flex items-center justify-center rounded-lg bg-[#fff8e1] text-primary dark:bg-primary/20 dark:text-primary shrink-0 size-10 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-outlined text-[24px]">present_to_all</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="sunumlar.php">
                                    <p class="text-base font-medium leading-normal truncate text-[#181611] dark:text-white">Yüklediğim Sunumlar</p>
                                </a>
                            </div>
                            <div class="flex items-center gap-2">
                            <span class="bg-primary/10 text-primary text-xs font-bold px-2 py-0.5 rounded-full dark:bg-primary/20">3</span>
                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-500">chevron_right</span>
                            </div>
                        </button>
                    <div class="h-px w-full bg-gray-100 dark:bg-white/5 mx-4"></div>
                        <!-- Settings -->
                        <button class="flex items-center gap-4 px-4 py-4 w-full hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group text-left">
                            <div class="flex items-center justify-center rounded-lg bg-[#fff8e1] text-primary dark:bg-primary/20 dark:text-primary shrink-0 size-10 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-outlined text-[24px]">settings</span>
                            </div>
                            <div class="flex-1 min-w-0">
                            <p class="text-base font-medium leading-normal truncate text-[#181611] dark:text-white">Ayarlar</p>
                            </div>
                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-500">chevron_right</span>
                        </button>
                    </div>
                    <!-- Additional Links / Help (Optional filler for modern look) -->
                        <div class="flex flex-col bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-white/5 mt-2">
                            <button class="flex items-center gap-4 px-4 py-4 w-full hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group text-left">
                            <div class="flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-gray-300 shrink-0 size-10">
                            <span class="material-symbols-outlined text-[24px]">help</span>
                            </div>
                            <div class="flex-1 min-w-0">
                            <p class="text-base font-medium leading-normal truncate text-[#181611] dark:text-white">Yardım ve Destek</p>
                            </div>
                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-500">chevron_right</span>
                            </button>
                        </div>
                    <!-- Logout Button -->
                        <div class="mt-4 flex justify-center">
                            <button class="text-red-600 dark:text-red-400 font-medium text-base py-3 px-6 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors w-full border border-transparent hover:border-red-100 dark:hover:border-red-900/30">
                                    Çıkış Yap
                            </button>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-xs text-gray-400 dark:text-gray-600">Versiyon 1.0.4</p>
                        </div>
            </div>
            <!-- Bottom Navigation Bar -->
            <!-- iOS Home Indicator Spacing -->
                <div class="h-5 w-full"></div>
            </div>
            </div>
        </div>

</body></html>