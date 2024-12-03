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
    <title>Journal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h2 {
            color: #333;
        }

        a {
            text-decoration: none;
            color: #007bff;
            margin-bottom: 15px;
            display: inline-block;
        }

        a:hover {
            color: #0056b3;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        td a {
            color: #28a745;
        }

        td a:hover {
            color: #218838;
        }

        .action-links a {
            margin-right: 10px;
        }

        .logout {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .logout:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <h2>Journal</h2>
    <a href="journal-input.php">Tambah Data</a>
    <a href="logout.php" class="logout">Logout</a>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Tanggal</th>
                <th>Note</th>
                <th>Categori</th>
                <th>Tipe</th>
                <th>Nominal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = mysqli_query($conn, "SELECT journals.*, categories.name as category_name, users.name as user_name FROM journals 
            INNER JOIN categories ON categories.id = journals.category_id 
            INNER JOIN users ON journals.user_id = users.id");
            if (mysqli_num_rows($query) > 0) {
                while ($item = mysqli_fetch_array($query)) {
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $item['user_name'] ?></td>
                        <td><?= $item['date'] ?></td>
                        <td><?= $item['note'] ?></td>
                        <td><?= $item['category_name'] ?></td>
                        <td><?= $item['type'] == 0 ? 'pemasukan' : 'pengeluaran' ?></td>
                        <td><?= $item['nominal'] ?></td>
                        <td>
                            <div class="action-links">
                                <a href="journal-input.php?id=<?= $item['id']; ?>">Edit</a>
                                <a href="journal-proses.php?id=<?= $item['id']; ?>"
                                    onclick="return confirm('Apakah yakin akan menghapus data ini?')">Hapus</a>
                            </div>
                        </td>
                    </tr>
                <?php }
            } else {
                echo "<tr><td colspan='8'>Belum Ada Data</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>
