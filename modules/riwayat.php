<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$conn = GetDbConnection();

// Ambil riwayat donasi user
$stmt = $conn->prepare("SELECT * FROM riwayat WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$riwayat = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Donasi - KitaBantu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #fff;
      font-family: "Poppins", sans-serif;
      max-width: 420px;
      margin: auto;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding-bottom: 80px; /* untuk beri ruang navbar bawah */
    }
    .header {
      background: #f78a8a;
      color: #fff;
      text-align: center;
      padding: 12px;
      font-weight: 600;
      position: sticky;
      top: 0;
      z-index: 10;
    }
    .riwayat-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #eee;
      padding: 15px;
    }
    .riwayat-info {
      flex: 1;
    }
    .riwayat-title {
      font-weight: 600;
      color: #333;
    }
    .riwayat-date {
      font-size: 13px;
      color: #777;
    }
    .riwayat-amount {
      color: #f78a8a;
      font-weight: 600;
      margin-top: 5px;
    }
    .status-badge {
      font-size: 13px;
      font-weight: 600;
      padding: 6px 10px;
      border-radius: 12px;
      text-align: center;
      min-width: 80px;
    }
    .status-sukses {
      background-color: #d4edda;
      color: #155724;
    }
    .status-pending {
      background-color: #fff3cd;
      color: #856404;
    }
    .status-gagal {
      background-color: #f8d7da;
      color: #721c24;
    }

    /* NAVBAR BAWAH */
    .navbar {
      background: #fff;
      border-radius: 45px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      width: 27%;
      padding: 5px;
      justify-self: center;
      margin-bottom: 5px;
    }
    .nav-item {
      flex: 1;
      text-align: center;
    }
    .nav-link {
      color: #888;
      font-size: 14px;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 5px;
    }
    .nav-link.active {
      background-color: #f78a8a;
      color: #fff !important;
      border-radius: 30px;
    }
    .nav-link i {
      font-size: 16px;
      margin-bottom: 3px;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <div class="header">Riwayat Donasi</div>

  <!-- Riwayat -->
  <div class="flex-grow-1">
    <?php if (empty($riwayat)): ?>
      <div class="text-center text-muted mt-5">
        <i class="bi bi-inbox" style="font-size: 48px;"></i><br>
        Belum ada riwayat donasi.
      </div>
    <?php else: ?>
      <?php foreach ($riwayat as $r): ?>
        <?php
          $statusClass = '';
          if (strtolower($r['status']) === 'sukses') $statusClass = 'status-sukses';
          elseif (strtolower($r['status']) === 'pending') $statusClass = 'status-pending';
          else $statusClass = 'status-gagal';
        ?>
        <div class="riwayat-item">
          <div class="riwayat-info">
            <div class="riwayat-title"><?php echo htmlspecialchars($r['nama_donasi']); ?></div>
            <div class="riwayat-date"><?php echo date('d M Y, H:i', strtotime($r['created_at'])); ?></div>
            <div class="riwayat-amount">Rp<?php echo number_format($r['jumlah_donasi'], 0, ',', '.'); ?></div>
          </div>
          <div class="status-badge <?php echo $statusClass; ?>">
            <?php echo ucfirst(htmlspecialchars($r['status'])); ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Navbar bawah -->
   <nav class="navbar fixed-bottom">
    <div class="container d-flex justify-content-around">
      <div class="nav-item">
        <a class="nav-link" href="dashboard.php">
          <i class="bi bi-house-fill"></i>
          Home
        </a>
      </div>
      <div class="nav-item">
        <a class="nav-link active" href="riwayat.php">
          <i class="bi bi-receipt"></i>
          Riwayat
        </a>
      </div>
      <div class="nav-item">
        <a class="nav-link" href="akun.php">
          <i class="bi bi-person-fill"></i>
          Akun
        </a>
      </div>
    </div>
  </nav>
  </div>
</body>
</html>
