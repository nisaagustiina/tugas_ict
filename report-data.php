<?php
session_start();
include 'conn.php';

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header("Location: login.php");
    exit();
}

$successMessage = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

// Fungsi untuk chart per bulan
function getMonthlyData($conn, $user_id) {
    $query_monthly = "
        SELECT 
            MONTH(date) AS month,
            SUM(CASE WHEN type = 1 THEN nominal ELSE 0 END) AS income, 
            SUM(CASE WHEN type = 0 THEN nominal ELSE 0 END) AS outcome
        FROM journals
        WHERE user_id = ?
        GROUP BY MONTH(date)
        ORDER BY MONTH(date);
    ";

    $monthly_data = [
        'months' => [],
        'income' => [],
        'outcome' => []
    ];

    if ($stmt = mysqli_prepare($conn, $query_monthly)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $monthly_data['months'][] = $row['month'];
            $monthly_data['income'][] = $row['income'];
            $monthly_data['outcome'][] = $row['outcome'];
        }

        mysqli_stmt_close($stmt);
    }

    return $monthly_data;
}

// Fungsi untuk chat pengeluaran per kategori
function getCategoryData($conn, $user_id) {
    $query_category = "
        SELECT 
            c.name, 
            SUM(CASE WHEN j.type = 0 THEN j.nominal ELSE 0 END) AS outcome
        FROM journals j
        JOIN categories c ON j.category_id = c.id
        WHERE j.user_id = ?
        GROUP BY c.name
        ORDER BY c.name;
    ";

    $category_data = [
        'categories' => [],
        'outcome' => []
    ];

    if ($stmt = mysqli_prepare($conn, $query_category)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $category_data['categories'][] = $row['name']; 
            $category_data['outcome'][] = $row['outcome']; 
        }

        mysqli_stmt_close($stmt);
    }

    return $category_data;
}

// get data
$monthly_data = getMonthlyData($conn, $user_id);
$category_data = getCategoryData($conn, $user_id);

// respon data
$response = [
    'monthly' => $monthly_data,  
    'category' => $category_data 
];

error_log("Response Data: " . print_r($response, true));
echo json_encode($response);  // return as JSON
?>
