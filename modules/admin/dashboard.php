<?php
include_once __DIR__ . '/../../config/database.php';
$conn = getDBConnection();

// === Ambil Statistik Cepat ===
$totalUsers   = $conn->query("SELECT COUNT(*) AS total FROM user")->fetch_assoc()['total'] ?? 0;
$totalDonasi  = $conn->query("SELECT SUM(jumlah) AS total FROM donasi WHERE status='success'")->fetch_assoc()['total'] ?? 0;
$donasiSuccess = $conn->query("SELECT COUNT(*) AS total FROM donasi WHERE status='success'")->fetch_assoc()['total'] ?? 0;
$donasiPending = $conn->query("SELECT COUNT(*) AS total FROM donasi WHERE status='pending'")->fetch_assoc()['total'] ?? 0;

// === Ambil User Terbaru (5 terakhir) ===
$sql_user = "SELECT nama_lengkap, email, created_at FROM user ORDER BY created_at DESC LIMIT 5";
$user_result = $conn->query($sql_user);

// === Grafik Donasi ===
$tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');
$data_bulan = array_fill(1, 12, 0);

$sql_donasi = "SELECT MONTH(tanggal_donasi) AS bulan, SUM(jumlah) AS total 
               FROM donasi 
               WHERE YEAR(tanggal_donasi)=? AND status='success'
               GROUP BY MONTH(tanggal_donasi)";
$stmt = $conn->prepare($sql_donasi);
if ($stmt) {
  $stmt->bind_param("i", $tahun);
  $stmt->execute();
  $res = $stmt->get_result();
  while ($row = $res->fetch_assoc()) {
    $data_bulan[(int)$row['bulan']] = (float)$row['total'];
  }
  $stmt->close();
}

include_once __DIR__ . '/partials/app.php';
?>

<!--begin::App Main-->
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h3 class="mb-0">Dashboard</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">

      <!-- Statistik Cepat -->
      <div class="row mb-4">
        <div class="col-md-3">
          <div class="card text-bg-primary shadow-sm">
            <div class="card-body text-center">
              <h5>Total User</h5>
              <h2><?= number_format($totalUsers) ?></h2>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-bg-success shadow-sm">
            <div class="card-body text-center">
              <h5>Total Donasi</h5>
              <h2>Rp <?= number_format($totalDonasi, 0, ',', '.') ?></h2>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-bg-info shadow-sm">
            <div class="card-body text-center">
              <h5>Donasi Sukses</h5>
              <h2><?= number_format($donasiSuccess) ?></h2>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-bg-warning shadow-sm">
            <div class="card-body text-center">
              <h5>Donasi Pending</h5>
              <h2><?= number_format($donasiPending) ?></h2>
            </div>
          </div>
        </div>
      </div>

      <!-- Grafik Donasi -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Grafik Donasi per Bulan (<?= $tahun ?>)</h5>
          <form method="GET" class="mb-0">
            <select name="tahun" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
              <?php
              $currentYear = date('Y');
              for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
                $selected = ($i == $tahun) ? 'selected' : '';
                echo "<option value='$i' $selected>$i</option>";
              }
              ?>
            </select>
          </form>
        </div>
        <div class="card-body">
          <canvas id="donasiChart" height="120"></canvas>
        </div>
      </div>

      <!-- User Terbaru -->
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">User Terbaru</h5>
        </div>
        <div class="card-body p-0">
          <table class="table table-striped mb-0">
            <thead class="table-light">
              <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($user_result && $user_result->num_rows > 0): ?>
                <?php while($u = $user_result->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($u['nama_lengkap']) ?></td>
                  <td><?= htmlspecialchars($u['email']) ?></td>
                  <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="3" class="text-center text-muted">Tidak dapat memuat data user (cek log error)</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</main>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('donasiChart').getContext('2d');
const dataBulan = <?php echo json_encode(array_values($data_bulan)); ?>;

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
    datasets: [{
      label: 'Total Donasi (Rp)',
      data: dataBulan,
      backgroundColor: 'rgba(54, 162, 235, 0.8)',
      borderColor: 'rgba(54, 162, 235, 1)',
      borderWidth: 1,
      borderRadius: 6
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: function(value) {
            return 'Rp ' + value.toLocaleString('id-ID');
          }
        }
      }
    },
    plugins: {
      legend: {
        display: true
      }
    }
  }
});
</script>