<?php
session_start();
include_once 'connect.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Check for existing pending claims
$pending_check = "SELECT COUNT(*) as pending_count FROM claims WHERE user_id = '$user_id' AND claim_status = 'Pending'";
$pending_result = mysqli_query($db, $pending_check);
$pending_data = mysqli_fetch_assoc($pending_result);

if ($pending_data['pending_count'] > 0) {
    $message = "You cannot submit a new claim while another claim is pending review.";
    $premium_expired = true; // To prevent form display
} else {
    // Select the most recent premium
    $query = "SELECT * FROM premiums WHERE user_id = '$user_id' ORDER BY premium_expiry DESC LIMIT 1";
    $result = mysqli_query($db, $query);
    $premium = mysqli_fetch_assoc($result);
    $premium_expired = !$premium || strtotime($premium['premium_expiry']) < time();

    // Handle form submission if premium is active
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$premium_expired) {
        $claim_type = $_POST['claim_type'];
        $description = $_POST['description'];
        $amount = $_POST['amount'];

        $query = "INSERT INTO claims (user_id, claim_type, description, claim_amount, claim_status, claim_date) 
                  VALUES ('$user_id', '$claim_type', '$description', '$amount', 'Pending', CURDATE())";
        if (mysqli_query($db, $query)) {
            $message = "Claim submitted successfully!";
        } else {
            $message = "Error submitting claim.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File a Claim - TruSecure Insurance</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { min-height: 100vh; background: linear-gradient(135deg, #4203a9, #90bafc); display: flex; justify-content: center; align-items: center; }
        .wrapper { min-height: 100vh; display: flex; justify-content: center; align-items: center; }
        .registration_form { background: white; padding: 25px; border-radius: 5px; width: 400px; }
        .title { padding: 25px; text-align: center; }
        .title p { color: #333; font-size: 24px; font-weight: bold; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; color: #555; margin-bottom: 8px; font-size: 14px; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; transition: border-color 0.3s ease; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #4203a9; box-shadow: 0 0 5px rgba(66, 3, 169, 0.2); }
        .login-btn { width: 100%; padding: 12px; background: #4203a9; border: none; border-radius: 8px; color: white; font-size: 16px; cursor: pointer; transition: background 0.3s ease; }
        .login-btn:hover { background: #35028c; }
        .message { text-align: center; color: green; margin-top: 15px; }
        .error { text-align: center; color: red; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="registration_form">
            <div class="title">
                <p><b>TRUESECURE INSURANCE</b></p>
                <p style="font-size: 18px; color: #666;">File a New Claim</p>
            </div>
            <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
            <?php if ($premium_expired) { ?>
                <?php if ($pending_data['pending_count'] > 0) { ?>
                    <p class="error">You have a pending claim under review. Please wait for it to be processed.</p>
                <?php } elseif ($premium) { ?>
                    <p class="error">Your premium has expired. Please renew to file a claim.</p>
                    <center><p><a href="premiums.php?plan_id=<?php echo $premium['plan_id']; ?>" style="color: #4203a9; text-decoration: none;">Renew Now</a></p></center>
                <?php } else { ?>
                    <p class="error">You do not have an active premium. Please choose a plan to file a claim.</p>
                    <center><p><a href="plans.php" style="color: #4203a9; text-decoration: none;">Choose a Plan</a></p></center>
                <?php } ?>
            <?php } else { ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="claim_type">Claim Type</label>
                        <select id="claim_type" name="claim_type" required>
                            <option value="Medical">Medical</option>
                            <option value="Accident">Accident</option>
                            <option value="Death">Death</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Describe your claim..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" id="amount" name="amount" step="0.01" placeholder="Enter amount..." required>
                    </div>
                    <input type="submit" value="Submit Claim" class="login-btn">
                </form>
                <center><p style="margin-top: 15px;">Back to <a href="Home.php" style="color: #4203a9; text-decoration: none;">Home</a></p></center>
            <?php } ?>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($db); ?>