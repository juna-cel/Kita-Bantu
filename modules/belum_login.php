<?php
session_start();

// Jika sudah login, langsung arahkan ke akun.php
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: akun.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Belum Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f5f5f5;
      font-family: "Poppins", sans-serif;
    }
    .header {
      background: #f78c8c;
      color: white;
      padding: 12px;
      text-align: center;
      font-weight: 600;
      max-width: 420px;
      width: 100%;
      margin: 0 auto;
    }
    .hero-section {
      background: linear-gradient(135deg, #f78c8c, #f8a5a5);
      color: white;
      text-align: center;
      padding: 2rem 1rem 3rem;
      border-bottom-left-radius: 20px;
      border-bottom-right-radius: 20px;
      max-width: 420px;
      width: 100%;
      margin: 0 auto;
    }
    .btn-login {
      background: #fff;
      color: #f78c8c;
      padding: 10px 30px;
      border-radius: 50px;
      font-weight: 600;
      text-decoration: none;
      transition: 0.3s;
    }
    .btn-login:hover {
      background: #fceaea;
      color: #f45c5c;
    }
    .menu-section {
      background: #fff;
      border-radius: 20px 20px 0 0;
      margin-top: -25px;
      padding: 1rem;
      max-width: 420px;
      width: 100%;
      min-height: 70vh;
      margin-left: auto;
      margin-right: auto;
    }
    .menu-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 0;
      border-bottom: 1px solid #eee;
      color: #333;
      text-decoration: none;
      font-size: 0.95rem;
    }
    .menu-item i {
      color: #f78c8c;
      margin-right: 10px;
      font-size: 1.2rem;
    }
    .banner img {
      width: 100%;
      border-radius: 12px;
    }
    .nav-bottom {
      background: #fff;
      border-top: 1px solid #ddd;
      display: flex;
      justify-content: space-around;
      padding: 8px 0;
      text-align: center;
      border-radius: 50px;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      max-width: 420px;
      width: 100%;
      margin: 0 auto;
    }
    .nav-bottom a {
      color: #555;
      text-decoration: none;
      font-size: 12px;
      flex: 1;
    }
    .nav-bottom a.active {
      color: #ff9ca3;
      font-weight: 600;
    }
    .nav-bottom i {
      display: block;
      font-size: 20px;
      margin-bottom: 3px;
    }
  </style>
</head>
<body>

  <div class="header">Akun Saya</div>

  <div class="hero-section">
    <p>Silahkan masuk dengan akun Anda<br>untuk membantu di KitaBantu</p>
    <a href="login.php" class="btn-login">Masuk Sekarang</a>
    <div class="link-register mt-2">
      Belum punya akun? <a href="register.php" style="color:white;font-weight:600;">Daftar</a>
    </div>
  </div>

  <div class="menu-section">
    <a href="pusat_bantuan.php" class="menu-item">
      <div><i class="bi bi-question-circle"></i> Frequently Asked Questions</div>
      <i class="bi bi-chevron-right"></i>
    </a>
    <a href="syarat_ketentuan.php" class="menu-item">
      <div><i class="bi bi-book"></i> Syarat & Ketentuan</div>
      <i class="bi bi-chevron-right"></i>
    </a>
    <a href="tentang_kita.php" class="menu-item">
      <div><i class="bi bi-info-circle"></i> Tentang Kita Bantu</div>
      <i class="bi bi-chevron-right"></i>
    </a>

    <div class="banner mt-3">
      <img src="image/banner1.png" alt="Banner">
    </div>
  </div>

  <div class="nav-bottom">
    <a href="dashboard.php"><i class="bi bi-house"></i>Home</a>
    <a href="riwayat.php"><i class="bi bi-clock-history"></i>Riwayat</a>
    <a href="belum_login.php" class="active"><i class="bi bi-person-fill"></i>Akun</a>
  </div>

</body>
</html>
