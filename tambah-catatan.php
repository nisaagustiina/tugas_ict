<?php
session_start();
include 'conn.php';

// Periksa apakah sesi user_id ada
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke login.php
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100vw;
        }
        .container {
            width: 100%;
            height: 100%;
            background-color: #ffef99;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            
            max-width: 400px;
            text-align: center;
            overflow: hidden;
        }
        h1 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 20px;
        }
        .balance {
            font-size: 2rem;
            color: #000;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group input,
        .form-group select {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            background-color: #f9f9f9;
        }
        .icons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .icon {
            width: 50px;
            height: 50px;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }
        .icon img {
            width: 30px;
            height: 30px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }
        .submit-btn {
            background-color: #4CAF50;
            color: white;
        }
        .reset-btn {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambahan Transaksi</h1>
        <div class="balance">Rp 0</div>
        <div class="form-group">
            <button id="kategoriBtn">Kategori ></button>
        </div>
        <!-- Tempat untuk menampilkan gambar -->
<div id="gambarList" style="display: none;">
    <img src="assets/keranjang.jpg" alt="Gambar 1" width="100">
    <!-- Tambahkan gambar lainnya jika diperlukan -->
</div>
        <div class="icons">
            <div class="icon"><img src="shopping-cart.png" alt="Shopping"></div>
            <div class="icon"><img src="groceries.png" alt="Groceries"></div>
            <div class="icon"><img src="house.png" alt="House"></div>
        </div>
        <div class="form-group">
            <input type="date" placeholder="Tanggal">
        </div>
        <div class="form-group">
            <input type="text" name = "Note" placeholder="Note">
        </div>
        <div class="form-group" >
            <select name = "Type">
                <option value="">Type</option>
                <option value="income">Income</option>
                <option value="expense">Expense</option>
            </select>
        </div>
        <div class="form-group">
            <input type="number" name="Nominal" placeholder="Nominal">
        </div>
        <div class="button-group">
            <button class="submit-btn">Submit</button>
            <button class="reset-btn">Reset</button>
        </div>
    </div>
</body>
</html>
<script>
document.getElementById("kategoriBtn").addEventListener("click", function() {
    var gambarList = document.getElementById("gambarList");
    // Toggle tampilkan gambar list
    if (gambarList.style.display === "none") {
        gambarList.style.display = "block"; // Menampilkan gambar
    } else {
        gambarList.style.display = "none"; // Menyembunyikan gambar
    }
});
</script>