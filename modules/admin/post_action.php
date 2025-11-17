<?php
include_once __DIR__ . '/../../config/database.php';

// Folder upload
$uploadDir = __DIR__ . '/../../uploads/posts/';
if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

header('Content-Type: application/json');

// CREATE POST (AJAX)
if (isset($_POST['action']) && $_POST['action'] === 'create') {
    $conn = getDBConnection();

    $judul = $conn->real_escape_string($_POST['judul']);
    $nominal = floatval($_POST['nominal']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $tanggal = $_POST['tanggal'];
    $status = $_POST['status'];
    $created_by = 1;
    $foto_path = '';

    if (!empty($_FILES['foto']['name'])) {
        $foto_file = time() . '_' . basename($_FILES['foto']['name']);
        $targetFile = $uploadDir . $foto_file;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
            $foto_path = 'uploads/posts/' . $foto_file;
        }
    }

    $conn->query("INSERT INTO post (judul, nominal, deskripsi, foto, tanggal, status, created_by)
                  VALUES ('$judul','$nominal','$deskripsi','$foto_path','$tanggal','$status','$created_by')");
    $insert_id = $conn->insert_id;

    $newPost = $conn->query("SELECT * FROM post WHERE id='$insert_id'")->fetch_assoc();
    $conn->close();

    echo json_encode(['success' => true, 'data' => $newPost]);
    exit;
}

// DELETE POST (AJAX)
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['id']);
    $conn = getDBConnection();

    $row = $conn->query("SELECT foto FROM post WHERE id='$id'")->fetch_assoc();
    if ($row && $row['foto'] && file_exists(__DIR__ . '/../../' . $row['foto'])) {
        unlink(__DIR__ . '/../../' . $row['foto']);
    }

    $conn->query("DELETE FROM post WHERE id='$id'");
    $conn->close();

    echo json_encode(['success' => true]);
    exit;
}
?>
