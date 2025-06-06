<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "noorja");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newContent = mysqli_real_escape_string($conn, $_POST['content']);
    $sql = "UPDATE about_us SET content = '$newContent' WHERE id = 1";
    if ($conn->query($sql) === TRUE) {
        $msg = "About Us content updated successfully.";
    } else {
        $msg = "Error updating content: " . $conn->error;
    }
}

// Fetch current content
$result = $conn->query("SELECT content FROM about_us WHERE id = 1");
$row = $result->fetch_assoc();
$currentContent = $row['content'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit About Us - Admin</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            padding: 30px;
        }

        .container {
            max-width: 800px;
            background: white;
            padding: 20px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        textarea {
            width: 100%;
            height: 300px;
            font-size: 16px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .message {
            color: green;
            margin-top: 10px;
        }

        a.back {
            display: inline-block;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit About Us Content</h2>
        <form method="POST">
            <textarea name="content"><?php echo htmlspecialchars($currentContent); ?></textarea>
            <button type="submit">Save Changes</button>
        </form>
        <?php if ($msg): ?>
            <div class="message"><?php echo $msg; ?></div>
        <?php endif; ?>
        <a class="back" href="about_us.php">← Back to About Page</a>
    </div>
</body>
</html>
