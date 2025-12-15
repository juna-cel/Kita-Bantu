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

    case '/register':
    case '/register/index':
        require_once __DIR__ . '/modules/register.php';
        break;

    case '/proses':
    case '/proses/index':
        require_once __DIR__ . '/modules/proses.php';
        break;

    case '/dashboard':
    case '/dashboard/index':
        require_once __DIR__ . '/middleware/auth.php';
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
        $requiredPermission = 'dashboard_view';
        require_once __DIR__ . '/middleware/auth.php';
        require_once __DIR__ . '/middleware/permission.php';
        require_once __DIR__ . '/modules/admin/dashboard.php';
        break;
    
    case '/admin/kategori':
    case '/admin/kategori/index':
        $requiredPermission = 'list_category';
        require_once __DIR__ . '/middleware/auth.php';
        require_once __DIR__ . '/middleware/permission.php';
        require_once __DIR__ . '/modules/admin/kategori.php';
        break;
    

    case '/admin/post':
    case '/admin/post/index':
        $requiredPermission = 'post_view';
        require_once __DIR__ . '/middleware/auth.php';
        require_once __DIR__ . '/middleware/permission.php';
        require_once __DIR__ . '/modules/admin/post.php';
        break;
        
    case '/admin/donasi':
    case '/admin/donasi/index':
        $requiredPermission = 'donation_view';
        require_once __DIR__ . '/middleware/auth.php';
        require_once __DIR__ . '/middleware/permission.php';
        require_once __DIR__ . '/modules/admin/donasi.php';
        break;
    
    
        case '/admin/masterbank':
        case '/admin/masterbank/index':
            $requiredPermission = 'bank_view';
            require_once __DIR__ . '/middleware/auth.php';
            require_once __DIR__ . '/middleware/permission.php';
            require_once __DIR__ . '/modules/admin/masterbank.php';
            break;

        case '/admin/user':
        case '/admin/user/index':
            $requiredPermission = 'user_view';  // atau permission yg kamu buat
            require_once __DIR__ . '/middleware/auth.php';
            require_once __DIR__ . '/middleware/permission.php';
            require_once __DIR__ . '/modules/admin/user.php';
            break;

    
        
  case '/admin/role-permission':
case '/admin/role-permission/index':
    require_once __DIR__ . '/modules/admin/role_permission.php';
    break;

    case '/admin/role-permission':
    case '/admin/role-permission/index':
        require_once __DIR__ . '/modules/admin/role_permission.php';
        break;

    case '/admin/role-show':
    case '/admin/role-show/index':
        require_once __DIR__ . '/modules/admin/show.php';
        break;

    case '/admin/role-permission-process':
        require_once __DIR__ . '/modules/admin/role_permission_process.php';
        break;    

    

 
        // LOGOUT
case '/logout':
case '/logout/index':
    require_once __DIR__ . '/modules/logout.php';
    break;

    default:
        http_response_code(404);
        echo "<div style='padding:20px; font-family:sans-serif;'>
                <h2>404 - Halaman tidak ditemukan</h2>
               
              </div>";
        break;
} 
