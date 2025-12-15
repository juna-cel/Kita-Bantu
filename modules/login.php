<?php
// ===========================
// START SESSION (HANYA SEKALI)
// ===========================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CEGAH AKSES LOGIN JIKA SUDAH LOGIN
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: /modules/akun.php");
    exit;
}

// CEK PERUBAHAN PASSWORD
if (isset($_GET['password_changed']) && $_GET['password_changed'] === 'success') {
    $passwordChangedMessage = "Kata sandi berhasil diubah! Silakan login dengan sandi baru.";
}

include __DIR__ . '/../config/database.php';
$conn = GetDbConnection();

// PROSES LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email === '' || $password === '') {
        $error = "Email dan password wajib diisi!";
    } else {
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['nama'] = $user['nama_lengkap'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['file_foto'] = $user['file_foto'];

            header("Location: /modules/akun.php");
            exit;

        } else {
            $error = "Email atau password salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color:#f5f5f5; }
.header { background-color:#fda2b2; color:white; text-align:center; padding:15px; font-weight:bold; }
.btn-login { background-color:#fda2b2; border:none; border-radius:30px; width:100%; padding:12px; color:white; font-weight:bold; }
.btn-login:hover { background-color:#f77b8f; }
.text-pink { color:#fda2b2 !important; }
.alert { border-radius:20px; }
</style>
</head>
<body>

<div class="container p-0 bg-white min-vh-100 d-flex flex-column" style="max-width:400px;">
  <div class="header">Login</div>
  <div class="flex-grow-1 px-4 py-4 d-flex flex-column justify-content-center">
    <h6 class="fw-bold mb-4">Selamat datang kembali di KitaBantu</h6>

    <?php if (!empty($passwordChangedMessage)): ?>
        <div class="alert alert-success"><?= $passwordChangedMessage ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['registered']) && $_GET['registered'] === 'success'): ?>
        <div class="alert alert-success">Pendaftaran berhasil! Silakan login.</div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3"><input type="email" class="form-control" name="email" placeholder="Email" required></div>
      <div class="mb-4"><input type="password" class="form-control" name="password" placeholder="Password" required></div>
      <button type="submit" class="btn btn-login mb-3">Login</button>
    </form>

    <p class="text-center text-muted">
      Belum punya akun? <a href="register.php" class="text-decoration-none text-pink">Daftar Disini</a>
    </p>
  </div>
</div>

</body>
</html>
  