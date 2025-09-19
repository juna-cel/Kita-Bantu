<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../models/User.php';

$users = getUsers();
?>
<div class="container mt-4">
    <h2>Data User</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Dibuat</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= $u['nama_lengkap'] ?></td>
                        <td><?= $u['email'] ?></td>
                        <td><?= $u['created_at'] ?></td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Belum ada data</td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
