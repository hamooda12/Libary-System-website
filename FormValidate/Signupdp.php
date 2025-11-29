<?php
require '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role']; // صححت الاسم

    // التحقق من تعبئة جميع الحقول
    if (empty($username) || empty($password) || empty($email) || empty($role)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header('Location: ../views/Signup.php');
        exit();
    }

    // التحقق من وجود المستخدم مسبقاً
    $checkSql = "SELECT username FROM user WHERE username = ?";
    $stmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Username already exists.";
        header('Location: ../views/Signup.php');
        exit();
    }
    mysqli_stmt_close($stmt);


    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

   
    $insertSql = "INSERT INTO user(username, password, email, role) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insertSql);
    mysqli_stmt_bind_param($stmt, "ssss", $username, $passwordHash, $email, $role);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Account created successfully!";
        header("Location: ../views/dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Error creating account. Please try again.";
        header('Location: ../views/Signup.php');
        exit();
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
