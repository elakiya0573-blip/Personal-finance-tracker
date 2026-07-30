<?php
include("auth.php");
include("db.php");

$user_id = $_SESSION['user_id'];

/* Search */

$search = "";

if(isset($_GET['search'])){
    $search = trim($_GET['search']);
}

/* Date Filter */

$from = "";

$to = "";

if(isset($_GET['from'])){
    $from = $_GET['from'];
}

if(isset($_GET['to'])){
    $to = $_GET['to'];
}

/* Pagination */

$limit = 10;

$page = 1;

if(isset($_GET['page'])){

    $page = (int)$_GET['page'];

    if($page < 1){
        $page = 1;
    }

}

$start = ($page-1) * $limit;

/* WHERE Condition */

$where = " WHERE user_id='$user_id' ";

if($search != ""){

    $where .= " AND (
        category LIKE '%$search%'
        OR description LIKE '%$search%'
    )";

}

if($from != "" && $to != ""){

    $where .= " AND transaction_date
                BETWEEN '$from'
                AND '$to'";

}

/* Count Records */

$countQuery = "SELECT COUNT(*) AS total
               FROM transactions
               $where";

$countResult = mysqli_query($conn,$countQuery);

$countRow = mysqli_fetch_assoc($countResult);

$totalRecords = $countRow['total'];

$totalPages = ceil($totalRecords/$limit);

/* Main Query */

$sql = "SELECT *
        FROM transactions
        $where
        ORDER BY transaction_date DESC,
        id DESC
        LIMIT $start,$limit";

$result = mysqli_query($conn,$sql);

/* Running Balance */

$balance = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Transaction History</title>

<link rel="stylesheet" href="dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="sidebar">

<h2>💰 Finance Tracker</h2>

<a href="dashboard.php">🏠 Dashboard</a>

<a href="add_income.php">➕ Add Income</a>

<a href="add_expense.php">➖ Add Expense</a>

<a href="transactions.php">📄 Transactions</a>

<a href="reports.php">📊 Reports</a>

<a href="profile.php">👤 Profile</a>

<a href="logout.php">🚪 Logout</a>

</div>

<div class="main">

<h1>Transaction History</h1>

<form method="GET" class="search-form">

<input
type="text"
name="search"
placeholder="Search Category or Description"
value="<?php echo $search; ?>">

<input
type="date"
name="from"
value="<?php echo $from; ?>">

<input
type="date"
name="to"
value="<?php echo $to; ?>">

<button type="submit">

Search

</button>

<a href="transactions.php">

<button type="button">

Reset

</button>

</a>

</form>

<table>

<tr>

<th>ID</th>

<th>Type</th>

<th>Category</th>

<th>Amount</th>

<th>Description</th>

<th>Date</th>

<th>Balance</th>

<th>Action</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($result)){

if($row['type']=="Income"){

$balance += $row['amount'];

$typeBadge="<span class='income'>Income</span>";

}else{

$balance -= $row['amount'];

$typeBadge="<span class='expense'>Expense</span>";

}

?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $typeBadge; ?></td>

<td><?php echo $row['category']; ?></td>

<td>₹<?php echo number_format($row['amount'],2); ?></td>

<td><?php echo $row['description']; ?></td>

<td><?php echo $row['transaction_date']; ?></td>

<td>

₹<?php echo number_format($balance,2); ?>

</td>

<td>

<a href="edit_transaction.php?id=<?php echo $row['id']; ?>">

<button class="edit-btn">

Edit

</button>

</a>

<a
href="delete_transaction.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this transaction?')">

<button class="delete-btn">

Delete

</button>

</a>

</td>

</tr>

<?php

}

?>

</table>

<?php if($totalPages > 1){ ?>

<div class="pagination">

<?php

if($page > 1){
    echo "<a href='transactions.php?page=".($page-1)."'>Previous</a>";
}

for($i = 1; $i <= $totalPages; $i++){
    echo "<a href='transactions.php?page=$i'>$i</a>";
}

if($page < $totalPages){
    echo "<a href='transactions.php?page=".($page+1)."'>Next</a>";
}

?>

</div>

<?php } ?>

</body>

</html>