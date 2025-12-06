<?php
require '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $_SESSION['error'] = "Please fill in all fields.";
        header('Location: ../views/login.php');
        exit();
    }

    $sql = "SELECT username, email, password, role FROM user WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        $_SESSION['error'] = "Unable to process your request. Please try again.";
        header('Location: ../views/login.php');
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $userType = trim($user['role']);
                header('Location: ../views/index.php');
            exit();
        }
         else{
        
            $_SESSION['error'] = "Invalid password Or userName";
            header('Location: ../views/login.php');
            exit();
        
    } 
    }
   


}
?>