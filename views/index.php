<?php
session_start();
$username = $_SESSION['username'] ?? '';
$role = $_SESSION['role'] ?? '';

include '../classes/author.php';
include '../classes/books.php';
include '../classes/publisher.php';
include '../classes/borrower.php';
include '../classes/loan.php';
include '../classes/sale.php';
include '../classes/borrowertype.php';
include '../classes/loanperiod.php';
include '../includes/helper.php';
include '../includes/reports.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$originalBooks = $getAllBooks;
$originalBorrowers = $getAllBorrowers;
$username=isset($_SESSION['username']) ? $_SESSION['username'] : 'user';
$role=isset($_SESSION['role']) ? $_SESSION['role'] : 'admin';
// Handle search for each table
$searchBooks = isset($_POST['search_books']) ? trim($_POST['search_books']) : '';
$searchAuthors = isset($_POST['search_authors']) ? trim($_POST['search_authors']) : '';
$searchPublishers = isset($_POST['search_publishers']) ? trim($_POST['search_publishers']) : '';
$searchBorrowers = isset($_POST['search_borrowers']) ? trim($_POST['search_borrowers']) : '';
$searchLoans = isset($_POST['search_loans']) ? trim($_POST['search_loans']) : '';
$searchSales = isset($_POST['search_sales']) ? trim($_POST['search_sales']) : '';

// Filter Books
if (!empty($searchBooks)) {
    $filteredBooks = [];
    foreach ($getAllBooks as $book) {
        if (
    stripos($book['title'], $searchBooks) !== false ||
    stripos($book['category'], $searchBooks) !== false ||
    stripos($book['book_type'], $searchBooks) !== false ||
    stripos((string)$book['original_price'], $searchBooks) !== false ||
    strtolower($searchBooks) === 'yes' && $book['available'] == 1||
    strtolower($searchBooks) === 'no' && $book['available'] == 0) {
            
            $filteredBooks[] = $book;
        }
    }
    $getAllBooks = $filteredBooks;
}

// Filter Authors
if (!empty($searchAuthors)) {
    $filteredAuthors = [];
    foreach ($getAllAuthors as $author) {
        if (stripos($author['first_name'], $searchAuthors) !== false || 
            stripos($author['last_name'], $searchAuthors) !== false || 
            stripos($author['country'], $searchAuthors) !== false||
             stripos($author['bio'], $searchAuthors) !== false)
            
            {
            $filteredAuthors[] = $author;
        }
    }
    $getAllAuthors = $filteredAuthors;
}

// Filter Publishers
if (!empty($searchPublishers)) {
    $filteredPublishers = [];
    foreach ($getAllPublishers as $publisher) {
        if (stripos($publisher['name'], $searchPublishers) !== false || 
            stripos($publisher['city'], $searchPublishers) !== false || 
            stripos($publisher['country'], $searchPublishers) !== false) {
            $filteredPublishers[] = $publisher;
        }
    }
    $getAllPublishers = $filteredPublishers;
}

// Filter Borrowers
if (!empty($searchBorrowers)) {
    $filteredBorrowers = [];
    foreach ($getAllBorrowers as $borrower) {
        if (stripos($borrower['first_name'], $searchBorrowers) !== false || 
            stripos($borrower['last_name'], $searchBorrowers) !== false) {
            $filteredBorrowers[] = $borrower;
        }
    }
    $getAllBorrowers = $filteredBorrowers;
}

// Filter Loans (search by borrower name or book title - need to get related data)
if (!empty($searchLoans)) {
    $filteredLoans = [];
    // Use original data for building maps
    $borrowerMap = [];
    foreach ($originalBorrowers as $borrower) {
        $borrowerMap[$borrower['borrower_id']] = $borrower['first_name'] . ' ' . $borrower['last_name'];
    }
    $bookMap = [];
    foreach ($originalBooks as $book) {
        $bookMap[$book['book_id']] = $book['title'];
    }
    
    foreach ($getAllLoans as $loan) {
        $borrowerName = isset($borrowerMap[$loan['borrower_id']]) ? $borrowerMap[$loan['borrower_id']] : '';
        $bookTitle = isset($bookMap[$loan['book_id']]) ? $bookMap[$loan['book_id']] : '';
        if (stripos($borrowerName, $searchLoans) !== false || 
            stripos($bookTitle, $searchLoans) !== false ||
            stripos($loan['loan_date'], $searchLoans) !== false) {
            $filteredLoans[] = $loan;
        }
    }
    $getAllLoans = $filteredLoans;
}

// Filter Sales (similar to loans)
if (!empty($searchSales)) {
    $filteredSales = [];
    // Use original data for building maps
    $borrowerMap = [];
    foreach ($originalBorrowers as $borrower) {
        $borrowerMap[$borrower['borrower_id']] = $borrower['first_name'] . ' ' . $borrower['last_name'];
    }
    $bookMap = [];
    foreach ($originalBooks as $book) {
        $bookMap[$book['book_id']] = $book['title'];
    }
    
    foreach ($getAllSales as $sale) {
        $borrowerName = isset($borrowerMap[$sale['borrower_id']]) ? $borrowerMap[$sale['borrower_id']] : '';
        $bookTitle = isset($bookMap[$sale['book_id']]) ? $bookMap[$sale['book_id']] : '';
        if (stripos($borrowerName, $searchSales) !== false || 
            stripos($bookTitle, $searchSales) !== false ||
            stripos($sale['sale_date'], $searchSales) !== false) {
            $filteredSales[] = $sale;
        }
    }
    $getAllSales = $filteredSales;
}

$allAuthors = JSON_ENCODE($getAllAuthors);// عشان أبعتهم للجافا سكريبت
$allBooks = JSON_ENCODE($getAllBooks);
$allPublishers = JSON_ENCODE($getAllPublishers);
$allBorrowers = JSON_ENCODE($getAllBorrowers);
$allLoans = JSON_ENCODE($getAllLoans);
$allSales = JSON_ENCODE($getAllSales);
$numTotalBooks = countRows('book', $conn);
$numAvailableBooks = countRows('book WHERE available = 1', $conn);
$numTotalBorrowers = countRows('borrower', $conn);
$numActiveLoans = countRows('loan WHERE return_date IS NULL', $conn);
$loansPerMonth = JSON_ENCODE(getLoansPerMonth($conn));
$categoryDistribution = JSON_ENCODE(getCategoryDistribution($conn));
$getAllBorrowersTypes = JSON_ENCODE(display('borrowertype', $conn));
$getAllLoanPeriods = JSON_ENCODE(display('loanperiod', $conn));
$getAllnotsoldBooks = JSON_ENCODE(getNotsoldBooks($conn));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">

        <nav class="col-12 col-md-3 col-lg-2 sidebar">
            <div class="brand">Library Dashboard</div>

            <div class="mt-3 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Current Role:</span>
                    <span class="badge bg-primary role-badge" id="currentRole"><?php echo $role?></span>
                </div>
            </div>

            <hr>

            <div class="d-grid gap-2 mt-2">
                <button class="btn btn-outline-light nav-button active" data-target="section-dashboard">Dashboard</button>
                <button class="btn btn-outline-light nav-button" data-target="section-books">Books</button>
                <button class="btn btn-outline-light nav-button" data-target="section-authors">Authors</button>
                <button class="btn btn-outline-light nav-button" data-target="section-publishers">Publishers</button>
                <button class="btn btn-outline-light nav-button" data-target="section-borrowers">Borrowers</button>
                <button class="btn btn-outline-light nav-button" data-target="section-loans">Loans</button>
                <button class="btn btn-outline-light nav-button" data-target="section-sales">Sales</button>
                <button class="btn btn-outline-light nav-button" data-target="section-reports">Reports</button>
                <button class="btn btn-outline-light nav-button" data-target="section-programmers">Programmer Info</button>

                <button class="btn btn-danger mt-3" id="btn-logout" href="login.php">Log Out</button>
            </div>

            <div class="mt-auto pt-3 small text-secondary text-center">
                &copy; 2025 Library System
            </div>
        </nav>

        <main class="col-12 col-md-9 col-lg-10 content-wrapper">

<!-- Overlay -->
<div id="overlay" class="overlay"></div>

<!-- Modal Book -->
<div id="modalBook" class="modal">
    <span id="closeModalBook" class="close">&times;</span>
    <h2>Update Book</h2>
<form id="formBook" class="modal-form" action="../classes/books.php" method="post">
 <input type="hidden" name="method" value="updateBook">
<label class="form-label" for="bookId">Book ID</label>
 <input type="text" id="bookId" class="form-control" name="book_id" readonly >

<label class="form-label" for="bookTitle">Title</label>
<input type="text" id="bookTitle" class="form-control" placeholder="Book Title" name="title">

        <label class="form-label" for="bookPublisher">Publisher_Id</label>
        <input type="number" id="bookPublisher" class="form-control" placeholder="Publisher_Id" name="publisher_id">

        <label class="form-label" for="bookCategory">Category</label>
        <input type="text" id="bookCategory" class="form-control" placeholder="Category" name="category">

        <label class="form-label" for="bookType">Type</label>
        <input type="text" id="bookType" class="form-control" placeholder="Type" name="type">

        <label class="form-label" for="bookPrice">Price</label>
        <input type="number" id="bookPrice" class="form-control" placeholder="Price" name="price" step="0.01">

        <label class="form-label" for="bookAvailable">Available</label>
        <select id="bookAvailable" class="form-select" name="available">
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>

        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
</div>
<!-- Overlay (Reuse the same overlay or separate if you want) -->
<div id="overlayDelete" class="overlay"></div>

<!-- Modal Delete Book -->
<div id="modalDeleteBook" class="modal">
    <span id="closeModalDeleteBook" class="close">&times;</span>
    <h2>Confirm Delete</h2>
    <p>Are you sure you want to delete this book?</p>

    <form id="formDeleteBook" action="../classes/books.php" method="post">
        <input type="hidden" name="method" value="deleteBook">
        <input type="hidden" id="deleteBookId" name="book_id">
        <div style="display: flex; gap: 10px; justify-content: space-between; margin-top: 20px;">
            <button type="button" class="btn btn-secondary" id="btnCancelDelete">Cancel</button>
            <button type="submit" class="btn btn-danger">Yes, Delete</button>
        </div>
    </form>
</div>
<div id="forginkey" class="modal-error" style="display: none;">
    <span id="closePforginkey" class="close">&times;</span>
    <h2>Foreign Key Error</h2>
    <p>Please try again. The Foreign Key is used in another table. Recheck the ID.</p>
</div>

<div id="publisherModel" class="modal-error" style="display: none;">
    <span id="closePublisherModal" class="close">&times;</span>
    <h2>Wrong Publisher ID</h2>
    <p>Please try again. The Publisher ID does not exist. Recheck the ID.</p>
</div>


            <section id="section-dashboard" class="section-view active">
                <h2 class="section-title">Dashboard Overview</h2>
                <p id="welcomeUser" class="welcome-text mb-4">welcome <?php echo $username ?></p>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="card card-stat p-3">
                            <div class="text-muted">Total Books</div>
                            <h3 id="statTotalBooks">0</h3>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="card card-stat p-3">
                            <div class="text-muted">Available Books</div>
                            <h3 id="statAvailableBooks">0</h3>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="card card-stat p-3">
                            <div class="text-muted">Total Borrowers</div>
                            <h3 id="statBorrowers">0</h3>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="card card-stat p-3">
                            <div class="text-muted">Active Loans</div>
                            <h3 id="statActiveLoans">0</h3>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <div class="card card-stat p-3">
                            <h5>Books per Category</h5>
                            <canvas id="chartBooksByCategory"></canvas>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="card card-stat p-3">
                            <h5>Loans per Month</h5>
                            <canvas id="chartLoansPerMonth"></canvas>
                        </div>
                    </div>
                </div>
            </section>

            <section id="section-books" class="section-view">
                <h2 class="section-title">Books</h2>

                <div class="card mb-4 admin-only">
                    <div class="card-header">Insert New Book</div>
                    <div class="card-body">
                        <form id="formBookInsert" action="../classes/books.php" method="post">
                            <input type="hidden" name="method" value="insertBook">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Publisher</label>
                                    <select class="form-select" name="publisher_id" id="publisherSelect">
                                        <option value="">Select Publisher</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Category</label>
                                    <input type="text" class="form-control" name="category">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Book Type</label>
                                    <input type="text" class="form-control" name="book_type">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Original Price</label>
                                    <input type="number" step="0.01" class="form-control" name="original_price">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Available</label>
                                    <select class="form-select" name="available">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3" id="insertBook">Insert Book</button>
                        </form>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <form method="POST" action="#section-books" class="d-flex gap-2">
                            <input type="text" class="form-control" name="search_books" placeholder="Search books by title, category, or type..." value="<?php echo htmlspecialchars($searchBooks ?? ''); ?>">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <?php if (!empty($searchBooks)): ?>
                            <a href="index.php#section-books" class="btn btn-secondary">Clear</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span>Books List</span>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-hover mb-0" id="tableBooks">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Publisher</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Original Price</th>
                                    <th>Available</th>
                                    <th class="admin-only">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="section-authors" class="section-view">
                <h2 class="section-title">Authors</h2>

                <div class="card mb-4 admin-only">
                    <div class="card-header">Insert New Author</div>
                    <div class="card-body">
                        <form id="formAuthorInsert" 
                    action="../classes/author.php" method="post">
                            <input type="hidden" name="method" value="insertAuthor">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Country</label>
                                    <input type="text" class="form-control" name="country">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Bio</label>
                                    <textarea class="form-control" name="bio"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Insert Author</button>
                        </form>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <form method="POST" action="#section-authors" class="d-flex gap-2">
                            <input type="text" class="form-control" name="search_authors" placeholder="Search authors by name or country..." value="<?php echo htmlspecialchars($searchAuthors ?? ''); ?>">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <?php if (!empty($searchAuthors)): ?>
                            <a href="index.php#section-authors" class="btn btn-secondary">Clear</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span>Authors List</span>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-hover mb-0" id="tableAuthors">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Country</th>
                                    <th>Bio</th>
                                    <th class="admin-only">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="section-publishers" class="section-view">
                <h2 class="section-title">Publishers</h2>

                <div class="card mb-4 admin-only">
                    <div class="card-header">Insert New Publisher</div>
                    <div class="card-body">
                        <form id="formPublisherInsert" action="../classes/publisher.php" method="post">
                            <input type="hidden" name="method" value="insertPublisher">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="city">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Country</label>
                                    <input type="text" class="form-control" name="country">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Contact Info</label>
                                    <input type="text" class="form-control" name="contact_info">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Insert Publisher</button>
                        </form>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <form method="POST" action="#section-publishers" class="d-flex gap-2">
                            <input type="text" class="form-control" name="search_publishers" placeholder="Search publishers by name, city, or country..." value="<?php echo htmlspecialchars($searchPublishers ?? ''); ?>">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <?php if (!empty($searchPublishers)): ?>
                            <a href="index.php#section-publishers" class="btn btn-secondary">Clear</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span>Publishers List</span>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-hover mb-0" id="tablePublishers">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>City</th>
                                    <th>Country</th>
                                    <th>Contact Info</th>
                                    <th class="admin-only">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </section>


            <section id="section-borrowers" class="section-view">
                <h2 class="section-title">Borrowers</h2>

                <div class="card mb-4 admin-only">
                    <div class="card-header">Insert New Borrower</div>
                    <div class="card-body">
                        <form id="formBorrowerInsert" action ="../classes/borrower.php" method="post">
                            <input type="hidden" name="method" value="insertBorrower">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Type</label>
                                    <select id= "borrowerTypeSelect" class="form-select" name="type_id">
                                        <option value="">Select Type</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Contact Info</label>
                                    <input type="text" class="form-control" name="contact_info">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Insert Borrower</button>
                        </form>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <form method="POST" action="#section-borrowers" class="d-flex gap-2">
                            <input type="text" class="form-control" name="search_borrowers" placeholder="Search borrowers by name..." value="<?php echo htmlspecialchars($searchBorrowers ?? ''); ?>">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <?php if (!empty($searchBorrowers)): ?>
                            <a href="index.php#section-borrowers" class="btn btn-secondary">Clear</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span>Borrowers List</span>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-hover mb-0" id="tableBorrowers">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Type</th>
                                    <th>Contact Info</th>
                            
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </section>


            <section id="section-loans" class="section-view">
                <h2 class="section-title">Loans</h2>

                <div class="card mb-4 admin-only">
                    <div class="card-header">Insert New Loan</div>
                    <div class="card-body">
                        <form id="formLoanInsert" action="../classes/loan.php" method="post">
                            <input type="hidden" name="method" value="insertLoan">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Borrower</label>
                                    <select id="LoanBorrowerTypeSelect" class="form-select" name="borrower_id">
                                        <option value="">Select Borrower</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Book</label>
                                    <select id="LoanBookTypeSelect" class="form-select" name="book_id">
                                        <option value="">Select Book</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Loan Period</label>
                                    <select id="LoanPeriodTypeSelect" class="form-select" name="period_id">
                                        <option value="">Select Period</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Loan Date</label>
                                    <input type="date" class="form-control" name="loan_date" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Due Date</label>
                                    <input type="date" class="form-control" name="due_date" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Return Date</label>
                                    <input type="date" class="form-control" name="return_date">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Insert Loan</button>
                        </form>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <form method="POST" action="#section-loans" class="d-flex gap-2">
                            <input type="text" class="form-control" name="search_loans" placeholder="Search loans by borrower name, book title, or date..." value="<?php echo htmlspecialchars($searchLoans ?? ''); ?>">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <?php if (!empty($searchLoans)): ?>
                            <a href="index.php#section-loans" class="btn btn-secondary">Clear</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span>Loans List</span>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-hover mb-0" id="tableLoans">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Borrower</th>
                                    <th>Book</th>
                                    <th>Period</th>
                                    <th>Loan Date</th>
                                    <th>Due Date</th>
                                    <th>Return Date</th>
                            
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </section>


            <section id="section-sales" class="section-view">
                <h2 class="section-title">Sales</h2>

                <div class="card mb-4 admin-only">
                    <div class="card-header">Insert New Sale</div>
                    <div class="card-body">
                        <form id="formSaleInsert" action="../classes/sale.php" method="post">
                            <input type="hidden" name="method" value="insertSale">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Book</label>
                                    <select id="SaleBookList" class="form-select" name="book_id">
                                        <option value="">Select Book</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Customer / Borrower</label>
                                    <select id="SaleBorrowerList" class="form-select" name="borrower_id">
                                        <option value="">Select Borrower</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Sale Date</label>
                                    <input type="date" class="form-control" name="sale_date" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Sale Price</label>
                                    <input type="number" step="0.01" class="form-control" name="sale_price" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Insert Sale</button>
                        </form>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <form method="POST" action="#section-sales" class="d-flex gap-2">
                            <input type="text" class="form-control" name="search_sales" placeholder="Search sales by borrower name, book title, or date..." value="<?php echo htmlspecialchars($searchSales ?? ''); ?>">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <?php if (!empty($searchSales)): ?>
                            <a href="index.php#section-sales" class="btn btn-secondary">Clear</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span>Sales List</span>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-hover mb-0" id="tableSales">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Book</th>
                                    <th>Borrower</th>
                                    <th>Sale Date</th>
                                    <th>Sale Price</th>
                        
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </section>


            <section id="section-reports" class="section-view">
                <h2 class="section-title">Reports</h2>

                <!-- Author Reports -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Author Reports</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <h5>1. Total Number of Books for Each Author</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Author ID</th>
                                                <th>Author Name</th>
                                                <th>Total Books</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $authorBookCounts = getAuthorBookCounts($conn);
                                            foreach ($authorBookCounts as $author): 
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($author['author_id']); ?></td>
                                                <td><?php echo htmlspecialchars($author['author_name']); ?></td>
                                                <td><?php echo htmlspecialchars($author['total_books']); ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <h5>2. Total Sales/Profit of Books for Each Author</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Author ID</th>
                                                <th>Author Name</th>
                                                <th>Total Sales</th>
                                                <th>Number of Sales</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $authorSales = getAuthorSalesProfit($conn);
                                            foreach ($authorSales as $sale): 
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($sale['author_id']); ?></td>
                                                <td><?php echo htmlspecialchars($sale['author_name']); ?></td>
                                                <td>$<?php echo number_format($sale['total_sales'], 2); ?></td>
                                                <td><?php echo htmlspecialchars($sale['total_sales_count']); ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <h5>3. List of All Books Written by Each Author</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Author ID</th>
                                                <th>Author Name</th>
                                                <th>Book ID</th>
                                                <th>Book Title</th>
                                                <th>Category</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $authorBooks = getAuthorBooksList($conn);
                                            foreach ($authorBooks as $book): 
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($book['author_id']); ?></td>
                                                <td><?php echo htmlspecialchars($book['author_name']); ?></td>
                                                <td><?php echo htmlspecialchars($book['book_id'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($book['title'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($book['category'] ?? 'N/A'); ?></td>
                                                <td>$<?php echo $book['original_price'] ? number_format($book['original_price'], 2) : 'N/A'; ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Borrower Reports -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Borrower Reports</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <h5>4. Number of Borrowed Books per Borrower</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Borrower ID</th>
                                                <th>Borrower Name</th>
                                                <th>Total Borrowed Books</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $borrowerCounts = getBorrowerBookCounts($conn);
                                            foreach ($borrowerCounts as $borrower): 
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($borrower['borrower_id']); ?></td>
                                                <td><?php echo htmlspecialchars($borrower['borrower_name']); ?></td>
                                                <td><?php echo htmlspecialchars($borrower['total_borrowed_books']); ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <h5>5. Borrowers Who Are Late Returning Books</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Borrower ID</th>
                                                <th>Borrower Name</th>
                                                <th>Book Title</th>
                                                <th>Loan Date</th>
                                                <th>Due Date</th>
                                                <th>Days Late</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $lateBorrowers = getLateBorrowers($conn);
                                            if (empty($lateBorrowers)): 
                                            ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No late borrowers found.</td>
                                            </tr>
                                            <?php else: ?>
                                            <?php foreach ($lateBorrowers as $late): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($late['borrower_id']); ?></td>
                                                <td><?php echo htmlspecialchars($late['borrower_name']); ?></td>
                                                <td><?php echo htmlspecialchars($late['book_title'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($late['loan_date']); ?></td>
                                                <td><?php echo htmlspecialchars($late['due_date']); ?></td>
                                                <td><span class="badge bg-danger"><?php echo htmlspecialchars($late['days_late']); ?> days</span></td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <h5>6. Most Active Borrower</h5>
                                <div class="table-responsive">
                                    <?php 
                                    $mostActive = getMostActiveBorrower($conn);
                                    if ($mostActive): 
                                    ?>
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Borrower ID</th>
                                                <th>Borrower Name</th>
                                                <th>Total Loans</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo htmlspecialchars($mostActive['borrower_id']); ?></td>
                                                <td><?php echo htmlspecialchars($mostActive['borrower_name']); ?></td>
                                                <td><strong><?php echo htmlspecialchars($mostActive['total_loans']); ?></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?php else: ?>
                                    <p class="text-muted">No borrower data available.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Reports -->
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">Sales Reports</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="card card-stat p-3">
                                    <h5>7. Total Sum of All Sold Books</h5>
                                    <?php 
                                    $totalSales = getTotalSales($conn);
                                    ?>
                                    <h3>$<?php echo number_format($totalSales['total_sales'], 2); ?></h3>
                                    <p class="text-muted mb-0">Total Books Sold: <?php echo $totalSales['total_books_sold']; ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-stat p-3">
                                    <h5>8. Best-Selling Book</h5>
                                    <?php 
                                    $bestSeller = getBestSellingBook($conn);
                                    if ($bestSeller): 
                                    ?>
                                    <h4><?php echo htmlspecialchars($bestSeller['title']); ?></h4>
                                    <p class="text-muted mb-0">
                                        Sales Count: <strong><?php echo $bestSeller['sales_count']; ?></strong><br>
                                        Total Revenue: <strong>$<?php echo number_format($bestSeller['total_revenue'], 2); ?></strong>
                                    </p>
                                    <?php else: ?>
                                    <p class="text-muted">No sales data available.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <h5>9. Monthly Sales Statistics</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Year</th>
                                                <th>Month</th>
                                                <th>Total Sales</th>
                                                <th>Total Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $monthlyStats = getMonthlySalesStats($conn);
                                            if (empty($monthlyStats)): 
                                            ?>
                                            <tr>
                                                <td colspan="4" class="text-center">No monthly sales data available.</td>
                                            </tr>
                                            <?php else: ?>
                                            <?php foreach ($monthlyStats as $stat): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($stat['sale_year']); ?></td>
                                                <td><?php echo htmlspecialchars($stat['month_name']); ?></td>
                                                <td><?php echo htmlspecialchars($stat['total_sales']); ?></td>
                                                <td>$<?php echo number_format($stat['total_revenue'], 2); ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <section id="section-programmers" class="section-view">
                <h2 class="section-title">Programmer Info</h2>

                <div class="card p-4">
                    <h4 class="mb-4 text-center">Development Team</h4>
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <img src="../assets/images/profile1.png" alt="Nizar Masalma" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                                    <h5 class="card-title">Nizar Masalma</h5>
                                    <p class="card-text text-muted">Frontend Developer and simulator of the project. Creating the frontend of the project and the simulator.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <img src="../assets/images/profile2.png" alt="Saeed Awad" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;" >
                                    <h5 class="card-title">Saeed Awad</h5>
                                    <p class="card-text text-muted">Backend Developer with expertise in database design and PHP. Focuses on building robust and scalable systems.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <img src="../assets/images/profile3.png" alt="Hamad Tarawa" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                                    <h5 class="card-title">Hamad Tarawa</h5>
                                    <p class="card-text text-muted">full stack developer. Creating the backend of the project and the database.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <img src="../assets/images/profile4.png" alt="Mohammed Sadah" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;" >
                                    <h5 class="card-title">Mohammed Sadah</h5>
                                    <p class="card-text text-muted">Software Engineer with experience in system architecture and database optimization. Ensures high performance and reliability.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="section-borrowertypes" class="section-view">
                <h2 class="section-title">Borrower Types</h2>

                <div class="card mb-4 admin-only">
                    <div class="card-header">Insert New Borrower Type</div>
                    <div class="card-body">
                        <form action="../classes/borrowertype.php" method="post">
                            <input type="hidden" name="method" value="insertBorrowerType">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Type Name</label>
                                    <input type="text" class="form-control" name="type_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" name="description">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Insert Borrower Type</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Borrower Types List</span>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-hover mb-0" id="tableBorrowerTypes">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type Name</th>
                                    <th>Description</th>
                                    <th class="admin-only">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $allBorrowerTypes = display('borrowertype', $conn);
                                foreach ($allBorrowerTypes as $type): 
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($type['type_id'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($type['type_name'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($type['description'] ?? ''); ?></td>
                                    <td class="admin-only">
                                        <form action="../classes/borrowertype.php" method="post" style="display: inline;">
                                            <input type="hidden" name="method" value="deleteBorrowerType">
                                            <input type="hidden" name="type_id" value="<?php echo htmlspecialchars($type['type_id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this borrower type?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="section-loanperiods" class="section-view">
                <h2 class="section-title">Loan Periods</h2>

                <div class="card mb-4 admin-only">
                    <div class="card-header">Insert New Loan Period</div>
                    <div class="card-body">
                        <form action="../classes/loanperiod.php" method="post">
                            <input type="hidden" name="method" value="insertLoanPeriod">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Period Name</label>
                                    <input type="text" class="form-control" name="period_name" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Days</label>
                                    <input type="number" class="form-control" name="days" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" name="description">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Insert Loan Period</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Loan Periods List</span>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-hover mb-0" id="tableLoanPeriods">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Period Name</th>
                                    <th>Days</th>
                                    <th>Description</th>
                                    <th class="admin-only">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $allLoanPeriodsData = display('loanperiod', $conn);
                                foreach ($allLoanPeriodsData as $period): 
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($period['period_id'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($period['period_name'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($period['days'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($period['description'] ?? ''); ?></td>
                                    <td class="admin-only">
                                        <form action="../classes/loanperiod.php" method="post" style="display: inline;">
                                            <input type="hidden" name="method" value="deleteLoanPeriod">
                                            <input type="hidden" name="period_id" value="<?php echo htmlspecialchars($period['period_id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this loan period?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </main>

    </div>
</div>
<!-- Overlay -->
<div id="overlay" class="overlay"></div>

<!-- Modal Author -->
<div id="modalAuthor" class="modal">
    <span id="closeModalAuthor" class="close">&times;</span>
    <h2>Update Author</h2>

    <form id="formAuthorUpdate" class="modal-form" action="../classes/author.php" method="post">
        <input type="hidden" name="method" value="updateAuthor">

        <label class="form-label" for="authorId">Author ID</label>
        <input type="text" id="authorId" class="form-control" name="author_id" readonly>

        <label class="form-label" for="authorFirst">First Name</label>
        <input type="text" id="authorFirst" class="form-control" name="first_name" placeholder="First Name">

        <label class="form-label" for="authorLast">Last Name</label>
        <input type="text" id="authorLast" class="form-control" name="last_name" placeholder="Last Name">

        <label class="form-label" for="authorCountry">Country</label>
        <input type="text" id="authorCountry" class="form-control" name="country" placeholder="Country">

        <label class="form-label" for="authorBio">Bio</label>
        <textarea id="authorBio" class="form-control" name="bio" placeholder="Short Bio"></textarea>

        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
</div>
<!-- Overlay -->
<div id="overlayDelete" class="overlay"></div>

<!-- Modal Delete Author -->
<div id="modalDeleteAuthor" class="modal">
    <span id="closeModalDeleteAuthor" class="close">&times;</span>
    <h2>Confirm Delete</h2>
    <p>Are you sure you want to delete this author?</p>

    <form id="formDeleteAuthor" action="../classes/author.php" method="post">
        <input type="hidden" name="method" value="deleteAuthor">
        <input type="hidden" id="deleteAuthorId" name="author_id">

        <div style="display: flex; gap: 10px; justify-content: space-between; margin-top: 20px;">
            <button type="button" class="btn btn-secondary" id="btnCancelDeleteAuthor">Cancel</button>
            <button type="submit" class="btn btn-danger">Yes, Delete</button>
        </div>
    </form>
</div>

<!-- Overlay -->
<div id="overlayBorrower" class="overlay"></div>

<!-- Modal Borrower -->
<div id="modalBorrower" class="modal">
    <span id="closeModalBorrower" class="close">&times;</span>
    <h2>Update Borrower</h2>

    <form id="formBorrowerUpdate" class="modal-form" action="../classes/borrowers.php" method="post">
        <input type="hidden" name="method" value="updateBorrower">

        <label class="form-label" for="borrowerId">Borrower ID</label>
        <input type="text" id="borrowerId" class="form-control" name="borrower_id" readonly>

        <label class="form-label" for="borrowerFirst">First Name</label>
        <input type="text" id="borrowerFirst" class="form-control" name="first_name" placeholder="First Name">

        <label class="form-label" for="borrowerLast">Last Name</label>
        <input type="text" id="borrowerLast" class="form-control" name="last_name" placeholder="Last Name">

        <label class="form-label" for="borrowerType">Type ID</label>
        <input type="number" id="borrowerType" class="form-control" name="type_id" placeholder="Type ID">

        <label class="form-label" for="borrowerContact">Contact Info</label>
        <input type="text" id="borrowerContact" class="form-control" name="contact_info" placeholder="Contact Info">

        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
</div>

<!-- Overlay -->
<div id="overlayDeleteBorrower" class="overlay"></div>

<!-- Modal Delete Borrower -->
<div id="modalDeleteBorrower" class="modal">
    <span id="closeModalDeleteBorrower" class="close">&times;</span>
    <h2>Confirm Delete</h2>
    <p>Are you sure you want to delete this borrower?</p>

    <form id="formDeleteBorrower" action="../classes/borrowers.php" method="post">
        <input type="hidden" name="method" value="deleteBorrower">
        <input type="hidden" id="deleteBorrowerId" name="borrower_id">

        <div style="display: flex; gap: 10px; justify-content: space-between; margin-top: 20px;">
            <button type="button" class="btn btn-secondary" id="btnCancelDeleteBorrower">Cancel</button>
            <button type="submit" class="btn btn-danger">Yes, Delete</button>
        </div>
    </form>
</div>

<!-- Overlay -->
<div id="overlayPublisher" class="overlay"></div>

<!-- Modal Publisher -->
<div id="modalPublisher" class="modal">
    <span id="closeModalPublisher" class="close">&times;</span>
    <h2>Update Publisher</h2>

    <form id="formPublisherUpdate" class="modal-form" action="../classes/publishers.php" method="post">
        <input type="hidden" name="method" value="updatePublisher">

        <label class="form-label" for="publisherId">Publisher ID</label>
        <input type="text" id="publisherId" class="form-control" name="publisher_id" readonly>

        <label class="form-label" for="publisherName">Name</label>
        <input type="text" id="publisherName" class="form-control" name="name" placeholder="Publisher Name">

        <label class="form-label" for="publisherCity">City</label>
        <input type="text" id="publisherCity" class="form-control" name="city" placeholder="City">

        <label class="form-label" for="publisherCountry">Country</label>
        <input type="text" id="publisherCountry" class="form-control" name="country" placeholder="Country">

        <label class="form-label" for="publisherContact">Contact Info</label>
        <input type="text" id="publisherContact" class="form-control" name="contact_info" placeholder="Contact Info">

        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
</div>

<!-- Overlay -->
<div id="overlayDeletePublisher" class="overlay"></div>

<!-- Modal Delete Publisher -->
<div id="modalDeletePublisher" class="modal">
    <span id="closeModalDeletePublisher" class="close">&times;</span>
    <h2>Confirm Delete</h2>
    <p>Are you sure you want to delete this publisher?</p>

    <form id="formDeletePublisher" action="../classes/publishers.php" method="post">
        <input type="hidden" name="method" value="deletePublisher">
        <input type="hidden" id="deletePublisherId" name="publisher_id">

        <div style="display: flex; gap: 10px; justify-content: space-between; margin-top: 20px;">
            <button type="button" class="btn btn-secondary" id="btnCancelDeletePublisher">Cancel</button>
            <button type="submit" class="btn btn-danger">Yes, Delete</button>
        </div>
    </form>
</div>

<!-- Overlay -->
<div id="overlayLoan" class="overlay"></div>

<!-- Modal Loan -->
<div id="modalLoan" class="modal">
    <span id="closeModalLoan" class="close">&times;</span>
    <h2>Update Loan</h2>

    <form id="formLoanUpdate" class="modal-form" action="../classes/loans.php" method="post">
        <input type="hidden" name="method" value="updateLoan">

        <label class="form-label" for="loanId">Loan ID</label>
        <input type="text" id="loanId" class="form-control" name="loan_id" readonly>

        <label class="form-label" for="loanBorrower">Borrower ID</label>
        <input type="number" id="loanBorrower" class="form-control" name="borrower_id" placeholder="Borrower ID">

        <label class="form-label" for="loanBook">Book ID</label>
        <input type="number" id="loanBook" class="form-control" name="book_id" placeholder="Book ID">

        <label class="form-label" for="loanPeriod">Period ID</label>
        <input type="number" id="loanPeriod" class="form-control" name="period_id" placeholder="Period ID">

        <label class="form-label" for="loanDate">Loan Date</label>
        <input type="date" id="loanDate" class="form-control" name="loan_date">

        <label class="form-label" for="dueDate">Due Date</label>
        <input type="date" id="dueDate" class="form-control" name="due_date">

        <label class="form-label" for="returnDate">Return Date</label>
        <input type="date" id="returnDate" class="form-control" name="return_date">

        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
</div>

<!-- Overlay -->
<div id="overlayDeleteLoan" class="overlay"></div>

<!-- Modal Delete Loan -->
<div id="modalDeleteLoan" class="modal">
    <span id="closeModalDeleteLoan" class="close">&times;</span>
    <h2>Confirm Delete</h2>
    <p>Are you sure you want to delete this loan?</p>

    <form id="formDeleteLoan" action="../classes/loans.php" method="post">
        <input type="hidden" name="method" value="deleteLoan">
        <input type="hidden" id="deleteLoanId" name="loan_id">

        <div style="display: flex; gap: 10px; justify-content: space-between; margin-top: 20px;">
            <button type="button" class="btn btn-secondary" id="btnCancelDeleteLoan">Cancel</button>
            <button type="submit" class="btn btn-danger">Yes, Delete</button>
        </div>
    </form>
</div>


<!-- Overlay -->
<div id="overlaySale" class="overlay"></div>

<!-- Modal Sale -->
<div id="modalSale" class="modal">
    <span id="closeModalSale" class="close">&times;</span>
    <h2>Update Sale</h2>

    <form id="formSaleUpdate" class="modal-form" action="../classes/sales.php" method="post">
        <input type="hidden" name="method" value="updateSale">

        <label class="form-label" for="saleId">Sale ID</label>
        <input type="text" id="saleId" class="form-control" name="sale_id" readonly>

        <label class="form-label" for="saleBookId">Book ID</label>
        <input type="number" id="saleBookId" class="form-control" name="book_id" placeholder="Book ID">

        <label class="form-label" for="saleBorrowerId">Borrower ID</label>
        <input type="number" id="saleBorrowerId" class="form-control" name="borrower_id" placeholder="Borrower ID">

        <label class="form-label" for="saleDate">Sale Date</label>
        <input type="date" id="saleDate" class="form-control" name="sale_date">

        <label class="form-label" for="salePrice">Sale Price</label>
        <input type="number" id="salePrice" class="form-control" name="sale_price" step="0.01" placeholder="Sale Price">

        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
</div>


<!-- Overlay -->
<div id="overlayDeleteSale" class="overlay"></div>

<!-- Modal Delete Sale -->
<div id="modalDeleteSale" class="modal">
    <span id="closeModalDeleteSale" class="close">&times;</span>
    <h2>Confirm Delete</h2>
    <p>Are you sure you want to delete this sale?</p>

    <form id="formDeleteSale" action="../classes/sales.php" method="post">
        <input type="hidden" name="method" value="deleteSale">
        <input type="hidden" id="deleteSaleId" name="sale_id">

        <div style="display: flex; gap: 10px; justify-content: space-between; margin-top: 20px;">
            <button type="button" class="btn btn-secondary" id="btnCancelDeleteSale">Cancel</button>
            <button type="submit" class="btn btn-danger">Yes, Delete</button>
        </div>
    </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const allAuthors = <?php echo $allAuthors; ?>;
    const allBooks = <?php echo $allBooks; ?>;
    const allPublisher = <?php echo $allPublishers; ?>;
    const allBorrowers = <?php echo $allBorrowers; ?>;
    const allLoans = <?php echo $allLoans; ?>;
    const allSales = <?php echo $allSales; ?>;
    const numTotalBooks = <?php echo $numTotalBooks; ?>;
    const numAvailableBooks = <?php echo $numAvailableBooks; ?>;
    const numTotalBorrowers = <?php echo $numTotalBorrowers; ?>;
    const numActiveLoans = <?php echo $numActiveLoans; ?>;
    const loansPerMonth = <?php echo $loansPerMonth; ?>;
    const categoryDistribution = <?php echo $categoryDistribution; ?>;
    const allBorrowersTypes = <?php echo $getAllBorrowersTypes; ?>;
    const allLoanPeriods = <?php echo $getAllLoanPeriods; ?>;
    const allNotsoldBooks = <?php echo $getAllnotsoldBooks; ?>;
   

    </script>
    <script>
// Display modal if foreign key or publisher error
document.addEventListener('DOMContentLoaded', function () {
    // Check for foreign key error in query string
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('foreign_key_error')) {
        const fkModal = document.getElementById('forginkey');
        if (fkModal) {
            fkModal.style.display = 'block';
            // Optional: Close modal handler
            document.getElementById('closePforginkey').onclick = function () {
                fkModal.style.display = 'none';
                // Remove query string from URL
              
            };
        }
    }
    // Check for publisher error in query string
    if (urlParams.has('publisher_error')) {
        const publisherModal = document.getElementById('publisherModel');
        if (publisherModal) {
            publisherModal.style.display = 'block';
            document.getElementById('closePublisherModal').onclick = function () {
                publisherModal.style.display = 'none';
                window.location.search = '';
            };
        }
    }
});
</script>
<script src="../assets/js/Main.js"></script>
</body>
    
</html>
