<?php

session_start();

if (!isset($_SESSION["admin_id"])) {

    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - CRA University</title>

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

        .navbar {
            background: #151515;
            color: white;

            padding: 18px 5%;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
        }

        .logo span {
            color: #c89b3c;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logout {
            color: white;
            text-decoration: none;

            background: #b33a3a;

            padding: 8px 14px;
            border-radius: 5px;

            font-size: 14px;
        }

        .container {
            width: 90%;
            max-width: 1100px;

            margin: 40px auto;
        }

        .welcome {
            margin-bottom: 30px;
        }

        .welcome h1 {
            margin-bottom: 8px;
            color: #151515;
        }

        .welcome p {
            color: #777;
        }

        .menu-grid {
            display: grid;

            grid-template-columns:
                repeat(auto-fit, minmax(250px, 1fr));

            gap: 20px;
        }

        .menu-card {
            background: white;

            padding: 30px;

            border-radius: 10px;

            box-shadow:
                0 5px 20px rgba(0, 0, 0, 0.08);

            text-decoration: none;

            color: #333;

            transition: 0.3s;
        }

        .menu-card:hover {
            transform: translateY(-4px);
        }

        .menu-card h2 {
            margin-bottom: 10px;
            color: #151515;
        }

        .menu-card p {
            color: #777;
            line-height: 1.6;
        }

        .icon {
            font-size: 35px;
            margin-bottom: 15px;
        }
    </style>

</head>

<body>

    <header class="navbar">

        <div class="logo">

            CRA <span>UNIVERSITY</span>

        </div>

        <div class="admin-info">

            <span>
                Admin:
                <?= htmlspecialchars($_SESSION["admin_username"]) ?>
            </span>

            <a
                href="logout.php"
                class="logout">
                Logout
            </a>

        </div>

    </header>

    <main class="container">

        <div class="welcome">

            <h1>
                Dashboard Admin
            </h1>

            <p>
                Kelola konten website CRA University.
            </p>

        </div>

        <div class="menu-grid">

            <a
                href="dokumentasi/index.php"
                class="menu-card">

                <div class="icon">
                    📷
                </div>

                <h2>
                    Dokumentasi
                </h2>

                <p>
                    Tambah, lihat, edit, dan hapus
                    dokumentasi website.
                </p>

            </a>


            <a
                href="blog/index.php"
                class="menu-card">

                <div class="icon">
                    📝
                </div>

                <h2>
                    Blog
                </h2>

                <p>
                    Tambah, lihat, edit, dan hapus
                    artikel blog website.
                </p>

            </a>

        </div>

    </main>

</body>

</html>