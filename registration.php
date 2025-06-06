<?php
session_start();
include 'config.php';

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    // Get and trim the inputs
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);
    $password_raw = $_POST['password'];
    $role = $_POST['role'];
    $is_verified = 1;

    // Validation
    if (empty($name) || empty($phone) || empty($address) || empty($email) || empty($password_raw) || empty($role)) {
        $error_message = "All fields are required.";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) { 
        $error_message = "Phone number must be exactly 10 digits.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif (strlen($password_raw) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    } elseif (!in_array($role, ['customer', 'vendor'])) {
        $error_message = "Invalid role selected.";
    } else {
        // Check if email already exists
        $check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $result = $check_email->get_result();

        if ($result->num_rows > 0) {
            $error_message = "This email is already registered!";
        } else {
            // Insert user into database
            $password = password_hash($password_raw, PASSWORD_BCRYPT);
            $sql = $conn->prepare("INSERT INTO users (name, phone, address, email, password, role, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $sql->bind_param("ssssssi", $name, $phone, $address, $email, $password, $role, $is_verified);

            if ($sql->execute()) {
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;
                $_SESSION['user_id'] = $conn->insert_id; 
                header("Location: home.php");
                exit();
            } else {
                $error_message = "Error: " . $conn->error;
            }
            $sql->close();
        }
        $check_email->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Noorja Jewelry</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f06, #ff8c00);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 40px;
            width: 400px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #ff6a00;
        }

        input, select, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
            background: #ff8c00;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #ff6a00;
        }

        .error-message, .success-message {
            font-size: 14px;
            margin-top: 10px;
        }

        .error-message { color: red; }
        .success-message { color: green; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Register</h2>

        <?php if (!empty($error_message)) { echo '<div class="error-message">'.$error_message.'</div>'; } ?>
        
        <form method="post"><input type="text" name="name" placeholder="Full Name" required pattern="[A-Za-z\s]+" title="Name must contain only letters and spaces" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">

            <input type="text" name="phone" placeholder="Phone Number" required pattern="[0-9]{10}" title="Phone number must be exactly 10 digits" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
            <input type="text" name="address" placeholder="Address" required value="<?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?>">
            <input type="email" name="email" placeholder="Email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            <input type="password" name="password" placeholder="Password" required minlength="6" autocomplete="new-password">
            
            <select name="role" required>
                <option value="customer" <?= (isset($_POST['role']) && $_POST['role'] == 'customer') ? 'selected' : '' ?>>Customer</option>
                <option value="vendor" <?= (isset($_POST['role']) && $_POST['role'] == 'vendor') ? 'selected' : '' ?>>Vendor</option>
            </select>
            
            <button type="submit" name="register">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

</body>
</html>
