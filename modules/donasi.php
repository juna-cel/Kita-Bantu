<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../models/Donasi.php';

$donasi = getDonasi();
?>
<div class="container mt-4">
    <h2>Data Donasi</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Donatur</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>ID Order</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($donasi)): ?>
                <?php foreach ($donasi as $d): ?>
                    <tr>
                        <td><?= $d['donatur'] ?></td>
                        <td><?= $d['jumlah'] ?></td>
                        <td><?= $d['status'] ?></td>
                        <td><?= $d['id_order'] ?></td>
                        <td><?= $d['tanggal_donasi'] ?></td>
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
