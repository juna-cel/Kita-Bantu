<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$conn = GetDbConnection();

// Pastikan metode pembayaran dipilih
if (!isset($_POST['selected_method'])) {
    header("Location: metode_pembayaran.php");
    exit;
}

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$selectedId = intval($_POST['selected_method']);
$donasiAmount = $_SESSION['donasiAmount'] ?? 0;
$nama_donasi = $_SESSION['nama_donasi'] ?? 'Donasi Umum';

// Ambil data metode pembayaran dari database
$stmt = $conn->prepare("SELECT * FROM rekening WHERE id = ?");
$stmt->bind_param("i", $selectedId);
$stmt->execute();
$result = $stmt->get_result();
$metode = $result->fetch_assoc();

if (!$metode) {
    die("Metode pembayaran tidak ditemukan!");
}

// Simpan ke tabel riwayat
$sql = "INSERT INTO riwayat (user_id, nama_donasi, jumlah_donasi, metode_pembayaran, status, created_at)
        VALUES (?, ?, ?, ?, 'Sukses', NOW())";
$stmt = $conn->prepare($sql);
$metode_pembayaran = $metode['nama'] . ' (' . $metode['kategori_bank'] . ')';
$stmt->bind_param("isds", $user_id, $nama_donasi, $donasiAmount, $metode_pembayaran);
$stmt->execute();

// Hapus session donasi agar tidak dobel
unset($_SESSION['donasiAmount']);
unset($_SESSION['nama_donasi']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sukses - KitaBantu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background: #f5f5f5;
      font-family: "Poppins", sans-serif;
    }
    .wrapper {
      background: #f78c8c;
      max-width: 420px;
      width: 100%;
      margin: 0 auto;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: white;
      text-align: center;
      padding: 20px;
    }

    .checkmark {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      display: block;
      stroke-width: 4;
      stroke: #fff;
      stroke-miterlimit: 10;
      margin: 20px auto;
      box-shadow: inset 0px 0px 0px #fff;
      animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    }
    .checkmark__circle {
      stroke-dasharray: 166;
      stroke-dashoffset: 166;
      stroke-width: 4;
      stroke-miterlimit: 10;
      stroke: #fff;
      fill: none;
      animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    .checkmark__check {
      transform-origin: 50% 50%;
      stroke-dasharray: 48;
      stroke-dashoffset: 48;
      animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }
    @keyframes stroke {
      100% { stroke-dashoffset: 0; }
    }
    @keyframes scale {
      0%, 100% { transform: none; }
      50% { transform: scale3d(1.1, 1.1, 1); }
    }
    @keyframes fill {
      100% { box-shadow: inset 0px 0px 0px 60px #fff; }
    }

    .fade-in {
      opacity: 0;
      transform: translateY(10px);
      animation: fadeIn 0.8s ease forwards;
    }
    @keyframes fadeIn {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .fade-delay-1 { animation-delay: 1.3s; }
    .fade-delay-2 { animation-delay: 1.6s; }
    .fade-delay-3 { animation-delay: 1.9s; }

    .btn-selesai {
      background: white;
      color: #f78c8c;
      font-weight: 600;
      border-radius: 8px;
      padding: 8px 20px;
      margin-top: 20px;
      border: none;
      text-decoration: none;
    }
    .btn-selesai:hover {
      background: #ffecec;
    }
    .info-box {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 10px;
      padding: 15px;
      margin-top: 10px;
      text-align: left;
      font-size: 14px;
      color: #fff;
      width: 100%;
      max-width: 300px;
    }
    .redirect-text {
      font-size: 13px;
      opacity: 0.8;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <h5 class="fw-bold mb-4 fade-in fade-delay-1">KitaBantu</h5>
    <h6 class="fade-in fade-delay-1">Terimakasih</h6>

    <svg class="checkmark fade-in" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
      <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
      <path class="checkmark__check" fill="none" d="M14 27l7 7 16-16"/>
    </svg>

    <p class="fw-bold fade-in fade-delay-2">Donasi Kamu Sudah<br>Kami Terima</p>

    <div class="info-box fade-in fade-delay-3">
      <p class="mb-1"><strong>Metode Pembayaran:</strong><br><?php echo htmlspecialchars($metode['nama']); ?></p>
      <p class="mb-1"><strong>No. Rekening:</strong><br><?php echo htmlspecialchars($metode['no_rekening']); ?></p>
      <p class="mb-1"><strong>Keterangan:</strong><br><?php echo htmlspecialchars($metode['keterangan']); ?></p>
      <p class="mb-0"><strong>Kategori:</strong> <?php echo htmlspecialchars($metode['kategori_bank']); ?></p>
    </div>

    <a href="riwayat.php" class="btn-selesai mt-4 fade-in fade-delay-3">Lihat Riwayat</a>
    <p class="redirect-text fade-in fade-delay-3">Kamu akan diarahkan otomatis dalam <span id="timer">5</span> detik...</p>
  </div>

  <script>
    // Tunda popup agar muncul setelah animasi checkmark selesai
    setTimeout(() => {
      Swal.fire({
        icon: 'success',
        title: 'Donasi Berhasil!',
        text: 'Terima kasih telah berdonasi melalui KitaBantu ❤️',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
      });
    }, 1200); // Delay 1.2 detik biar sinkron dengan animasi

    // Timer redirect ke halaman riwayat
    let timer = 5;
    const timerEl = document.getElementById("timer");
    const interval = setInterval(() => {
      timer--;
      timerEl.textContent = timer;
      if (timer <= 0) {
        clearInterval(interval);
        window.location.href = "riwayat.php";
      }
    }, 1000);
  </script>
</body>
</html>
