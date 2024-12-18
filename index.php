<?php
session_start();
include 'conn.php';

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header("Location: login.php");
    exit();
}

$successMessage = "";
if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// Query untuk menghitung total pendapatan (type = 0) dan pengeluaran (type = 1)
$totalIncomeQuery = mysqli_query($conn, "SELECT SUM(nominal) as total_income FROM journals WHERE type = 0 AND user_id = $user_id");
$totalExpenseQuery = mysqli_query($conn, "SELECT SUM(nominal) as total_expense FROM journals WHERE type = 1 AND user_id = $user_id");

$totalIncome = mysqli_fetch_assoc($totalIncomeQuery)['total_income'] ?? 0;
$totalExpense = mysqli_fetch_assoc($totalExpenseQuery)['total_expense'] ?? 0;
$balance = $totalIncome - $totalExpense;

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal</title>
    <link rel="stylesheet" href="./assets/css/journal.css">
</head>

<body>
    <?php if ($successMessage): ?>
        <div id="success-alert" class="alert-success">
            <?= htmlspecialchars($successMessage) ?>
        </div>
    <?php endif; ?>

    <div class="header">
        <h1>Pencatatan Keuangan</h1>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <div class="summary">
        <div>
            <h2>Total Pendapatan</h2>
            <p>Rp <?= number_format($totalIncome, 0, ',', '.') ?></p>
        </div>
        <div>
            <h2>Balance</h2>
            <p>Rp <?= number_format($balance, 0, ',', '.') ?></p>
        </div>
        <div>
            <h2>Total Pengeluaran</h2>
            <p>
                <?php
                if ($totalExpense > $totalIncome) {
                    echo "(-Rp " . number_format($totalExpense - $totalIncome, 0, ',', '.') . ")";
                } elseif ($totalExpense == $totalIncome) {
                    echo "Rp 0";
                } else {
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
            INNER JOIN users ON journals.user_id = users.id WHERE journals.user_id = $user_id ORDER BY date DESC ");
        if (mysqli_num_rows($query) > 0) {
            while ($item = mysqli_fetch_array($query)) {
                $icon = $item['type'] == 0 ? './assets/image/gaji.jpg' : './assets/image/keranjang.jpg';
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
                            <a href="journal-input.php?id=<?= $item['id']; ?>" title="Edit">Edit</a>
                            <a href="journal-proses.php?id=<?= $item['id']; ?>" onclick="return confirm('Apakah yakin akan menghapus data ini?')" title="Hapus">Hapus</a>
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
        <a href="#">
            <img src="./assets/image/transaksi.png" alt="">
            <span>Transaksi</span>
        </a>
        <a href="journal-input.php">
            <img src="./assets/image/tambah1.jpg" alt="Add">
            <span>Tambah</span>
        </a>
        <a href="laporan.php">
            <img src="./assets/image/laporan.png" alt="">
            <span>Laporan</span>
        </a>
    </div>

</body>

</html>