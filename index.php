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
    <title>Tampilan Transaksi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100vw;
        }
        .container {
            width: 100%;
            height: 100%;
            max-width: 400px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            display: flex;
            flex-direction: column;
        }
        .header {
            background-color: #ffef99;
            padding: 20px;
            text-align: center;
            flex-shrink: 0;
        }
        .header h2 {
            margin: 10px 0;
            font-size: 18px;
            color: #333;
        }
        .header .amount {
            font-size: 24px;
            font-weight: bold;
            margin: 5px 0;
            color: #000;
        }
        .content {
            flex-grow: 1;
            padding: 30px 20px;
            text-align: center;
            background-color: #f9f9f9;
        }
        .content img {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
            border-radius: 50%;
        }
        .content p {
            font-size: 16px;
            color: #555;
            margin: 5px 0;
        }
        .content p strong {
            font-weight: bold;
            color: #000;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f1f1f1;
            padding: 10px 20px;
            border-top: 1px solid #ddd;
            flex-shrink: 0;
        }
        .footer button {
            flex: 1;
            background: none;
            border: none;
            font-size: 14px;
            color: #555;
            cursor: pointer;
            padding: 10px;
        }
        .footer button:hover {
            color: #000;
        }
        .footer button span {
            display: block;
            font-size: 20px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Total Pendapatan</h2>
            <div class="amount">Rp 0</div>
            <h2>Total Pengeluaran</h2>
            <div class="amount">Rp 0</div>
        </div>
        <div class="content">
            <img src="assets/meong.jpg" alt="Cat Icon">
            <p><strong>Tidak ada catatan</strong></p>
            <p>Tekan <strong>“+”</strong> untuk mencatat transaksi dan mulai kelola pengeluaran Anda</p>
        </div>
        <div class="footer">
            <button>
                <span>↔</span>
                Transaksi
            </button>
            <button onclick="window.location.href='tambah-catatan.php'">
                <span>+</span>
                Tambah
            </button>

            <button>
                <span>📄</span>
                Laporan
            </button>
        </div>
    </div>
</body>
</html>
