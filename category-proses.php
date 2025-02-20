<?php
include 'conn.php';

// Handle Create (Insert) Category
function createCategory($conn, $name, $description) {
    $stmt = $conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $description);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Tambah data berhasil!";
        header("Location: category.php");
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location='category.php';</script>";
    }
}

// Handle Update Category
function updateCategory($conn, $id, $name, $description) {
    $stmt = $conn->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $description, $id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Edit data berhasil!";
        header("Location: category.php");
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location='category.php';</script>";
    }
}

// Handle Delete Category
function deleteCategory($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Hapus data berhasil!";
        header("Location: category.php");
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location='category.php';</script>";
    }
}

// Handle Read All Categories
function getAllCategories($conn) {
    $query = mysqli_query($conn, "SELECT * FROM categories");
    return mysqli_fetch_all($query, MYSQLI_ASSOC);
}

// Handle Read One Category
function getCategoryById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Main logic for POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $id = $_POST['id'] ?? null;

    if (isset($_POST['submit']) && !$id) {
        // Create category
        createCategory($conn, $name, $description);
    } elseif (isset($_POST['submit']) && $id) {
        // Update category
        updateCategory($conn, $id, $name, $description);
    }
}

// Handle GET request for delete
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    deleteCategory($conn, $id);
}
?>
