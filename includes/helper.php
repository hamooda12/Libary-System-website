<?php
include 'header.php';
require 'db.php';
function countRows($table, $conn) {
    $sql = "SELECT COUNT(*) AS total FROM $table";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}
function getLoansPerMonth($conn) {
    $sql = "SELECT MONTH(loan_date) AS month, COUNT(*) AS total FROM loan GROUP BY MONTH(loan_date)";
    $result = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[$row['month']] = $row['total'];
    }
    return $data;
}
function getCategoryDistribution($conn) {
    $sql = "SELECT category, COUNT(*) AS total FROM book GROUP BY category";
    $result = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[$row['category']] = $row['total'];
    }
    return $data;
}
function getNotsoldBooks($conn) {
    $sql = "SELECT * FROM book WHERE available = 1";
    $result = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

?>