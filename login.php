<?php
session_start();
require 'conn.php';

$error = '';
$success = '';

function sanitizeInput($conn, $input)
{
    return mysqli_real_escape_string($conn, trim($input));
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = sanitizeInput($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['success_message'] = "Login Berhasil!";

                header("Location: index.php");
                exit();
            } else {
                $error = "Login gagal! Password salah.";
            }
        } else {
            $error = "Login gagal! Email tidak ditemukan.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "Terjadi kesalahan pada server.";
    }
}

// Proses register
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = sanitizeInput($conn, $_POST['name']);
    $email = sanitizeInput($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua kolom wajib diisi.";
    } elseif (strlen($password) < 5) {
        $error = "Password harus terdiri dari minimal 5 karakter.";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $error = "Email sudah terdaftar.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $insert_query = "INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";

                $insert_stmt = mysqli_prepare($conn, $insert_query);

                if ($insert_stmt) {
                    mysqli_stmt_bind_param($insert_stmt, 'sss', $name, $email, $hashed_password);

                    if (mysqli_stmt_execute($insert_stmt)) {
                        $success = "Akun berhasil dibuat. Silakan login.";
                    } else {
                        $error = "Gagal mendaftarkan akun. Silakan coba lagi.";
                    }
                } else {
                    $error = "Terjadi kesalahan pada server.";
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Terjadi kesalahan pada server.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="./assets/css/login.css">
</head>

<body>
    <div class="container">
        <h2>SELAMAT DATANG</h2>
        <div class="avatar"></div>
        <div class="toggle-buttons">
            <button id="login-toggle" class="active" onclick="showForm('login')">MASUK</button>
            <button id="register-toggle" onclick="showForm('register')">BUAT AKUN</button>
        </div>

        <!-- Form Login -->
        <form id="login-form" class="active" method="POST" action="">
            <input type="email" name="email" placeholder="Masukkan email" required>
            <div style="position: relative;">
                <input type="password" name="password" placeholder="Masukkan sandi" required>
                <span class="toggle-password" onclick="togglePassword(this)">👁️</span>
            </div>
            <div class="helper-text">
                <a href="#">Lupa sandi?</a>
            </div>
            <button type="submit" name="login">MASUK</button>
        </form>

        <!-- Form Register -->
        <form id="register-form" method="POST" action="">
            <input type="text" name="name" placeholder="Masukkan nama lengkap" required value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <input type="email" name="email" placeholder="Masukkan email" required value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <div style="position: relative;">
                <input type="password" name="password" placeholder="Buat sandi" required>
                <span class="toggle-password" onclick="togglePassword(this)">👁️</span>
            </div>
            <div style="position: relative;">
                <input type="password" name="confirm_password" placeholder="Konfirmasi sandi" required>
                <span class="toggle-password" onclick="togglePassword(this)">👁️</span>
            </div>
            <button type="submit" name="register">BUAT AKUN</button>
        </form>
    </div>

    <!-- Alert Notification -->
    ` <div id="toast" class="toast"
        data-error="<?= !empty($error) ? htmlspecialchars($error, ENT_QUOTES, 'UTF-8') : ''; ?>"
        data-success="<?= !empty($success) ? htmlspecialchars($success, ENT_QUOTES, 'UTF-8') : ''; ?>">
    </div>

    <script src="./assets/js/login.js"></script>
</body>

</html>