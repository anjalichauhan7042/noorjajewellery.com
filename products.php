<?php
session_start();
include('config.php');

// Get filters from URL
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$gender_filter = isset($_GET['gender']) ? $_GET['gender'] : '';
$price_filter = isset($_GET['price']) ? $_GET['price'] : '';
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';

// Build the query
$query = "SELECT * FROM products WHERE 1=1";

// Apply filters if set
if (!empty($type_filter)) {
    $query .= " AND type = '" . mysqli_real_escape_string($conn, $type_filter) . "'";
}
if (!empty($category_filter)) {
    $query .= " AND category = '" . mysqli_real_escape_string($conn, $category_filter) . "'";
}
if (!empty($gender_filter)) {
    $query .= " AND gender = '" . mysqli_real_escape_string($conn, $gender_filter) . "'";
}

// Apply sorting
if (!empty($price_filter)) {
    if ($price_filter == "low") {
        $query .= " ORDER BY price ASC";
    } elseif ($price_filter == "high") {
        $query .= " ORDER BY price DESC";
    }
}

// Execute the query
$result = mysqli_query($conn, $query);

// Debugging: Check if query failed
if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Jewelry Collection</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }

        .filter-form {
            margin: 20px auto;
            text-align: center;
        }

        .filter-form select, .filter-form button {
            padding: 10px;
            margin: 5px;
            font-size: 16px;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 30px;
        }

        .product {
            width: 250px;
            max-width: 280px;
            padding: 15px;
            border-radius: 10px;
            background: white;
            text-align: center;
            transition: transform 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .product img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .product p {
            margin: 10px 0;
            font-size: 16px;
            font-weight: bold;
        }

        .btn {
            display: block;
            margin: 8px auto;
            padding: 10px;
            width: 90%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
            text-decoration: none;
            text-align: center;
        }

        .btn.details { background: #001f3f; color: white; }
        .btn.cart { background: #ff4081; color: white; }
        .btn.wishlist { background: #ffd700; color: black; }

        .btn:hover { opacity: 0.8; }
    </style>
</head>
<body>

<h2>Our Jewelry Collection</h2>

<!-- Filter Form -->
<form action="products.php" method="GET" class="filter-form">
    <!-- Keep the selected type when filtering -->
    <input type="hidden" name="type" value="<?php echo htmlspecialchars($type_filter); ?>">

    <select name="category">
        <option value="">All Categories</option>
        <option value="Diamond" <?php if ($category_filter == 'Diamond') echo 'selected'; ?>>Diamond</option>
        <option value="Gold" <?php if ($category_filter == 'Gold') echo 'selected'; ?>>Gold</option>
        <option value="Silver" <?php if ($category_filter == 'Silver') echo 'selected'; ?>>Silver</option>
        <option value="Handmade" <?php if ($category_filter == 'Handmade') echo 'selected'; ?>>Handmade</option>
    </select>

    <select name="gender">
        <option value="">All Genders</option>
        <option value="Men" <?php if ($gender_filter == 'Men') echo 'selected'; ?>>Men</option>
        <option value="Women" <?php if ($gender_filter == 'Women') echo 'selected'; ?>>Women</option>
    </select>

    <select name="price">
        <option value="">Sort by Price</option>
        <option value="low" <?php if ($price_filter == 'low') echo 'selected'; ?>>Low to High</option>
        <option value="high" <?php if ($price_filter == 'high') echo 'selected'; ?>>High to Low</option>
    </select>

    <button type="submit">Apply Filters</button>
</form>

<!-- Display Filtered Products -->
<div class="products">
    <?php 
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="product">
                <!-- Display Image -->
                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" onerror="this.onerror=null;this.src='default.jpg';">

                <!-- Product Name & Price -->
                <p><?php echo htmlspecialchars($row['name']); ?></p>
                <p><strong>₹<?php echo number_format($row['price'], 2); ?></strong></p>

                <!-- View Details Button -->
                <a href="product_details.php?id=<?php echo $row['id']; ?>" class="btn details">🔍 View Details</a>

                <!-- Add to Cart Button -->
                <a href="add_to_cart.php?id=<?php echo $row['id']; ?>" class="btn cart">🛒 Add to Cart</a>

                <!-- Wishlist Button -->
                <a href="wishlist.php?id=<?php echo $row['id']; ?>" class="btn wishlist">❤️ Add to Wishlist</a>
            </div>
        <?php } 
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
</div>

</body>
</html>
