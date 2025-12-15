<?php
require_once __DIR__ . '/../../config/database.php';
$conn = GetDbConnection();

$role_id = $_POST['role_id'];
$permissions = $_POST['permissions'] ?? [];

// Hapus permission lama
$conn->query("DELETE FROM role_permission WHERE role_id='$role_id'");

// Simpan yang baru
foreach ($permissions as $pid) {
    $conn->query("
        INSERT INTO role_permission (role_id, permission_id)
        VALUES ('$role_id', '$pid')
    ");
}

header("Location: /admin/role-permission");
exit;
