<?php
session_start();
include 'conn.php';

$user_id = $_POST['user_id'];
$note = mysqli_real_escape_string($conn, $_POST['note']);
$date = $_POST['date'];
$category_id = $_POST['category_id'];
$type = $_POST['type'];
$nominal = $_POST['nominal'];

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'save') {
        $stmt = $conn->prepare("INSERT INTO journals (user_id, date, note, category_id, type, nominal) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssii", $user_id, $date, $note, $category_id, $type, $nominal);
        if ($stmt->execute()) {
            echo "<script>alert('Tambah data berhasil!'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location='index.php';</script>";
        }
    } else {
        $id = $_POST['id'];

        $stmt = $conn->prepare("UPDATE journals SET user_id=?, date=?, note=?, category_id=?, type=?, nominal=? WHERE id=?");
        $stmt->bind_param("isssiis", $user_id, $date, $note, $category_id, $type, $nominal, $id);
        if ($stmt->execute()) {
            echo "<script>alert('Edit Data Berhasil!'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location='index.php';</script>";
        }
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM journals WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Hapus Data Berhasil!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location='index.php';</script>";
    }
}
?>