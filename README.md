# 💰 Personal Finance Tracker

A web-based Personal Finance Tracker developed using **PHP**, **MySQL**, **HTML**, **CSS**, **JavaScript**, and **Chart.js**. This application helps users manage their income and expenses, monitor financial reports, visualize spending through charts, and export reports.

---

## 📌 Features

### 🔐 User Authentication
- User Registration
- User Login
- Secure Logout
- Session Management

### 📊 Dashboard
- View Total Income
- View Total Expense
- View Current Balance
- Quick Financial Overview

### 💵 Income Management
- Add Income
- Edit Income
- Delete Income
- View Income Records

### 💸 Expense Management
- Add Expense
- Edit Expense
- Delete Expense
- View Expense Records

### 📋 Transactions
- Display all Income & Expense Transactions
- Search Transactions
- Filter Transactions
- Edit/Delete Transactions

### 📈 Financial Reports
- Date-wise Filter
- Month-wise Filter
- Total Income Summary
- Total Expense Summary
- Current Balance
- Income vs Expense Pie Chart
- Expenses by Category Bar Chart
- Category-wise Expense Pie Chart

### 📤 Export Reports
- Download Reports as PDF
- Download Reports as Excel (CSV)

### 👤 User Profile
- Display User Details
- Profile Picture
- Quick Financial Summary
- View Detailed Reports

---

## 🛠️ Technologies Used

### Frontend
- HTML5
- CSS3
- JavaScript
- Bootstrap
- Chart.js

### Backend
- PHP

### Database
- MySQL

### Server
- XAMPP (Apache + MySQL)

---

## 📂 Project Structure

```
PersonalFinanceTracker/
│
├── images/
├── css/
├── js/
│
├── login.php
├── register.php
├── dashboard.php
├── income.php
├── expense.php
├── transactions.php
├── reports.php
├── profile.php
├── logout.php
├── export_pdf.php
├── export_excel.php
├── db.php
│
└── database.sql
```

---

## 📊 Database

The project uses MySQL with tables such as:

- users
- transactions

Each transaction stores:

- User ID
- Type (Income/Expense)
- Category
- Amount
- Description
- Transaction Date

---

## 📈 Charts Used

- Income vs Expense (Pie Chart)
- Expenses by Category (Bar Chart)
- Category-wise Expenses (Pie Chart)

Charts are generated using **Chart.js**.

---

## 🚀 Installation

### 1. Clone Repository

```bash
git clone https://github.com/elakiya0573-blip/PersonalFinanceTracker.git
```

### 2. Move Project

Copy the project folder into:

```
xampp/htdocs/
```

### 3. Start XAMPP

Start:

- Apache
- MySQL

### 4. Import Database

Open phpMyAdmin

Create a database:

```
personal_finance_tracker
```

Import:

```
database.sql
```

### 5. Configure Database

Edit `db.php`

```php
$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "personal_finance_tracker"
);
```

### 6. Run Project

Open your browser:

```
http://localhost/PersonalFinanceTracker/
```

---

## 📷 Screenshots

### Dashboard
- Displays financial overview.

### Reports
- Income vs Expense
- Category-wise Charts
- Export PDF & Excel

### Profile
- User information
- Financial Summary

---

## 🔒 Security Features

- Session Authentication
- Prepared SQL Statements
- Input Validation
- Protected User Data

---

## 📌 Future Enhancements

- Email Notifications
- Budget Planning
- Savings Goals
- Dark Mode
- Currency Selection
- Mobile Responsive Design
- Expense Predictions using Machine Learning
- AI-powered Financial Insights

---

## 👩‍💻 Author

**Elakiya S M**

B.Tech Information Technology

---

## 📜 License

This project is developed for educational and learning purposes.
