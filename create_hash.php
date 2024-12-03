<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];

    // Generate hash menggunakan password_hash
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    echo "Password: $password<br>";
    echo "Hashed Password: $hashedPassword";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Password Hash</title>
</head>
<body>
    <h2>Create Password Hash</h2>
    <form method="POST" action="">
        <label for="password">Masukkan Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <button type="submit">Generate Hash</button>
    </form>
</body>
</html>
