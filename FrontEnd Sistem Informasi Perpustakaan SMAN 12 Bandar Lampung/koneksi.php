<?php
$servername = "localhost";
$username = "admin";
$password = "admin";
$dbname = "siperpus_sman12";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>