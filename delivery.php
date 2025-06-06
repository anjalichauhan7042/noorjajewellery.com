<?php
session_start(); // <-- Required for accessing session variables
include 'config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = mysqli_real_escape_string($conn, $_POST['name']);
    $address       = mysqli_real_escape_string($conn, $_POST['address']);
    $pincode       = mysqli_real_escape_string($conn, $_POST['pincode']);
    $city          = mysqli_real_escape_string($conn, $_POST['city']);
    $state         = mysqli_real_escape_string($conn, $_POST['state']);
    $full_address  = $address . ", " . $city . ", " . $state . " - " . $pincode;

    // Assume product details and price are stored in session
    $product_name  = $_SESSION['product_name'];
    $total_price   = $_SESSION['total_price'];
    $vendor_id     = $_SESSION['vendor_id'];
    
    $payment_method = $_SESSION['payment_method'];
    $upi_app        = $_SESSION['upi_app'] ?? NULL;
    $upi_id         = $_SESSION['upi_id'] ?? NULL;

    $sql = "INSERT INTO orders (customer_name, product_name, total_price, order_date, status, address, payment_method, upi_app, upi_id, vendor_id)
            VALUES ('$customer_name', '$product_name', '$total_price', NOW(), 'Pending', '$full_address', '$payment_method', '$upi_app', '$upi_id', '$vendor_id')";

    if (mysqli_query($conn, $sql)) {
        $order_id = mysqli_insert_id($conn);
        header('Location: payment.php?id=' . $order_id);
        exit();
    } else {
        echo "Order Failed: " . mysqli_error($conn);
    }
}


// User data (you can replace this with real user session data if needed)
$user = [
    'name' => 'John Doe',
    'email' => 'john@example.com'
];

// Local pincode database
$local_pincode_data = [
    "110001" => ["city" => "New Delhi", "state" => "Delhi"],
    "133001" => ["city" => "Ambala", "state" => "Haryana"],
    "160017" => ["city" => "Chandigarh", "state" => "Chandigarh"],
    "147001" => ["city" => "Patiala", "state" => "Punjab"],
    "180001" => ["city" => "Jammu", "state" => "Jammu and Kashmir"],
    "190001" => ["city" => "Srinagar", "state" => "Jammu and Kashmir"],
    "171001" => ["city" => "Shimla", "state" => "Himachal Pradesh"],
    "263001" => ["city" => "Haldwani", "state" => "Uttarakhand"],
    "123501" => ["city" => "Bawal", "state" => "Haryana"],
    "110091" => ["city" => "Mayur Vihar Phase1", "state" => "Delhi"],
    "201301" => ["city" => "Noida", "state" => "Uttar Pradesh"],
    "400001" => ["city" => "Mumbai", "state" => "Maharashtra"],
    "411001" => ["city" => "Pune", "state" => "Maharashtra"],
    "380001" => ["city" => "Ahmedabad", "state" => "Gujarat"],
    "362001" => ["city" => "Junagadh", "state" => "Gujarat"],
    "302001" => ["city" => "Jaipur", "state" => "Rajasthan"],
    "313001" => ["city" => "Udaipur", "state" => "Rajasthan"],
    "403001" => ["city" => "Panaji", "state" => "Goa"],
    "396445" => ["city" => "Daman", "state" => "Daman and Diu"],
    "396210" => ["city" => "Silvassa", "state" => "Dadra and Nagar Haveli"],
    "700001" => ["city" => "Kolkata", "state" => "West Bengal"],
    "751001" => ["city" => "Bhubaneswar", "state" => "Odisha"],
    "834001" => ["city" => "Ranchi", "state" => "Jharkhand"],
    "826001" => ["city" => "Dhanbad", "state" => "Jharkhand"],
    "799001" => ["city" => "Agartala", "state" => "Tripura"],
    "852201" => ["city" => "Saharsha", "state" => "Bihar"],
    "600001" => ["city" => "Chennai", "state" => "Tamil Nadu"],
    "500001" => ["city" => "Hyderabad", "state" => "Telangana"],
    "560001" => ["city" => "Bangalore", "state" => "Karnataka"],
    "682001" => ["city" => "Kochi", "state" => "Kerala"],
    "462001" => ["city" => "Bhopal", "state" => "Madhya Pradesh"],
    "492001" => ["city" => "Raipur", "state" => "Chhattisgarh"],
];

// Pincode lookup if requested via AJAX
if (isset($_GET['pincode'])) {
    $pincode = $_GET['pincode'];

    if (array_key_exists($pincode, $local_pincode_data)) {
        echo json_encode([
            "status" => "success",
            "city"   => $local_pincode_data[$pincode]['city'],
            "state"  => $local_pincode_data[$pincode]['state']
        ]);
    } else {
        $url = "https://api.postalpincode.in/pincode/" . $pincode;
        $response = file_get_contents($url);
        $responseData = json_decode($response, true);

        if ($responseData[0]['Status'] == "Success" && !empty($responseData[0]['PostOffice'])) {
            $city = $responseData[0]['PostOffice'][0]['District'];
            $state = $responseData[0]['PostOffice'][0]['State'];

            echo json_encode([
                "status" => "success",
                "city"   => $city,
                "state"  => $state
            ]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery - Noorja Jewelry</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #f9f9f9, #e0f7fa);
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 600px;
            background: white;
            margin: 40px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 10px 20px rgba(0,0,0,0.1);
            position: relative;
        }
        h2, h3 {
            color: #003366;
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 12px;
            margin: 8px 0 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            background: #fafafa;
        }
        label {
            font-weight: bold;
            margin-bottom: 6px;
            display: block;
        }
        .btn {
            background: linear-gradient(to right, #0066cc, #003366);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            width: 100%;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: linear-gradient(to right, #005bb5, #002244);
        }
        .info {
            background-color: #e3f2fd;
            padding: 15px;
            margin-top: 20px;
            border-radius: 10px;
            font-size: 15px;
            color: #333;
        }
        .info strong {
            color: #000;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Shipping Address</h2>

    <form method="POST" action="delivery.php" onsubmit="return validateForm()">
        <label for="pincode">Pincode:</label>
        <input type="text" id="pincode" name="pincode" maxlength="6" required>

        <label for="city">City:</label>
        <input type="text" id="city" name="city" required>

        <label for="state">State:</label>
        <input type="text" id="state" name="state" required>

        <label for="address">Full Address (House No, Street, Area, Landmark):</label>
        <textarea name="address" id="address" rows="4" required></textarea>

        <h3>Shipping Details</h3>

        <!-- Now the Name and Email fields are empty by default -->
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <p><strong>Shipping Method:</strong> Free Shipping</p>

        <button type="submit" class="btn">Continue to Payment</button>
    </form>
</div>

<script>
// Validate the form before submit
function validateForm() {
    var pincode = document.getElementById('pincode').value.trim();
    var city = document.getElementById('city').value.trim();
    var state = document.getElementById('state').value.trim();
    var address = document.getElementById('address').value.trim();
    var name = document.getElementById('name').value.trim();
    var email = document.getElementById('email').value.trim();

    if (pincode.length !== 6 || isNaN(pincode)) {
        alert('Please enter a valid 6-digit Pincode.');
        return false;
    }
    if (city.length < 2) {
        alert('Please enter a valid City name.');
        return false;
    }
    if (state.length < 2) {
        alert('Please enter a valid State name.');
        return false;
    }
    if (address.length < 10) {
        alert('Please enter a full address including House No., Street, Area, and Landmark.');
        return false;
    }
    if (name.length < 2) {
        alert('Please enter a valid Name.');
        return false;
    }
    if (email.length < 5 || !email.includes('@')) {
        alert('Please enter a valid Email.');
        return false;
    }
    return true;
}

// Auto-fill city and state when pincode entered
document.getElementById('pincode').addEventListener('blur', function() {
    var pincode = this.value.trim();
    if (pincode.length === 6) {
        fetch('?pincode=' + pincode)
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                document.getElementById('city').value = data.city;
                document.getElementById('state').value = data.state;
            } else {
                alert('Invalid pincode or not found!');
            }
        })
        .catch(error => {
            console.error('Error fetching pincode:', error);
        });
    }
});
</script>

</body>
</html>
