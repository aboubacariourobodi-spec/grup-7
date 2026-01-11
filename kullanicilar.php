<?php
session_start();
include "connection.php";

// If not logged in → go to login
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION['kullanici_id'];
$error = '';
$success = '';

// Flash messages (for redirect after actions)
if (isset($_SESSION['flash_success'])) {
    $success = $_SESSION['flash_success'];
    unset($_SESSION['flash_success']);
}
if (isset($_SESSION['flash_error'])) {
    $error = $_SESSION['flash_error'];
    unset($_SESSION['flash_error']);
}

// ADD NEW RECORD
if (isset($_POST['submit'])) {
    $adiSoyadi = trim($_POST['adiSoyadi'] ?? '');
    $e_posta   = trim($_POST['e_posta'] ?? '');
    $numara    = trim($_POST['numara'] ?? '');

    if (empty($adiSoyadi) || empty($e_posta)) {
        $_SESSION['flash_error'] = "Ad Soyad ve E-posta zorunludur.";
    } else {
        $stmt = $conn->prepare("INSERT INTO ogrenci_detaylar (kullanici_id, adiSoyadi, e_posta, numara) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $kullanici_id, $adiSoyadi, $e_posta, $numara);

        if ($stmt->execute()) {
            $_SESSION['flash_success'] = "Bilgi başarıyla eklendi!";
        } else {
            if ($stmt->errno == 1062) {
                $_SESSION['flash_error'] = "Bu e-posta adresi zaten kullanılmış.";
            } else {
                $_SESSION['flash_error'] = "Bir hata oluştu.";
            }
        }
        $stmt->close();
    }
    header("Location: kullanicilar.php");
    exit;
}

// DELETE OWN RECORD
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);

    $stmt = $conn->prepare("DELETE FROM ogrenci_detaylar WHERE id = ? AND kullanici_id = ?");
    $stmt->bind_param("ii", $delete_id, $kullanici_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $_SESSION['flash_success'] = "Kayıt silindi.";
    }
    $stmt->close();

    header("Location: kullanicilar.php");
    exit;
}

// SEND LINK (Admin-only action)
if (isset($_GET['send']) && is_numeric($_GET['send'])) {
    $record_id = intval($_GET['send']);

    // Get the record and check if it belongs to current user (security)
    $stmt = $conn->prepare("SELECT adiSoyadi, e_posta FROM ogrenci_detaylar WHERE id = ? AND kullanici_id = ?");
    $stmt->bind_param("ii", $record_id, $kullanici_id);
    $stmt->execute();
    $result_check = $stmt->get_result();

    if ($result_check->num_rows === 1) {
        $row = $result_check->fetch_assoc();
        $adiSoyadi = $row['adiSoyadi'];
        $email     = $row['e_posta'];

        // Generate a unique token (you can improve this with expiry later)
        $token = bin2hex(random_bytes(32));
        $link  = "https://yourdomain.com/confirm.php?token=" . urlencode($token) . "&email=" . urlencode($email);

        // Save token to database (create a new table or add columns if needed)
        // For now, we'll just simulate sending. You need a table like `email_links`
        // Example SQL: CREATE TABLE email_links (id INT AUTO_INCREMENT PRIMARY KEY, record_id INT, token VARCHAR(255), created_at DATETIME);

        // --- EMAIL SENDING ---
        $subject = "ÖPSP - Sunum Davet Linki";
        $message = "
        <html>
        <body style='font-family: Arial, sans-serif;'>
            <h2>Merhaba $adiSoyadi,</h2>
            <p>Sunumunuz için özel davet linkiniz:</p>
            <p style='margin: 20px 0;'>
                <a href='$link' style='background:#d97706; color:white; padding:12px 24px; text-decoration:none; border-radius:8px; font-weight:bold;'>Davet Linkine Git</a>
            </p>
            <p>Bu link ile sunumunuza erişim sağlayabilirsiniz.</p>
            <br>
            <p>İyi günler,<br>ÖPSP Ekibi</p>
        </body>
        </html>
        ";

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: no-reply@yourdomain.com\r\n";

        if (mail($email, $subject, $message, $headers)) {
            $_SESSION['flash_success'] = "Davet linki başarıyla gönderildi: $email";
        } else {
            $_SESSION['flash_error'] = "E-posta gönderilemedi. Sunucu ayarlarını kontrol edin.";
        }
    } else {
        $_SESSION['flash_error'] = "Kayıt bulunamadı veya yetkiniz yok.";
    }
    $stmt->close();

    header("Location: kullanicilar.php");
    exit;
}

// FETCH ONLY CURRENT USER'S DATA
$stmt = $conn->prepare("SELECT id, adiSoyadi, e_posta, numara FROM ogrenci_detaylar WHERE kullanici_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $kullanici_id);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html class="light" lang="tr">
<head>
    <!-- ... same head as before ... -->
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>ÖPSP - Kayıtlarım</title>
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
                        display: ["Lexend", "sans-serif"],
                        body: ["Noto Sans", "sans-serif"],
                    },
                },
            },
        }
    </script>
    <!-- ... same fonts and tailwind ... -->
    <style>
        body { min-height: max(884px, 100dvh); }
    </style>
</head>
<body class="font-display antialiased text-slate-900 dark:text-white bg-background-light dark:bg-background-dark flex flex-col h-screen overflow-hidden">
    <?php include "sidebar.php" ?>
    <div class="flex-1 flex flex-col md:ml-64">
        <?php include "navbar.php" ?>

        <main class="flex-1 px-6 pt-6 pb-8 w-full">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Messages -->
                <?php if ($error): ?>
                    <div class="col-span-1 lg:col-span-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 p-4 rounded-xl mb-4">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="col-span-1 lg:col-span-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 p-4 rounded-xl mb-4">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <!-- ADD FORM -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow p-6">
                    <h1 class="text-2xl font-bold mb-2">Yeni Kayıt Ekle</h1>
                    <p class="text-slate-500 dark:text-slate-400 mb-6">Kişi bilgilerini ekleyin</p>

                    <form class="flex flex-col gap-5" action="" method="post">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium">Adı Soyadı *</label>
                            <input type="text" name="adiSoyadi" placeholder="Adınız Soyadınız" required class="h-12 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4" />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium">E-posta *</label>
                            <input type="email" name="e_posta" placeholder="ornek@mail.com" required class="h-12 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4" />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium">Telefon Numarası</label>
                            <input type="text" name="numara" placeholder="0551 123 45 67" class="h-12 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4" />
                        </div>
                        <button type="submit" name="submit" class="h-12 bg-primary text-primary-text font-bold rounded-lg hover:brightness-95 transition shadow">
                            Kaydet
                        </button>
                    </form>
                </div>

                <!-- LIST -->
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow p-6 overflow-x-auto">
                    <h2 class="text-2xl font-bold mb-4">Kayıtlarım</h2>

                    <table class="min-w-full border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                        <thead class="bg-slate-100 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold">#</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Ad Soyad</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">E-posta</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Numara</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $index => $user): ?>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                        <td class="px-4 py-3 text-sm"><?= $index + 1 ?></td>
                                        <td class="px-4 py-3 font-medium"><?= htmlspecialchars($user['adiSoyadi']) ?></td>
                                        <td class="px-4 py-3"><?= htmlspecialchars($user['e_posta']) ?></td>
                                        <td class="px-4 py-3"><?= htmlspecialchars($user['numara'] ?: '-') ?></td>
                                        <td class="px-4 py-3 flex gap-2 flex-row">
                                            <a href="kullanicilarduzenle.php?id=<?= $user['id'] ?>"
                                               class="px-3 py-1 text-sm rounded bg-blue-600 text-white hover:bg-blue-700 transition">
                                               <span class="material-symbols-outlined text-2xl">edit</span>
                                            </a>
                                            <a href="?send=<?= $user['id'] ?>"
                                               onclick="return confirm('Bu kişiye davet linki gönderilsin mi?\nE-posta: <?= htmlspecialchars($user['e_posta']) ?>')"
                                               class="px-3 py-1 text-sm rounded bg-amber-600 text-white hover:bg-amber-700 transition">
                                                <span class="material-symbols-outlined text-2xl">link</span>
                                            </a>
                                            <a href="?delete=<?= $user['id'] ?>"
                                               onclick="return confirm('Bu kaydı silmek istediğinize emin misiniz?')"
                                               class="px-3 py-1 text-sm rounded bg-red-600 text-white hover:bg-red-700 transition">
                                               <span class="material-symbols-outlined text-2xl">delete</span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-slate-500">Henüz kayıt eklenmemiş.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>