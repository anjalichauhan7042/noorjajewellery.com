<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment - Noorja Jewelry</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f8ff; padding: 20px; }
        .container { max-width: 600px; background: #fff; margin: 0 auto; padding: 30px; border-radius: 10px; box-shadow: 0px 0px 10px #ccc; }
        h2 { text-align: center; color: #003366; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input[type="text"], input[type="number"], input[type="email"], input[type="password"] {
            width: 100%; padding: 10px; margin-top: 8px; border-radius: 5px; border: 1px solid #ccc;
        }
        .btn { margin-top: 20px; width: 100%; padding: 15px; background: #0066cc; color: white; border: none; font-size: 18px; border-radius: 5px; cursor: pointer; }
        .btn:hover { background: #004080; }
        .payment-option { margin-top: 10px; }
        #upi_fields, #card_fields, #netbanking_fields { display: none; margin-top: 20px; }
        #upi_extra_fields { display: none; margin-top: 15px; }
        .error-box { background: #ffe0e0; color: #cc0000; padding: 10px; border: 1px solid #cc0000; border-radius: 5px; margin-bottom: 15px; display: none; }
    </style>

    <script>
        function showFields() {
            var mode = document.querySelector('input[name="payment_mode"]:checked').value;

            document.getElementById('upi_fields').style.display = (mode === 'UPI') ? 'block' : 'none';
            document.getElementById('card_fields').style.display = (mode === 'Card') ? 'block' : 'none';
            document.getElementById('netbanking_fields').style.display = (mode === 'Netbanking') ? 'block' : 'none';
            document.getElementById('upi_extra_fields').style.display = (mode === 'UPI') ? 'block' : 'none';
            
            document.getElementById('errorBox').style.display = 'none';
            document.getElementById('errorBox').innerHTML = '';
        }

        function validateForm() {
            var mode = document.querySelector('input[name="payment_mode"]:checked').value;
            var errorBox = document.getElementById('errorBox');
            var errors = [];

            if (mode === 'UPI') {
                var upiApp = document.querySelector('input[name="upi_app"]:checked');
                var userUpi = document.getElementById('user_upi').value.trim();

                if (!upiApp) {
                    errors.push('Please select a UPI App.');
                }

                if (userUpi === "") {
                    errors.push('Please enter your UPI ID or Mobile Number.');
                } else {
                    var upiPattern = /^[\w.-]+@[\w.-]+$/;
                    var phonePattern = /^\d{10}$/;
                    if (!upiPattern.test(userUpi) && !phonePattern.test(userUpi)) {
                        errors.push('Enter a valid 10-digit mobile number or valid UPI ID.');
                    }
                }
            }

            if (errors.length > 0) {
                errorBox.innerHTML = errors.join('<br>');
                errorBox.style.display = 'block';
                return false;
            }

            // Proceed to UPI deep linking
            if (mode === 'UPI') {
                openUpiApp();
                return false; // prevent normal form submit
            }

            return true;
        }

        function openUpiApp() {
            var amount = "100"; // You can dynamically set the order amount
            var merchantUpi = "merchant@upi"; // Your merchant UPI ID
            var merchantName = "Noorja Jewelry";

            var userUpiOrPhone = document.getElementById('user_upi').value.trim();
            var upiLink = "";

            upiLink = `upi://pay?pa=${merchantUpi}&pn=${encodeURIComponent(merchantName)}&am=${amount}&cu=INR`;

            window.location.href = upiLink;
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Choose Payment Method</h2>

    <div id="errorBox" class="error-box"></div>

    <form method="POST" action="order_success.php" onsubmit="return validateForm();">
        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id ?? ''); ?>">

        <label>Select Payment Mode:</label>
        <div class="payment-option">
            <input type="radio" name="payment_mode" value="COD" checked onclick="showFields()"> Cash on Delivery<br>
            <input type="radio" name="payment_mode" value="UPI" onclick="showFields()"> UPI Payment<br>
            <input type="radio" name="payment_mode" value="Card" onclick="showFields()"> Debit/Credit Card<br>
            <input type="radio" name="payment_mode" value="Netbanking" onclick="showFields()"> Net Banking
        </div>

        <div id="upi_fields">
            <label>Select UPI App:</label>
            <div class="payment-option">
                <input type="radio" name="upi_app" value="Google Pay"> Google Pay<br>
                <input type="radio" name="upi_app" value="PhonePe"> PhonePe<br>
                <input type="radio" name="upi_app" value="Paytm"> Paytm<br>
            </div>
        </div>

        <div id="upi_extra_fields">
            <label>Enter Your Mobile Number or UPI ID:</label>
            <input type="text" id="user_upi" name="user_upi" placeholder="example@upi OR 9876543210">
        </div>

        <div id="card_fields">
            <label>Card Number:</label>
            <input type="text" name="card_number" maxlength="16" placeholder="Enter 16-digit Card Number">
            <label>Expiry Date (MM/YY):</label>
            <input type="text" name="card_expiry" placeholder="MM/YY">
            <label>CVV:</label>
            <input type="password" name="cvv" maxlength="3" placeholder="XXX">
        </div>

        <div id="netbanking_fields">
            <label>Bank Name:</label>
            <input type="text" name="bank_name" placeholder="HDFC, SBI, ICICI">
            <label>Account Number:</label>
            <input type="text" name="account_number" placeholder="Your Account Number">
        </div>

        <button type="submit" class="btn">Proceed to Pay</button>
    </form>
</div>

</body>
</html>
