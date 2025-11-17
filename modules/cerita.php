<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$conn = GetDbConnection();

// Ambil slug dari URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if (!$slug) {
  header("Location: /");
  exit;
}

// Ambil data post dari database berdasarkan slug
$stmt = $conn->prepare("SELECT * FROM post WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
  die("Cerita tidak ditemukan!");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($post['judul']); ?> - KitaBantu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f5f5f5;
      font-family: "Poppins", sans-serif;
    }
    .wrapper {
      background: #fff;
      max-width: 420px;
      width: 100%;
      margin: 0 auto;
      min-height: 100vh;
    }
    .header {
      background: #f78c8c;
      color: white;
      padding: 10px;
      text-align: center;
      font-weight: 600;
      position: sticky;
      top: 0;
      z-index: 10;
    }
    .donasi-img {
      border-radius: 12px;
      width: 100%;
    }
    .btn-donasi {
      background: #f78c8c;
      color: white;
      border-radius: 30px;
      padding: 10px;
      font-weight: 600;
      text-align: center;
      display: block;
      text-decoration: none;
      margin: 15px 0;
    }
    .btn-donasi:hover {
      background: #f56767;
      color: white;
    }
    .content {
      padding: 15px;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <!-- Header -->
    <div class="header d-flex justify-content-between align-items-center">
      <a href="/modules/detail_post.php?slug=<?= urlencode($post['slug']); ?>" class="text-white"><i class="bi bi-arrow-left"></i></a>
      <span>Cerita Penggalangan Dana</span>
      <i class="bi bi-share text-white"></i>
    </div>

    <!-- Konten -->
    <div class="content">
      <div class="mb-2 text-muted small">
        Yayasan KitaBantu <i class="bi bi-patch-check-fill text-primary"></i>
      </div>

      <h6 class="fw-bold"><?= htmlspecialchars($post['judul']); ?></h6>

      <?php if (!empty($post['foto'])): ?>
        <img src="../<?= htmlspecialchars($post['foto']); ?>" alt="Donasi" class="donasi-img mt-2">
      <?php else: ?>
        <img src="../public/img/default.jpg" alt="Donasi" class="donasi-img mt-2">
      <?php endif; ?>

      <p class="text-muted small mt-3">
        <?= nl2br(htmlspecialchars($post['deskripsi'])); ?>
      </p>

      <a href="/modules/donasi.php?slug=<?= urlencode($post['slug']); ?>" class="btn-donasi">
        <i class="bi bi-heart-fill me-1"></i> Donasi Sekarang
      </a>
    </div>
  </div>
</body>
</html>
