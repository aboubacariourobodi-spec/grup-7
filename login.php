<?php
session_start();
include 'connection.php';
  // user id from database

$error = '';
$success = '';

if (isset($_POST['submit'])) {
    $_SESSION['kullanici_id'] = $user['id'];
    $e_posta = $_POST['e_posta'];
    $sifre = $_POST['sifre'];

    $stmt = $conn->prepare("SELECT * FROM kullanici WHERE e_posta = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $e_posta);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($sifre, $row['sifre'])) {

        // 🔥 STORE USER DATA IN SESSION
        $_SESSION['user_id']   = $row['id'];
        $_SESSION['adiSoyadi'] = $row['adiSoyadi'];
        $_SESSION['e_posta']   = $row['e_posta'];
        $_SESSION['kullanici_id'] = $row['id']; 

        header("Location: adminPage.php");
        exit; // IMPORTANT
    } else {
        
        echo '<script>
            alert("Email or password is incorrect");
            window.location.href = "login.php";
        </script>';
        exit;
    }
}
?>


<!DOCTYPE html>
<html class="light" lang="tr"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>ÖPSP - Kullanıcı Girişi</title>
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
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
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
          min-height: max(884px, 100dvh);
        }
    </style>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
  </head>
<body class="font-display antialiased text-slate-900 dark:text-white bg-background-light dark:bg-background-dark">
<div class="relative flex h-full min-h-screen w-full flex-col overflow-x-hidden">
    <div class="flex items-center justify-between p-4">
        <button class="flex items-center justify-center p-2 rounded-full hover:bg-black/5 dark:hover:bg-white/10 transition-colors">
            <span class="material-symbols-outlined text-slate-900 dark:text-white text-2xl"><a href="index.php">arrow_back</a></span>
        </button>
        <div class="flex-1"></div>
        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-amber-700 font-bold">Ö</div>
    </div>
    <main class="flex-1 flex flex-col px-6 pt-2 pb-8 max-w-md mx-auto w-full">
        <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white mb-2">Giriş Yap</h1>
        <p class="text-slate-500 dark:text-slate-400 text-base font-normal leading-relaxed">
                        Projelerinizi sergileyin ve başkalarına ilham verin.
                    </p>
        </div>
        <form class="flex flex-col gap-5" method="post" action="login.php">
            <div class="flex flex-col gap-2">
                <label class="text-sm font-medium text-slate-900 dark:text-white" for="email">E-posta Adresi</label>
                <input class="form-input flex w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 h-14 px-4 focus:border-amber-400 focus:ring-amber-200 transition-all shadow-sm" id="email" placeholder="ogrenci@universite.edu.tr" type="email" 
                name="e_posta"/>
            </div>
            <div class="flex flex-col gap-2">
                <label class="text-sm font-medium text-slate-900 dark:text-white" for="password">Şifre</label>
                <div class="relative flex items-center">
                    <input class="form-input flex w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 h-14 px-4 pr-12 focus:border-amber-400 focus:ring-amber-200 transition-all shadow-sm" id="password" placeholder="••••••••" type="password" 
                    name="sifre"/>
                    <button class="absolute right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors" type="button">
                        <span class="material-symbols-outlined text-[20px]">visibility_off</span>
                    </button>
                </div>
            </div>
            <div class="flex justify-end">
                <a class="text-sm font-medium text-amber-600 hover:text-amber-700 transition-colors" href="#">
                                    Şifremi Unuttum?
                </a>
            </div>
            <button class="mt-2 w-full bg-primary hover:bg-amber-200 active:bg-amber-300 text-amber-900 font-bold h-14 rounded-xl flex items-center justify-center transition-all shadow-md hover:shadow-lg focus:ring-4 focus:ring-amber-200/50" type="submit" 
            name="submit">
                            Giriş Yap
            </button>
        </form>
        <div class="relative my-8">
            <div aria-hidden="true" class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
            </div>
            <div class="relative flex justify-center">
                <span class="bg-background-light dark:bg-background-dark px-2 text-sm text-slate-500">veya</span>
            </div>
        </div>
        <div class="flex items-center justify-center gap-1">
            <span class="text-slate-600 dark:text-slate-400">Hesabınız yok mu?</span>
            <a class="font-bold text-amber-600 hover:text-amber-700 transition-colors" href="register.php">
                            Hesap Oluştur
            </a>
        </div>
    </main>
    <div class="h-5 w-full"></div>
</div>

</body></html>