<?php
require 'db.php';

// Author Reports
function getAuthorBookCounts($conn) {
    // Total number of books for each author
    // Check if book_author table exists, otherwise use direct relationship
    $checkTable = mysqli_query($conn, "SHOW TABLES LIKE 'book_author'");
    if (mysqli_num_rows($checkTable) > 0) {
        // Junction table exists
        $sql = "SELECT a.author_id, CONCAT(a.first_name, ' ', a.last_name) AS author_name, 
                COUNT(DISTINCT ba.book_id) AS total_books
                FROM author a
                LEFT JOIN book_author ba ON a.author_id = ba.author_id
                GROUP BY a.author_id, a.first_name, a.last_name
                ORDER BY total_books DESC";
    } else {
        // Check if book table has author_id column
        $checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM book LIKE 'author_id'");
        if (mysqli_num_rows($checkColumn) > 0) {
            $sql = "SELECT a.author_id, CONCAT(a.first_name, ' ', a.last_name) AS author_name, 
                    COUNT(b.book_id) AS total_books
                    FROM author a
                    LEFT JOIN book b ON b.author_id = a.author_id
                    GROUP BY a.author_id, a.first_name, a.last_name
                    ORDER BY total_books DESC";
        } else {
            // No direct relationship - return authors with 0 books
            $sql = "SELECT a.author_id, CONCAT(a.first_name, ' ', a.last_name) AS author_name, 
                    0 AS total_books
                    FROM author a
                    ORDER BY a.author_id";
        }
    }
    
    $result = mysqli_query($conn, $sql);
    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

function getAuthorSalesProfit($conn) {
    // Total sales/profit of books for each author
    $checkTable = mysqli_query($conn, "SHOW TABLES LIKE 'book_author'");
    if (mysqli_num_rows($checkTable) > 0) {
        $sql = "SELECT a.author_id, CONCAT(a.first_name, ' ', a.last_name) AS author_name,
                COALESCE(SUM(s.sale_price), 0) AS total_sales,
                COUNT(s.sale_id) AS total_sales_count
                FROM author a
                LEFT JOIN book_author ba ON a.author_id = ba.author_id
                LEFT JOIN book b ON ba.book_id = b.book_id
                LEFT JOIN sale s ON b.book_id = s.book_id
                GROUP BY a.author_id, a.first_name, a.last_name
                ORDER BY total_sales DESC";
    } else {
        $checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM book LIKE 'author_id'");
        if (mysqli_num_rows($checkColumn) > 0) {
            $sql = "SELECT a.author_id, CONCAT(a.first_name, ' ', a.last_name) AS author_name,
                    COALESCE(SUM(s.sale_price), 0) AS total_sales,
                    COUNT(s.sale_id) AS total_sales_count
                    FROM author a
                    LEFT JOIN book b ON b.author_id = a.author_id
                    LEFT JOIN sale s ON b.book_id = s.book_id
                    GROUP BY a.author_id, a.first_name, a.last_name
                    ORDER BY total_sales DESC";
        } else {
            $sql = "SELECT a.author_id, CONCAT(a.first_name, ' ', a.last_name) AS author_name,
                    0 AS total_sales, 0 AS total_sales_count
                    FROM author a
                    ORDER BY a.author_id";
        }
    }
    
    $result = mysqli_query($conn, $sql);
    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

function getAuthorBooksList($conn) {
    // List of all books written by each author
    $checkTable = mysqli_query($conn, "SHOW TABLES LIKE 'book_author'");
    if (mysqli_num_rows($checkTable) > 0) {
        $sql = "SELECT a.author_id, CONCAT(a.first_name, ' ', a.last_name) AS author_name,
                b.book_id, b.title, b.category, b.original_price
                FROM author a
                LEFT JOIN book_author ba ON a.author_id = ba.author_id
                LEFT JOIN book b ON ba.book_id = b.book_id
                ORDER BY a.author_id, b.title";
    } else {
        $checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM book LIKE 'author_id'");
        if (mysqli_num_rows($checkColumn) > 0) {
            $sql = "SELECT a.author_id, CONCAT(a.first_name, ' ', a.last_name) AS author_name,
                    b.book_id, b.title, b.category, b.original_price
                    FROM author a
                    LEFT JOIN book b ON b.author_id = a.author_id
                    ORDER BY a.author_id, b.title";
        } else {
            $sql = "SELECT a.author_id, CONCAT(a.first_name, ' ', a.last_name) AS author_name,
                    NULL AS book_id, NULL AS title, NULL AS category, NULL AS original_price
                    FROM author a
                    ORDER BY a.author_id";
        }
    }
    
    $result = mysqli_query($conn, $sql);
    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

// Borrower Reports
function getBorrowerBookCounts($conn) {
    // Number of borrowed books per borrower
    $sql = "SELECT br.borrower_id, CONCAT(br.first_name, ' ', br.last_name) AS borrower_name,
            COUNT(l.loan_id) AS total_borrowed_books
            FROM borrower br
            LEFT JOIN loan l ON br.borrower_id = l.borrower_id
            GROUP BY br.borrower_id, br.first_name, br.last_name
            ORDER BY total_borrowed_books DESC";
    
    $result = mysqli_query($conn, $sql);
    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

function getLateBorrowers($conn) {
    // Borrowers who are late returning books
    $sql = "SELECT br.borrower_id, CONCAT(br.first_name, ' ', br.last_name) AS borrower_name,
            l.loan_id, b.title AS book_title, l.loan_date, l.due_date, l.return_date,
            DATEDIFF(CURDATE(), l.due_date) AS days_late
            FROM borrower br
            INNER JOIN loan l ON br.borrower_id = l.borrower_id
            LEFT JOIN book b ON l.book_id = b.book_id
            WHERE l.return_date IS NULL AND l.due_date < CURDATE()
            ORDER BY days_late DESC";
    
    $result = mysqli_query($conn, $sql);
    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

function getMostActiveBorrower($conn) {
    // Most active borrower
    $sql = "SELECT br.borrower_id, CONCAT(br.first_name, ' ', br.last_name) AS borrower_name,
            COUNT(l.loan_id) AS total_loans
            FROM borrower br
            LEFT JOIN loan l ON br.borrower_id = l.borrower_id
            GROUP BY br.borrower_id, br.first_name, br.last_name
            ORDER BY total_loans DESC
            LIMIT 1";
    
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

// Sales Reports
function getTotalSales($conn) {
    // Total sum of all sold books
    $sql = "SELECT COALESCE(SUM(sale_price), 0) AS total_sales, COUNT(*) AS total_books_sold
            FROM sale";
    
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return ['total_sales' => 0, 'total_books_sold' => 0];
}

function getBestSellingBook($conn) {
    // Best-selling book
    $sql = "SELECT b.book_id, b.title, COUNT(s.sale_id) AS sales_count,
            COALESCE(SUM(s.sale_price), 0) AS total_revenue
            FROM book b
            INNER JOIN sale s ON b.book_id = s.book_id
            GROUP BY b.book_id, b.title
            ORDER BY sales_count DESC, total_revenue DESC
            LIMIT 1";
    
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function getMonthlySalesStats($conn) {
    // Monthly sales statistics
    $sql = "SELECT YEAR(sale_date) AS sale_year, MONTH(sale_date) AS sale_month,
            MONTHNAME(sale_date) AS month_name,
            COUNT(*) AS total_sales,
            COALESCE(SUM(sale_price), 0) AS total_revenue
            FROM sale
            WHERE sale_date IS NOT NULL
            GROUP BY YEAR(sale_date), MONTH(sale_date), MONTHNAME(sale_date)
            ORDER BY sale_year DESC, sale_month DESC";
    
    $result = mysqli_query($conn, $sql);
    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

?>
