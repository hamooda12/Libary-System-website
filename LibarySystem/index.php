<?php
include '../classes/author.php';
$allAuthors = JSON_ENCODE($getALlAuthors);// عشان أبعتهم للجافا سكريبت
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
                        <form id="formBookInsert">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Publisher</label>
                                    <select class="form-select" name="publisher_id">
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
                            <button type="submit" class="btn btn-primary mt-3">Insert Book</button>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const allAuthors = <?php echo $allAuthors; ?>;
    console.log(allAuthors); </script>
<script src="../assets/js/Main.js"></script>
</body>
</html>
