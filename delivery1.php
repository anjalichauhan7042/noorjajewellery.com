
<?php
session_start();
include 'db_connect.php';

// Fetch user details
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['address'] = $_POST['address'];
    header('Location: payment.php');
}
?>

<h2>Delivery Address</h2>
<form method="POST">
    <input type="text" name="address" value="<?php echo $user['address']; ?>" required>
    <button type="submit">Continue to Payment</button>
</form>
