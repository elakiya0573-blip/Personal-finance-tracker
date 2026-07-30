<?php

include("auth.php");
include("db.php");

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$sql = "DELETE FROM transactions
        WHERE id='$id'
        AND user_id='$user_id'";

mysqli_query($conn,$sql);

header("Location: transactions.php");

exit();

?>