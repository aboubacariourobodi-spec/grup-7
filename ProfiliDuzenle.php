<?php
session_start();
include "connection.php";

// Login check
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION['kullanici_id'];
$error = '';
$success = '';

// Fetch current user data safely by ID (not by name!)
$stmt = $conn->prepare("SELECT adiSoyadi, e_posta FROM kullanici WHERE id = ?");
$stmt->bind_param("i", $kullanici_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$user = $result->fetch_assoc()) {
    $error = "Kullanıcı bulunamadı. Lütfen tekrar giriş yapın.";
    $stmt->close();
} else {
    $stmt->close();
}

// Handle form submission
if (isset($_POST['update'])) {
    $newAdiSoyadi = trim($_POST['adiSoyadi'] ?? '');
    $newEmail     = trim($_POST['e_posta'] ?? '');
    $newSifre     = $_POST['sifre'] ?? '';
    $sifreTekrar  = $_POST['sifre_tekrar'] ?? '';

    // Validation
    if (empty($newAdiSoyadi)) {
        $error = "Ad Soyad zorunludur.";
    } elseif (empty($newEmail)) {
        $error = "E-posta zorunludur.";
    } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $error = "Geçerli bir e-posta adresi girin.";
    } elseif ($newSifre !== '' || $sifreTekrar !== '') {
        // Only validate password if user wants to change it
        if ($newSifre !== $sifreTekrar) {
            $error = "Şifreler eşleşmiyor.";
        } elseif (strlen($newSifre) < 6) {
            $error = "Şifre en az 6 karakter olmalıdır.";
        }
    }

    // Check if email is already used by another user
    if (empty($error)) {
        $stmt = $conn->prepare("SELECT id FROM kullanici WHERE e_posta = ? AND id != ?");
        $stmt->bind_param("si", $newEmail, $kullanici_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Bu e-posta adresi başka bir hesapta kullanılıyor.";
        }
        $stmt->close();
    }

    // If no errors → update database
    if (empty($error)) {
        if (!empty($newSifre)) {
            // User wants to change password → hash it
            $hashedPassword = password_hash($newSifre, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE kullanici SET adiSoyadi = ?, e_posta = ?, sifre = ? WHERE id = ?");
            $stmt->bind_param("sssi", $newAdiSoyadi, $newEmail, $hashedPassword, $kullanici_id);
        } else {
            // Only update name and email (keep old password)
            $stmt = $conn->prepare("UPDATE kullanici SET adiSoyadi = ?, e_posta = ? WHERE id = ?");
            $stmt->bind_param("ssi", $newAdiSoyadi, $newEmail, $kullanici_id);
        }

        if ($stmt->execute()) {
            $success = "Profiliniz başarıyla güncellendi!";

            // Update session
            $_SESSION['adiSoyadi'] = $newAdiSoyadi;

            // Refresh user data for form
            $user['adiSoyadi'] = $newAdiSoyadi;
            $user['e_posta'] = $newEmail;
        } else {
            $error = "Güncelleme sırasında bir hata oluştu.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html class="light" lang="tr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>ÖPSP - Profili Düzenle</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;700&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
                        "display": ["Lexend", "Noto Sans", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"],
                    },
                },
            },
        }
    </script>
    <style>
        body { min-height: max(884px, 100dvh); }
    </style>
</head>
<body class="font-display antialiased text-slate-900 dark:text-white bg-background-light dark:bg-background-dark flex flex-col h-screen overflow-hidden">
    <?php include "sidebar.php" ?>
    <div class="flex-1 flex flex-col md:ml-64">
        <?php include "navbar.php" ?>

        <main class="flex-1 px-6 pt-6 pb-8 w-full">
            <div class="max-w-7xl mx-auto">
                <div class="max-w-2xl mx-auto">

                    <h1 class="text-3xl font-bold text-center mb-8">Profilimi Düzenle</h1>

                    <!-- Success Message -->
                    <?php if ($success): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl mb-6 text-center font-medium">
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Error Message -->
                    <?php if ($error): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl mb-6 text-center font-medium">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Profile Edit Form -->
                    <div class="bg-white dark:bg-surface-dark rounded-xl shadow p-8">
                        <form method="post" class="flex flex-col gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-medium">Adı Soyadı</label>
                                <input 
                                    type="text" 
                                    name="adiSoyadi" 
                                    value="<?= htmlspecialchars($user['adiSoyadi'] ?? '') ?>" 
                                    required 
                                    class="h-12 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 focus:border-amber-400 transition"
                                />
                            </div>

                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-medium">E-Posta Adresi</label>
                                <input 
                                    type="email" 
                                    name="e_posta" 
                                    value="<?= htmlspecialchars($user['e_posta'] ?? '') ?>" 
                                    required 
                                    class="h-12 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 focus:border-amber-400 transition"
                                />
                            </div>

                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-medium text-amber-600">Yeni Şifre (Değiştirmek istemiyorsanız boş bırakın)</label>
                                <input 
                                    type="password" 
                                    name="sifre" 
                                    placeholder="••••••••" 
                                    class="h-12 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 focus:border-amber-400 transition"
                                />
                            </div>

                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-medium">Yeni Şifre Tekrar</label>
                                <input 
                                    type="password" 
                                    name="sifre_tekrar" 
                                    placeholder="••••••••" 
                                    class="h-12 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 focus:border-amber-400 transition"
                                />
                            </div>

                            <div class="flex gap-4 mt-6">
                                <button 
                                    type="submit" 
                                    name="update" 
                                    class="flex-1 h-12 bg-primary text-primary-text font-bold rounded-lg hover:brightness-95 transition shadow-md"
                                >
                                    Kaydet
                                </button>
                                <a href="adminPage.php" 
                                   class="flex-1 h-12 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-lg flex items-center justify-center hover:bg-slate-300 dark:hover:bg-slate-600 transition">
                                    İptal
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="mt-8 text-center">
                        <a href="kullanicilar.php" class="text-amber-600 hover:text-amber-700 font-medium">
                            ← Bilgilerime Dön
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>