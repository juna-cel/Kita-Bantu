<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../models/Post.php';

$posts = getPosts();
?>
<div class="container mt-4">
    <h2>Data Post</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Pembuat</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $p): ?>
                    <tr>
                        <td><?= $p['judul'] ?></td>
                        <td><?= $p['nominal'] ?></td>
                        <td><?= $p['status'] ?></td>
                        <td><?= $p['pembuat'] ?></td>
                        <td><?= $p['tanggal'] ?></td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Belum ada data</td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
