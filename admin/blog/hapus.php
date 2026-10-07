<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";

$id = $_GET["id"] ?? "";

if (!ctype_digit($id)) {
    header("Location: index.php");
    exit;
}


$sql = "SELECT gambar FROM blog WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "id" => $id
]);

$blog = $stmt->fetch();


if (!$blog) {
    header("Location: index.php");
    exit;
}


// Hapus gambar
$file =
    "../../assets/uploads/blog/" .
    $blog["gambar"];


if (file_exists($file)) {
    unlink($file);
}


// Hapus data database
$sql =
    "DELETE FROM blog WHERE id = :id";

$stmt =
    $pdo->prepare($sql);

$stmt->execute([
    "id" => $id
]);


header(
    "Location: index.php"
);

exit;
