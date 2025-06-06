<?php
session_start();
include "config.php"; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle wishlist removal
if (isset($_GET['remove_id'])) {
    $remove_id = mysqli_real_escape_string($conn, $_GET['remove_id']);
    $delete_query = "DELETE FROM wishlist WHERE user_id = '$user_id' AND product_id = '$remove_id'";
    mysqli_query($conn, $delete_query);
    header("Location: wishlist.php");
    exit();
}

// Fetch wishlist items
$query = "SELECT p.id, p.name, p.image, p.price 
          FROM wishlist w 
          JOIN products p ON w.product_id = p.id 
          WHERE w.user_id = '$user_id'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Wishlist - Noorja Jewelry</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .wishlist-container {
            width: 60%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .wishlist-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .wishlist-item img {
            width: 80px;
            border-radius: 5px;
        }
        .wishlist-item .details {
            flex-grow: 1;
            padding-left: 15px;
            text-align: left;
        }
        .wishlist-item .details h3 {
            margin: 5px 0;
            font-size: 18px;
        }
        .wishlist-item .buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
        .btn.details { background: #001f3f; color: white; }
        .btn.delete { background: red; color: white; }
        .btn:hover { opacity: 0.8; }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            width: 300px;
        }
        .modal-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
        }
        .modal-buttons button {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .confirm-btn {
            background-color: red;
            color: white;
        }
        .cancel-btn {
            background-color: #ccc;
        }
    </style>
</head>
<body>

<div class="wishlist-container">
    <h2>My Wishlist</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="wishlist-item">
                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <div class="details">
                    <h3><?php echo $row['name']; ?></h3>
                    <p>Price: ₹<?php echo number_format($row['price'], 2); ?></p>
                </div>
                <div class="buttons">
                    <a href="product_details.php?id=<?php echo $row['id']; ?>" class="btn details">
                        <i class="fas fa-search"></i> View Details
                    </a>
                    <button class="btn delete" onclick="openModal(<?php echo $row['id']; ?>)">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            </div>
        <?php } ?>
    <?php else: ?>
        <p>Your wishlist is empty.</p>
    <?php endif; ?>
</div>

<!-- Custom Modal -->
<div class="modal" id="confirmModal">
    <div class="modal-content">
        <h3>Remove from Wishlist?</h3>
        <p>Are you sure you want to remove this item?</p>
        <div class="modal-buttons">
            <button class="confirm-btn" id="confirmDeleteBtn">Yes</button>
            <button class="cancel-btn" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

<script>
    let productIdToRemove = null;

    function openModal(productId) {
        productIdToRemove = productId;
        document.getElementById('confirmModal').style.display = 'flex';
    }

    function closeModal() {
        productIdToRemove = null;
        document.getElementById('confirmModal').style.display = 'none';
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (productIdToRemove) {
            window.location.href = "wishlist.php?remove_id=" + productIdToRemove;
        }
    });
</script>

</body>
</html>
