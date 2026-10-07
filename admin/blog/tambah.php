<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $judul = trim($_POST["judul"] ?? "");
    $isi = trim($_POST["isi"] ?? "");

    if ($judul === "") {

        $error = "Judul blog wajib diisi.";
    } elseif ($isi === "") {

        $error = "Isi blog wajib diisi.";
    } elseif (
        !isset($_FILES["gambar"]) ||
        $_FILES["gambar"]["error"] !== UPLOAD_ERR_OK
    ) {

        $error = "Gambar wajib diupload.";
    } else {

        $gambar = $_FILES["gambar"];

        $maxSize = 5 * 1024 * 1024;

        if ($gambar["size"] > $maxSize) {

            $error = "Ukuran gambar maksimal 5 MB.";
        } else {

            $imageInfo =
                getimagesize($gambar["tmp_name"]);

            if ($imageInfo === false) {

                $error = "File yang diupload bukan gambar.";
            } else {

                $allowedTypes = [
                    "image/jpeg",
                    "image/png",
                    "image/webp"
                ];

                if (
                    !in_array(
                        $imageInfo["mime"],
                        $allowedTypes
                    )
                ) {

                    $error =
                        "Format gambar harus JPG, PNG, atau WEBP.";
                } else {

                    $extension =
                        strtolower(
                            pathinfo(
                                $gambar["name"],
                                PATHINFO_EXTENSION
                            )
                        );


                    $filename =
                        uniqid(
                            "blog_",
                            true
                        ) .
                        "." .
                        $extension;


                    $uploadDir =
                        "../../assets/uploads/blog/";


                    if (
                        move_uploaded_file(
                            $gambar["tmp_name"],
                            $uploadDir . $filename
                        )
                    ) {

                        $sql = "
                            INSERT INTO blog
                            (judul, gambar, isi)
                            VALUES
                            (:judul, :gambar, :isi)
                        ";

                        $stmt =
                            $pdo->prepare($sql);


                        $stmt->execute([
                            "judul" => $judul,
                            "gambar" => $filename,
                            "isi" => $isi
                        ]);


                        header(
                            "Location: index.php"
                        );

                        exit;
                    } else {

                        $error =
                            "Gagal mengupload gambar.";
                    }
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Tambah Blog</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 8px;
        }

        h1 {
            margin-top: 0;
        }

        label {
            display: block;
            margin-top: 20px;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-family: inherit;
        }

        textarea {
            min-height: 250px;
            resize: vertical;
        }

        input[type="file"] {
            padding: 8px;
        }

        button,
        .back {
            display: inline-block;
            margin-top: 20px;
            padding: 11px 18px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        button {
            background: #c89b3c;
            color: white;
        }

        .back {
            background: #555;
            color: white;
            margin-right: 8px;
        }

        .error {
            background: #f8d7da;
            color: #842029;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>

</head>

<body>

    <div class="container">

        <div class="box">

            <h1>Tambah Blog</h1>

            <?php if ($error !== ""): ?>

                <div class="error">
                    <?= htmlspecialchars($error) ?>
                </div>

            <?php endif; ?>


            <form
                method="POST"
                enctype="multipart/form-data">

                <label for="judul">
                    Judul Blog
                </label>

                <input
                    type="text"
                    id="judul"
                    name="judul"
                    required>


                <label for="gambar">
                    Gambar
                </label>

                <input
                    type="file"
                    id="gambar"
                    name="gambar"
                    accept="image/jpeg,image/png,image/webp"
                    required>


                <label for="isi">
                    Isi Blog
                </label>

                <textarea
                    id="isi"
                    name="isi"
                    required></textarea>


                <a
                    href="index.php"
                    class="back">
                    Kembali
                </a>

                <button type="submit">
                    Simpan Blog
                </button>

            </form>

        </div>

    </div>

</body>

</html>