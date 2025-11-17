<?php
session_start();
include __DIR__ . '/../config/database.php';

// Cek login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

$conn = GetDbConnection();
$user_id = $_SESSION['user_id'];

// Ambil data user dari DB
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama           = trim($_POST['nama']);
    $email          = trim($_POST['email']);
    $tanggal_lahir  = $_POST['tanggal_lahir'] ?? null;
    $bio            = trim($_POST['bio']);

    // Default path foto lama
    $file_foto = $user['file_foto'];

    // Upload foto baru jika ada
    if (!empty($_FILES['file_foto']['name'])) {
        $upload_dir = __DIR__ . '/../public/img/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $file_name = time() . '_' . basename($_FILES['file_foto']['name']);
        $target_file = $upload_dir . $file_name;
        $relative_path = 'public/img/' . $file_name;

        if (move_uploaded_file($_FILES['file_foto']['tmp_name'], $target_file)) {
            $file_foto = $relative_path;
        }
    }

    // Update data ke database
    $update = $conn->prepare("UPDATE user SET nama_lengkap=?, email=?, tanggal_lahir=?, bio=?, file_foto=? WHERE id=?");
    $update->bind_param("sssssi", $nama, $email, $tanggal_lahir, $bio, $file_foto, $user_id);
    $update->execute();

    // Update session
    $_SESSION['nama'] = $nama;
    $_SESSION['email'] = $email;

    header("Location: profil.php?updated=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Edit Profil</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
body { background:#f5f5f5; font-family:"Poppins",sans-serif; }
.header { background:#f48c8c; padding:12px; text-align:left; max-width:420px; margin:auto; }
.header a { color:#fff; text-decoration:none; font-weight:600; }
.profile-header { background:linear-gradient(135deg,#f48c8c,#f7a0a0); padding:40px 0 60px; text-align:center; border-bottom-left-radius:30px; border-bottom-right-radius:30px; max-width:420px; margin:auto; }
.avatar-wrapper { position:relative; display:inline-block; cursor:pointer; }
.avatar-wrapper img { width:120px; height:120px; border-radius:50%; object-fit:cover; border:4px solid #fff; }
.camera-badge { position:absolute; right:8px; bottom:8px; background:#fff; border-radius:50%; padding:6px; box-shadow:0 2px 6px rgba(0,0,0,.2); display:flex; align-items:center; justify-content:center; width:28px; height:28px; }
.camera-badge svg { width:16px; height:16px; }
.card-form { background:#fff; border-radius:20px; margin:-40px auto 24px; padding:20px; box-shadow:0 4px 12px rgba(0,0,0,.06); max-width:420px; }
.btn-confirm { background:#f48c8c; border:none; width:100%; padding:12px; border-radius:28px; font-weight:600; color:#fff; }
.btn-confirm:hover { background:#e57878; }
</style>
</head>
<body>
  <div class="header">
    <a href="profil.php">&larr; Kembali</a>
  </div>

  <!-- Avatar -->
  <div class="profile-header">
    <label class="avatar-wrapper" for="avatarInput">
      <img id="avatarPreview" src="../<?= htmlspecialchars($user['file_foto'] ?: 'public/img/default.jpg') ?>" alt="Avatar">
      <span class="camera-badge">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M9 3l-1.5 2H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2h-2.5L15 3H9z" stroke="#f48c8c" stroke-width="1.6"/>
          <circle cx="12" cy="12" r="3.5" stroke="#f48c8c" stroke-width="1.6"/>
        </svg>
      </span>
    </label>
  </div>

  <div class="container">
    <form method="POST" enctype="multipart/form-data" class="card-form">
      <input type="file" name="file_foto" id="avatarInput" accept="image/*" hidden>

      <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control" value="<?= htmlspecialchars($user['tanggal_lahir'] ?? '') ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Bio Singkat</label>
        <textarea name="bio" class="form-control" rows="2"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
      </div>

      <button type="submit" class="btn-confirm">Simpan Perubahan</button>
    </form>
  </div>

  <script>
  const avatarInput = document.getElementById("avatarInput");
  const avatarPreview = document.getElementById("avatarPreview");
  avatarInput.addEventListener("change", function() {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = e => avatarPreview.src = e.target.result;
      reader.readAsDataURL(file);
    }
  });
  </script>
</body>
</html>
