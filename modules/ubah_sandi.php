<?php
session_start();
include __DIR__ . '/../config/database.php';
$conn = GetDbConnection();

// Pastikan pengguna sudah login
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = trim($_POST['old_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($old_password === '' || $new_password === '' || $confirm_password === '') {
        $error = "Semua kolom wajib diisi!";
    } elseif ($new_password !== $confirm_password) {
        $error = "Kata sandi baru dan konfirmasi tidak sama!";
    } else {
        // Ambil data user
        $stmt = $conn->prepare("SELECT password FROM user WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($old_password, $user['password'])) {
            // Enkripsi password baru
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password
            $update = $conn->prepare("UPDATE user SET password = ?, updated_at = NOW() WHERE id = ?");
            $update->bind_param("si", $hashed, $user_id);
            if ($update->execute()) {
                // Hapus session agar user login ulang dengan password baru
                session_destroy();
                header("Location: login.php?password_changed=success");
                exit;
            } else {
                $error = "Terjadi kesalahan saat mengubah kata sandi.";
            }
        } else {
            $error = "Kata sandi lama salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Ubah Kata Sandi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #e9ecef;
      display: flex;
      justify-content: center;
    }
    .mobile-container {
      max-width: 480px;
      width: 100%;
      min-height: 100vh;
      background: #fff;
      display: flex;
      flex-direction: column;
    }
    .topbar {
      background-color: #f78c8c;
      color: white;
      padding: 0.9rem 1rem;
      font-size: 1rem;
      display: flex;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    .topbar i { cursor: pointer; }
    .form-container {
      flex: 1;
      padding: 1.5rem;
    }
    .form-label {
      font-weight: 600;
      font-size: 0.95rem;
      margin-bottom: 0.5rem;
    }
    .form-control {
      border-radius: 8px;
      padding: 0.8rem;
      font-size: 0.9rem;
    }
    .btn-submit {
      background-color: #f78c8c;
      color: #fff;
      font-weight: 600;
      border-radius: 25px;
      padding: 0.9rem;
      width: 100%;
      border: none;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
      margin-top: 1.5rem;
    }
  </style>
</head>
<body>

  <div class="mobile-container">
    <div class="topbar">
      <a href="akun.php" class="text-white text-decoration-none"><i class="bi bi-arrow-left"></i></a>
      <span class="ms-2">Ubah Kata Sandi</span>
    </div>

    <div class="form-container">
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Kata Sandi Lama</label>
          <input type="password" name="old_password" class="form-control" placeholder="Masukkan kata sandi lama" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Kata Sandi Baru</label>
          <input type="password" name="new_password" class="form-control" placeholder="Masukkan kata sandi baru" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Konfirmasi Kata Sandi Baru</label>
          <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi kata sandi baru" required>
        </div>

        <button type="submit" class="btn-submit">Simpan Perubahan</button>
      </form>
    </div>
  </div>

</body>
</html>
