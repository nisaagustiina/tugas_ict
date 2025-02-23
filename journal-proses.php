<?php
session_start();
include 'conn.php';

require_once('./assets/vendor/TCPDF/tcpdf.php');

// crud journal
if (isset($_POST['submit'])) {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $note = isset($_POST['note']) ? mysqli_real_escape_string($conn, $_POST['note']) : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $nominal = isset($_POST['nominal']) ? $_POST['nominal'] : '';

    if ($_POST['submit'] == 'save') {
        $stmt = $conn->prepare("INSERT INTO journals (user_id, date, note, category_id, type, nominal) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssii", $user_id, $date, $note, $category_id, $type, $nominal);
        if ($stmt->execute()) {
            echo "<script>alert('Tambah data berhasil!'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location='index.php';</script>";
        }
    } else {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $stmt = $conn->prepare("UPDATE journals SET user_id=?, date=?, note=?, category_id=?, type=?, nominal=? WHERE id=?");
        $stmt->bind_param("isssiis", $user_id, $date, $note, $category_id, $type, $nominal, $id);
        if ($stmt->execute()) {
            echo "<script>alert('Edit Data Berhasil!'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location='index.php';</script>";
        }
    }
}

//delete data
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

// Fungsi untuk mendownload data sebagai CSV
function downloadCSV($conn)
{
    $sql = "SELECT journals.*, categories.name as category_name, users.name as user_name 
            FROM journals 
            INNER JOIN categories ON categories.id = journals.category_id 
            INNER JOIN users ON journals.user_id = users.id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Set headers for CSV file
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="journals_data.csv"');
        header('Cache-Control: max-age=0');

        // Open the output file in write mode to "php://output" (directly to browser)
        $output = fopen('php://output', 'w');

        // Write the column headers to the CSV
        fputcsv($output, ['ID', 'Name', 'Date', 'Note', 'Category', 'Type', 'Nominal']);

        // Write the data to the CSV file
        while ($row = $result->fetch_assoc()) {
            $type = $row['type'] == 0? 'Pemasukan' : 'Pengeluaran';

            $rowData = [
                $row['id'],
                $row['user_name'],
                $row['date'],
                $row['note'],
                $row['category_name'],
                $type,
                $row['nominal'],
            ];
            fputcsv($output, $rowData);
        }

        // Close the output file
        fclose($output);
    } else {
        echo "Tidak ada data ditemukan.";
    }
}

// Fungsi untuk mendownload data sebagai PDF
function downloadPDF($conn)
{

    $sql = "SELECT journals.*, categories.name as category_name, users.name as user_name FROM journals 
            INNER JOIN categories ON categories.id = journals.category_id 
            INNER JOIN users ON journals.user_id = users.id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Membuat instance TCPDF
        $pdf = new TCPDF();
        $pdf->AddPage();

        // Menambahkan judul ke PDF
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Kas', 0, 1, 'C');

        // Menulis header kolom
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(10, 10, 'ID', 1);
        $pdf->Cell(30, 10, 'User', 1);
        $pdf->Cell(30, 10, 'Date', 1);
        $pdf->Cell(35, 10, 'Note', 1);
        $pdf->Cell(30, 10, 'Category', 1);
        $pdf->Cell(30, 10, 'Type', 1);
        $pdf->Cell(20, 10, 'Nominal', 1);
        $pdf->Ln();

        // Menulis data ke dalam PDF
        while ($row = $result->fetch_assoc()) {
            $type = $row['type'] == 0 ? 'Pemasukan' : 'Pengeluaran';

            $pdf->Cell(10, 10, $row['id'], 1);
            $pdf->Cell(30, 10, $row['user_name'], 1);
            $pdf->Cell(30, 10, $row['date'], 1);
            $pdf->Cell(35, 10, $row['note'], 1);
            $pdf->Cell(30, 10, $row['category_name'], 1);
            $pdf->Cell(30, 10, $type, 1);
            $pdf->Cell(20, 10, $row['nominal'], 1);
            $pdf->Ln();
        }

        // Output the PDF
        $pdf->Output('journals_data.pdf', 'D'); // The 'D' flag forces the download
    } else {
        echo "Tidak ada data ditemukan.";
    }
}

// Cek request download
if (isset($_GET['download'])) {
    if ($_GET['download'] == 'csv') {
        downloadCSV($conn);
    }

    if ($_GET['download'] == 'pdf') {
        downloadPDF($conn);
    }
}
