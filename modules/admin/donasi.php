<?php
include_once __DIR__ . '/../../config/database.php';
$conn = getDBConnection();

// ====== PROSES TAMBAH ======
if (isset($_POST['tambah'])) {
    $nama_donatur = $_POST['nama_donatur'];
    $jumlah = $_POST['jumlah'];
    $tanggal_donasi = $_POST['tanggal_donasi'];
    $doa = $_POST['doa'];
    $no_wa = $_POST['no_wa'];
    $email = $_POST['email'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $status = $_POST['status'];
    $id_order = "ORD" . rand(100000, 999999);

    $stmt = $conn->prepare("INSERT INTO donasi (nama_donatur, jumlah, tanggal_donasi, doa, no_wa, email, metode_pembayaran, status, id_order, created_at, updated_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("sdsssssss", $nama_donatur, $jumlah, $tanggal_donasi, $doa, $no_wa, $email, $metode_pembayaran, $status, $id_order);
    $stmt->execute();
    $stmt->close();

    header("Location: /admin/donasi");
    exit;
}

// ====== PROSES UPDATE ======
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_donatur = $_POST['nama_donatur'];
    $jumlah = $_POST['jumlah'];
    $tanggal_donasi = $_POST['tanggal_donasi'];
    $doa = $_POST['doa'];
    $no_wa = $_POST['no_wa'];
    $email = $_POST['email'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE donasi 
                            SET nama_donatur=?, jumlah=?, tanggal_donasi=?, doa=?, no_wa=?, email=?, metode_pembayaran=?, status=?, updated_at=NOW() 
                            WHERE id=?");
    $stmt->bind_param("sdssssssi", $nama_donatur, $jumlah, $tanggal_donasi, $doa, $no_wa, $email, $metode_pembayaran, $status, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: /admin/donasi");
    exit;
}

// ====== PROSES HAPUS ======
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $conn->query("DELETE FROM donasi WHERE id=$id");
    header("Location: /admin/donasi");
    exit;
}

// ====== AMBIL DATA ======
$result = $conn->query("SELECT * FROM donasi ORDER BY id DESC");

include_once __DIR__ . '/partials/app.php';
?>

<main class="app-main">
    <div class="container mt-4">
        <h3 class="mb-4 text-primary">Manajemen Donasi</h3>

```
    <!-- ===== FORM TAMBAH ===== -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="POST" class="row g-2">
                <div class="col-md-3"><input type="text" name="nama_donatur" class="form-control" placeholder="Nama Donatur" required></div>
                <div class="col-md-2"><input type="number" name="jumlah" class="form-control" placeholder="Jumlah (Rp)" required></div>
                <div class="col-md-2"><input type="date" name="tanggal_donasi" class="form-control" required></div>
                <div class="col-md-2"><input type="text" name="no_wa" class="form-control" placeholder="No. WA"></div>
                <div class="col-md-3"><input type="email" name="email" class="form-control" placeholder="Email"></div>
                <div class="col-md-3 mt-2"><input type="text" name="doa" class="form-control" placeholder="Doa / Pesan"></div>
                <div class="col-md-2 mt-2">
                    <select name="metode_pembayaran" class="form-select">
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="E-Wallet">E-Wallet</option>
                        <option value="Manual">Manual</option>
                    </select>
                </div>
                <div class="col-md-2 mt-2">
                    <select name="status" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="success">Success</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div class="col-md-1 mt-2">
                    <button type="submit" name="tambah" class="btn btn-primary w-100">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== TABEL DATA ===== -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Donatur</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Doa</th>
                        <th>No. WA</th>
                        <th>Email</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>ID Order</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_donatur']) ?></td>
                        <td>Rp<?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                        <td><?= $row['tanggal_donasi'] ?></td>
                        <td><?= htmlspecialchars($row['doa']) ?></td>
                        <td><?= htmlspecialchars($row['no_wa']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                        <td>
                            <span class="badge 
                                <?= $row['status']=='pending'?'bg-warning':($row['status']=='success'?'bg-success':'bg-danger') ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                        <td><?= $row['id_order'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>
                            <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus data ini?')">Hapus</a>
                        </td>
                    </tr>

                    <!-- ===== MODAL EDIT ===== -->
                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Donasi - <?= htmlspecialchars($row['nama_donatur']) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body row g-2">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <div class="col-md-4"><label>Nama Donatur</label><input type="text" name="nama_donatur" value="<?= $row['nama_donatur'] ?>" class="form-control" required></div>
                                        <div class="col-md-3"><label>Jumlah</label><input type="number" name="jumlah" value="<?= $row['jumlah'] ?>" class="form-control" required></div>
                                        <div class="col-md-3"><label>Tanggal Donasi</label><input type="date" name="tanggal_donasi" value="<?= $row['tanggal_donasi'] ?>" class="form-control" required></div>
                                        <div class="col-md-4"><label>No. WA</label><input type="text" name="no_wa" value="<?= $row['no_wa'] ?>" class="form-control"></div>
                                        <div class="col-md-4"><label>Email</label><input type="email" name="email" value="<?= $row['email'] ?>" class="form-control"></div>
                                        <div class="col-md-4"><label>Doa</label><input type="text" name="doa" value="<?= $row['doa'] ?>" class="form-control"></div>
                                        <div class="col-md-4">
                                            <label>Metode</label>
                                            <select name="metode_pembayaran" class="form-select">
                                                <option <?= $row['metode_pembayaran']=='Transfer Bank'?'selected':'' ?>>Transfer Bank</option>
                                                <option <?= $row['metode_pembayaran']=='E-Wallet'?'selected':'' ?>>E-Wallet</option>
                                                <option <?= $row['metode_pembayaran']=='Manual'?'selected':'' ?>>Manual</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Status</label>
                                            <select name="status" class="form-select">
                                                <option <?= $row['status']=='pending'?'selected':'' ?>>pending</option>
                                                <option <?= $row['status']=='success'?'selected':'' ?>>success</option>
                                                <option <?= $row['status']=='failed'?'selected':'' ?>>failed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" name="update" class="btn btn-success">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


</main>

<?php include_once __DIR__ . '/partials/footer.php'; ?>
