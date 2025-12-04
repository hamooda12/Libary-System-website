<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit();
}

// Check if user is not Admin (regular User)
$userRole = isset($_SESSION['role']) ? trim($_SESSION['role']) : '';
if (strcasecmp($userRole, 'Admin') === 0) {
    header('Location: ../admin/index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h2>User Dashboard</h2>
                    </div>
                    <div class="card-body">
                        <p class="lead">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                        <p>You are logged in as a <strong>User</strong>.</p>
                        
                        <div class="mt-4">
                            <h4>User Features:</h4>
                            <ul>
                                <li>View books and authors</li>
                                <li>Search library resources</li>
                                <li>View your borrowing history</li>
                                <li>Access basic reports</li>
                            </ul>
                        </div>
                        
                        <div class="mt-4">
                            <a href="../views/index.php" class="btn btn-success">Go to Main Dashboard</a>
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

