<?php

session_start();

if (!isset($_SESSION["admin_id"])) {

    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";

$sql = "
    SELECT *
    FROM dokumentasi
    ORDER BY id DESC
";

$stmt = $pdo->query($sql);

$dokumentasi = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Dokumentasi - Admin CRA University</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f7f3eb;
            color: #333;
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

            margin-bottom: 30px;

            gap: 20px;
        }

        .header h1 {
            color: #151515;
            margin-bottom: 5px;
        }

        .header p {
            color: #777;
        }

        .buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            display: inline-block;

            padding: 11px 18px;

            border-radius: 6px;

            text-decoration: none;

            color: white;

            font-size: 14px;
        }

        .btn-add {
            background: #c89b3c;
        }

        .btn-add:hover {
            background: #9f7625;
        }

        .btn-back {
            background: #555;
        }

        .table-box {
            background: white;

            border-radius: 10px;

            overflow: hidden;

            box-shadow:
                0 5px 20px rgba(0, 0, 0, 0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 15px;

            text-align: left;

            border-bottom: 1px solid #eee;
        }

        th {
            background: #151515;
            color: white;
        }

        .thumbnail {
            width: 110px;
            height: 75px;

            object-fit: cover;

            border-radius: 6px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-edit,
        .btn-delete {
            display: inline-block;

            padding: 7px 12px;

            color: white;

            text-decoration: none;

            border-radius: 5px;

            font-size: 13px;
        }

        .btn-edit {
            background: #c89b3c;
        }

        .btn-delete {
            background: #b33a3a;
        }

        .btn-edit:hover,
        .btn-delete:hover {
            opacity: 0.8;
        }

        .empty {
            text-align: center;

            padding: 50px;

            color: #777;
        }

        @media (max-width: 700px) {

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .table-box {
                overflow-x: auto;
            }

            table {
                min-width: 700px;
            }

        }
    </style>

</head>

<body>

    <div class="container">

        <div class="header">

            <div>

                <h1>
                    Dokumentasi
                </h1>

                <p>
                    Kelola dokumentasi CRA University
                </p>

            </div>

            <div class="buttons">

                <a
                    href="tambah.php"
                    class="btn btn-add">
                    + Tambah Dokumentasi
                </a>

                <a
                    href="../dashboard.php"
                    class="btn btn-back">
                    Dashboard
                </a>

            </div>

        </div>

        <div class="table-box">

            <?php if (count($dokumentasi) > 0): ?>

                <table>

                    <thead>

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                Gambar
                            </th>

                            <th>
                                Judul
                            </th>

                            <th>
                                Tanggal
                            </th>

                            <th>
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach (
                            $dokumentasi as $index => $item
                        ): ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>

                                <td>

                                    <img
                                        src="../../assets/uploads/dokumentasi/<?= htmlspecialchars($item["gambar"]) ?>"
                                        alt="<?= htmlspecialchars($item["judul"]) ?>"
                                        class="thumbnail">

                                </td>

                                <td>
                                    <?= htmlspecialchars($item["judul"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item["created_at"]) ?>
                                </td>

                                <td>

                                    <div class="action-buttons">

                                        <a
                                            href="edit.php?id=<?= $item["id"] ?>"
                                            class="btn-edit">
                                            Edit
                                        </a>

                                        <a
                                            href="hapus.php?id=<?= $item["id"] ?>"
                                            class="btn-delete"
                                            onclick="return confirm('Yakin ingin menghapus dokumentasi ini?')">
                                            Hapus
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <div class="empty">

                    <p>
                        Belum ada dokumentasi.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>

</body>

</html>