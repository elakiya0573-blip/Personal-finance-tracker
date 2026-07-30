<?php
session_start();
include("db.php");

$message = "";

if(isset($_POST['register'])){

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Check empty fields
    if(empty($fullname) || empty($email) || empty($password) || empty($confirm)){
        $message = "Please fill all fields.";
    }

    // Password match
    elseif($password != $confirm){
        $message = "Passwords do not match.";
    }

    else{

        // Check email already exists
        $check = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn,$check);

        if(mysqli_num_rows($result)>0){

            $message = "Email already registered.";

        }else{

            // Encrypt Password
            $hash = password_hash($password,PASSWORD_DEFAULT);

            $sql = "INSERT INTO users(fullname,email,password)
                    VALUES('$fullname','$email','$hash')";

            if(mysqli_query($conn,$sql)){

                echo "<script>
                alert('Registration Successful');
                window.location='login.php';
                </script>";

                exit();

            }else{

                $message = "Registration Failed.";

            }

        }

    }

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Register</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="form-container">

<h1>Create Account</h1>

<?php

if($message!=""){

echo "<p style='color:red;text-align:center;'>$message</p>";

}

?>

<form method="POST">

<label>Full Name</label>

<input
type="text"
name="fullname"
required>

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

<label>Confirm Password</label>

<input
type="password"
name="confirm_password"
required>

<button
type="submit"
name="register">

Register

</button>

</form>

<p>

Already have an account?

<a href="login.php">

Login

</a>

</p>

</div>

</body>

</html>