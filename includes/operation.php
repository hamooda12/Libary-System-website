<?php
include 'header.php';
require 'db.php';
function display($collomuns ,$fields ,$table, $conn) {
    $sql = "SELECT * FROM $table";
    $result = mysqli_query($conn, $sql);
            $values = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $values[] = array_values($row);
        }
    }
    
    print_r ($values);
}
display(['title','category','book_type','original_price'], ['Title','Category','Type','Price'], 'book', $conn);
?>