<?php
include "config.php";

if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($conn, $_POST['query']);
    
    // Search for matching products
    $query = "SELECT * FROM products 
              WHERE name LIKE '%$search%' 
              OR type LIKE '%$search%'
              OR description LIKE '%$search%' 
              LIMIT 10";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='product-card'>
                    <img src='" . $row['image'] . "' alt='" . $row['name'] . "'>
                    <h3>" . $row['name'] . " - ₹" . $row['price'] . "</h3>

                   <div class='btn-container'>
                        <a href='product_details.php?id=" . $row['id'] . "' class='btn details'><i class='fas fa-search'></i> View Details</a>
                        <a href='add_to_cart.php?id=" . $row['id'] . "' class='btn cart'><i class='fas fa-shopping-cart'></i> Add to Cart</a>
                        <a href='add_to_wishlist.php?id=" . $row['id'] . "' class='btn wishlist'><i class='fas fa-heart'></i> Wishlist</a>
                   </div>
                  </div>";
        }
    } else {
        echo "<p style='color:white;'>No products found.</p>";
    }
}
?>
