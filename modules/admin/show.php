<?php
require_once __DIR__ . '/../../config/database.php';
$conn = GetDbConnection();

if (!isset($_GET['id'])) {
    die("ID role tidak ditemukan.");
}

$id = $_GET['id'];

$role = $conn->query("SELECT * FROM roles WHERE id='$id'")->fetch_assoc();
$permissions = $conn->query("SELECT * FROM permissions");
$role_permissions = $conn->query("SELECT permission_id FROM role_permission WHERE role_id='$id'");

$selected = [];
while ($rp = $role_permissions->fetch_assoc()) {
    $selected[] = $rp['permission_id'];
}

// Header + Sidebar
include_once __DIR__ . '/partials/app.php';
?>
<!-- ================== START CONTENT WRAPPER ================== -->
<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <h3>Kelola Permission Role: <?= $role['name'] ?></h3>
        </div>
    </section>

    <section class="content">
        <div class="container">

            <div class="card shadow-sm">
                <div class="card-body">

                    <form method="POST" action="/admin/role-permission-process">

                        <?php while ($p = $permissions->fetch_assoc()): ?>
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="permissions[]"
                                       value="<?= $p['id'] ?>"
                                       <?= in_array($p['id'], $selected) ? 'checked' : '' ?>>
                                <label class="form-check-label">
                                    <?= $p['name'] ?>
                                </label>
                            </div>
                        <?php endwhile; ?>

                        <br>
                        <input type="hidden" name="role_id" value="<?= $id ?>">
                        <button type="submit" class="btn btn-primary mt-2">Simpan</button>

                    </form>

                </div>
            </div>

        </div>
    </section>

</div>
<!-- ================== END CONTENT WRAPPER ================== -->

<?php include_once __DIR__ . '/../partials/footer.php'; ?>
