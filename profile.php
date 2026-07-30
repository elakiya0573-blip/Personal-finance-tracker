<?php
// profile.php
session_start();
include('db.php'); // your database connection

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details (adjust column names to match your DB schema)
$query = "SELECT fullname, email, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch quick financial summary
$summary_query = "
    SELECT 
        SUM(CASE WHEN type='Income' THEN amount ELSE 0 END) AS total_income,
        SUM(CASE WHEN type='Expense' THEN amount ELSE 0 END) AS total_expense
    FROM transactions 
    WHERE user_id = ?";
$stmt = $conn->prepare($summary_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$summary = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Profile - Personal Finance Tracker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        .profile-card, .summary-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin: 20px auto;
            padding: 20px;
            width: 80%;
            max-width: 600px;
        }

        .profile-card h2 {
            margin-top: 0;
            color: #007bff;
        }

        .summary-card p {
            font-size: 16px;
            margin: 8px 0;
        }

        .actions {
            text-align: center;
            margin: 20px;
        }

        .actions a {
            display: inline-block;
            margin: 10px;
            padding: 10px 15px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .actions a:hover {
            background: #0056b3;
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #ddd;
            display: block;
            margin: 0 auto 15px auto;
        }
    </style>
</head>
<body>
    <h1>User Profile</h1>
    <div class="profile-card">

    <div class="profile-info">
        <img src="images/profile.png" class="avatar" alt="Profile Picture">

        <div class="details">
            <h2><?php echo htmlspecialchars($user['fullname']); ?></h2>

            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

            <p>Member since: <?php echo date("F j, Y", strtotime($user['created_at'])); ?></p>
        </div>
    </div>

</div>


    <h2>Quick Financial Summary</h2>
    <div class="summary-card">
        <p>Total Income: ₹<?php echo number_format($summary['total_income'], 2); ?></p>
        <p>Total Expense: ₹<?php echo number_format($summary['total_expense'], 2); ?></p>
        <p>Net Savings: ₹<?php echo number_format($summary['total_income'] - $summary['total_expense'], 2); ?></p>
    </div>

    <div class="actions">
        <a href="reports.php">View Detailed Reports</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    
</body>
</html>
