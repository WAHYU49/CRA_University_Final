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


$sql = "SELECT * FROM blog WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "id" => $id
]);

$blog = $stmt->fetch();


if (!$blog) {
    header("Location: index.php");
    exit;
}


$error = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $judul = trim($_POST["judul"] ?? "");
    $isi = trim($_POST["isi"] ?? "");


    if ($judul === "") {

        $error = "Judul blog wajib diisi.";
    } elseif ($isi === "") {

        $error = "Isi blog wajib diisi.";
    } else {

        $filename =
            $blog["gambar"];


        // Jika admin memilih gambar baru
        if (
            isset($_FILES["gambar"]) &&
            $_FILES["gambar"]["error"] === UPLOAD_ERR_OK
        ) {

            $gambar =
                $_FILES["gambar"];


            $maxSize =
                5 * 1024 * 1024;


            if ($gambar["size"] > $maxSize) {

                $error =
                    "Ukuran gambar maksimal 5 MB.";
            } else {

                $imageInfo =
                    getimagesize(
                        $gambar["tmp_name"]
                    );


                if ($imageInfo === false) {

                    $error =
                        "File yang diupload bukan gambar.";
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


                        $newFilename =
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
                                $uploadDir .
                                    $newFilename
                            )
                        ) {

                            // Hapus gambar lama
                            $oldFile =
                                $uploadDir .
                                $blog["gambar"];


                            if (
                                file_exists($oldFile)
                            ) {

                                unlink($oldFile);
                            }


                            $filename =
                                $newFilename;
                        } else {

                            $error =
                                "Gagal mengupload gambar.";
                        }
                    }
                }
            }
        }


        if ($error === "") {

            $sql = "
                UPDATE blog
                SET
                    judul = :judul,
                    gambar = :gambar,
                    isi = :isi
                WHERE id = :id
            ";


            $stmt =
                $pdo->prepare($sql);


            $stmt->execute([
                "judul" => $judul,
                "gambar" => $filename,
                "isi" => $isi,
                "id" => $id
            ]);


            header(
                "Location: index.php"
            );

            exit;
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

    <title>Edit Blog</title>

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

        .current-image {
            width: 200px;
            height: 130px;
            object-fit: cover;
            border-radius: 5px;
            margin-top: 10px;
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

            <h1>Edit Blog</h1>


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
                    value="<?= htmlspecialchars($blog["judul"]) ?>"
                    required>


                <label>
                    Gambar Saat Ini
                </label>

                <img
                    src="../../assets/uploads/blog/<?= htmlspecialchars($blog["gambar"]) ?>"
                    alt="<?= htmlspecialchars($blog["judul"]) ?>"
                    class="current-image">


                <label for="gambar">
                    Ganti Gambar
                </label>

                <input
                    type="file"
                    id="gambar"
                    name="gambar"
                    accept="image/jpeg,image/png,image/webp">


                <label for="isi">
                    Isi Blog
                </label>

                <textarea
                    id="isi"
                    name="isi"
                    required><?= htmlspecialchars($blog["isi"]) ?></textarea>


                <a
                    href="index.php"
                    class="back">
                    Kembali
                </a>

                <button type="submit">
                    Simpan Perubahan
                </button>

            </form>

        </div>

    </div>

</body>

</html>