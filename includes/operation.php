<?php
include 'header.php';
require 'db.php';
function display($table, $conn) {
    $sql = "SELECT * FROM $table";
    $result = mysqli_query($conn, $sql);
            $values = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $values[] = $row;
        }
    }
    return ($values);
}
function insert($table, $data, $conn) {
    $fields = implode(",", array_keys($data));
    $values = implode("','", array_values($data));
    $sql = "INSERT INTO $table ($fields) VALUES ('$values')";
    $result = mysqli_query($conn, $sql);
    return $result;
}
function update($table, $data, $id, $conn) {
    
    $fields = implode(",", array_keys($data));
    $values = implode("','", array_values($data));
    $str = '';
    for ($i = 0; $i < count(array_keys($data)); $i++) {
        $key = array_keys($data)[$i];
        $value = array_values($data)[$i];
        $str .= "$key='$value'";
        if ($i < count(array_keys($data)) - 1) {
            $str .= ", ";
        }}
     $sql = "
        UPDATE book 
        SET $str
        WHERE $id = $data[$id]
     ";
    $result = mysqli_query($conn, $sql);
    return $result;
}
function delete($table, $field, $id, $conn) {
    $sql = "delete from $table where $field = $id; ";
    $result = mysqli_query($conn, $sql);
    return $result;
}
?>
