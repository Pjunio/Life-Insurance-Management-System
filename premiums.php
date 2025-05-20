<?php
session_start();
include_once 'connect.php';
if (!isset($_SESSION['id']) || !isset($_GET['plan_id']) || !isset($_GET['amount'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['id'];
$plan_id = $_GET['plan_id'];
$plan_amount = $_GET['amount'];

$query = "SELECT * FROM plans WHERE plan_id = '$plan_id'";
$plan_result = mysqli_query($db, $query);
$plan = mysqli_fetch_assoc($plan_result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currency = $_POST['currency'];
    $expiry = date('Y-m-d H:i:s', strtotime('+1 year'));
    $rates = ['USD' => 1, 'EUR' => 0.85, 'GBP' => 0.73, 'INR' => 83, 'JPY' => 150, 'CAD' => 1.35, 'AUD' => 1.50];
    $base_amount = $plan_amount / $rates[$currency]; // Convert to USD

    $check_premium = "SELECT * FROM premiums WHERE user_id = '$user_id'";
    $premium_result = mysqli_query($db, $check_premium);
    if (mysqli_num_rows($premium_result) > 0) {
        $query = "UPDATE premiums SET plan_id = '$plan_id', premium_amount = '$base_amount', currency = '$currency', premium_expiry = '$expiry' WHERE user_id = '$user_id'";
    } else {
        $query = "INSERT INTO premiums (user_id, plan_id, premium_amount, currency, premium_expiry) VALUES ('$user_id', '$plan_id', '$base_amount', '$currency', '$expiry')";
    }
    if (mysqli_query($db, $query)) {
        $update_user = "UPDATE users SET plan_id = '$plan_id' WHERE user_id = '$user_id'";
        mysqli_query($db, $update_user);
        $payment_query = "INSERT INTO payments (payment_date, payment_amount, user_id) VALUES (NOW(), '$base_amount', '$user_id')";
        mysqli_query($db, $payment_query);
        header("Location: dashboard.php?message=Payment successful!");
    } else {
        $message = "Error processing payment.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Premium - TruSecure Insurance</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #4203a9, #90bafc); min-height: 100vh; display: flex; justify-content: center; align-items: center; }
        .form-container { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 400px; }
        h2 { color: #4203a9; text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { color: #555; font-size: 14px; }
        .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .btn-submit { width: 100%; padding: 12px; background: #4203a9; border: none; border-radius: 5px; color: #fff; font-size: 16px; transition: background 0.3s; }
        .btn-submit:hover { background: #35028c; }
        .message { color: green; text-align: center; margin-bottom: 15px; }
        .back-link { text-align: center; margin-top: 15px; }
        .back-link a { color: #4203a9; text-decoration: none; }
        .back-link a:hover { color: #35028c; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Pay for <?php echo $plan['plan_name']; ?></h2>
        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
        <p style="text-align: center; color: #555;">Amount: $<?php echo $plan_amount; ?></p>
        <form method="POST" action="">
            <div class="form-group">
                <label for="currency">Select Currency</label>
                <select id="currency" name="currency" required>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                    <option value="INR">INR</option>
                    <option value="JPY">JPY</option>
                    <option value="CAD">CAD</option>
                    <option value="AUD">AUD</option>
                </select>
            </div>
            <button type="submit" class="btn-submit">Pay Now</button>
        </form>
        <div class="back-link">
            <a href="plans.php">Back to Plans</a>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($db); ?>