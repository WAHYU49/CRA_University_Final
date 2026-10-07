<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";

$sql = "SELECT * FROM blog ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$blogs = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Kelola Blog - Admin</title>

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
            max-width: 1100px;
            margin: 40px auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        h1 {
            margin: 0;
        }

        .button {
            display: inline-block;
            padding: 10px 16px;
            background: #c89b3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .button:hover {
            opacity: 0.85;
        }

        .back {
            background: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: #222;
            color: white;
        }

        .thumbnail {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }

        .action {
            display: inline-block;
            padding: 7px 10px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
        }

        .edit {
            background: #2980b9;
        }

        .delete {
            background: #c0392b;
        }

        .empty {
            text-align: center;
            padding: 30px;
        }
    </style>

</head>

<body>

    <div class="container">

        <div class="header">

            <h1>Kelola Blog</h1>

            <div>

                <a
                    href="../dashboard.php"
                    class="button back">
                    Dashboard
                </a>

                <a
                    href="tambah.php"
                    class="button">
                    + Tambah Blog
                </a>

            </div>

        </div>


        <table>

            <thead>

                <tr>

                    <th>No</th>

                    <th>Gambar</th>

                    <th>Judul</th>

                    <th>Tanggal</th>

                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                <?php if (count($blogs) > 0): ?>

                    <?php foreach ($blogs as $index => $blog): ?>

                        <tr>

                            <td>
                                <?= $index + 1 ?>
                            </td>

                            <td>

                                <img
                                    src="../../assets/uploads/blog/<?= htmlspecialchars($blog["gambar"]) ?>"
                                    alt="<?= htmlspecialchars($blog["judul"]) ?>"
                                    class="thumbnail">

                            </td>

                            <td>
                                <?= htmlspecialchars($blog["judul"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($blog["created_at"]) ?>
                            </td>

                            <td>

                                <a
                                    href="edit.php?id=<?= $blog["id"] ?>"
                                    class="action edit">
                                    Edit
                                </a>

                                <a
                                    href="hapus.php?id=<?= $blog["id"] ?>"
                                    class="action delete"
                                    onclick="return confirm('Yakin ingin menghapus blog ini?')">
                                    Hapus
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td
                            colspan="5"
                            class="empty">
                            Belum ada data blog.
                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</body>

</html>