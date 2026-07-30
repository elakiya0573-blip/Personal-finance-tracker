<?php
include("auth.php");
include("db.php");

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM transactions WHERE id='$id' AND user_id='$user_id'";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){

    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $date = $_POST['transaction_date'];

    $update = "UPDATE transactions SET
               category='$category',
               amount='$amount',
               description='$description',
               transaction_date='$date'
               WHERE id='$id'";

    if(mysqli_query($conn,$update)){
        header("Location: transactions.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Transaction</title>

<link rel="stylesheet"
href="dashboard.css">

</head>

<body>

<div class="main">

<h1>Edit Transaction</h1>

<form method="POST">

<label>Category</label>

<input
type="text"
name="category"
value="<?php echo $row['category']; ?>"
required>

<label>Amount</label>

<input
type="number"
step="0.01"
name="amount"
value="<?php echo $row['amount']; ?>"
required>

<label>Description</label>

<textarea
name="description"><?php echo $row['description']; ?></textarea>

<label>Date</label>

<input
type="date"
name="transaction_date"
value="<?php echo $row['transaction_date']; ?>"
required>

<button
type="submit"
name="update">

Update Transaction

</button>

</form>

</div>

</body>

</html>