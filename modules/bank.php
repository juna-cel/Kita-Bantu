<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../models/Bank.php';

$banks = getBank();
?>
<div class="container mt-4">
    <h2>Data Bank</h2>
    <ul class="list-group">
        <?php if (!empty($banks)): ?>
            <?php foreach ($banks as $b): ?>
                <li class="list-group-item">
                    <?= $b['nama'] ?> - <?= $b['no_rekening'] ?> (<?= $b['status'] ?>)
                </li>
            <?php endforeach ?>
        <?php else: ?>
            <li class="list-group-item">Belum ada data</li>
        <?php endif ?>
    </ul>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
