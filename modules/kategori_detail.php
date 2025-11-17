<?php
require_once __DIR__ . '/../config/database.php';
$conn = GetDbConnection();

if (!isset($_GET['id'])) {
  die("Kategori tidak ditemukan");
}

$id = intval($_GET['id']);

// Ambil nama kategori
$kategoriQuery = $conn->query("SELECT nama_kategori FROM kategori WHERE id = $id");
$kategori = $kategoriQuery->fetch_assoc();
$namaKategori = $kategori['nama_kategori'] ?? 'Tidak diketahui';

// Ambil semua post dengan kategori ini
$postQuery = $conn->query("SELECT * FROM post WHERE kategori_id = $id ORDER BY created_at DESC");
$posts = $postQuery->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($namaKategori) ?> - KitaBantu</title>

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
    .donation-card {
      display: flex;
      gap: 10px;
      margin-bottom: 15px;
      background-color: #fff;
      border-radius: 10px;
      padding: 10px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      text-decoration: none;
      color: inherit;
    }
    .donation-card img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 10px;
    }
    .donation-content { flex: 1; }
    .donation-title { font-weight: 600; font-size: 0.9rem; margin-bottom: 5px; }
    .progress { height: 5px; border-radius: 10px; margin: 6px 0; }
    .progress-bar { background-color: #fdaeb4; }
  </style>
</head>
<body>

<div class="mobile-container">
  <div class="topbar">
    <a href="kategori.php" style="color:white; text-decoration:none;">&larr; <?= htmlspecialchars($namaKategori) ?></a>
  </div>

  <div class="container my-3">

    <?php if (!empty($posts)): ?>
    <?php foreach ($posts as $row): 
        $terkumpul = (int)$row['nominal'];
        $target = (int)$row['target_donasi'];
        $persen = $target > 0 ? min(100, ($terkumpul / $target) * 100) : 0;
    ?>
        <a href="../detail-post?slug=<?= $row['slug'] ?>" class="donation-card">
            <img src="../<?= $row['foto'] ?>" alt="<?= $row['judul'] ?>">

            <div class="donation-content">
                <div class="donation-title"><?= $row['judul'] ?></div>

                <!-- Progress bar -->
                <div class="progress">
                    <div class="progress-bar" style="width: <?= $persen ?>%;"></div>
                </div>

                <!-- Nominal & Target -->
                <div style="font-size:12px;">
                    <strong>Rp<?= number_format($terkumpul, 0, ',', '.') ?></strong>
                    <span class="text-muted">/ Rp<?= number_format($target, 0, ',', '.') ?></span>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-center mt-3">Belum ada donasi di kategori ini.</p>
<?php endif; ?>

</div>

</body>
</html>