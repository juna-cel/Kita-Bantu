<?php

// load semua models
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Kategori.php';
require_once __DIR__ . '/models/Post.php';
require_once __DIR__ . '/models/Donasi.php';
require_once __DIR__ . '/models/Bank.php';

$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH);

// Routing sederhana
switch ($request) {
    case '/':
    case '/index.php':
         require_once __DIR__ . '/modules/login.php';
        break;

    // Modul User
    case '/user':
    case '/user/index':
        require_once __DIR__ . '/modules/user.php';
        break;

    // Modul Kategori
    case '/kategori':
    case '/kategori/index':
        require_once __DIR__ . '/modules/kategori.php';
        break;

    // Modul Post
    case '/post':
    case '/post/index':
        require_once __DIR__ . '/modules/post.php';
        break;

    // Modul Donasi
    case '/donasi':
    case '/donasi/index':
        require_once __DIR__ . '/modules/donasi.php';
        break;

    // Modul Bank
    case '/bank':
    case '/bank/index':
        require_once __DIR__ . '/modules/bank.php';
        break;

    case '/login':
    case '/login/index':
        require_once __DIR__ . '/modules/login.php';
        break;

    case '/proses':
    case '/proses/index':
        require_once __DIR__ . '/modules/proses.php';
        break;

    case '/dashboard':
    case '/dashboard/index':
        require_once __DIR__ . '/modules/dashboard.php';
        break;

    // Detail Post
    case '/detail-post':
    case '/detail-post/index':
        require_once __DIR__ . '/modules/detail_post.php';
        break;


    // Modul Admin    
    case '/admin/dashboard':
    case '/admin/dashboard/index':
        require_once __DIR__ . '/modules/admin/dashboard.php';
        break;
    
    case '/admin/kategori':
    case '/admin/kategori/index':
        require_once __DIR__ . '/modules/admin/kategori.php';
        break;
    

    case '/admin/post':
    case '/admin/post/index':
        require_once __DIR__ . '/modules/admin/post.php';
        break;
        
    case '/admin/donasi':
    case '/admin/donasi/index':
        require_once __DIR__ . '/modules/admin/donasi.php';
        break;
        
    case '/admin/masterbank':
    case '/admin/masterbank/index':
        require_once __DIR__ . '/modules/admin/masterbank.php';
        break;
        
    case '/admin/user':
    case '/admin/user/index':
        require_once __DIR__ . '/modules/admin/user.php';
        break;

    default:
        http_response_code(404);
        echo "<div style='padding:20px; font-family:sans-serif;'>
                <h2>404 - Halaman tidak ditemukan</h2>
                <p>Halaman <b>{$request}</b> tidak tersedia.</p>
              </div>";
        break;
}
