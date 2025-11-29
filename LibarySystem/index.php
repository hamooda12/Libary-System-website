<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System</title>
</head>
<body>
    <h1>Library System Dashboard</h1>
    <p>Welcome, <?php session_start(); echo htmlspecialchars($_SESSION['username']); ?>!</p>
    <h2>Authors List</h2>
    <?php
        // The authors table will be displayed here from classes/author.php
        require '../classes/author.php';
    ?>  
</body>
</html>