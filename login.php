<?php
session_start();
include("db.php");

$message = "";

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT id, fullname, password FROM users WHERE email = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) == 1){

        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];

            header("Location: dashboard.php");
            exit();

        }else{

            $message = "Incorrect Password.";

        }

    }else{

        $message = "Email not found.";

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Login</title>

<link rel="stylesheet"
href="style.css">

</head>

<body>

<div class="form-container">

<h1>Login</h1>

<?php
if($message!=""){
    echo "<p style='color:red;text-align:center;'>$message</p>";
}
?>

<form method="POST">

<label>Email</label>

<input
type="email"
name="email"
required>

<label>Password</label>

<input
type="password"
name="password"
required>

<button
type="submit"
name="login">

Login

</button>

</form>

<p>

Don't have an account?

<a href="register.php">

Register

</a>

</p>

</div>

</body>

</html>