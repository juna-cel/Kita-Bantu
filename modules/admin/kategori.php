<?php
include_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '../../../middleware/auth.php';

if (!hasPermission('list_category')) {
    http_response_code(403);
    require __DIR__ . '../../403.php';
    exit;
}

$conn = getDBConnection();

// === CREATE ===
if (isset($_POST['simpan'])) {
    $nama_kategori = $conn->real_escape_string($_POST['nama_kategori']);
    $icon = $conn->real_escape_string($_POST['icon']);
    $slug = $conn->real_escape_string($_POST['slug']);

    $query = "INSERT INTO kategori (nama_kategori, icon, slug, created_at, updated_at)
              VALUES ('$nama_kategori', '$icon', '$slug', NOW(), NOW())";
    if ($conn->query($query)) {
        header("Location: /admin/kategori");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal menambahkan kategori: " . $conn->error . "</div>";
    }
}

// === UPDATE ===
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nama_kategori = $conn->real_escape_string($_POST['nama_kategori']);
    $icon = $conn->real_escape_string($_POST['icon']);
    $slug = $conn->real_escape_string($_POST['slug']);

    $query = "UPDATE kategori SET 
                nama_kategori='$nama_kategori',
                icon='$icon',
                slug='$slug',
                updated_at=NOW()
              WHERE id='$id'";
    if ($conn->query($query)) {
        header("Location: /admin/kategori");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal update kategori: " . $conn->error . "</div>";
    }
}

// === DELETE ===
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    if ($conn->query("DELETE FROM kategori WHERE id=$id")) {
        header("Location: /admin/kategori");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal menghapus kategori: " . $conn->error . "</div>";
    }
}

include_once __DIR__ . '/partials/app.php';
?>

<main class="app-main">
    <div class="container mt-4">
        <h2 class="mb-4 text-primary">Manajemen Kategori</h2>

        <!-- Form tambah / edit kategori -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <?php
                $editMode = isset($_GET['edit']);
                $data = null;
                if ($editMode) {
                    $id = intval($_GET['edit']);
                    $data = $conn->query("SELECT * FROM kategori WHERE id='$id'")->fetch_assoc();
                }
                ?>
                <h5 class="card-title"><?= $editMode ? 'Edit Kategori' : 'Tambah Kategori' ?></h5>

                <form method="post" class="row g-3">
                    <?php if ($editMode): ?>
                        <input type="hidden" name="id" value="<?= $data['id'] ?>">
                    <?php endif; ?>

                    <div class="col-md-4">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control"
                            value="<?= htmlspecialchars($data['nama_kategori'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Icon (class Bootstrap Icon)</label>
                        <input type="text" name="icon" class="form-control"
                            placeholder="misal: bi bi-heart" value="<?= htmlspecialchars($data['icon'] ?? '') ?>" required>
                        <small class="text-muted">Gunakan class icon dari <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a></small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control"
                            placeholder="contoh: pendidikan.php"
                            value="<?= htmlspecialchars($data['slug'] ?? '') ?>" required>
                    </div>

                    <div class="col-12">
                        <button type="submit" name="<?= $editMode ? 'update' : 'simpan' ?>" 
                                class="btn <?= $editMode ? 'btn-success' : 'btn-primary' ?>">
                            <?= $editMode ? 'Update' : 'Simpan' ?>
                        </button>
                        <?php if ($editMode): ?>
                            <a href="/admin/kategori" class="btn btn-secondary">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel daftar kategori -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title"></h5>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Icon</th>
                                <th>Slug</th>
                                <th>Dibuat</th>
                                <th>Diubah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $result = $conn->query("SELECT * FROM kategori ORDER BY id DESC");
                            while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                    <td><i class="<?= htmlspecialchars($row['icon']) ?>"></i> <?= htmlspecialchars($row['icon']) ?></td>
                                    <td><?= htmlspecialchars($row['slug']) ?></td>
                                    <td><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
                                    <td><?= date('d M Y H:i', strtotime($row['updated_at'])) ?></td>
                                    <td>
                                        <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning me-1">Edit</a>
                                        <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once __DIR__ . '/partials/footer.php'; ?>
