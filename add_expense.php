<?php
include("auth.php");
include("db.php");

$message = "";

if(isset($_POST['save'])){

    $user_id = $_SESSION['user_id'];
    $type = "Expense";
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $description = trim($_POST['description']);
    $date = $_POST['transaction_date'];

    if($amount <= 0){
        $message = "Amount must be greater than zero.";
    } else {

        $sql = "INSERT INTO transactions
        (user_id,type,category,amount,description,transaction_date)
        VALUES (?,?,?,?,?,?)";

        $stmt = mysqli_prepare($conn,$sql);

        mysqli_stmt_bind_param(
            $stmt,
            "issdss",
            $user_id,
            $type,
            $category,
            $amount,
            $description,
            $date
        );

        if(mysqli_stmt_execute($stmt)){
            $message = "Expense added successfully.";
        }else{
            $message = "Error saving expense.";
        }

    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Add Expense</title>

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

<h1>Add Expense</h1>

<?php
if($message!=""){
echo "<p style='color:green;font-weight:bold;'>$message</p>";
}
?>

<form method="POST">

<label>Expense Category</label>

<select name="category" required>

<option value="">Select Category</option>

<option>Food</option>

<option>Travel</option>

<option>Shopping</option>

<option>Rent</option>

<option>Electricity</option>

<option>Internet</option>

<option>Medical</option>

<option>Entertainment</option>

<option>Education</option>

<option>Fuel</option>

<option>Other</option>

</select>

<br><br>

<label>Amount (₹)</label>

<input
type="number"
step="0.01"
name="amount"
required>

<br><br>

<label>Description</label>

<textarea
name="description"
rows="4"></textarea>

<br><br>

<label>Date</label>

<input
type="date"
name="transaction_date"
required>

<br><br>

<button
type="submit"
name="save">

Save Expense

</button>

</form>

</div>

</body>

</html>