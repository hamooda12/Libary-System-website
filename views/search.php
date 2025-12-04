<?php
require '../includes/db.php';
session_start();

// Get list of tables
$tables = [];
$result = mysqli_query($conn, "SHOW TABLES");
if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        $tables[] = $row[0];
    }
}

// Get columns for selected table
$columns = [];
$selectedTable = '';
$searchResults = [];
$searchValue = '';
$selectedColumn = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedTable = $_POST['table'] ?? '';
    $selectedColumn = $_POST['column'] ?? '';
    $searchValue = $_POST['search_value'] ?? '';
    
    if ($selectedTable) {
        // Get columns for the selected table
        $result = mysqli_query($conn, "SHOW COLUMNS FROM `$selectedTable`");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $columns[] = $row['Field'];
            }
        }
        
        // Perform search
        if ($selectedColumn && $searchValue !== '') {
            if ($selectedColumn === 'ALL') {
                // Search all columns
                $sql = "SELECT * FROM `$selectedTable`";
            } else {
                // Search specific column
                $escapedValue = mysqli_real_escape_string($conn, $searchValue);
                $sql = "SELECT * FROM `$selectedTable` WHERE `$selectedColumn` LIKE '%$escapedValue%'";
            }
            
            $result = mysqli_query($conn, $sql);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $searchResults[] = $row;
                }
            }
        } elseif ($selectedColumn === 'ALL') {
            // Show all records
            $sql = "SELECT * FROM `$selectedTable`";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $searchResults[] = $row;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Search System</h2>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Search Options</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Select Table</label>
                                    <select class="form-select" name="table" id="tableSelect" required onchange="this.form.submit()">
                                        <option value="">-- Select Table --</option>
                                        <?php foreach ($tables as $table): ?>
                                        <option value="<?php echo htmlspecialchars($table); ?>" <?php echo ($selectedTable === $table) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($table); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <?php if ($selectedTable && !empty($columns)): ?>
                                <div class="col-md-4">
                                    <label class="form-label">Select Column</label>
                                    <select class="form-select" name="column" id="columnSelect" required>
                                        <option value="">-- Select Column --</option>
                                        <option value="ALL" <?php echo ($selectedColumn === 'ALL') ? 'selected' : ''; ?>>ALL (Search All)</option>
                                        <?php foreach ($columns as $column): ?>
                                        <option value="<?php echo htmlspecialchars($column); ?>" <?php echo ($selectedColumn === $column) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($column); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Search Value</label>
                                    <input type="text" class="form-control" name="search_value" value="<?php echo htmlspecialchars($searchValue); ?>" placeholder="Enter search value">
                                    <small class="text-muted">Leave empty if searching ALL</small>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($selectedTable && !empty($columns)): ?>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <a href="search.php" class="btn btn-secondary">Reset</a>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
                
                <?php if (!empty($searchResults)): ?>
                <div class="card">
                    <div class="card-header">
                        <h4>Search Results (<?php echo count($searchResults); ?> records found)</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <?php if (!empty($searchResults)): ?>
                                        <?php foreach (array_keys($searchResults[0]) as $header): ?>
                                        <th><?php echo htmlspecialchars($header); ?></th>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($searchResults as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $value): ?>
                                        <td><?php echo htmlspecialchars($value ?? 'NULL'); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $selectedTable && $selectedColumn): ?>
                <div class="alert alert-info">
                    No results found for your search criteria.
                </div>
                <?php endif; ?>
                
                <div class="mt-4">
                    <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

