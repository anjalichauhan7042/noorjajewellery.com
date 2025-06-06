<?php
// Start session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config.php';

// Get user ID from session (if logged in)
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : "NULL";

// Get vendor ID from session (if applicable)
$vendor_id = isset($_SESSION['vendor_id']) ? intval($_SESSION['vendor_id']) : "NULL";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Correctly format NULL values for SQL
    $user_id_sql = ($user_id !== "NULL") ? $user_id : "NULL";
    $vendor_id_sql = ($vendor_id !== "NULL") ? $vendor_id : "NULL";

    // Insert feedback into the database
    $sql = "INSERT INTO feedback (user_id, vendor_id, name, message) 
            VALUES ($user_id_sql, $vendor_id_sql, '$name', '$message')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Thank you for your feedback!'); window.location.href='feedback.php';</script>";
    } else {
        echo "<script>alert('Error submitting feedback. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback & Suggestions</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container */
        .container {
            background: #ffffff;
            padding: 20px;
            width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        /* Heading */
        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        /* Form Styling */
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Labels */
        label {
            font-weight: bold;
            margin-bottom: 5px;
            text-align: left;
            width: 100%;
        }

        /* Inputs & Textarea */
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        /* Button */
        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
       <a href="home.php">
            <button class="btn-dashboard">Back to Dashboard</button>
        </a>
        <h2>Feedback & Suggestions</h2>
        <form action="feedback.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="message">Your Feedback/Any Suggestions:</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <!-- Hidden fields for user_id and vendor_id -->
            <input type="hidden" name="user_id" value="<?= $user_id !== 'NULL' ? $user_id : '' ?>"> 
            <input type="hidden" name="vendor_id" value="<?= $vendor_id !== 'NULL' ? $vendor_id : '' ?>"> 

            <button type="submit">Submit Feedback</button>
        </form>
    </div>
</body>
</html>
