<?php
session_start();
include __DIR__ . '/../config/database.php';
$conn = GetDbConnection();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Tentukan foto profil yang digunakan
$fotoPath = $user['file_foto'];
if (empty($fotoPath) || !file_exists(__DIR__ . '/../' . $fotoPath)) {
    $fotoPath = 'public/img/default.jpg';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil Saya</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f5f5f5; font-family:"Poppins",sans-serif; }
.header { background:#f48c8c; padding:12px; text-align:left; color:#fff; font-weight:600; max-width:420px; margin:auto; }
.profile-header { background:linear-gradient(135deg,#f48c8c,#f7a0a0); padding:40px 0 60px; text-align:center; border-bottom-left-radius:30px; border-bottom-right-radius:30px; max-width:420px; margin:auto; }
.profile-header img { width:120px; height:120px; border-radius:50%; border:4px solid #fff; object-fit:cover; }
.card-profile { background:#fff; border-radius:20px; margin:-40px auto 20px; padding:20px; max-width:420px; box-shadow:0 4px 12px rgba(0,0,0,0.08); text-align:center; }
.btn-edit { background-color:#f48c8c; color:white; border:none; border-radius:25px; width:100%; padding:10px; font-weight:600; margin-bottom:15px; }
.btn-edit:hover { background-color:#e57878; }
.home-icon { display:inline-flex; align-items:center; justify-content:center; background:#f48c8c; color:#fff; border-radius:50%; width:48px; height:48px; text-decoration:none; box-shadow:0 4px 8px rgba(0,0,0,0.1); transition:background 0.3s ease; }
.home-icon:hover { background:#e57878; }
.home-icon svg { width:22px; height:22px; }
</style>
</head>
<body>
  <div class="header">Profil Saya</div>

  <div class="profile-header">
    <img src="../<?= htmlspecialchars($fotoPath) ?>" alt="Foto Profil">
  </div>

  <div class="card-profile">
    <h5 class="text-center mb-3 fw-bold"><?= htmlspecialchars($user['nama_lengkap']); ?></h5>
    <p><strong>Email:</strong><br><?= htmlspecialchars($user['email']); ?></p>
    <p><strong>Tanggal Lahir:</strong><br><?= htmlspecialchars($user['tanggal_lahir'] ?: '-'); ?></p>
    <p><strong>Bio:</strong><br><?= htmlspecialchars($user['bio'] ?: 'Belum ada bio.'); ?></p>

    <a href="edit_profile.php" class="btn btn-edit mt-3">Edit Profil</a>

    <!-- Tombol Home -->
    <div class="mt-2">
      <a href="dashboard.php" class="home-icon" title="Kembali ke Dashboard">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M3 9.75L12 3l9 6.75M4.5 10.5V21h15V10.5M9 21V15h6v6" />
        </svg>
      </a>
    </div>
  </div>
</body>
</html>
