<?php
date_default_timezone_set('Asia/Jakarta');

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "tugas_ict";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        throw new Exception("Gagal terhubung dengan database: " . mysqli_connect_error());
    }
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
