<?php

session_start();

if (!isset($_SESSION["admin_id"])) {

    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";

$id = $_GET["id"] ?? null;

if (!$id || !is_numeric($id)) {

    header("Location: index.php");
    exit;
}

/*
 * Ambil data gambar
 */
$sql = "
    SELECT gambar
    FROM dokumentasi
    WHERE id = :id
    LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "id" => $id
]);

$dokumentasi = $stmt->fetch();

if ($dokumentasi) {

    /*
     * Hapus file gambar
     */
    $gambarPath =
        "../../assets/uploads/dokumentasi/"
        . $dokumentasi["gambar"];

    if (file_exists($gambarPath)) {

        unlink($gambarPath);
    }

    /*
     * Hapus data dari database
     */
    $sql = "
        DELETE FROM dokumentasi
        WHERE id = :id
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "id" => $id
    ]);
}

header("Location: index.php");

exit;
