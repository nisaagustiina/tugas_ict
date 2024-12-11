<?php
session_start();
require 'conn.php';

$error = '';
$success = '';

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Ambil data pengguna berdasarkan email
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
    
        if (password_verify($password, $user['password'])) {
            // Set sesi login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
    
            // Tambahkan pesan berhasil login
            $success = "Berhasil login.";
            
            // Redirect ke index.php (opsional jika tidak ingin redirect langsung)
            header("Location: index.php?success=" . urlencode($success));
            exit();
        } else {
            $error = "Login gagal. Password salah.";
        }
    } else {
        $error = "Login gagal. Email tidak ditemukan.";
    }
    
}

// Proses register
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua kolom wajib diisi.";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok.";
    } elseif (strlen($password) < 5) {
        $error = "Password harus terdiri dari minimal 5 karakter.";
    } else {
        // Periksa apakah email sudah digunakan
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Simpan ke database
            $insert_query = "INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $hashed_password);

            if (mysqli_stmt_execute($stmt)) {
                $success = "Akun berhasil dibuat. Silakan login.";
            } else {
                $error = "Gagal mendaftarkan akun. Silakan coba lagi.";
            }
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
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="container">
        <h2>SELAMAT DATANG</h2>
        <div class="avatar"></div>
        <div class="toggle-buttons">
            <button id="login-toggle" class="active" onclick="showForm('login')">MASUK </button>
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
            <input type="text" name="name" placeholder="Masukkan nama lengkap" required value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>">
            <input type="email" name="email" placeholder="Masukkan email" required value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
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

    <!-- Error and Success Toast Notifications -->
`    <div id="toast" class="toast" 
     data-error="<?= !empty($error) ? htmlspecialchars($error, ENT_QUOTES, 'UTF-8') : ''; ?>" 
     data-success="<?= !empty($success) ? htmlspecialchars($success, ENT_QUOTES, 'UTF-8') : ''; ?>">
</div>



    <script src="js/login.js"></script>
</body>

</html>