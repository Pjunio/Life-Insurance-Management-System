<?php
include_once 'connect.php';
session_start();
if (!isset($_SESSION['id'])) {
    header("location:login.php");
    die();
}
$userID = $_SESSION['id'];
$user_query = "SELECT u.*, COUNT(c.claim_id) as claim_count 
               FROM users u 
               LEFT JOIN claims c ON u.user_id = c.user_id 
               WHERE u.user_id = '$userID' 
               GROUP BY u.user_id";
$user_result = mysqli_query($db, $user_query);
$premium_query = "SELECT * FROM premiums WHERE user_id = '$userID' ORDER BY premium_expiry DESC LIMIT 1";
$premium_result = mysqli_query($db, $premium_query);
$premium = mysqli_fetch_assoc($premium_result);

// Check for pending claims that are older than 1 minute
$pending_check = "SELECT claim_id, claim_date FROM claims WHERE user_id = '$userID' AND claim_status = 'Pending'";
$pending_result = mysqli_query($db, $pending_check);
while ($pending = mysqli_fetch_assoc($pending_result)) {
    $claim_time = strtotime($pending['claim_date']);
    if ((time() - $claim_time) > 60) { // More than 1 minute
        // Update status to either Approved or Rejected (random for demo)
        $new_status = (rand(0,1) == 1) ? 'Approved' : 'Rejected';
        $update_query = "UPDATE claims SET claim_status = '$new_status' WHERE claim_id = '{$pending['claim_id']}'";
        mysqli_query($db, $update_query);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TruSecure Insurance</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #4203a9, #90bafc); min-height: 100vh; color: #333; }
        .dashboard-container { max-width: 1200px; margin: 30px auto; padding: 20px; }
        .header { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); display: flex; align-items: center; margin-bottom: 20px; }
        .header img { height: 50px; margin-right: 20px; }
        .header h1 { color: #4203a9; font-size: 28px; margin: 0; }
        .card { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .card h2 { color: #4203a9; font-size: 24px; margin-bottom: 15px; }
        .card h2 i { margin-right: 10px; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 12px; text-align: left; }
        .data-table th { background: #f5f5f5; color: #555; }
        .data-table tr:nth-child(even) { background: #fafafa; }
        .btn-primary { background: #4203a9; border: none; padding: 10px 20px; border-radius: 5px; color: #fff; text-decoration: none; transition: background 0.3s; }
        .btn-primary:hover { background: #35028c; }
        .action-buttons { text-align: center; }
        footer { background: #333; color: #fff; padding: 20px; text-align: center; margin-top: 30px; }
        footer a { color: #ffd700; text-decoration: none; }
        footer a:hover { color: #fff; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <img src="img/logo.png" alt="TruSecure Insurance Logo">
            <h1>TruSecure Insurance Dashboard</h1>
        </div>
        <?php if (mysqli_num_rows($user_result) > 0) {
            $user = mysqli_fetch_assoc($user_result);
        ?>
        <div class="card">
            <h2><i class="fas fa-user-circle"></i> Profile Details</h2>
            <table class="data-table">
                <tr><th>Name</th><td><?php echo $user['user_firstName'] . " " . $user['user_lastName']; ?></td></tr>
                <tr><th>Customer ID</th><td><?php echo $user['user_id']; ?></td></tr>
                <tr><th>Mobile Number</th><td><?php echo $user['user_mobile']; ?></td></tr>
                <tr><th>Total Claims</th><td><?php echo $user['claim_count']; ?></td></tr>
                <?php if ($premium) { ?>
                <tr><th>Premium Expiry</th><td><?php echo $premium['premium_expiry']; ?></td></tr>
                <?php } ?>
            </table>
        </div>

        <div class="card">
            <h2><i class="fas fa-file-alt"></i> Claim Details</h2>
            <table class="data-table">
                <tr><th>Claim ID</th><th>Submission Date</th><th>Amount</th><th>Status</th></tr>
                <?php
                $claim_query = "SELECT * FROM claims WHERE user_id = '$userID'";
                $claim_result = mysqli_query($db, $claim_query);
                if (mysqli_num_rows($claim_result) > 0) {
                    while ($claim = mysqli_fetch_assoc($claim_result)) {
                ?>
                <tr>
                    <td><?php echo $claim['claim_id']; ?></td>
                    <td><?php echo $claim['claim_date']; ?></td>
                    <td><?php echo $claim['claim_amount']; ?></td>
                    <td><?php echo $claim['claim_status']; ?></td>
                </tr>
                <?php } } else { ?>
                <tr><td colspan="4">No claims found</td></tr>
                <?php } ?>
            </table>
        </div>

        <div class="card">
            <h2><i class="fas fa-money-bill-wave"></i> Payment History</h2>
            <table class="data-table">
                <tr><th>Date</th><th>Amount</th></tr>
                <?php
                $payment_query = "SELECT * FROM payments WHERE user_id = '$userID'";
                $payment_result = mysqli_query($db, $payment_query);
                if (mysqli_num_rows($payment_result) > 0) {
                    while ($payment = mysqli_fetch_assoc($payment_result)) {
                ?>
                <tr>
                    <td><?php echo $payment['payment_date']; ?></td>
                    <td><?php echo $payment['payment_amount']; ?></td>
                </tr>
                <?php } } else { ?>
                <tr><td colspan="2">No payments found</td></tr>
                <?php } ?>
            </table>
        </div>

        <div class="action-buttons">
            <a href="Home.php" class="btn-primary">Home</a>
            <a href="claims_check.php" class="btn-primary">View Claims Report</a>
        </div>
        <?php } else { ?>
        <p class="text-danger text-center">User not found!</p>
        <?php } ?>
    </div>
    <footer>
        <p>TruSecure Insurance © 2025 - All Rights Reserved | <a href="contactUs.html">Contact Us</a> | <a href="FAQ page.html">FAQ</a></p>
    </footer>
</body>
</html>
<?php mysqli_close($db); ?>