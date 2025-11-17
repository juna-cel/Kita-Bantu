<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home - KitaBantu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body, html {
      height: 100%;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
      overflow: hidden;
    }
    .page-wrapper {
      max-width: 400px;
      margin: auto;
      height: 100vh;
      display: flex;
      flex-direction: column;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    .search-header {
      background-color: #fdaeb4;
      padding: 10px;
    }
    .search-header .form-control {
      border-radius: 20px;
      font-size: 0.9rem;
    }
    .search-header .btn {
      border-radius: 50%;
      padding: 6px 10px;
      background-color: white;
      border: none;
      color: #fdaeb4;
    }
    .content {
      flex: 1;
      overflow-y: auto;
      padding: 15px 20px;
      scrollbar-width: none;
      -ms-overflow-style: none;
    }
    .content::-webkit-scrollbar { display: none; }
    .hero {
      background-image: url(../public/img/banner1.png);
      background-size: cover;
      background-position: center;
      width: 100%;
      height: 200px;
      border-radius: 10px;
      margin-bottom: 20px;
    }
    .filter-buttons {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
      overflow-x: auto;
      white-space: nowrap;
      flex-wrap: nowrap;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: none;
    }
    .filter-buttons::-webkit-scrollbar { display: none; }
    .filter-buttons a {
      display: inline-block;
      border-radius: 30px;
      font-size: 0.8rem;
      padding: 6px 15px;
      white-space: nowrap;
      text-decoration: none;
      color: #555;
      border: 1px solid #ccc;
      transition: all 0.2s ease;
    }
    .filter-buttons a.active,
    .filter-buttons a:hover {
      background-color: #fdaeb4;
      color: white !important;
      border: none;
    }
    .donation-card {
      display: flex;
      gap: 10px;
      margin-bottom: 15px;
      background-color: #fff;
      border-radius: 10px;
      padding: 10px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      cursor: pointer;
      text-decoration: none;
      color: inherit;
    }
    .donation-card img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 10px;
    }
    .donation-content { flex: 1; }
    .donation-title {
      font-weight: 600;
      font-size: 0.9rem;
      margin-bottom: 5px;
      line-height: 1.2;
    }
    .donation-meta {
      font-size: 0.75rem;
      color: #666;
    }
    .donation-meta strong { color: #fdaeb4; font-weight: 600; }
    .progress { height: 5px; border-radius: 10px; margin: 6px 0; }
    .progress-bar { background-color: #fdaeb4; }
    .nav-bottom {
      background: #fff;
      border-top: 1px solid #ddd;
      display: flex;
      justify-content: space-around;
      padding: 8px 0;
      text-align: center;
      border-radius: 50px;
    }
    .nav-bottom a {
      color: #555;
      text-decoration: none;
      font-size: 12px;
      flex: 1;
    }
    .nav-bottom a.active {
      color: #ff9ca3;
      font-weight: 600;
    }
    .nav-bottom i {
      display: block;
      font-size: 20px;
      margin-bottom: 3px;
    }
  </style>
</head>
<body>
  <div class="page-wrapper">
    <div class="search-header">
      <form class="d-flex" onsubmit="return false;">
        <input id="searchInput" class="form-control me-2" type="search" placeholder="Cari Donasi" aria-label="Search">
        <button class="btn" type="button" onclick="searchCampaign()"><i class="bi bi-search"></i></button>
      </form>
    </div>

    <div class="content">
      <div class="hero"></div>

      <?php
      $conn = new mysqli("127.0.0.1", "root", "", "kita_bantu");
      if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

      // Ambil hanya 3 kategori unik dari tabel post
      $sqlKategori = "
        SELECT DISTINCT kategori
        FROM post
        WHERE kategori IS NOT NULL AND kategori != ''
        ORDER BY kategori ASC
        LIMIT 3
      ";
      $resultKategori = $conn->query($sqlKategori);
      ?>

      <!-- Tombol Kategori -->
      <div class="filter-buttons" id="kategoriScroll">
        <a href="/modules/kategori.php" class="active" onclick="filterCategory('all', this)">All</a>
        <?php while ($kat = $resultKategori->fetch_assoc()): ?>
          <a href="#" onclick="filterCategory('<?= htmlspecialchars($kat['kategori']) ?>', this)">
            <?= htmlspecialchars($kat['kategori']) ?>
          </a>
        <?php endwhile; ?>
      </div>

      <?php
      // Ambil semua data donasi aktif
      $sql = "
        SELECT *
        FROM post
        WHERE status = 'aktif'
        ORDER BY created_at DESC
      ";
      $result = $conn->query($sql);

      if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
          $target = number_format($row['nominal'], 0, ',', '.');
          $terkumpul = number_format($row['jumlah_terkumpul'], 0, ',', '.');
          $percent = ($row['nominal'] > 0) ? ($row['jumlah_terkumpul'] / $row['nominal']) * 100 : 0;
      ?>
      <a href="/detail-post?slug=<?= $row['slug'] ?>" 
         class="donation-card" 
         data-category="<?= htmlspecialchars($row['kategori']) ?>">
        <img src="../<?= htmlspecialchars($row['foto']) ?>" alt="<?= htmlspecialchars($row['judul']) ?>">
        <div class="donation-content">
          <div class="donation-title"><?= htmlspecialchars($row['judul']) ?></div>
          <div class="donation-meta">
            <i class="bi bi-building"></i> Yayasan KitaBantu<br>
            <i class="bi bi-tags"></i> <?= htmlspecialchars($row['kategori']) ?>
          </div>
          <div class="progress">
            <div class="progress-bar" style="width: <?= $percent ?>%;"></div>
          </div>
          <div class="donation-meta">
            <strong>Rp<?= $terkumpul ?></strong> dari Rp<?= $target ?>
          </div>
        </div>
      </a>
      <?php
        endwhile;
      else:
        echo "<p>Tidak ada kampanye aktif saat ini.</p>";
      endif;
      $conn->close();
      ?>
    </div>

    <!-- Footer -->
    <div class="nav-bottom">
      <a href="dashboard.php" class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">
        <i class="bi bi-house<?= ($current_page == 'dashboard.php') ? '-fill' : '' ?>"></i> Home
      </a>
      <a href="riwayat.php" class="<?= ($current_page == 'riwayat.php') ? 'active' : '' ?>">
        <i class="bi bi-clock-history<?= ($current_page == 'riwayat.php') ? '-fill' : '' ?>"></i> Riwayat
      </a>
      <a href="/modules/belum_login.php" class="<?= ($current_page == 'modules/belum_login.php') ? 'active' : '' ?>">
        <i class="bi bi-person<?= ($current_page == 'modules/belum_login.php') ? '-fill' : '' ?>"></i> Akun
      </a>
    </div>
  </div>

  <script>
    function searchCampaign() {
      let input = document.getElementById("searchInput").value.toLowerCase();
      let cards = document.querySelectorAll(".donation-card");
      cards.forEach(card => {
        let title = card.querySelector(".donation-title").textContent.toLowerCase();
        card.style.display = title.includes(input) ? "flex" : "none";
      });
    }

   function filterCategory(category, el) {
  let cards = document.querySelectorAll(".donation-card");
  cards.forEach(card => {
    let cardCategory = card.dataset.category.toLowerCase();
    if (category === "all" || cardCategory === category.toLowerCase()) {
      card.style.display = "flex";
    } else {
      card.style.display = "none";
    }
  });

  document.querySelectorAll(".filter-buttons a").forEach(btn => btn.classList.remove("active"));
  el.classList.add("active");
}

  </script>
</body>
</html>
