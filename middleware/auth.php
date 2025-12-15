<?php
 if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
function hasPermission($permName)
{
    // Pastikan session aktif
   

    // Jika user belum login → tidak punya permission
    if (!isset($_SESSION['role_id'])) {
        return false;
    }

    $role_id = $_SESSION['role_id'];

    // Gunakan koneksi global agar tidak load ulang database tiap panggil fungsi
    global $conn;
    if (!$conn) {
        require __DIR__ . '/../config/database.php';
        $conn = GetDbConnection();
    }

    // Cek permission berdasarkan role
    $sql = "
        SELECT COUNT(*) AS total
        FROM role_permission rp
        JOIN permissions p ON rp.permission_id = p.id
        WHERE rp.role_id = ? 
        AND p.name = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $role_id, $permName);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return ($row['total'] > 0);
}
