<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "../admin/config/database.php";

try {

    $sql = "SELECT
                id,
                judul,
                gambar,
                isi,
                created_at
            FROM blog
            ORDER BY created_at DESC, id DESC";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $data = $stmt->fetchAll();

    echo json_encode(
        [
            "success" => true,
            "data" => $data
        ],
        JSON_UNESCAPED_UNICODE
    );
} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode(
        [
            "success" => false,
            "message" => "Gagal mengambil data blog"
        ],
        JSON_UNESCAPED_UNICODE
    );
}
