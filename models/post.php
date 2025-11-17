<?php
require_once __DIR__ . '/../config/database.php';

function getPosts() {
    $conn = getDBConnection();
    $sql = "SELECT p.*, u.nama_lengkap AS pembuat
            FROM post p
            JOIN user u ON p.created_by = u.id
            ORDER BY p.id DESC";
    $result = $conn->query($sql);

    $posts = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }
    $conn->close();
    return $posts;
}

// Fungsi untuk mendapatkan detail post berdasarkan ID
function getPostBySlug($slug) {
    $conn = getDBConnection();

    $stmt = $conn->prepare("SELECT p.*, u.nama_lengkap AS pembuat
                            FROM post p
                            JOIN user u ON p.created_by = u.id
                            WHERE p.slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();

    $post = null;
    if ($result && $result->num_rows > 0) {
        $post = $result->fetch_assoc();
    }
    $stmt->close();
    $conn->close();

    return $post;
}