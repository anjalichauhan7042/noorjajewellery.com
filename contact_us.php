<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Noorja Jewelry</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
        }

        /* Header */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #0a1128;
            color: white;
        }

        .logo img {
            width: 80px;
        }

        /* Contact Section */
        .contact-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        .contact-container h1 {
            color: #0a1128;
            margin-bottom: 10px;
        }

        .contact-container p {
            font-size: 18px;
            margin-bottom: 15px;
            color: #444;
        }

        .contact-details {
            margin-top: 20px;
        }

        .contact-details i {
            font-size: 20px;
            margin-right: 10px;
            color: #ffd700;
        }

        .contact-form {
            margin-top: 20px;
        }

        .contact-form input, 
        .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .contact-form button {
            background: #001f3f;
            color: white;
            padding: 10px 15px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .contact-form button:hover {
            background: #ffd700;
            color: black;
        }

        /* Google Map */
        .map-container {
            margin-top: 20px;
        }

        iframe {
            width: 100%;
            height: 300px;
            border: none;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            background-color: #001f3f;
            color: white;
            margin-top: 20px;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header-container">
        <div class="logo">
            <img src="logo.png" alt="Noorja Logo">
        </div>
    </div>

    <!-- Contact Section -->
    <div class="contact-container">
        <h1>Contact Us</h1>
        <p>Have questions? We’d love to hear from you! Contact us using the details below.</p>

        <div class="contact-details">
            <p><i class="fas fa-map-marker-alt"></i> Noorja Jewelry, India</p>
            <p><i class="fas fa-phone"></i> +91 9990483329</p>
            <p><i class="fas fa-envelope"></i> support@noorjajewelry.com</p>
        
        </div>

    

    <!-- Footer Section -->
    <footer>
        <p>© 2025 Noorja Jewelry. All rights reserved.</p>
        <div class="footer-links">
            <a href="about_us.php">💎 About Us</a>
            <a href="feedback.php">💬 Feedback/Suggestions</a>
            <a href="help_center.php">❓ Help Center</a>
        </div>
    </footer>

</body>
</html>
