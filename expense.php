<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(isset($_POST['save'])){

    $user_id = $_SESSION['user_id'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    $sql = "INSERT INTO expenses(user_id, amount, category, description, date)
            VALUES('$user_id','$amount','$category','$description','$date')";

    if(mysqli_query($conn,$sql)){
        echo "<script>alert('Expense Added Successfully');</script>";
    }else{
        echo "Error : ".mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Expense</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="form-container">

<h1>Add Expense</h1>

<form method="POST">

<label>Amount</label>
<input type="number" name="amount" required>

<label>Category</label>

<select name="category" required>

<option>Food</option>
<option>Travel</option>
<option>Shopping</option>
<option>Rent</option>
<option>Electricity</option>
<option>Medical</option>
<option>Entertainment</option>
<option>Education</option>
<option>Fuel</option>
<option>Other</option>

</select>

<label>Description</label>

<textarea
name="description"
rows="4"></textarea>

<label>Date</label>

<input
type="date"
name="date"
required>

<button
type="submit"
name="save">

Save Expense

</button>

</form>

<br>

<a href="dashboard.php">
<button>Back to Dashboard</button>
</a>

</div>

</body>
</html>