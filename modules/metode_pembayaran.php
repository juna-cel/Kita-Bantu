<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Ambil koneksi dari fungsi GetDbConnection()
$pdo = GetDbConnection();

// Ambil nominal donasi dari session
$donasiAmount = $_SESSION['donasiAmount'] ?? 0;

if ($donasiAmount <= 0) {
    header("Location: konfirm_donasi.php");
    exit;
}

// Ambil semua metode pembayaran dari tabel rekening
$query = "SELECT * FROM rekening WHERE status = 'aktif'";
$result = $pdo->query($query);
$rekeningList = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembayaran - KitaBantu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background: #f5f5f5; font-family: "Poppins", sans-serif; }
    .wrapper { background: #fff; max-width: 420px; width: 100%; margin: 0 auto; min-height: 100vh; display: flex; flex-direction: column; }
    .header { background: #f78c8c; color: white; padding: 10px; font-weight: 600; text-align: center; position: sticky; top: 0; z-index: 10; }
    .btn-konfirmasi { background: #f78c8c; color: white; border-radius: 30px; padding: 12px; font-weight: 600; text-align: center; margin: 20px; justify-self: center; display: block; text-decoration: none; }
    .btn-konfirmasi:hover { background: #f56767; color: white; }
    .amount-box { font-size: 22px; font-weight: 700; color: #f78c8c; }
    .timer-box { background: #f78c8c; color: white; text-align: center; padding: 6px; border-radius: 6px; margin: 15px 0; font-weight: 600; }
    .powered { text-align: center; font-size: 12px; color: #888; margin-top: 20px; }
    .powered img { height: 35px; margin-top: 5px; }

    /* Accordion Style */
    .payment-method {
      border: 1px solid #ddd;
      border-radius: 10px;
      margin-bottom: 10px;
      overflow: hidden;
      transition: all 0.3s ease;
    }

    .payment-header {
      padding: 12px;
      cursor: pointer;
      background-color: #fff;
      font-weight: 600;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .payment-body {
      padding: 12px;
      display: none;
      background-color: #f9f9f9;
    }

    .payment-method.active .payment-body {
      display: block;
      animation: slideDown 0.3s ease;
    }

    /* Efek metode terpilih */
    .payment-method.selected {
      border-color: #f78c8c;
      box-shadow: 0 0 5px rgba(247, 140, 140, 0.6);
    }

    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-5px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .btn-copy {
      background: #f78c8c;
      color: #fff;
      border: none;
      border-radius: 20px;
      padding: 6px 14px;
      margin-top: 8px;
      font-size: 14px;
    }
    .btn-copy:hover { background: #f56767; }
  </style>
</head>
<body>
  <div class="wrapper">

    <!-- Header -->
    <div class="header d-flex justify-content-between align-items-center">
      <a href="konfirm_donasi.php" class="text-white"><i class="bi bi-arrow-left"></i></a>
      <span>Pembayaran</span>
      <span></span>
    </div>

    <!-- Content -->
    <div class="content p-3">
      <div class="amount-box mb-2">
        <?php echo "Rp" . number_format($donasiAmount, 0, ',', '.'); ?>
      </div>
      <small class="text-muted">Order ID#<?php echo uniqid('ORD'); ?></small>

      <div class="timer-box">Pilih dalam <span id="countdown">23:59:59</span></div>

      <!-- LOOP: Tampilkan semua metode pembayaran -->
      <?php foreach ($rekeningList as $rek): ?>
        <div class="payment-method" data-id="<?php echo $rek['id']; ?>">
          <div class="payment-header">
            <span><?php echo htmlspecialchars($rek['nama']); ?></span>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="payment-body text-center">
            <p>No. Rekening: <strong><?php echo htmlspecialchars($rek['no_rekening']); ?></strong></p>
            <p><em><?php echo htmlspecialchars($rek['keterangan']); ?></em></p>
            <p><strong>Kategori:</strong> <?php echo htmlspecialchars($rek['kategori_bank']); ?></p>
            <button class="btn-copy" onclick="copyRekening('<?php echo $rek['no_rekening']; ?>')">
              <i class="bi bi-clipboard"></i> Salin Nomor
            </button>
          </div>
        </div>
      <?php endforeach; ?>

      <form id="formMetode" action="succes.php" method="POST">
        <input type="hidden" name="selected_method" id="selected_method">
        <button type="submit" class="btn-konfirmasi w-100">Konfirmasi</button>
      </form>

      <div class="powered">
        Powered by <br>
        <img src="/public/img/midtrans.png" alt="Midtrans">
      </div>
    </div>
  </div>

  <script>
    // Countdown 24 jam
    let countdownEl = document.getElementById("countdown");
    let timeLeft = 24 * 60 * 60;

    function updateCountdown() {
      let hours = Math.floor(timeLeft / 3600);
      let minutes = Math.floor((timeLeft % 3600) / 60);
      let seconds = timeLeft % 60;
      countdownEl.textContent =
        String(hours).padStart(2, "0") + ":" +
        String(minutes).padStart(2, "0") + ":" +
        String(seconds).padStart(2, "0");
      if (timeLeft > 0) timeLeft--;
    }
    setInterval(updateCountdown, 1000);
    updateCountdown();

    // Toggle buka/tutup metode pembayaran + pilih salah satu
    const paymentMethods = document.querySelectorAll(".payment-method");
    const inputSelected = document.getElementById("selected_method");

    paymentMethods.forEach(method => {
      const header = method.querySelector(".payment-header");
      header.addEventListener("click", () => {
        // Tutup semua dan hapus "selected"
        paymentMethods.forEach(m => {
          if (m !== method) m.classList.remove("active", "selected");
        });

        // Toggle buka/tutup detail
        method.classList.toggle("active");

        // Tandai metode ini sebagai terpilih
        method.classList.add("selected");
        inputSelected.value = method.dataset.id;
      });
    });

    // Tombol salin nomor rekening
    function copyRekening(no) {
      navigator.clipboard.writeText(no);
      alert("Nomor rekening " + no + " disalin ke clipboard!");
    }

    // Pastikan user memilih sebelum submit
    document.getElementById("formMetode").addEventListener("submit", function(e) {
      if (!inputSelected.value) {
        e.preventDefault();
        alert("Silakan pilih salah satu metode pembayaran terlebih dahulu.");
      }
    });
  </script>
</body>
</html>
