<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../models/Kategori.php';

$kategori = getKategori();
?>
<div class="container mt-4">
    <h2>Data Kategori</h2>
    <ul class="list-group">
        <?php if (!empty($kategori)): ?>
            <?php foreach ($kategori as $k): ?>
                <li class="list-group-item">
                    <?= $k['nama_kategori'] ?> 
                    <?= $k['icon'] ? " <i class='{$k['icon']}'></i>" : "" ?>
                </li>
            <?php endforeach ?>
        <?php else: ?>
            <li class="list-group-item">Belum ada data</li>
        <?php endif ?>
    </ul>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
