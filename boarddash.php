<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch premium details
$premium_query = "SELECT premium_amount, currency, premium_expiry FROM premiums WHERE user_id = ?";
$stmt = $conn->prepare($premium_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$premium_result = $stmt->get_result();
$premium = $premium_result->fetch_assoc();

// Fetch claims
$claims_query = "SELECT claim_type, description, amount, status, created_at FROM claims WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($claims_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$claims_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto; }
        h2 { color: #333; margin-bottom: 20px; }
        .section { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #007bff; color: white; }
        .expired { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Dashboard</h2>
        
        <div class="section">
            <h3>Premium Details</h3>
            <?php if ($premium) { ?>
                <p>Amount: <?php echo $premium['premium_amount'] . " " . $premium['currency']; ?></p>
                <p>Expiry: <?php echo $premium['premium_expiry']; ?>
                    <?php if (strtotime($premium['premium_expiry']) < time()) echo "<span class='expired'>(Expired)</span>"; ?>
                </p>
            <?php } else { ?>
                <p>No active premium found.</p>
            <?php } ?>
        </div>

        <div class="section">
            <h3>Your Claims</h3>
            <table>
                <tr>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
                <?php while ($claim = $claims_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $claim['claim_type']; ?></td>
                        <td><?php echo $claim['description']; ?></td>
                        <td><?php echo $claim['amount']; ?></td>
                        <td><?php echo $claim['status']; ?></td>
                        <td><?php echo $claim['created_at']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>