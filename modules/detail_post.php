<?php
require_once __DIR__ . '/../models/post.php';

// Mendapatkan slug dari parameter URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
// memanggil sebuah function berdasarkan slug donasi yang dipilih
$post = getPostBySlug($slug);


?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Donasi - KitaBantu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f5f5f5;
      font-family: "Poppins", sans-serif;
      display: flex;
      justify-content: center;
    }
    .main-wrapper {
      background: #fff;
      max-width: 420px;
      width: 100%;
      min-height: 100vh;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
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
    .donasi-img,
    .donasi-img1 {
      border-radius: 12px;
      width: 100%;
    }
    .donasi-card {
      background: #fff;
      border-radius: 12px;
      padding: 15px;
      margin-top: 15px;
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
    }
    .btn-donasi:hover {
      background: #f56767;
      color: white;
    }
    .section-cerita {
      margin-top: 20px;
    }
    .card {
      border-radius: 12px;
    }
    .progress {
      height: 12px;
      border-radius: 30px;
      background: #f1f1f1;
    }
    .progress-bar {
      background: #f78c8c;
    }
  </style>
</head>
<body>
  <div class="main-wrapper">
    <!-- Header -->
    <div class="header d-flex justify-content-between align-items-center">
      <a href="/modules/dashboard.php" class="text-white"><i class="bi bi-arrow-left"></i></a>
      <span>Detail Donasi</span>
      <i class="bi bi-share text-white"></i>
    </div>

    <!-- Gambar utama -->
    <div class="p-3">
      <img src="<?= '../' . $post['foto']; ?>" alt="<?php echo $post ? htmlspecialchars($post['judul']) : 'Donasi'; ?>" class="donasi-img">
    </div>

    <!-- Info Donasi -->
    <div class="px-3">
      <div class="donasi-card shadow-sm">
        <div class="mb-2 text-muted small">Yayasan Kita Bantu <i class="bi bi-patch-check-fill text-primary"></i></div>
        <h6 class="fw-bold"><?php echo $post ? htmlspecialchars($post['judul']) : 'Judul Donasi'; ?></h6>
        <?php if ($post && isset($post['target']) && isset($post['terkumpul']) && isset($post['end_date'])): ?>
          <?php
          // Hitung sisa hari
          $endDate = new DateTime($post['end_date'], new DateTimeZone('Asia/Jakarta'));
          $today = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
          $interval = $today->diff($endDate);
          $daysLeft = $interval->invert ? 0 : $interval->days;

          // Hitung persentase progress
          $progressPercent = ($post['target'] > 0) ? ($post['terkumpul'] / $post['target'] * 100) : 0;
          $target = number_format($post['target'], 0, ',', '.');
          $terkumpul = number_format($post['terkumpul'], 0, ',', '.');
          ?>
          <div class="row text-center mt-3">
            <div class="col">
              <div class="text-danger fw-bold">Rp<?php echo htmlspecialchars($terkumpul); ?></div>
              <div class="small text-muted">Terkumpul</div>
            </div>
            <div class="col">
              <div class="fw-bold">Rp<?php echo htmlspecialchars($target); ?></div>
              <div class="small text-muted">Dari</div>
            </div>
          </div>
          <!-- Progress bar -->
          <div class="mt-3">
            <div class="progress">
              <div class="progress-bar" role="progressbar" style="width: <?php echo htmlspecialchars($progressPercent); ?>%;" aria-valuenow="<?php echo htmlspecialchars($progressPercent); ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="small text-muted mt-1"><?php echo htmlspecialchars(number_format($progressPercent, 2)); ?>% tercapai</div>
          </div>
          <div class="text-end small text-danger mt-2"><i class="bi bi-clock"></i> <?php echo htmlspecialchars($daysLeft); ?> hari lagi</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Cerita Penggalangan Dana -->
    <div class="px-3 section-cerita">
      <h6>Cerita Penggalangan Dana</h6>
      <img src="<?= '../' . $post['foto']; ?>" alt="<?php echo $post ? htmlspecialchars($post['judul']) : 'Donasi'; ?>" class="donasi-img">
      <a href="/modules/donasi.php" class="btn-donasi mt-3"><i class="bi bi-heart-fill me-1"></i> Donasi Sekarang</a>
      <p class="text-muted small mt-3">
        <?php echo $post ? htmlspecialchars($post['deskripsi']) : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'; ?>
      </p>
      <a href="/modules/cerita.php?slug=<?= urlencode($post['slug']); ?>" class="text-danger small">
    Lihat Selengkapnya
  </a>

    </div>

    <!-- Donatur -->
    <div class="px-3 section-cerita">
      <h6>Donatur</h6>
      <?php if (!empty($donatur)): ?>
        <?php foreach ($donatur as $d): ?>
          <div class="card mb-2 p-2 shadow-sm border-0">
            <div class="d-flex align-items-center">
              <img src="img/animasi.jpg" class="rounded-circle me-2" alt="donatur" width="40" height="40">
              <div>
                <div class="fw-bold"><?php echo htmlspecialchars($d['nama'] ?: 'Donatur'); ?></div>
                <div class="text-muted small">
                  Berdonasi sebesar <span class="text-danger fw-bold">Rp<?php echo htmlspecialchars(number_format($d['jumlah'], 0, ',', '.')); ?></span>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-muted small">Belum ada donatur.</p>
      <?php endif; ?>
    </div>

    <!-- Doa Donatur -->
    <div class="px-3 section-cerita pb-5">
      <h6>Doa Donatur</h6>
      <?php if (!empty($doa)): ?>
        <?php foreach ($doa as $d): ?>
          <div class="card mb-3 p-3 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <div class="d-flex align-items-center">
                <img src="/public/img/profile.png" class="rounded-circle me-2" alt="donatur" width="40" height="40">
                <div>
                  <div class="fw-bold"><?php echo htmlspecialchars($d['nama'] ?: 'Donatur'); ?></div>
                </div>
              </div>
              <span class="text-muted small">
                <?php
                $time_diff = time() - strtotime($d['created_at']);
                $minutes = floor($time_diff / 60);
                echo htmlspecialchars($minutes . ' menit yang lalu');
                ?>
              </span>
            </div>
            <p class="mb-0 text-muted small">
              <?php echo htmlspecialchars($d['pesan']); ?>
            </p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-muted small">Belum ada doa donatur.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>