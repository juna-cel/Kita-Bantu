<?php
session_start();
include __DIR__ . "/../config/database.php"; // koneksi database
$conn = GetDbConnection();

$user = null;

// Kalau user sudah login, ambil datanya
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: belum_login.php");
    exit;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Akun Saya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f5f5f5;
      font-family: "Poppins", sans-serif;
      margin-bottom: 5vh;
      justify-self: center;
    }
    .header {
      background: #f78c8c;
      color: white;
      padding: 10px;
      text-align: center;
      font-weight: 600;
      max-width: 420px;
      width: 100%;
      justify-self: center;
    }
    .profile-section {
      background: linear-gradient(135deg, #f78c8c, #f8a5a5);
      color: white;
      padding: 1.5rem 1rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      max-width: 420px;
      width: 100%;
      justify-self: center;
    }
    .profile-section img {
      width: 85px;
      height: 85px;
      border-radius: 50%;
      background: #fff;
      padding: 2px;
      flex-shrink: 0;
      object-fit: cover;
    }
    .profile-info {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .profile-name {
      font-weight: 600;
      font-size: 1.1rem;
      margin-bottom: 0.3rem;
    }
    .profile-extra {
      font-size: 0.85rem;
      color: #fceaea;
      margin-bottom: 2px;
    }
    .btn-edit {
      display: inline-block;
      background: #ffffff;
      color: #f78c8c;
      border: none;
      padding: 6px 20px;
      border-radius: 50px;
      font-size: 0.85rem;
      font-weight: 500;
      text-decoration: none;
      transition: background 0.3s ease;
      max-width: 20vh;
      width: 100%;
    }
    .btn-edit:hover {
      background: #f57878;
      color: white;
    }
    .menu-section {
      background: white;
      border-radius: 20px 20px 0 0;
      margin-top: -15px;
      padding: 1rem;
      max-width: 420px;
      width: 100%;
      min-height: 77vh;
      justify-self: center;
    }
    .menu-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 0;
      border-bottom: 1px solid #eee;
      color: #333;
      text-decoration: none;
    }
    .menu-item:last-child { border-bottom: none; }
    .menu-item i {
      color: #f78c8c;
      margin-right: 10px;
      font-size: 1.2rem;
    }
    .banner { 
      border-radius: 
      12px; text-align: center;
      margin-top: 1rem;
    }
    .banner img {
      max-width: 380px;
      border-radius: 10px;
      width: 100%;
      margin-top: 20px;
    }
    .footer {
      text-align: center;
      font-size: 0.8rem;
      color: #aaa;
      margin-top: 1rem;
    }
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
  <div class="header">Akun Saya</div>

  <!-- Bagian Profil -->
  <div class="profile-section">
    <img id="avatarDisplay" src="<?php echo !empty($user['file_foto']) ? '../' . htmlspecialchars($user['file_foto']) : 'image/profile.png'; ?>" alt="User">
    <div class="profile-info">
      <div class="profile-name" id="namaDisplay"><?php echo htmlspecialchars($user['nama_lengkap'] ?? 'Pengguna'); ?></div>
      <div class="profile-extra" id="emailDisplay"><?php echo htmlspecialchars($user['email'] ?? 'Email belum diatur'); ?></div>
      <div class="profile-extra" id="bioDisplay"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></div>
      <div class="profile-extra" id="tglDisplay">
        <?php echo !empty($user['tanggal_lahir']) ? "Lahir: " . htmlspecialchars($user['tanggal_lahir']) : ''; ?>
      </div>
      <a href="edit_profile.php" class="btn-edit">Edit Profile</a>
    </div>
  </div>

  <!-- Bagian Menu & Banner -->
  <div class="menu-section">
    <a href="/modules/ubah_sandi.php" class="menu-item">
      <div><i class="bi bi-shield-lock"></i> Ubah Kata Sandi</div>
      <i class="bi bi-chevron-right"></i>
    </a>
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
    <a href="logout.php" class="menu-item" class="menu-item">
      <div><i class="bi bi-people-fill"></i> Login Akun Lain</div>
      <i class="bi bi-chevron-right"></i>
    </a>
    
    <div class="banner">
      <img src="../public/img/banner1.png" alt="Banner">
    </div>
    <div class="footer">KitaBantu v1.0</div>
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
        <a class="nav-link " href="riwayat.php">
          <i class="bi bi-receipt"></i>
          Riwayat
        </a>
      </div>
      <div class="nav-item">
        <a class="nav-link active" href="akun.php">
          <i class="bi bi-person-fill"></i>
          Akun
        </a>
      </div>
    </div>
  </nav>
</body>
</html>
