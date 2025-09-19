<?php
require_once __DIR__ . '/../config/database.php';

function getKategori() {
    $conn = getDBConnection();
    $sql = "SELECT * FROM kategori ORDER BY id DESC";
    $result = $conn->query($sql);

    $kategori = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $kategori[] = $row;
        }
    }
    $conn->close();
    return $kategori;
}
