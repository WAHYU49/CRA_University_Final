<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "../admin/config/database.php";

try {

    $sql = "SELECT id, judul, gambar, created_at
            FROM dokumentasi
            ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $data = $stmt->fetchAll();

    echo json_encode([
        "success" => true,
        "data" => $data
    ]);
} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Gagal mengambil data dokumentasi"
    ]);
}
