<?php

session_start();

require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {

        $error = "Username dan password wajib diisi.";
    } else {

        $sql = "
            SELECT *
            FROM users
            WHERE username = :username
            LIMIT 1
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            "username" => $username
        ]);

        $user = $stmt->fetch();

        if (
            $user &&
            password_verify($password, $user["password"])
        ) {

            $_SESSION["admin_id"] = $user["id"];
            $_SESSION["admin_username"] = $user["username"];

            header("Location: dashboard.php");
            exit;
        } else {

            $error = "Username atau password salah.";
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

    <title>Login Admin - CRA University</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f7f3eb;

            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-box {
            background: #ffffff;
            padding: 40px;

            border-radius: 12px;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo h1 {
            font-size: 24px;
            color: #151515;
        }

        .logo span {
            color: #c89b3c;
        }

        .logo p {
            margin-top: 8px;
            color: #777;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;

            color: #333;
            font-size: 14px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 13px 14px;

            border: 1px solid #ddd;
            border-radius: 6px;

            font-size: 15px;
            outline: none;
        }

        .form-group input:focus {
            border-color: #c89b3c;
        }

        .error {
            background: #f8d7da;
            color: #842029;

            padding: 12px;
            border-radius: 6px;

            margin-bottom: 20px;

            font-size: 14px;
        }

        .btn-login {
            width: 100%;

            padding: 13px;

            border: none;
            border-radius: 6px;

            background: #c89b3c;
            color: white;

            font-size: 15px;
            font-weight: bold;

            cursor: pointer;
        }

        .btn-login:hover {
            background: #9f7625;
        }

        .back-home {
            text-align: center;
            margin-top: 20px;
        }

        .back-home a {
            color: #777;
            text-decoration: none;
            font-size: 14px;
        }

        .back-home a:hover {
            color: #c89b3c;
        }
    </style>

</head>

<body>

    <div class="login-container">

        <div class="login-box">

            <div class="logo">

                <h1>
                    CRA <span>UNIVERSITY</span>
                </h1>

                <p>
                    Admin Panel
                </p>

            </div>

            <?php if ($error): ?>

                <div class="error">
                    <?= htmlspecialchars($error) ?>
                </div>

            <?php endif; ?>

            <form method="POST">

                <div class="form-group">

                    <label for="username">
                        Username
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Masukkan username"
                        required>

                </div>

                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required>

                </div>

                <button
                    type="submit"
                    class="btn-login">
                    Login
                </button>

            </form>

            <div class="back-home">

                <a href="../index.html">
                    ← Kembali ke Website
                </a>

            </div>

        </div>

    </div>

</body>

</html>