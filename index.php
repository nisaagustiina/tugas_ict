<?php
session_start();
include 'conn.php';

// Periksa apakah sesi user_id ada
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke login.php
    header("Location: login.php");
    exit();
}


// Query untuk menghitung total pendapatan (type = 0) dan pengeluaran (type = 1)
$totalIncomeQuery = mysqli_query($conn, "SELECT SUM(nominal) as total_income FROM journals WHERE type = 0");
$totalExpenseQuery = mysqli_query($conn, "SELECT SUM(nominal) as total_expense FROM journals WHERE type = 1");

// Ambil hasil query
$totalIncome = mysqli_fetch_assoc($totalIncomeQuery)['total_income'] ?? 0;
$totalExpense = mysqli_fetch_assoc($totalExpenseQuery)['total_expense'] ?? 0;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal</title>
    <style>
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f7fc;
    color: #333;
}

.header {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #ffd700;
    padding: 10px 15px; /* Padding lebih kecil */
    color: #333;
}

.header h1 {
    margin: 0;
    font-size: 20px; /* Ukuran font lebih kecil */
    text-align: center;
    width: 100%;
}

.summary {
    display: flex;
    justify-content: space-between;
    padding: 10px 15px; /* Padding lebih kecil */
    background-color: #fff;
    margin-bottom: 15px; /* Margin lebih kecil */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.summary div {
    text-align: center;
}

.summary div h2 {
    margin: 0;
    font-size: 18px; /* Ukuran font lebih kecil */
    color: #444;
}

.summary div p {
    margin: 5px 0 0;
    font-size: 16px; /* Ukuran font lebih kecil */
    font-weight: bold;
}

.transactions {
    width: 90%;
    max-width: 1000px; /* Lebar maksimum lebih kecil */
    margin: 0 auto;
    background-color: #fff;
    padding: 15px; /* Padding lebih kecil */
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    height: 350px; /* Tinggi lebih kecil */
    overflow-y: auto; /* Menampilkan scroll */
}

.transactions h2 {
    margin-bottom: 15px; /* Margin lebih kecil */
    font-size: 20px; /* Ukuran font lebih kecil */
    color: #444;
}

.transaction-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px; /* Padding lebih kecil */
    border-bottom: 1px solid #ddd;
}

.transaction-item:last-child {
    border-bottom: none;
}

.transaction-item img {
    width: 35px; /* Ukuran ikon lebih kecil */
    height: 35px;
    margin-right: 10px; /* Margin lebih kecil */
}

.transaction-info {
    display: flex;
    align-items: center;
}

.transaction-info div {
    display: flex;
    flex-direction: column;
}

.transaction-info div span {
    font-size: 14px; /* Ukuran font lebih kecil */
}

.transaction-info div span.description {
    font-weight: bold;
    color: #333;
}

.transaction-date {
    font-size: 12px; /* Ukuran font lebih kecil */
    color: #777;
}

.transaction-amount {
    font-size: 16px; /* Ukuran font lebih kecil */
    font-weight: bold;
    color: #333;
}

.transaction-amount.expense {
    color: #e74c3c;
}

.transaction-amount.income {
    color: #2ecc71;
}

.transaction-actions {
    display: flex;
    gap: 5px; /* Gap lebih kecil */
}

.transaction-actions a {
    text-decoration: none;
    padding: 5px 8px; /* Padding lebih kecil */
    color: #fff;
    border-radius: 5px;
    font-size: 12px; /* Ukuran font lebih kecil */
}
/*
.edit {
    background-color:;
}

.edit:hover {
    background-color:;
}

.delete {
    background-color:;
}

.delete:hover {
    background-color:;
}*/

.bottom-menu {
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: #fff;
    display: flex;
    justify-content: space-around;
    align-items: center;
    box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.1);
    padding: 8px 0; /* Padding lebih kecil */
}

.bottom-menu a {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: #333;
    font-size: 12px; /* Ukuran font lebih kecil */
    text-decoration: none;
}

.bottom-menu a img {
    width: 20px; /* Ukuran ikon lebih kecil */
    height: 20px;
    margin-bottom: 5px;
}

.add-button {
    display: inline-block;
    margin: 20px auto;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    text-align: center;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s;
}

.add-button:hover {
    background-color: #0056b3;
}

.header .logout {
    text-decoration: none;
    color: #fff;
    background-color: #dc3545;
    padding: 8px 12px; /* Padding lebih kecil */
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s;
}

.header .logout:hover {
    background-color: #c82333;
}

@media (max-width: 768px) {
    .summary {
        flex-direction: column;
    }

    .transaction-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .transaction-info {
        margin-bottom: 10px;
    }

    .transactions {
        height: 300px; /* Menyesuaikan tinggi untuk layar kecil */
    }

    .bottom-menu {
        flex-direction: column;
    }
}


    </style>
</head>

<body>
<div class="header">
    <h1>Journal Keuangan</h1>
    <a href="logout.php" class="logout">Logout</a>
</div>


<div class="summary">
    <div>
        <h2>Total Pendapatan</h2>
        <p>Rp <?= number_format($totalIncome, 0, ',', '.') ?></p>
    </div>
    <div>
        <h2>Total Pengeluaran</h2>
        <p>
            <?php 
            // Periksa apakah pengeluaran lebih besar dari pendapatan
            if ($totalExpense > $totalIncome) {
                // Jika pengeluaran lebih besar, tampilkan selisih sebagai angka negatif
                echo "(-Rp " . number_format($totalExpense - $totalIncome, 0, ',', '.') . ")";
            } 
            // Periksa jika pendapatan dan pengeluaran sama
            else if ($totalExpense == $totalIncome) {
                echo "Rp 0";
            } 
            else {
                // Jika pengeluaran lebih kecil, tampilkan pengeluaran seperti biasa
                echo "Rp " . number_format($totalExpense, 0, ',', '.');
            }
            ?>
        </p>
    </div>
</div>




    <div class="transactions">
        <h2>Transaksi</h2>
        <?php
        $query = mysqli_query($conn, "SELECT journals.*, categories.name as category_name, users.name as user_name FROM journals 
            INNER JOIN categories ON categories.id = journals.category_id 
            INNER JOIN users ON journals.user_id = users.id");
        if (mysqli_num_rows($query) > 0) {
            while ($item = mysqli_fetch_array($query)) {
                $icon = $item['type'] == 0 ? 'assets/gaji.jpg' : 'assets/keranjang.jpg'; // Ganti dengan ikon sesuai tipe transaksi
                $amountClass = $item['type'] == 0 ? 'income' : 'expense';
        ?>
            <div class="transaction-item">
                <div class="transaction-info">
                    <img src="<?= $icon ?>" alt="icon">
                    <div>
                        <span class="description"><?= $item['note'] ?></span>
                        <span class="transaction-date"><?= $item['date'] ?></span>
                    </div>
                </div>
                <div>
                    <span class="transaction-amount <?= $amountClass ?>">Rp <?= number_format($item['nominal'], 0, ',', '.') ?></span>
                    <div class="transaction-actions">
    <!-- Link Edit dengan ikon -->
    <a href="journal-input.php?id=<?= $item['id']; ?>" class="edit" title="Edit">
        ✏️ Edit
    </a>

    <!-- Link Hapus dengan ikon -->
    <a href="journal-proses.php?id=<?= $item['id']; ?>" class="delete" onclick="return confirm('Apakah yakin akan menghapus data ini?')" title="Hapus">
        🗑️ Hapus
    </a>
</div>


                </div>
            </div>
        <?php
            }
        } else {
            echo "<p style='text-align:center;'>Belum Ada Data</p>";
        }
        ?>
    </div>

    <div class="bottom-menu">
    <a href="transactions.php">
            <img src="assets/transaksi.png" alt="">
            <span>Transaksi</span>
        </a>
        <a href="journal-input.php">
            <img src="assets/tambah1.jpg" alt="Add">
            <span>Tambah</span>
        </a>
        <a href="laporan.php">
            <img src="assets/laporan.png" alt="">
            <span>laporan</span>
       
