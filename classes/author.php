<?php
require_once '../includes/db.php';
$sql = "SELECT * FROM author";
$result = mysqli_query($conn, $sql);
$table="<table border='1' cellpadding='10'>
<tbody>
<tr><th>author_id</th><th>first_name</th><th>last_name</th><th>country</th><th>bio</th></tr>";
// Check if the query returned rows
if (mysqli_num_rows($result) > 0) {
// fetch a record from result set into an associative array (Loop through each row )
while ($row = mysqli_fetch_assoc($result)) {
$table .= "<tr><td>". $row['author_id'] . "</td><td>" . $row['first_name'] . "</td><td>" . $row['last_name'] . "</td><td>" . $row['country'] . "</td><td>" . $row['bio'] . "</td></tr>";
}
$table .= "</tbody></table>";
echo $table;
}
else {
echo "0 results";
}
mysqli_close($conn);
?>