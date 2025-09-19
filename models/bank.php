<?php
require_once __DIR__ . '/../config/database.php';

function getBank() {
    $conn = getDBConnection();
    $sql = "SELECT * FROM master_bank ORDER BY id DESC";
    $result = $conn->query($sql);

    $bank = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bank[] = $row;
        }
    }
    $conn->close();
    return $bank;
}
