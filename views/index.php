<?php
include '../classes/author.php';
include '../classes/books.php';
include '../classes/publisher.php';
include '../classes/borrower.php';
include '../classes/loan.php';
include '../classes/sale.php';
include '../includes/helper.php';
$allAuthors = JSON_ENCODE($getAllAuthors);// عشان أبعتهم للجافا سكريبت
$allBooks = JSON_ENCODE($getAllBooks);
$allPublishers = JSON_ENCODE($getAllPublishers);
$allBorrowers = JSON_ENCODE($getAllBorrowers);
$allLoans = JSON_ENCODE($getAllLoans);
$numTotalBooks = countRows('book', $conn);
$numAvailableBooks = countRows('book WHERE available = 1', $conn);
$numTotalBorrowers = countRows('borrower', $conn);
$numActiveLoans = countRows('loan WHERE return_date IS NULL', $conn);
$loansPerMonth = JSON_ENCODE(getLoansPerMonth($conn));
$categoryDistribution = JSON_ENCODE(getCategoryDistribution($conn));
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
                    <span class="badge bg-primary role-badge" id="currentRole">-</span>
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

                <button class="btn btn-danger mt-3" id="btn-logout">Log Out</button>
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
<div id="publisherModel" class="modal-error" style="display: none;">
    <span id="closePublisherModal" class="close">&times;</span>
    <h2>Wrong Publisher ID</h2>
    <p>Please try again. The Publisher ID does not exist. Recheck the ID.</p>
</div>


            <section id="section-dashboard" class="section-view active">
                <h2 class="section-title">Dashboard Overview</h2>
                <p id="welcomeUser" class="welcome-text mb-4"></p>

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

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Books List</span>
                        <input type="text" class="form-control form-control-sm" placeholder="Search...">
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
                        <form id="formAuthorInsert">
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

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Authors List</span>
                        <input type="text" class="form-control form-control-sm" placeholder="Search...">
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
                        <form id="formPublisherInsert">
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

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Publishers List</span>
                        <input type="text" class="form-control form-control-sm" placeholder="Search...">
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
                        <form id="formBorrowerInsert">
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
                                    <select class="form-select" name="type_id">
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

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Borrowers List</span>
                        <input type="text" class="form-control form-control-sm" placeholder="Search...">
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
                                    <th class="admin-only">Actions</th>
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
                        <form id="formLoanInsert">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Borrower</label>
                                    <select class="form-select" name="borrower_id">
                                        <option value="">Select Borrower</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Book</label>
                                    <select class="form-select" name="book_id">
                                        <option value="">Select Book</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Loan Period</label>
                                    <select class="form-select" name="period_id">
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

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Loans List</span>
                        <input type="text" class="form-control form-control-sm" placeholder="Search...">
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
                                    <th class="admin-only">Actions</th>
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
                        <form id="formSaleInsert">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Book</label>
                                    <select class="form-select" name="book_id">
                                        <option value="">Select Book</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Customer / Borrower</label>
                                    <select class="form-select" name="borrower_id">
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

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Sales List</span>
                        <input type="text" class="form-control form-control-sm" placeholder="Search...">
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
                                    <th class="admin-only">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </section>


            <section id="section-reports" class="section-view">
                <h2 class="section-title">Reports</h2>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card card-stat p-3">
                            <h5>Total Value of All Books</h5>
                            <h3 id="reportTotalBooksValue">0</h3>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-stat p-3">
                            <h5>Books Currently Available</h5>
                            <h3 id="reportAvailableBooksCount">0</h3>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">Books per Category (Table)</div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped mb-0" id="reportBooksPerCategory">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Books Count</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </section>


            <section id="section-programmers" class="section-view">
                <h2 class="section-title">Programmer Info</h2>

                <div class="card p-4">
                    <h4 class="mb-3">Developers</h4>
                    <ul class="list-group">
                        <li class="list-group-item">Nizar Masalma</li>
                        <li class="list-group-item">Saeed Awad</li>
                        <li class="list-group-item">Hamad Tarawa</li>
                        <li class="list-group-item">Mohammed Sadah</li>
                    </ul>
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
    const numTotalBooks = <?php echo $numTotalBooks; ?>;
    const numAvailableBooks = <?php echo $numAvailableBooks; ?>;
    const numTotalBorrowers = <?php echo $numTotalBorrowers; ?>;
    const numActiveLoans = <?php echo $numActiveLoans; ?>;
    const loansPerMonth = <?php echo $loansPerMonth; ?>;
    const categoryDistribution = <?php echo $categoryDistribution; ?>;
    </script>
<script src="../assets/js/Main.js"></script>
</body>
<?php if(isset($_GET['publisher_error'])): ?>
<script>
    document.getElementById('publisherModel').style.display = 'block';
</script>
<?php endif; ?>

</html>
