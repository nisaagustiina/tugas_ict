<?php
session_start();
include 'conn.php';

// Periksa apakah sesi user_id ada
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke login.php
    header("Location: login.php");
    exit();
}

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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-control {
            margin-bottom: 20px;
        }

        .radio-inline {
            margin-right: 20px;
        }

        .error {
            color: red;
            font-size: 14px;
            background-color: #fdd;
            padding: 10px;
            border: 1px solid red;
            border-radius: 4px;
            margin-bottom: 20px;
            display: none;
            /* Hide error message initially */
        }

        a {
            text-decoration: none;
            color: #0066cc;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Journal Form</h2>
        <a href="index.php" class="back-button">Kembali</a>
        <br>

        <form name="journalForm" action="journal-proses.php" method="post" onsubmit="return validateForm()">
            <div id="errorMessages" class="error"></div>
            <input value="<?= isset($data['id']) ? $data['id'] : 0 ?>" name="id" type="hidden">
            <input value="<?= $_SESSION['user_id'] ?>" name="user_id" type="hidden">

            <!-- <label for="user_id">User</label>
            <select class="form-control" name="user_id" id="user_id" required>
                <option value="">--Pilih--</option>
               <?php
               // $getUser = mysqli_query($conn, "SELECT * FROM users");
               // while ($user = mysqli_fetch_array($getUser)) {
               //     echo '<option value="' . $user['id'] . '" ' . ($data['user_id'] == $user['id'] ? 'selected' : '') . '>' . $user['name'] . '</option>';
               // }
               // ?> 
            </select> -->

            <label for="date">Tanggal</label>
            <input type="date" name="date" id="date" value="<?= isset($data['date']) ? $data['date'] : '' ?>" required>

            <label for="note">Note</label>
            <textarea name="note" rows="3"
                required><?= isset($data['note']) ? htmlspecialchars($data['note']) : '' ?></textarea>

            <label for="category_id">Category</label>
            <select class="form-control" name="category_id" id="category_id" required>
                <option value="">--Pilih--</option>
                <?php
                $getCategory = mysqli_query($conn, "SELECT * FROM categories");
                while ($category = mysqli_fetch_array($getCategory)) {
                    echo '<option value="' . $category['id'] . '" ' . ($data['category_id'] == $category['id'] ? 'selected' : '') . '>' . $category['name'] . '</option>';
                }
                ?>
            </select>

            <label for="type">Tipe</label>
            <div>
                <label class="radio-inline"><input type="radio" name="type" id="type" value="0" required
                        <?= isset($data['type']) && $data['type'] == "0" ? "checked" : null ?>>Pemasukan</label>
                <label class="radio-inline"><input type="radio" name="type" id="type" value="1" required
                        <?= isset($data['type']) && $data['type'] == "1" ? "checked" : null ?>>Pengeluaran</label>
            </div>

            <label for="nominal">Nominal</label>
            <input type="number" name="nominal" id="nominal"
                value="<?= isset($data['nominal']) ? $data['nominal'] : '' ?>" required>

            <button type="submit" name="submit"
                value="<?= isset($data) && $data['id'] ? 'edit' : 'save' ?>">Submit</button>

        </form>
    </div>

    <script>
        function validateForm() {
            let user = document.forms["journalForm"]["user_id"].value;
            let date = document.forms["journalForm"]["date"].value;
            let note = document.forms["journalForm"]["note"].value;
            let category = document.forms["journalForm"]["category_id"].value;
            let type = document.forms["journalForm"]["type"].value;
            let nominal = document.forms["journalForm"]["nominal"].value;
            let errors = [];

            // Validate user selection
            if (user == "") {
                errors.push("User is required.");
            }

            // Validate date input
            if (date == "") {
                errors.push("Date is required.");
            }

            // Validate note input
            if (note == "") {
                errors.push("Note is required.");
            }

            // Validate category selection
            if (category == "") {
                errors.push("Category is required.");
            }

            // Validate type radio buttons
            if (type == "") {
                errors.push("Type is required.");
            }

            // Validate nominal input
            if (nominal == "") {
                errors.push("Nominal is required.");
            } else if (isNaN(nominal) || nominal <= 0) {
                errors.push("Nominal must be a valid number greater than 0.");
            }

            if (errors.length > 0) {
                let errorMessage = errors.join("<br>");
                document.getElementById("errorMessages").innerHTML = errorMessage;
                document.getElementById("errorMessages").style.display = "block";
                return false;
            }

            return true;
        }
    </script>

</body>

</html>