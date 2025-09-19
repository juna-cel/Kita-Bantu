<?php
require_once __DIR__ . '/../config/database.php';

// Create User
function createUser($nama_lengkap, $email, $password) {
    global $pdo; // Menggunakan PDO dari config/database.php

    try {
        $nama_lengkap = htmlspecialchars(trim($nama_lengkap));
        $email = htmlspecialchars(trim($email));
        $password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO user (nama_lengkap, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$nama_lengkap, $email, $password]);

        return $result;
    } catch (PDOException $e) {
        error_log("Create user error: " . $e->getMessage());
        return false;
    }
}

// Read Users
    function getUsers() {
        $conn = getDBConnection();

        $users = [];
        if ($conn) {
            $sql = "SELECT id, nama_lengkap, email, created_at FROM user ORDER BY id DESC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
                $result->free();
            }

            $conn->close();
        }

        return $users;
    }

// Login User
/**
 * Cek login user berdasarkan email + password.
 * Mengembalikan array user (assoc) jika sukses, atau false jika gagal.
 *
 * Asumsi: getDBConnection() mengembalikan mysqli connection.
 * Password di DB harus disimpan dengan password_hash().
 */
function loginUser($email, $password) {
    $conn = getDBConnection();
    if (!$conn) {
        error_log("DB connection failed");
        return false;
    }

    $sql = "SELECT id, nama_lengkap, email, password FROM user WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            return $user;
        }
    }

    return false;
}


?>