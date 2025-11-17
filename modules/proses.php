<?php
session_start();
require_once __DIR__ . '/../models/User.php';

// Ambil input dari form login
$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Cek user dari database lewat fungsi loginUser()
$user = loginUser($email, $password);

if ($user) {
    // Simpan data user ke session
    $_SESSION['login']   = true;
    $_SESSION['user_id'] = $user['id'];           // simpan id user
    $_SESSION['email']   = $user['email'];        // opsional
    $_SESSION['nama']    = $user['nama_lengkap']; // opsional

    // Arahkan ke halaman riwayat
    header("Location: /modules/riwayat.php");
    exit;
} else {
    // Kalau gagal login
    echo "<script>alert('Email atau password salah!'); window.location.href='login.php';</script>";
    exit;
}
?>
