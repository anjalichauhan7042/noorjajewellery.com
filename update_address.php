<?php
session_start();
include "config.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $new_address = mysqli_real_escape_string($conn, $_POST['new_address']);

    $query = "UPDATE users SET address = '$new_address' WHERE id = '$user_id'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Address updated successfully!";
    } else {
        $_SESSION['message'] = "Failed to update address.";
    }
}

header("Location: profile.php");
exit();
?>
