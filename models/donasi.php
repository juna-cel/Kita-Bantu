<?php
require_once __DIR__ . '/../config/database.php';

function getDonasi() {
    $conn = getDBConnection();
    $sql = "SELECT d.*, u.nama_lengkap AS donatur
            FROM donasi d
            JOIN user u ON d.created_by = u.id
            ORDER BY d.id DESC";
    $result = $conn->query($sql);

    $donasi = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $donasi[] = $row;
        }
    }
    $conn->close();
    return $donasi;
}
