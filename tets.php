<?php
session_start();
include 'conn.php';

// Periksa apakah sesi user_id ada
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$data = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM journals WHERE id = '$id'");
    $data = mysqli_fetch_array($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Journal Form</h2>
        <a href="index.php" class="back-button">Kembali</a>
        <br>

        <form id="journalForm" action="journal-proses.php" method="post" onsubmit="return validateForm()">
            <div id="errorMessages" class="error"></div>
            <input type="hidden" name="id" value="<?= isset($data['id']) ? $data['id'] : 0 ?>">
            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">

             <!-- <label for="user_id">User</label>
            <select class="form-control" name="user_id" id="user_id" required>
                <option value="">--Pilih--</option>
                <?php
                // $getUser = mysqli_query($conn, "SELECT * FROM users");
                // while ($user = mysqli_fetch_array($getUser)) {
                //     echo '<option value="' . $user['id'] . '" ' . ($data['user_id'] == $user['id'] ? 'selected' : '') . '>' . $user['name'] . '</option>';
                // }
                // 
                ?> 
            </select> -->
            
            <label for="date">Tanggal</label>
            <input type="date" name="date" id="date" value="<?= isset($data['date']) ? $data['date'] : '' ?>" required>

            <label for="note">Note</label>
            <textarea name="note" rows="3" required><?= isset($data['note']) ? htmlspecialchars($data['note']) : '' ?></textarea>

            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" required>
                <option value="">--Pilih--</option>
                <?php
                $getCategory = mysqli_query($conn, "SELECT * FROM categories");
                while ($category = mysqli_fetch_array($getCategory)) {
                    $selected = isset($data['category_id']) && $data['category_id'] == $category['id'] ? 'selected' : '';
                    echo '<option value="' . $category['id'] . '" ' . $selected . '>' . $category['name'] . '</option>';
                }
                ?>
            </select>

            <label for="type">Tipe</label>
            <div>
                <label><input type="radio" name="type" value="0" <?= isset($data['type']) && $data['type'] == "0" ? "checked" : "" ?>>Pemasukan</label>
                <label><input type="radio" name="type" value="1" <?= isset($data['type']) && $data['type'] == "1" ? "checked" : "" ?>>Pengeluaran</label>
            </div>

            <label for="nominal">Nominal</label>
            <input type="number" name="nominal" id="nominal" value="<?= isset($data['nominal']) ? $data['nominal'] : '' ?>" required>

            <button type="submit" name="submit" value="<?= isset($data) && $data['id'] ? 'edit' : 'save' ?>">Submit</button>
        </form>
    </div>

    <script>
        function validateForm() {
            let errors = [];
            const form = document.forms["journalForm"];
            const fields = ["date", "note", "category_id", "type", "nominal"];

            fields.forEach(field => {
                const element = form[field];
                if (!element.value || (field === "nominal" && isNaN(element.value))) {
                    errors.push(field.charAt(0).toUpperCase() + field.slice(1) + " is required or invalid.");
                }
            });

            if (errors.length > 0) {
                document.getElementById("errorMessages").innerHTML = errors.join("<br>");
                document.getElementById("errorMessages").style.display = "block";
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
