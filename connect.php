<?php
$conn = new mysqli("localhost", "root", "", "noorja");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
