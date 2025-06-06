<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noorja Jewelry</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
    
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: black;
        }

        /* Header Section */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #0a1128;
            color: white;
        }

        .logo img {
            width: 70px;
        }

        .search-bar input {
            width: 300px;
            padding: 8px;
            border-radius: 5px;
            border: none;
        }

        .search-bar button {
            padding: 8px;
            border: none;
            background-color: #ffd700;
            color: black;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
        }

        .header-icons a {
            text-decoration: none;
            color: #ffd700;
            font-size: 16px;
            font-weight: bold;
            margin-left: 15px;
        }

        /* Navigation Bar */
        nav {
            background-color: white;
            padding: 15px 0;
            position: relative;
            z-index: 10;
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 25px;
            position: relative;
        }

        nav ul li a {
            text-decoration: none;
            color: black;
            font-size: 18px;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #ffd700;
        }

        /* Dropdown Menu */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            width: 180px;
            z-index: 20;
            text-align: center;
        }

        .dropdown-menu li a {
            padding: 10px;
            display: block;
            font-size: 16px;
            color: black;
        }

        .dropdown-menu li a:hover {
            background: #f4f4f4;
        }

        /* Show Dropdown on Hover */
        .dropdown:hover .dropdown-menu {
            display: block;
        }

        /* Video Section */
        .video-container {
            position: relative;
            width: 100%;
        }

        .video-container video {
            width: 100%;
            height: auto;
            filter: brightness(70%);
        }

        /* Product Section */
        .product-section {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 30px;
            background-color: #0a1128;
        }

        .product-card {
            width: 250px;
            flex-grow: 1;
            max-width: 280px;
            margin: 15px;
            padding: 15px;
            border-radius: 10px;
            background: white;
            text-align: center;
            color: black;
            transition: transform 0.3s;
        }

        .product-card img {
            width: 100%;
            border-radius: 5px;
        }

        /* Buttons */
        .btn-container {
            margin-top: 10px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
        }

        .details {
            background: #001f3f;
            color: white;
        }

        .cart {
            background: #ffd700;
            color: black;
        }

        .wishlist {
            background: #f4f4f4;
            color: black;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            background-color: #001f3f;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header-container">
        <div class="logo">
            <img src="logo.png" alt="Noorja Logo">
        </div>

        <div class="search-bar">
            <form action="products.php" method="GET">
                <input type="text" name="type" placeholder="Search Jewelry..." required>
                <button type="submit">Search</button>
            </form>
        </div>

        <div class="header-icons">
            <a href="login.php">Login</a> / <a href="registration.php">Signup</a>
            <a href="wishlist.php"><i class="fas fa-heart"></i> Wishlist</a>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
        </div>
    </div>

    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li class="dropdown">
                <a href="#">All Jewelry ▾</a>
                <ul class="dropdown-menu">
                    <li><a href="products.php?type=Rings">Rings</a></li>
                    <li><a href="products.php?type=Bracelets">Bracelet</a></li>
                    <li><a href="products.php?type=Pendant">Pendant</a></li>
                    <li><a href="products.php?type=Earrings">Earrings</a></li>
                    <li><a href="products.php?type=Anklets">Anklet</a></li>
                </ul>
            </li>
            <li><a href="about_us.php">About Us</a></li>
            <li><a href="profile.php">User Profile</a></li>
        </ul>
    </nav>

    <!-- Background Video -->
    <div class="video-container">
        <video autoplay muted loop>
            <source src="Noorjavedio.mp4" type="video/mp4">
        </video>
    </div>
    <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['message'])) {
    echo "<div style='background: #ffd700; color: black; text-align: center; padding: 10px; font-weight: bold;'>
            " . $_SESSION['message'] . "
          </div>";
    unset($_SESSION['message']);
}
?>


    <!-- Product Section -->
    <div class="product-section">
        <?php
            include "config.php";
            $query = "SELECT * FROM products LIMIT 10";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='product-card'>
                            <img src='" . $row['image'] . "' alt='" . $row['name'] . "'>
                            <h3>" . $row['name'] . " - ₹" . $row['price'] . "</h3>
                            <div class='btn-container'>
                                <a href='product_details.php?id=" . $row['id'] . "' class='btn details'><i class='fas fa-search'></i> View Details</a>
                                <a href='add_to_cart.php?id=" . $row['id'] . "' class='btn cart'><i class='fas fa-shopping-cart'></i> Add to Cart</a>
                                <a href='add_to_wishlist.php?id=" . $row['id'] . "' class='btn wishlist'><i class='fas fa-heart'></i> Add to Wishlist</a>
                            </div>
                          </div>";
                }
            } else {
                echo "<p style='color:white; text-align:center;'>No products found.</p>";
            }
        ?>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>© 2025 Noorja Jewelry. All rights reserved.</p>
        <div class="footer-links">
            <a href="contact_us.php">📞 Contact Us</a>
            <a href="feedback.php">💬 Feedback/Suggestions</a>
            <a href="help_center.php">❓ Help Center</a>
            <a href="about_us.php">ℹ️ About Us</a>
        </div>
    </footer>

</body>
</html>
