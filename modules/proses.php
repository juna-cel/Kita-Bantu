<?php
session_start();
require_once __DIR__ . '/../models/User.php';

$email    = $_POST['email'];
$password = $_POST['password'];

$user = loginUser($email, $password);

if ($user) {
    $_SESSION['login']   = true;

    header("Location: /dashboard");
    exit;
} else {
    echo "Email atau password salah!";
}