<?php
session_start();
include __DIR__ . '/../config/database.php';
$conn = GetDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($nama === '' || $email === '' || $password === '') {
        $error = "Semua field wajib diisi!";
    } else {
        $check = $conn->prepare("SELECT id FROM user WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {
            $error = "Email sudah digunakan!";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO user (nama_lengkap, email, password, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $nama, $email, $hashed);
            $stmt->execute();

            header("Location: login.php?registered=success");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f5f5f5; }
.header { background:#fda2b2; color:#fff; text-align:center; padding:15px; font-weight:bold; }
.btn-login { background:#fda2b2; border:none; border-radius:30px; width:100%; padding:12px; color:#fff; font-weight:bold; }
.btn-login:hover { background:#f77b8f; }
.text-pink { color:#fda2b2 !important; }
.alert { border-radius:12px; }
</style>
</head>
<body>
<div class="container p-0 bg-white min-vh-100 d-flex flex-column" style="max-width:400px;">
  <div class="header">Register</div>

  <div class="flex-grow-1 px-4 py-3 d-flex flex-column">
    <h6 class="fw-bold mb-4">Daftar untuk menjadi salah satu donatur di KitaBantu</h6>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" required>
      </div>
      <div class="mb-3">
        <input type="email" class="form-control" name="email" placeholder="Email" required>
      </div>
      <div class="mb-4">
        <input type="password" class="form-control" name="password" placeholder="Password" required>
      </div>

      <button type="submit" class="btn btn-login mb-3">Daftar Sekarang</button>
    </form>

    <p class="text-center text-muted">
      Sudah punya akun? <a href="login.php" class="text-decoration-none text-pink">Login Disini</a>
    </p>
  </div>
</div>
</body>
</html>
