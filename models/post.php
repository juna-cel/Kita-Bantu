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
