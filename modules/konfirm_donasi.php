<?php
session_start();
include __DIR__ . '/../config/database.php';

$conn = GetDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_donatur = isset($_POST['nama']) ? mysqli_real_escape_string($conn, $_POST['nama']) : '';
    $email        = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $no_wa        = isset($_POST['no_wa']) ? mysqli_real_escape_string($conn, $_POST['no_wa']) : '';
    $doa          = isset($_POST['doa']) ? mysqli_real_escape_string($conn, $_POST['doa']) : '';
    $jumlah       = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 0;
    $metode       = 'Transfer Bank';
    $id_order     = 'ORD' . rand(100000, 999999);
    $status       = 'Pending';
    $created_by   = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NULL'; // jaga foreign key

    // Gunakan NULL tanpa tanda kutip kalau tidak login
    $created_by_sql = ($created_by === 'NULL') ? 'NULL' : "'$created_by'";

    $sql = "INSERT INTO donasi 
            (nama_donatur, jumlah, tanggal_donasi, doa, no_wa, email, metode_pembayaran, status, id_order, created_at, updated_at, created_by)
            VALUES 
            ('$nama_donatur', '$jumlah', NOW(), '$doa', '$no_wa', '$email', '$metode', '$status', '$id_order', NOW(), NOW(), $created_by_sql)";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['donasiAmount'] = $jumlah;
        $_SESSION['id_order'] = $id_order;
        header("Location: metode_pembayaran.php");
        exit;
    } else {
        echo "<div style='color:red; padding:20px;'>Error: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Konfirmasi Donasi - KitaBantu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f5f5f5;
      font-family: "Poppins", sans-serif;
    }
    .wrapper {
      background: #fff;
      max-width: 420px;
      width: 100%;
      margin: 0 auto;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .header {
      background: #f78c8c;
      color: white;
      padding: 10px;
      font-weight: 600;
      text-align: center;
      position: sticky;
      top: 0;
      z-index: 10;
    }
    .donasi-box {
      background: #f78c8c;
      color: white;
      border-radius: 12px;
      padding: 15px;
      text-align: center;
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 20px;
    }
    .form-control {
      border-radius: 12px;
      margin-bottom: 15px;
    }
    .btn-konfirmasi {
      background: #f78c8c;
      color: white;
      border-radius: 30px;
      padding: 12px;
      font-weight: 600;
      text-align: center;
      display: block;
      margin-top: 20px;
      width: 100%;
      border: none;
    }
    .btn-konfirmasi:hover {
      background: #f56767;
      color: white;
    }
    .small-text {
      font-size: 12px;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="header d-flex justify-content-between align-items-center">
      <a href="donasi.php" class="text-white"><i class="bi bi-arrow-left"></i></a>
      <span>Konfirmasi Donasi</span>
      <span></span>
    </div>

    <div class="content p-3">
      <div class="donasi-box">
        Donasi Sebesar <br> <span id="donasiValue">Rp0</span>
      </div>

      <form method="POST" action="">
        <input type="hidden" name="jumlah" id="jumlahInput" value="">

        <label class="form-label">Nama</label>
        <input type="text" class="form-control" name="nama" placeholder="Nama lengkap" required>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="anonim">
          <label class="form-check-label small-text" for="anonim">Sembunyikan Anonim</label>
        </div>

        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" placeholder="Email" required>

        <label class="form-label">No Whatsapp</label>
        <input type="text" class="form-control" name="no_wa" placeholder="Nomor Whatsapp" required>

        <label class="form-label">Doa</label>
        <textarea class="form-control" name="doa" rows="3" placeholder="Tulis doa..."></textarea>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="terms" required>
          <label class="form-check-label small-text" for="terms">
            Dengan melanjutkan pembayaran, Anda menyetujui
            <a href="#">Syarat & Ketentuan</a> serta <a href="#">Kebijakan Privasi</a>.
          </label>
        </div>

        <button type="submit" class="btn-konfirmasi">Konfirmasi</button>
      </form>
    </div>
  </div>

  <script>
    const value = localStorage.getItem("donasiAmount");

    function formatRupiah(angka) {
      return "Rp" + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    if (value) {
      document.getElementById("donasiValue").textContent = formatRupiah(value);
      document.getElementById("jumlahInput").value = value;
    }

    document.getElementById("anonim").addEventListener("change", function() {
      const namaInput = document.querySelector('input[name="nama"]');
      if (this.checked) {
        namaInput.value = "Hamba Allah";
        namaInput.setAttribute("readonly", true);
      } else {
        namaInput.value = "";
        namaInput.removeAttribute("readonly");
      }
    });
  </script>
</body>
</html>
