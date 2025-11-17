<?php
if (!function_exists('GetDbConnection')) {
    function GetDbConnection() {
        $host = "localhost";
        $user = "root";
        $pass = "";
        $db   = "kita_bantu";

        $conn = new mysqli($host, $user, $pass, $db);
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        return $conn;
    }
}
?>
