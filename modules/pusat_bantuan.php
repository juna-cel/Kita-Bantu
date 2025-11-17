<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pusat Bantuan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    /* Batasi lebar agar di desktop tampil seperti mobile */
    .app-wrapper {
      max-width: 420px;
      width: 100%;
      margin: auto;
      background: #fff;
      min-height: 100vh;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .header {
      background:#f48c8c; 
      padding:12px; 
      text-align:left; 
      max-width:420px; 
      width: 100%;
      margin:auto;
    }
    .header a{
      color:#fff; 
      text-decoration:none; 
      font-weight:600;
    }
    
    .list-group-item {
      border: none;
      border-bottom: 1px solid #eee;
      font-size: 14px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .list-group-item strong {
      font-weight: 600;
    }
    .list-group-item small {
      color: #666;
      display: block;
      margin-top: 4px;
    }
  </style>
</head>
<body>

  <div class="app-wrapper">
    <!-- Header -->
    <div class="header">
      <a href="/modules/akun.php">&larr; Pusat Bantuan</a>
    </div>

    <!-- Content -->
    <div class="container my-3">
      <h5 class="fw-bold mb-3">Hi! ada yang bisa kami bantu?</h5>

      <ul class="list-group">
        <li class="list-group-item">
          <span>Apa yang harus dilakukan ketika lupa kata sandi</span>
          <i class="bi bi-chevron-right"></i>
        </li>
        <li class="list-group-item">
          <span>Apakah donasi saya bisa di refund</span>
          <i class="bi bi-chevron-right"></i>
        </li>
        <li class="list-group-item">
          <span>Apakah saya wajib konfirmasi setelah transfer donasi</span>
          <i class="bi bi-chevron-right"></i>
        </li>
        <li class="list-group-item">
          <div>
            <strong>Apakah donasi saya tersalurkan 100% ?</strong>
            <small>Ya donasi anda akan disalurkan 100%</small>
          </div>
          <i class="bi bi-chevron-right"></i>
        </li>
      </ul>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>