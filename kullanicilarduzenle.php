<?php
include "connection.php";

$id = intval($_GET['id']);

if (isset($_POST['update'])) {
    $adiSoyadi = $_POST['adiSoyadi'];
    $e_posta   = $_POST['e_posta'];
    $numara    = $_POST['numara'];

    $stmt = $conn->prepare(
        "UPDATE kullanici SET adiSoyadi=?, e_posta=?, numara=? WHERE id=?"
    );
    $stmt->bind_param("sssi", $adiSoyadi, $e_posta, $numara, $id);
    $stmt->execute();

    header("Location: kullanicilar.php");
    exit;
}

$result = $conn->prepare("SELECT * FROM kullanici WHERE id=?");
$result->bind_param("i", $id);
$result->execute();
$user = $result->get_result()->fetch_assoc();
?>

<?php
session_start();



if (!isset($_SESSION['adiSoyadi'])) {
    header("Location: login.php");
    exit;
}

$adiSoyadi = $_SESSION['adiSoyadi'];
?>



<!DOCTYPE html>
<html class="light" lang="tr"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>ÖPSP - Kullanıcı Düzenle Paneli</title>
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
         <main class="flex-1 px-6 pt-6 pb-8 w-full">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-surface-dark rounded-xl shadow p-6">
            <form method="post" class="flex flex-col gap-5">
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">Adı Soyadı</label>
                    <input name="adiSoyadi" value="<?= $user['adiSoyadi'] ?>" required  class="h-12 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">E-Posta</label>
                    <input name="e_posta" value="<?= $user['e_posta'] ?>" required 
                    class="h-12 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">Numara</label>
                    <input name="numara" value="<?= $user['numara'] ?> " 
                    class="h-12 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4">
                </div>
                <button name="update" class="h-12 bg-primary text-primary-text font-bold rounded-lg hover:brightness-95 transition">Update</button>
            </form>
        </div>
       
    </div>

</body></html>