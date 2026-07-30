<?php
include("db.php");
include("auth.php");

$user_id = $_SESSION['user_id'];

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=Finance_Report.csv");

$output = fopen("php://output", "w");

fputcsv($output, array("Date", "Type", "Category", "Amount", "Description"));

$sql = "SELECT * FROM transactions WHERE user_id='$user_id' ORDER BY transaction_date DESC";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, array(
        $row['transaction_date'],
        $row['type'],
        $row['category'],
        $row['amount'],
        $row['description']
    ));
}

fclose($output);
exit;
?>