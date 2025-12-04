<?php
require '../includes/db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if(empty($username) || empty($password)){
        $_SESSION['error'] = "Please fill in all fields.";
        header('Location: ../views/login.php');
        exit();
    }
    
    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect based on user type (case-insensitive check)
            $userType = trim($user['role']);
            if (strcasecmp($userType, 'Admin') === 0) {
                header('Location: ../admin/index.php');
            } else {
                header('Location: ../user/index.php');
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid password.";
            header('Location: ../views/login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid username.";
        header('Location: ../views/login.php');
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>