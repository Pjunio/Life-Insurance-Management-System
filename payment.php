<?php
session_start();
include_once 'connect.php';

if (!isset($_SESSION['id'])) {
    header("location:login.php");
    die();
}

if (!isset($_GET['plan_id'])) {
    header("location:home.php");
    die();
}

$plan_id = $_GET['plan_id'];
$query = "SELECT * FROM plans WHERE plan_id = '$plan_id'";
$result = mysqli_query($db, $query);
$plan = mysqli_fetch_assoc($result);

if (!$plan) {
    header("location:home.php");
    die();
}

$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currency = $_POST['currency'];
    $expiry = date('Y-m-d H:i:s', strtotime('+1 year')); // Yearly subscription
    $base_amount = $plan['plan_amount']; // Amount in USD

    // Simulate payment success (replace with actual payment gateway in production)
    $query = "INSERT INTO premiums (user_id, plan_id, premium_amount, currency, premium_expiry) VALUES ('$user_id', '$plan_id', '$base_amount', '$currency', '$expiry')";
    if (mysqli_query($db, $query)) {
        // Update user's plan_id
        $update_query = "UPDATE users SET plan_id = '$plan_id' WHERE user_id = '$user_id'";
        mysqli_query($db, $update_query);
        header("location:dashboard.php");
        die();
    } else {
        $message = "Error subscribing.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe to Plan</title>
    <style>
        body { background: linear-gradient(135deg, #4203a9, #90bafc); display: flex; justify-content: center; align-items: center; min-height: 100vh; font-family: 'Segoe UI'; }
        .registration_form { background: white; padding: 25px; border-radius: 5px; width: 400px; }
        .title { text-align: center; padding: 25px; }
        .title p { color: #333; font-size: 24px; font-weight: bold; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; color: #555; margin-bottom: 8px; font-size: 14px; }
        .form-group select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; }
        .login-btn { width: 100%; padding: 12px; background: #4203a9; border: none; border-radius: 8px; color: white; font-size: 16px; cursor: pointer; }
        .login-btn:hover { background: #35028c; }
        .message { text-align: center; color: red; margin-top: 15px; }
        #converted { color: #666; font-size: 14px; text-align: center; }
    </style>
    <script>
        function updateAmount() {
            const amount = <?php echo $plan['plan_amount']; ?>;
            const currency = document.getElementById('currency').value;
            const rates = {'USD': 1, 'EUR': 0.85, 'GBP': 0.73, 'INR': 83};
            const converted = amount * rates[currency];
            document.getElementById('converted').innerText = `Amount to pay: ${converted.toFixed(2)} ${currency}`;
        }
    </script>
</head>
<body>
    <div class="registration_form">
        <div class="title">
            <p>TRUESECURE INSURANCE</p>
            <p style="font-size: 18px; color: #666;">Subscribe to <?php echo $plan['plan_name']; ?></p>
        </div>
        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="currency">Choose Currency</label>
                <select id="currency" name="currency" onchange="updateAmount()">
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                    <option value="INR">INR</option>
                </select>
            </div>
            <p id="converted"></p>
            <input type="submit" value="Subscribe" class="login-btn">
        </form>
        <center><p style="margin-top: 15px;"><a href="home.php" style="color: #4203a9; text-decoration: none;">Back to Home</a></p></center>
    </div>
    <script>
        updateAmount(); // Initial display
    </script>
</body>
</html>
<?php mysqli_close($db); ?>