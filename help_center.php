<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Center - Noorja Jewelry</title>
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
            width: 140px;
        }

        /* Help Center Section */
        .help-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        .help-container h1 {
            color: #0a1128;
            margin-bottom: 10px;
        }

        .faq-section {
            margin-top: 20px;
            text-align: left;
        }

        .faq-question {
            font-size: 18px;
            font-weight: bold;
            color: #001f3f;
            margin: 10px 0;
            cursor: pointer;
        }

        .faq-answer {
            display: none;
            font-size: 16px;
            color: #555;
            margin-bottom: 15px;
        }

        /* Customer Support */
        .support-section {
            margin-top: 30px;
        }

        .support-section h2 {
            color: #001f3f;
        }

        .support-options {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .support-box {
            width: 45%;
            padding: 15px;
            background: #ffd700;
            color: black;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            transition: background 0.3s;
        }

        .support-box:hover {
            background: #ff4081;
            color: white;
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
    <script>
        // Toggle FAQ Answers
        function toggleAnswer(id) {
            var answer = document.getElementById(id);
            answer.style.display = answer.style.display === "none" ? "block" : "none";
        }
    </script>
</head>
<body>

    <!-- Header Section -->
    <div class="header-container">
        <div class="logo">
            <img src="logo.png" alt="Noorja Logo">
        </div>
    </div>

    <!-- Help Center Section -->
    <div class="help-container">
        <h1>Help Center</h1>
        <p>Welcome to the Noorja Jewelry Help Center. Find answers to your questions below.</p>

        <!-- FAQ Section -->
        <div class="faq-section">
            <h2>Frequently Asked Questions</h2>
            
            <div class="faq-question" onclick="toggleAnswer('faq1')">📦 How can I track my order?</div>
            <div class="faq-answer" id="faq1">You can track your order in the "My Orders" section after logging in.</div>

            <div class="faq-question" onclick="toggleAnswer('faq2')">💳 What payment methods do you accept?</div>
            <div class="faq-answer" id="faq2">We accept credit/debit cards, PayPal, UPI, and net banking.</div>

            <div class="faq-question" onclick="toggleAnswer('faq3')">🔄 Can I return or exchange an item?</div>
            <div class="faq-answer" id="faq3">Yes, you can return/exchange items within 7 days of delivery.</div>

            <div class="faq-question" onclick="toggleAnswer('faq4')">📞 How can I contact customer support?</div>
            <div class="faq-answer" id="faq4">You can call us at +91 98765 43210 or email support@noorjajewelry.com.</div>
        </div>

        <!-- Customer Support Section -->
        <div class="support-section">
            <h2>Need Further Assistance?</h2>
            <div class="support-options">
                <div class="support-box" onclick="window.location.href='contact_us.php'">📧 Contact Us</div>
                <div class="support-box" onclick="window.location.href='feedback.php'">💬 Submit Feedback</div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>© 2025 Noorja Jewelry. All rights reserved.</p>
        <div class="footer-links">
            <a href="about.php">💎 About Us</a>
            <a href="contact_us.php">📞 Contact Us</a>
            <a href="feedback.php">💬 Feedback/Suggestions</a>
        </div>
    </footer>

</body>
</html>
