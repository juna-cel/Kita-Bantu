<?php
require_once __DIR__ . '/../models/Donasi.php'; // Baris ini tidak diperlukan untuk tampilan formulir, uncomment jika Anda memerlukannya.
?>
<!DOCTYPE html> 
<html lang="id"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Donasi - KitaBantu</title> 
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
        .donasi-option { 
            border: 1px solid #ddd; 
            border-radius: 12px; 
            padding: 10px; 
            margin-bottom: 10px; 
            display: flex; 
            align-items: center; 
            cursor: pointer; 
            transition: all 0.2s ease; 
        } 
        .donasi-option:hover { 
            border-color: #f78c8c; 
            background: #fff7f7; 
        } 
        .donasi-option i { 
            color: #f78c8c; 
            margin-right: 10px; 
            font-size: 20px; 
        } 
        .donasi-option.active { 
            border-color: #f78c8c; 
            background: #ffecec; 
        } 
        .donasi-display { 
            border-radius: 12px; 
            border: 1px solid #ddd; 
            padding: 12px; 
            width: 100%; 
            text-align: center; 
            font-weight: 600; 
            background: #f5f5f5; 
            margin: 15px 0; 
        } 
        .btn-donasi { 
            background: #f78c8c; 
            color: white; 
            border-radius: 30px; 
            padding: 12px; 
            font-weight: 600; 
            text-align: center; 
            margin-top: auto; 
            display: block; 
            text-decoration: none; 
        } 
        .btn-donasi:hover { 
            background: #f56767; 
            color: white; 
        } 
        input[type="number"] { 
            width: 100%; 
            text-align: center; 
            border: 1px solid #ddd; 
            border-radius: 12px; 
            padding: 12px; 
            font-weight: 600; 
            margin-top: 10px; 
        } 
    </style> 
</head> 
<body> 
    <div class="wrapper"> 
        <div class="header d-flex justify-content-between align-items-center"> 
            <a href="cerita.php" class="text-white"><i class="bi bi-arrow-left"></i></a> 
            <span>Donasi</span> 
            <span></span> 
        </div> 


        <div class="content p-3"> 
            <h6 class="fw-bold mb-3">Berapa Donasi Yang Akan Kamu Berikan?</h6> 
            
            <div class="donasi-option" data-value="30000"><i class="bi bi-heart-fill"></i> Rp30.000</div> 
            <div class="donasi-option" data-value="50000"><i class="bi bi-heart-fill"></i> Rp50.000</div> 
            <div class="donasi-option" data-value="80000"><i class="bi bi-heart-fill"></i> Rp80.000</div> 
            <div class="donasi-option" data-value="100000"><i class="bi bi-heart-fill"></i> Rp100.000</div> 
            
            <p class="text-center text-muted small mt-3">Atau Masukkan Jumlah Yang Diinginkan</p> 
            
            <input type="number" id="manualInput" placeholder="Masukkan nominal"> 
            
            <div id="donasiDisplay" class="donasi-display">Rp0</div> 
        </div> 


        <div class="p-3"> 
            <a href="#" class="btn-donasi" id="kirimDonasi"> 
                <i class="bi bi-box-arrow-in-up-right me-1"></i> Kirim Donasi 
            </a> 
        </div> 
    </div> 


    <script> 
        const options = document.querySelectorAll(".donasi-option"); 
        const display = document.getElementById("donasiDisplay"); 
        const manualInput = document.getElementById("manualInput"); 


        // Fungsi format ke Rupiah 
        function formatRupiah(angka) { 
            // Ubah input menjadi string dan hapus non-digit
            let value = String(angka).replace(/[^0-9]/g, ''); 
            if (value === "") return "Rp0";
            
            // Format ke Rupiah
            return "Rp" + value.replace(/\B(?=(\d{3})+(?!\d))/g, "."); 
        } 


        // Klik preset 
        options.forEach(option => { 
            option.addEventListener("click", () => { 
                options.forEach(opt => opt.classList.remove("active")); 
                option.classList.add("active"); 
                let value = option.dataset.value; 
                display.textContent = formatRupiah(value); 
                manualInput.value = ""; // reset manual 
            }); 
        }); 


        // Input manual 
        manualInput.addEventListener("input", () => { 
            options.forEach(opt => opt.classList.remove("active")); // reset preset 
            let value = manualInput.value; 
            if (value) { 
                display.textContent = formatRupiah(value); 
            } else { 
                display.textContent = "Rp0"; 
            } 
        }); 


        // Tombol kirim donasi simpan ke localStorage dan alihkan
        const kirimDonasiBtn = document.getElementById("kirimDonasi"); 
        kirimDonasiBtn.addEventListener("click", function (e) { 
            e.preventDefault(); 
            
            // Ambil nominal, hilangkan "Rp" dan tanda titik
            let nominal = display.textContent.replace(/[^0-9]/g, ""); 
            
            if (!nominal || nominal === "0") { 
                alert("Silakan pilih atau masukkan nominal donasi"); 
                return; 
            } 
            
            // Simpan ke localStorage (seperti file HTML asli)
            localStorage.setItem("donasiAmount", nominal); 
            
            // Alihkan ke halaman konfirmasi
            window.location.href = "konfirm_donasi.php"; // Diubah dari .html ke .php
        }); 
    </script> 
</body> 
</html>
<?php
// require_once __DIR__ . '/../includes/footer.php'; // Uncomment jika Anda ingin menyertakan footer