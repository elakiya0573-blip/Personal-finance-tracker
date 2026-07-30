<?php
include("auth.php");
include("db.php");

$user_id = $_SESSION['user_id'];

// Totals
$sql = "SELECT SUM(amount) AS totalIncome FROM transactions WHERE user_id='$user_id' AND type='Income'";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);
$totalIncome = $row['totalIncome'] ?? 0;

$sql = "SELECT SUM(amount) AS totalExpense FROM transactions WHERE user_id='$user_id' AND type='Expense'";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);
$totalExpense = $row['totalExpense'] ?? 0;

$balance = $totalIncome - $totalExpense;

// Filters
$month = $_GET['month'] ?? "";
$from  = $_GET['from'] ?? "";
$to    = $_GET['to'] ?? "";

$monthlyQuery = "SELECT * FROM transactions WHERE user_id ='$user_id'";
if ($month != "") {
   $monthlyQuery .= " AND DATE_FORMAT(transaction_date, '%Y-%m')= '" .$month."'";
}
if ($from != "" && $to != "") {
   $monthlyQuery .= " AND transaction_date BETWEEN '$from' AND '$to'";
}
$monthlyQuery .= " ORDER BY transaction_date DESC";
$monthlyResult = mysqli_query($conn,$monthlyQuery);
?>

<!DOCTYPE html>
<html>
<head>
<title>Financial Reports</title>
<link rel="stylesheet" href="dashboard.css">
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
<h1>Financial Reports</h1>

<div class="cards">
  <div class="card"><h3>Total Income</h3><h2 style="color:green;">₹<?php echo number_format($totalIncome,2); ?></h2></div>
  <div class="card"><h3>Total Expense</h3><h2 style="color:red;">₹<?php echo number_format($totalExpense,2); ?></h2></div>
  <div class="card"><h3>Current Balance</h3><h2 style="color:blue;">₹<?php echo number_format($balance,2); ?></h2></div>
</div>

<hr><br>

<h2>Report Filter</h2>
<form method="GET">
  <label for="from">From:</label>
  <input type="date" id="from" name="from" value="<?php echo $from; ?>">
  <label for="to">To:</label>
  <input type="date" id="to" name="to" value="<?php echo $to; ?>">
  <label for="month">Month:</label>
  <input type="month" id="month" name="month" value="<?php echo $month; ?>">
  <button type="submit">Filter</button>
  <a href="reports.php"><button type="button" class="reset-btn">Reset</button></a>
</form>

<br>

<div class="table-container">
<?php
if(mysqli_num_rows($monthlyResult) > 0){
    echo "<table><tr><th>Date</th><th>Type</th><th>Category</th><th>Amount</th><th>Description</th></tr>";
    while($row = mysqli_fetch_assoc($monthlyResult)){
        echo "<tr>";
        echo "<td>".$row['transaction_date']."</td>";
        echo "<td>".($row['type']=="Income" ? "<span class='income'>Income</span>" : "<span class='expense'>Expense</span>")."</td>";
        echo "<td>".$row['category']."</td>";
        echo "<td>₹".number_format($row['amount'],2)."</td>";
        echo "<td>".$row['description']."</td>";
        echo "</tr>";
    }
    echo "</table>";
}else{
    echo "<table><tr><th>Date</th><th>Type</th><th>Category</th><th>Amount</th><th>Description</th></tr>";
    echo "<tr><td colspan='5' style='text-align:center;'>No Transactions Found</td></tr></table>";
}
?>
</div>

<div class="charts">
  <div class="chart-card"><h3>Income vs Expense</h3><canvas id="pieChart"></canvas></div>
  <div class="chart-card"><h3>Expenses by Category</h3><canvas id="barChart"></canvas></div>
  <div class="chart-card"><h3>Category-wise Expenses</h3><canvas id="categoryPieChart"></canvas></div>
</div>

<button id="downloadPDF">Download Report as PDF</button>
<a href="export_excel.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&month=<?php echo $month; ?>">
  <button class="reset-btn">Download Excel</button>
</a>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const from = urlParams.get("from") || "";
  const to   = urlParams.get("to") || "";
  const month = urlParams.get("month") || "";

  fetch(`transactions_api.php?from=${from}&to=${to}&month=${month}`)
    .then(response => response.json())
    .then(data => {
      // Pie chart: Income vs Expense
      const pie = document.getElementById("pieChart");
      if (pie) {
        new Chart(pie, {
          type: "pie",
          data: {
            labels: ["Income", "Expense"],
            datasets: [{ data: [data.totals.income, data.totals.expense], backgroundColor: ["green", "red"] }]
          }
        });
      }

      // Bar chart: Expenses by Category
      const bar = document.getElementById("barChart");
      if (bar) {
        new Chart(bar, {
          type: "bar",
          data: {
            labels: data.categories.map(item => item.category),
            datasets: [{ label: "Expense", data: data.categories.map(item => parseFloat(item.total)), backgroundColor: "blue" }]
          },
          options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
      }

      // Category-wise Expense Pie
      const categoryPie = document.getElementById("categoryPieChart");
      if (categoryPie) {
        new Chart(categoryPie, {
          type: "pie",
          data: {
            labels: data.categories.map(item => item.category),
            datasets: [{ data: data.categories.map(item => parseFloat(item.total)), backgroundColor: ["#FF6384","#36A2EB","#FFCE56","#4BC0C0","#9966FF","#FF9F40","#66FF66","#FF6666"] }]
          },
          options: { responsive: true }
        });
      }
    })
    .catch(error => console.error("Error loading data:", error));
});
</script>

<!-- PDF libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- Export to PDF -->
<script>
document.getElementById("downloadPDF").addEventListener("click", () => {
  const { jsPDF } = window.jspdf;
  const pdf = new jsPDF("p", "mm", "a4");
  html2canvas(document.querySelector(".charts")).then(canvas => {
    const imgData = canvas.toDataURL("image/png");
    const imgWidth = 190;
    const pageHeight = 295;
    const imgHeight = canvas.height * imgWidth / canvas.width;
    let heightLeft = imgHeight;
    let position = 10;

    pdf.addImage(imgData, "PNG", 10, position, imgWidth, imgHeight);
    heightLeft -= pageHeight;

    while (heightLeft > 0) {
      position = heightLeft - imgHeight;
      pdf.addPage();
      pdf.addImage(imgData, "PNG", 10, position, imgWidth, imgHeight);
      heightLeft -= pageHeight;
    }

    pdf.save("Financial_Report.pdf");
  });
});
</script>

</div>
</body>
</html>
