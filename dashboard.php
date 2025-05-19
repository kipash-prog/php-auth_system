<?php
session_start();
// Redirect to login if not logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$username = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?= htmlspecialchars($username) ?>!</h2>
    <p>This is your dashboard.</p>
    <a href="logout.php">Logout</a>
</body>
</html> 