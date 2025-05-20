<?php
session_start();
include_once 'connect.php';
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['id'];
$query = "SELECT * FROM plans";
$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plans - TruSecure Insurance</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #4203a9, #90bafc); color: #fff; min-height: 100vh; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { text-align: center; font-size: 36px; margin-bottom: 30px; }
        .plans-list { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
        .plan-card { background: #fff; color: #333; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; text-align: center; }
        .plan-card h3 { color: #4203a9; font-size: 24px; }
        .plan-card p { font-size: 16px; margin: 10px 0; }
        .plan-card a { display: inline-block; padding: 10px 20px; background: #4203a9; color: #fff; text-decoration: none; border-radius: 5px; transition: background 0.3s; }
        .plan-card a:hover { background: #35028c; }
        .back-btn { display: block; text-align: center; margin-top: 30px; }
        .back-btn a { color: #fff; text-decoration: none; font-weight: bold; }
        .back-btn a:hover { color: #ffd700; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Choose Your Insurance Plan</h1>
        <div class="plans-list">
            <?php while ($plan = mysqli_fetch_assoc($result)) { ?>
                <div class="plan-card">
                    <h3><?php echo $plan['plan_name']; ?></h3>
                    <p>Yearly Amount: $<?php echo $plan['plan_amount']; ?></p>
                    <p><?php echo $plan['description']; ?></p>
                    <a href="premiums.php?plan_id=<?php echo $plan['plan_id']; ?>&amount=<?php echo $plan['plan_amount']; ?>">Select Plan</a>
                </div>
            <?php } ?>
        </div>
        <div class="back-btn">
            <a href="home.php">Back to Home</a>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($db); ?>