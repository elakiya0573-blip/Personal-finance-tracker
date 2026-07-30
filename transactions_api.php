<?php
header('Content-Type: application/json');
include("auth.php");
include("db.php");

$user_id = $_SESSION['user_id'];

// Filters
$from  = $_GET['from'] ?? "";
$to    = $_GET['to'] ?? "";
$month = $_GET['month'] ?? "";

// Base WHERE clause
$where = " WHERE user_id='$user_id' ";
if ($from && $to) {
    $where .= " AND transaction_date BETWEEN '$from' AND '$to' ";
}
if ($month) {
    $where .= " AND DATE_FORMAT(transaction_date, '%Y-%m')='$month' ";
}

// Totals
$sqlIncome = "SELECT SUM(amount) AS totalIncome FROM transactions $where AND type='Income'";
$sqlExpense = "SELECT SUM(amount) AS totalExpense FROM transactions $where AND type='Expense'";

$resIncome = mysqli_query($conn, $sqlIncome);
$resExpense = mysqli_query($conn, $sqlExpense);

$rowIncome = mysqli_fetch_assoc($resIncome);
$rowExpense = mysqli_fetch_assoc($resExpense);

$totalIncome = $rowIncome['totalIncome'] ?? 0;
$totalExpense = $rowExpense['totalExpense'] ?? 0;

// Category-wise totals (only expenses)
$sqlCategories = "SELECT category, SUM(amount) AS total 
                  FROM transactions $where AND type='Expense' 
                  GROUP BY category ORDER BY total DESC";
$resCategories = mysqli_query($conn, $sqlCategories);

$categories = [];
while ($row = mysqli_fetch_assoc($resCategories)) {
    $categories[] = [
        "category" => $row['category'],
        "total" => $row['total']
    ];
}

// Final JSON response (no trend)
$response = [
    "totals" => [
        "income" => $totalIncome,
        "expense" => $totalExpense
    ],
    "categories" => $categories
];

echo json_encode($response);
?>
