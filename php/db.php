
<?php
$servername = "localhost";
$username = "kaisham1";
$password = "kaisham1";
$dbname = "kaisham1";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
