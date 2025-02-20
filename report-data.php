<?php
include './conn.php';
header('Content-Type: application/json');

$query = mysqli_query($conn, "SELECT MONTH(date) as month, SUM(nominal) as total FROM journals WHERE type = 0 GROUP BY MONTH(date)");
$data = [];
if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $data]);
} else {
    echo json_encode(['success' => false, 'data' => $data]);
}
