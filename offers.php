<?php
session_start();
include 'config.php';

// Ensure only a manager can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

$message = "";

// Handle Offer Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $discount = mysqli_real_escape_string($conn, $_POST['discount']);
    $valid_until = mysqli_real_escape_string($conn, $_POST['valid_until']);

    if (!empty($title) && !empty($description) && !empty($discount) && !empty($valid_until)) {
        $query = "INSERT INTO offers (title, description, discount_percentage, valid_until) 
                  VALUES ('$title', '$description', '$discount', '$valid_until')";
        if (mysqli_query($conn, $query)) {
            $message = "✅ Offer added successfully!";
        } else {
            $message = "❌ Error adding offer: " . mysqli_error($conn);
        }
    } else {
        $message = "❌ Please fill in all fields.";
    }
}

// Handle Offer Deletion
if (isset($_GET['delete'])) {
    $offer_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM offers WHERE id = $offer_id");
    header("Location: offers.php");
    exit();
}

// Fetch All Offers
$offers = mysqli_query($conn, "SELECT * FROM offers ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Offers</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 50%; margin: 50px auto; background: #fff; padding: 20px; border-radius: 8px; }
        h2 { text-align: center; color: #003366; }
        label { font-weight: bold; display: block; margin: 10px 0 5px; }
        input, textarea, .btn { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: #003366; color: white; cursor: pointer; }
        .btn:hover { background: #ff8c00; }
        .message { text-align: center; margin-top: 10px; color: red; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: #003366; color: white; }
        .delete-btn { color: red; text-decoration: none; font-weight: bold; }
        .delete-btn:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Offers</h2>
        <?php if (!empty($message)) { echo "<p class='message'>$message</p>"; } ?>
        <form method="POST">
            <label>Offer Title</label>
            <input type="text" name="title" required>

            <label>Description</label>
            <textarea name="description" rows="3" required></textarea>

            <label>Discount Percentage</label>
            <input type="number" name="discount" step="0.01" required>

            <label>Valid Until</label>
            <input type="date" name="valid_until" required>

            <button type="submit" class="btn">Add Offer</button>
        </form>

        <h2>Existing Offers</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Discount</th>
                <th>Valid Until</th>
                <th>Action</th>
            </tr>
            <?php while ($offer = mysqli_fetch_assoc($offers)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($offer['title']); ?></td>
                    <td><?php echo htmlspecialchars($offer['description']); ?></td>
                    <td><?php echo $offer['discount_percentage']; ?>%</td>
                    <td><?php echo $offer['valid_until']; ?></td>
                    <td>
                        <a href="offers.php?delete=<?php echo $offer['id']; ?>" class="delete-btn">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
<a href="manager_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>


    </div>
</body>
</html>
