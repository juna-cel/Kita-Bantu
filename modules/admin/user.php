<?php
// === Include koneksi dan layout ===
include_once __DIR__ . '/../../config/database.php';

// === Ambil koneksi database ===
$conn = getDbConnection();


// ==== HANDLE HAPUS ====
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $getFoto = $conn->query("SELECT file_foto FROM user WHERE id=$id")->fetch_assoc();

    if ($getFoto && $getFoto['file_foto']) {
        $path = dirname(__DIR__, 3) . "/uploads/user/" . $getFoto['file_foto'];
        if (file_exists($path)) unlink($path);
    }

    $conn->query("DELETE FROM user WHERE id=$id");
    header("Location: /admin/user");
    exit;
}


// ==== HANDLE TAMBAH / EDIT ====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id     = $_POST['id'] ?? null;
    $nama   = $conn->real_escape_string($_POST['nama_lengkap']);
    $email  = $conn->real_escape_string($_POST['email']);
    $tgl    = $conn->real_escape_string($_POST['tanggal_lahir']);
    $bio    = $conn->real_escape_string($_POST['bio']);

    // Pastikan folder upload tersedia
    $uploadDir = dirname(__DIR__, 3) . "/uploads/user/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Upload foto (jika ada)
    $foto = $_POST['old_foto'] ?? null;
    if (!empty($_FILES['file_foto']['name'])) {
        $ext = pathinfo($_FILES['file_foto']['name'], PATHINFO_EXTENSION);
        $foto = time() . "_" . uniqid() . "." . $ext;
        $target = $uploadDir . $foto;
        move_uploaded_file($_FILES['file_foto']['tmp_name'], $target);
    }

    if ($id) {

        // ==== UPDATE USER ====
        $sql = "UPDATE user SET 
                    nama_lengkap='$nama',
                    email='$email',
                    tanggal_lahir='$tgl',
                    bio='$bio',
                    file_foto='$foto',
                    updated_at=NOW()
                WHERE id=$id";

        $conn->query($sql);

    } else {

        // ==== TAMBAH USER BARU ====
        $password_raw = $_POST['password'] ?? '';
        if (empty($password_raw)) {
            echo "<script>alert('Password wajib diisi untuk user baru!');history.back();</script>";
            exit;
        }

        $password = password_hash($password_raw, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user 
                (nama_lengkap, email, password, tanggal_lahir, bio, file_foto, created_at, updated_at)
                VALUES ('$nama', '$email', '$password', '$tgl', '$bio', '$foto', NOW(), NOW())";

        $conn->query($sql);
    }

    header("Location: /admin/user");
    exit;
}



// ==== HANDLE EDIT ====
$editUser = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $editUser = $conn->query("SELECT * FROM user WHERE id=$id")->fetch_assoc();
}


// ==== CEK TOMBOL TAMBAH ====
$isTambah = isset($_GET['tambah']);


// ==== AMBIL SEMUA DATA ====
$user = $conn->query("SELECT * FROM user ORDER BY id DESC");


include_once __DIR__ . '/partials/app.php';
?>

<!--begin::App Main-->
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0">Manajemen User</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <?php if ($editUser): ?>
                        <a href="/admin/user" class="btn btn-secondary">Kembali</a>
                    <?php elseif ($isTambah): ?>
                        <a href="/admin/user" class="btn btn-secondary">Tutup</a>
                    <?php else: ?>
                        <a href="/admin/user?tambah=1" class="btn btn-primary">+ Tambah User</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <!-- Form Tambah/Edit -->
            <div class="collapse <?= ($editUser || $isTambah) ? 'show' : '' ?>" id="formUser">
                <div class="card card-body mb-4">
                    <h5><?= $editUser ? 'Edit User' : 'Tambah User Baru' ?></h5>

                    <form method="POST" enctype="multipart/form-data">

                        <?php if ($editUser): ?>
                            <input type="hidden" name="id" value="<?= $editUser['id'] ?>">
                            <input type="hidden" name="old_foto" value="<?= $editUser['file_foto'] ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control"
                                       value="<?= htmlspecialchars($editUser['nama_lengkap'] ?? '') ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control"
                                       value="<?= htmlspecialchars($editUser['email'] ?? '') ?>" required>
                            </div>
                        </div>

                        <?php if (!$editUser): ?>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control"
                                   value="<?= $editUser['tanggal_lahir'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label>Bio</label>
                            <textarea name="bio" class="form-control" rows="4"><?= htmlspecialchars($editUser['bio'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Foto Profil</label><br>

                            <?php if (!empty($editUser['file_foto'])): ?>
                                <img src="../../../uploads/user/<?= $editUser['file_foto'] ?>" width="80" class="rounded mb-2"><br>
                            <?php endif; ?>

                            <input type="file" name="file_foto" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-success">Simpan</button>
                        <a href="/admin/user" class="btn btn-secondary">Batal</a>

                    </form>
                </div>
            </div>


            <!-- Data Tabel User -->
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead>
                            <tr class="text-center">
                                <th width="50">#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Tgl Lahir</th>
                                <th>Foto</th>
                                <th>Bio</th>
                                <th>Dibuat</th>
                                <th>Diubah</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if ($user->num_rows > 0): $no = 1; ?>
                                <?php while ($u = $user->fetch_assoc()): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($u['nama_lengkap']) ?></td>
                                        <td><?= htmlspecialchars($u['email']) ?></td>
                                        <td><?= $u['tanggal_lahir'] ?></td>

                                        <td class="text-center">
                                            <?php if ($u['file_foto']): ?>
                                                <img src="../<?= $u['file_foto'] ?>" width="50" class="rounded">
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>

                                        <td><?= nl2br(htmlspecialchars($u['bio'])) ?></td>

                                        <td class="text-center"><?= date('d M Y H:i', strtotime($u['created_at'])) ?></td>
                                        <td class="text-center"><?= date('d M Y H:i', strtotime($u['updated_at'])) ?></td>

                                        <td class="text-center">
                                            <a href="/admin/user?edit=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="/admin/user?hapus=<?= $u['id'] ?>" onclick="return confirm('Yakin ingin hapus user ini?')" class="btn btn-sm btn-danger">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>

                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Belum ada data user.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>
</main>
<!--end::App Main-->
