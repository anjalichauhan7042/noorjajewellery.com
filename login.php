<?php
session_start();
include 'config.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if email exists and is verified
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND is_verified=1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];

            // Redirect based on role
            switch ($row['role']) {
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                case 'vendor':
                    header("Location: vendor_dashboard.php");
                    break;
                case 'manager':
                    header("Location: manager_dashboard.php");
                    break;
                default:
                    header("Location: home.php");
            }
            exit;
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "Account not verified. Please check your email.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Noorja Jewelry</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #f06, #ff8c00);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 40px;
            width: 350px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        input:focus {
            border-color: #ff8c00;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #ff8c00;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #ff6a00;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
        .signup-link {
            margin-top: 15px;
            font-size: 14px;
        }
        .signup-link a {
            color: #ff8c00;
            text-decoration: none;
            font-weight: bold;
        }
        .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Login</h2>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>

            <?php if (!empty($error_message)) { echo '<div class="error-message">'.$error_message.'</div>'; } ?>
        </form>
        <div class="signup-link">
            Don't have an account? <a href="registration.php">Sign Up</a>
        </div>
    </div>

</body>
</html>
