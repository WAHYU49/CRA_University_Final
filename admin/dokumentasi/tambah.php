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

    if ($judul === "") {

        $error = "Judul wajib diisi.";
    } elseif (
        !isset($_FILES["gambar"]) ||
        $_FILES["gambar"]["error"] !== UPLOAD_ERR_OK
    ) {

        $error = "Silakan pilih gambar.";
    } else {

        $file = $_FILES["gambar"];

        $allowedTypes = [
            "image/jpeg",
            "image/png",
            "image/webp"
        ];

        $imageInfo = getimagesize($file["tmp_name"]);

        if (!$imageInfo) {

            $error = "File yang dipilih bukan gambar.";
        } elseif (
            !in_array(
                $imageInfo["mime"],
                $allowedTypes,
                true
            )
        ) {

            $error = "Format gambar harus JPG, PNG, atau WEBP.";
        } elseif ($file["size"] > 5 * 1024 * 1024) {

            $error = "Ukuran gambar maksimal 5 MB.";
        } else {

            $extension = strtolower(
                pathinfo(
                    $file["name"],
                    PATHINFO_EXTENSION
                )
            );

            $filename =
                uniqid("dokumentasi_", true)
                . "."
                . $extension;

            $uploadDir =
                "../../assets/uploads/dokumentasi/";

            $uploadPath =
                $uploadDir . $filename;

            if (
                move_uploaded_file(
                    $file["tmp_name"],
                    $uploadPath
                )
            ) {

                $sql = "
                    INSERT INTO dokumentasi
                    (judul, gambar)
                    VALUES
                    (:judul, :gambar)
                ";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    "judul" => $judul,
                    "gambar" => $filename
                ]);

                header("Location: index.php");
                exit;
            } else {

                $error =
                    "Gagal mengupload gambar.";
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

    <title>Tambah Dokumentasi</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f7f3eb;
        }

        .container {
            width: 90%;
            max-width: 600px;

            margin: 50px auto;
        }

        .box {
            background: white;

            padding: 35px;

            border-radius: 10px;

            box-shadow:
                0 5px 20px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-bottom: 25px;
            color: #151515;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;

            margin-bottom: 8px;

            font-weight: bold;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;

            padding: 12px;

            border: 1px solid #ddd;

            border-radius: 6px;
        }

        .error {
            background: #f8d7da;

            color: #842029;

            padding: 12px;

            border-radius: 6px;

            margin-bottom: 20px;
        }

        .buttons {
            display: flex;

            gap: 10px;

            margin-top: 25px;
        }

        button,
        .cancel {
            padding: 12px 18px;

            border-radius: 6px;

            border: none;

            text-decoration: none;

            cursor: pointer;

            font-size: 14px;
        }

        button {
            background: #c89b3c;
            color: white;
        }

        button:hover {
            background: #9f7625;
        }

        .cancel {
            background: #555;
            color: white;
        }

        small {
            display: block;

            margin-top: 8px;

            color: #777;
        }
    </style>

</head>

<body>

    <div class="container">

        <div class="box">

            <h1>
                Tambah Dokumentasi
            </h1>

            <?php if ($error): ?>

                <div class="error">

                    <?= htmlspecialchars($error) ?>

                </div>

            <?php endif; ?>

            <form
                method="POST"
                enctype="multipart/form-data">

                <div class="form-group">

                    <label for="judul">
                        Judul Dokumentasi
                    </label>

                    <input
                        type="text"
                        id="judul"
                        name="judul"
                        placeholder="Contoh: Business Summit 2025"
                        required>

                </div>

                <div class="form-group">

                    <label for="gambar">
                        Gambar
                    </label>

                    <input
                        type="file"
                        id="gambar"
                        name="gambar"
                        accept=".jpg,.jpeg,.png,.webp"
                        required>

                    <small>
                        Format JPG, PNG, WEBP. Maksimal 5 MB.
                    </small>

                </div>

                <div class="buttons">

                    <button type="submit">
                        Simpan
                    </button>

                    <a
                        href="index.php"
                        class="cancel">
                        Batal
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>

</html>