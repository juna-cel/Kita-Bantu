<?php
include_once __DIR__ . '/../../config/database.php';
$conn = getDBConnection();

// === TAMBAH DATA ===
if (isset($_POST['tambah'])) {
    $no_rekening   = $conn->real_escape_string($_POST['no_rekening']);
    $nama          = $conn->real_escape_string($_POST['nama']);
    $status        = $conn->real_escape_string($_POST['status']);
    $keterangan    = $conn->real_escape_string($_POST['keterangan']);
    $kategori_bank = $conn->real_escape_string($_POST['kategori_bank']);

    $conn->query("INSERT INTO master_bank (no_rekening, nama, status, keterangan, kategori_bank, created_at, updated_at)
                  VALUES ('$no_rekening', '$nama', '$status', '$keterangan', '$kategori_bank', NOW(), NOW())");
    header("Location: index.php");
    exit;
}

// === HAPUS DATA ===
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $conn->query("DELETE FROM master_bank WHERE id = $id");
    header("Location: index.php");
    exit;
}

// === EDIT DATA ===
if (isset($_POST['edit'])) {
    $id            = (int) $_POST['id'];
    $no_rekening   = $conn->real_escape_string($_POST['no_rekening']);
    $nama          = $conn->real_escape_string($_POST['nama']);
    $status        = $conn->real_escape_string($_POST['status']);
    $keterangan    = $conn->real_escape_string($_POST['keterangan']);
    $kategori_bank = $conn->real_escape_string($_POST['kategori_bank']);

    $conn->query("UPDATE master_bank 
                  SET no_rekening='$no_rekening', nama='$nama', status='$status', 
                      keterangan='$keterangan', kategori_bank='$kategori_bank', updated_at=NOW()
                  WHERE id=$id");
    header("Location: index.php");
    exit;
}

include_once __DIR__ . '/partials/app.php';
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Master Bank</title>
  <link rel="stylesheet" href="../../../public/assets/css/adminlte.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

<div class="container">
    <h3 class="mb-3">Master Bank</h3>

    <!-- FORM TAMBAH -->
    <form method="post" class="card p-3 mb-4">
        <h5>Tambah Bank Baru</h5>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label>No Rekening</label>
                <input type="text" name="no_rekening" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Nama Bank</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="col-md-2 mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label>Kategori Bank</label>
                <input type="text" name="kategori_bank" class="form-control" placeholder="VA / QRIS / Gopay">
            </div>
            <div class="col-md-12 mb-3">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="2"></textarea>
            </div>
            <div class="col-md-2">
                <button type="submit" name="tambah" class="btn btn-success w-100 mt-2">Tambah</button>
            </div>
        </div>
    </form>

    <!-- TABEL DATA -->
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>No Rekening</th>
                <th>Nama Bank</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Kategori Bank</th>
                <th>Dibuat</th>
                <th>Diperbarui</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        $result = $conn->query("SELECT * FROM master_bank ORDER BY id DESC");
        while ($row = $result->fetch_assoc()):
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['no_rekening']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td>
                    <span class="badge bg-<?= $row['status'] == 'aktif' ? 'success' : 'secondary' ?>">
                        <?= htmlspecialchars($row['status']) ?>
                    </span>
                </td>
                <td><?= htmlspecialchars($row['keterangan']) ?></td>
                <td><?= htmlspecialchars($row['kategori_bank']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td><?= htmlspecialchars($row['updated_at']) ?></td>
                <td>
                    <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Hapus data ini?')">Hapus</a>

                    <!-- Tombol Edit -->
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>
                </td>
            </tr>

            <!-- MODAL EDIT -->
            <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form method="post">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Bank</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                      <div class="mb-2">
                        <label>No Rekening</label>
                        <input type="text" name="no_rekening" value="<?= htmlspecialchars($row['no_rekening']) ?>" class="form-control" required>
                      </div>
                      <div class="mb-2">
                        <label>Nama Bank</label>
                        <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" class="form-control" required>
                      </div>
                      <div class="mb-2">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif" <?= $row['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= $row['status'] == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                      </div>
                      <div class="mb-2">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2"><?= htmlspecialchars($row['keterangan']) ?></textarea>
                      </div>
                      <div class="mb-2">
                        <label>Kategori Bank</label>
                        <input type="text" name="kategori_bank" value="<?= htmlspecialchars($row['kategori_bank']) ?>" class="form-control">
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" name="edit" class="btn btn-primary">Simpan</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include_once __DIR__ . '/partials/footer.php'; ?>
