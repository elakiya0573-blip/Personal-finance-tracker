<?php
include("auth.php");
include("db.php");

$user_id = $_SESSION['user_id'];

/* Total Income */
$sql = "SELECT SUM(amount) AS total_income
        FROM transactions
        WHERE user_id='$user_id'
        AND type='Income'";

$result = mysqli_query($conn,$sql);
$income = mysqli_fetch_assoc($result);
$totalIncome = $income['total_income'] ?? 0;

/* Total Expense */
$sql = "SELECT SUM(amount) AS total_expense
        FROM transactions
        WHERE user_id='$user_id'
        AND type='Expense'";

$result = mysqli_query($conn,$sql);
$expense = mysqli_fetch_assoc($result);
$totalExpense = $expense['total_expense'] ?? 0;

$balance = $totalIncome - $totalExpense;

/* Transaction Count */
$sql = "SELECT COUNT(*) AS total
        FROM transactions
        WHERE user_id='$user_id'";

$result = mysqli_query($conn,$sql);
$count = mysqli_fetch_assoc($result);
$totalTransactions = $count['total'];
?>

<!DOCTYPE html>
<html>

<head>

<title>Dashboard</title>

<link rel="stylesheet"
href="dashboard.css">

</head>

<body>

<div class="sidebar">

<h2>💰 Finance Tracker</h2>

<a href="dashboard.php">Dashboard</a>

<a href="add_income.php">Add Income</a>

<a href="add_expense.php">Add Expense</a>

<a href="transactions.php">Transactions</a>

<a href="reports.php">Reports</a>

<a href="profile.php">Profile</a>

<a href="logout.php">Logout</a>

</div>

<div class="main">

<h1>

Welcome,
<?php echo $_SESSION['fullname']; ?>

👋

</h1>

<div class="cards">

<div class="card">

<h3>Current Balance</h3>

<h2>₹<?php echo $balance; ?></h2>

</div>

<div class="card">

<h3>Total Income</h3>

<h2>₹<?php echo $totalIncome; ?></h2>

</div>

<div class="card">

<h3>Total Expense</h3>

<h2>₹<?php echo $totalExpense; ?></h2>

</div>

<div class="card">

<h3>Transactions</h3>

<h2><?php echo $totalTransactions; ?></h2>

</div>

</div>

</div>

</body>

</html>