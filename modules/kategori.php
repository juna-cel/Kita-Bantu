<?php
require_once __DIR__ . '/../config/database.php';
$conn = GetDbConnection();

$query = $conn->query("SELECT * FROM kategori ORDER BY id ASC");
$kategori = $query->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Kategori</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      display: flex;
      justify-content: center;
    }
    .mobile-container {
      max-width: 420px;
      width: 100%;
      min-height: 100vh;
      background: #ffffff;
      display: flex;
      flex-direction: column;
    }
    .topbar {
      background:#f48c8c; 
      padding:12px; 
      text-align:left; 
      color: #ffffff;
      font-weight:600;
    }
    .topbar a {
      color: #fff;
      text-decoration: none;
    }
    .category-card {
      background: #f8f9fa;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      transition: 0.2s;
      height: 110px;
    }
    .category-card:active {
      transform: scale(0.97);
    }
    .category-card i {
      font-size: 30px;
      color: #f78c8c;
      display: block;
      margin-bottom: 10px;
    }
    .category-card p {
      margin: 0;
      font-size: 14px;
      font-weight: 500;
    }
    a {
      text-decoration: none !important;
      color: inherit !important;
    }
  </style>
</head>
<body>

  <div class="mobile-container">
    <!-- Top Bar -->
    <div class="topbar">
      <a href="/modules/dashboard.php">&larr; Kembali</a>
    </div>

    <!-- Content -->
    <div class="container my-3">
      <div class="row g-3">
        <?php if (!empty($kategori)): ?>
          <?php foreach ($kategori as $k): ?>
            <div class="col-6">
              <a href="kategori_detail.php?id=<?= $k['id'] ?>">
                <div class="category-card">
                  <i class="<?= $k['icon'] ?? 'bi bi-folder' ?>"></i>
                  <p><?= htmlspecialchars($k['nama_kategori']) ?></p>
                </div>
              </a>
            </div>
          <?php endforeach ?>
        <?php else: ?>
          <p class="text-center">Belum ada kategori</p>
        <?php endif ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
