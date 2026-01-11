<?php
session_start();
include "connection.php";

// Login control
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION['kullanici_id'];
$error = '';
$success = '';

// UPLOAD PROCESS
if (isset($_POST['submit'])) {
    $baslik    = trim($_POST['baslik'] ?? '');
    $kategori  = trim($_POST['kategori'] ?? '');
    $aciklama  = trim($_POST['aciklama'] ?? '');

    if (empty($baslik) || empty($kategori)) {
        $error = "Başlık ve kategori zorunludur.";
    } elseif (!isset($_FILES['sunum_dosya']) || $_FILES['sunum_dosya']['error'] !== UPLOAD_ERR_OK) {
        $error = "Dosya yüklenirken hata oluştu.";
    } else {
        $file = $_FILES['sunum_dosya'];
        $allowed_types = ['application/pdf', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
        $allowed_ext = ['pdf', 'ppt', 'pptx'];
        $max_size = 50 * 1024 * 1024; // 50MB

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($file['type'], $allowed_types) || !in_array($ext, $allowed_ext)) {
            $error = "Sadece PDF, PPT veya PPTX dosyalarına izin verilir.";
        } elseif ($file['size'] > $max_size) {
            $error = "Dosya boyutu 50MB'dan büyük olamaz.";
        } else {
            // Secure filename
            $new_filename = 'sunum_' . $kullanici_id . '_' . time() . '_' . uniqid() . '.' . $ext;
            $upload_path = 'uploads/' . $new_filename;

            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                // Save to database
                $stmt = $conn->prepare("INSERT INTO sunumlar (kullanici_id, baslik, kategori, aciklama, dosya_adi, dosya_yolu) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssss", $kullanici_id, $baslik, $kategori, $aciklama, $file['name'], $upload_path);

                if ($stmt->execute()) {
                    $success = "Sunum başarıyla yüklendi!";
                } else {
                    $error = "Veritabanına kaydedilirken hata oluştu.";
                    unlink($upload_path); // delete file if DB fails
                }
                $stmt->close();
            } else {
                $error = "Dosya sunucuya yüklenemedi.";
            }
        }
    }
}

// DELETE OWN PRESENTATION
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $delete_id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT dosya_yolu FROM sunumlar WHERE id = ? AND kullanici_id = ?");
    $stmt->bind_param("ii", $delete_id, $kullanici_id);
    $stmt->execute();
    $stmt->bind_result($dosya_yolu);
    if ($stmt->fetch()) {
        $stmt->close();

        // Delete from database
        $stmt = $conn->prepare("DELETE FROM sunumlar WHERE id = ? AND kullanici_id = ?");
        $stmt->bind_param("ii", $delete_id, $kullanici_id);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            // Delete file from server
            if (file_exists($dosya_yolu)) {
                unlink($dosya_yolu);
            }
            $success = "Sunum silindi.";
        }
        $stmt->close();
    } else {
        $stmt->close();
    }

    header("Location: sunum.php");
    exit;
}

// FETCH ONLY CURRENT USER'S PRESENTATIONS
$stmt = $conn->prepare("SELECT id, baslik, kategori, aciklama, dosya_adi, dosya_yolu, yukleme_tarihi FROM sunumlar WHERE kullanici_id = ? ORDER BY yukleme_tarihi DESC");
$stmt->bind_param("i", $kullanici_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html class="light" lang="tr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>ÖPSP - Bilgilerim</title>
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
    <style>
        body { min-height: max(884px, 100dvh); }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        
    </style>
</head>
<body class="font-display antialiased text-slate-900 dark:text-white bg-background-light dark:bg-background-dark flex flex-col h-screen overflow-hidden">
    <?php include "sidebar.php" ?>
    <div class="flex-1 flex flex-col md:ml-64">
        <?php include "navbar.php" ?>

        <main class="flex flex-col lg:flex-row gap-6 p-6 w-full h-full overflow-hidden">
            <!-- LEFT: UPLOAD FORM -->
           

            <!-- RIGHT: MY PRESENTATIONS -->
          <!-- RIGHT: MY PRESENTATIONS - IMPROVED DESIGN -->
<div class="w-full lg:w-2/3 bg-white dark:bg-surface-dark rounded-xl shadow p-6 overflow-y-auto">
                <h1 class="text-2xl font-bold mb-6">📄 Yüklediğim Sunumlar</h1>

                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-2 gap-6">
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-5 flex flex-col gap-4 hover:shadow-lg transition">
                                <div>
                                    <h2 class="font-bold text-lg"><?= htmlspecialchars($row['baslik']) ?></h2>
                                    <p class="text-sm text-slate-500 mt-1"><?= htmlspecialchars($row['kategori']) ?></p>
                                    <?php if ($row['aciklama']): ?>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2"><?= htmlspecialchars($row['aciklama']) ?></p>
                                    <?php endif; ?>
                                    <p class="text-xs text-gray-400 mt-2">
                                        Yüklendi: <?= date('d.m.Y H:i', strtotime($row['yukleme_tarihi'])) ?>
                                    </p>
                                </div>

                                <!-- PDF Preview (only works for PDF) -->
                                <?php if (pathinfo($row['dosya_yolu'], PATHINFO_EXTENSION) === 'pdf'): ?>
                                    <iframe src="<?= htmlspecialchars($row['dosya_yolu']) ?>#view=FitH" 
                                            class="w-full h-64 rounded border border-slate-300"></iframe>
                                <?php else: ?>
                                    <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center border-2 border-dashed">
                                        <span class="text-gray-500">PPTX Önizleme Yok</span>
                                    </div>
                                <?php endif; ?>

                                <div class="flex gap-2 mt-auto">
                                    
                                    <a href="<?= htmlspecialchars($row['dosya_yolu']) ?>" target="_blank"
                                       class="flex-1 text-center py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                        Önizle
                                    </a>
                                    <a href="<?= htmlspecialchars($row['dosya_yolu']) ?>" download="<?= htmlspecialchars($row['dosya_adi']) ?>"
                                       class="flex-1 text-center py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                        İndir
                                    </a>
                                    <a href="?id=<?= $row['id'] ?>"
                                       onclick="return confirm('Bu sunumu silmek istediğinize emin misiniz?')"
                                       class="flex-1 text-center py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                                        Sil
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <span class="text-6xl block mb-4">📂</span>
                        <p class="text-xl text-gray-500">Henüz sunum yüklemediniz.</p>
                        <p class="text-gray-400 mt-2">Sol taraftan ilk sunumunuzu yükleyin!</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>