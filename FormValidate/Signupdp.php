<?php
require '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize inputs
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? '');

    // Validate required fields
    if ($username === '' || $password === '' || $email === '' || $role === '') {
        $_SESSION['error'] = "Please fill in all fields.";
        header('Location: ../views/Signup.php');
        exit();
    }

    // Check if email OR username already exists
    $checkSql = "SELECT username FROM user WHERE email = ? OR username = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $checkSql);

    if (!$stmt) {
        $_SESSION['error'] = "Unable to process your request. Please try again.";
        header('Location: ../views/Signup.php');
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $email, $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && $result->num_rows > 0) {
        $_SESSION['error'] = "Email or Username already exists.";
        header('Location: ../views/Signup.php');
        exit();
    }
    mysqli_stmt_close($stmt);

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user
    $insertSql = "INSERT INTO user(username, password, email, role) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insertSql);

    if (!$stmt) {
        $_SESSION['error'] = "Unable to create your account. Please try again.";
        header('Location: ../views/Signup.php');
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssss", $username, $passwordHash, $email, $role);

    if (mysqli_stmt_execute($stmt)) {
        // No user_id available
        $normalizedRole = strtolower($role);

        // Create session
        $_SESSION['success'] = "Account created successfully!";
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        $_SESSION['email'] = $email;
header('Location: ../views/login.php');
        exit();
    }

    // If insert failed
    $_SESSION['error'] = "Error creating account. Please try again.";
    header('Location: ../views/Signup.php');
    exit();
}
?>
