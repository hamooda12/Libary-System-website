<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit();
}

// Check if user is Admin
$userRole = isset($_SESSION['role']) ? trim($_SESSION['role']) : '';
if (strcasecmp($userRole, 'Admin') !== 0) {
    header('Location: ../user/index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2>Admin Dashboard</h2>
                    </div>
                    <div class="card-body">
                        <p class="lead">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                        <p>You are logged in as an <strong>Admin</strong>.</p>
                        
                        <div class="mt-4">
                            <h4>Admin Features:</h4>
                            <ul>
                                <li>Full access to all library management features</li>
                                <li>Manage books, authors, publishers, borrowers</li>
                                <li>View and generate reports</li>
                                <li>Manage loans and sales</li>
                                <li>Access search system</li>
                            </ul>
                        </div>
                        
                        <div class="mt-4">
                            <a href="../views/index.php" class="btn btn-primary">Go to Main Dashboard</a>
                            <a href="../views/search.php" class="btn btn-info">Search System</a>
                            <a href="../FormValidate/logout.php" class="btn btn-danger">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

