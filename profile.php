<?php
include_once 'connect.php';
session_start();
if (!isset($_SESSION['id'])) {
    header("location:login.php");
    die();
}
$userID = $_SESSION['id'];
$query = "SELECT * FROM users WHERE user_id = '$userID'";
$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - TruSecure Insurance</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body { background: linear-gradient(135deg, #4203a9, #90bafc); min-height: 100vh; }
        .profile-card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); max-width: 600px; margin: 50px auto; }
        .profile-card h2 { color: #4203a9; font-weight: bold; }
        .profile-card .data-table { width: 100%; margin-top: 20px; }
        .profile-card .data-table th { color: #555; width: 40%; }
        .profile-card .data-table td { color: #333; }
    </style>
</head>
<body>
    <div class="profile-card">
        <h2>Your Profile</h2>
        <?php if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        ?>
        <table class="data-table">
            <tr><th>First Name</th><td><?php echo $row['user_firstName']; ?></td></tr>
            <tr><th>Last Name</th><td><?php echo $row['user_lastName']; ?></td></tr>
            <tr><th>Email</th><td><?php echo $row['user_email']; ?></td></tr>
            <tr><th>Mobile</th><td><?php echo $row['user_mobile']; ?></td></tr>
            <tr><th>Address</th><td><?php echo $row['user_address']; ?></td></tr>
            <tr><th>Gender</th><td><?php echo $row['user_gender']; ?></td></tr>
            <tr><th>Registration Date</th><td><?php echo $row['user_createdDateTime']; ?></td></tr>
        </table>
        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
        </div>
        <?php } else { ?>
            <p class="text-danger text-center">User not found!</p>
        <?php } ?>
    </div>
</body>
</html>
<?php mysqli_close($db); ?>