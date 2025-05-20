<?php
session_start();
include_once 'connect.php';
require_once 'tcpdf/tcpdf.php'; // You'll need to download TCPDF library

if (!isset($_SESSION['id'])) {
    header("location:login.php");
    die();
}

$userID = $_SESSION['id'];

// Get user details
$user_query = "SELECT * FROM users WHERE user_id = '$userID'";
$user_result = mysqli_query($db, $user_query);
$user = mysqli_fetch_assoc($user_result);

if (isset($_GET['action'])) {
    // Create PDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('TruSecure Insurance');
    $pdf->SetTitle('Claims Report');
    $pdf->SetSubject('Claims Details');
    
    $pdf->AddPage();
    
    if ($_GET['action'] == 'single' && isset($_GET['claim_id'])) {
        $claim_id = $_GET['claim_id'];
        $claim_query = "SELECT * FROM claims WHERE claim_id = '$claim_id' AND user_id = '$userID' AND claim_status = 'Approved'";
        $claim_result = mysqli_query($db, $claim_query);
        
        if (mysqli_num_rows($claim_result) > 0) {
            $claim = mysqli_fetch_assoc($claim_result);
            
            $html = '<h1>Claim Details</h1>';
            $html .= '<h2>User Information</h2>';
            $html .= '<p>Name: ' . $user['user_firstName'] . ' ' . $user['user_lastName'] . '</p>';
            $html .= '<p>Email: ' . $user['user_email'] . '</p>';
            $html .= '<p>Mobile: ' . $user['user_mobile'] . '</p>';
            $html .= '<h2>Claim Information</h2>';
            $html .= '<p>Claim ID: ' . $claim['claim_id'] . '</p>';
            $html .= '<p>Date: ' . $claim['claim_date'] . '</p>';
            $html .= '<p>Type: ' . $claim['claim_type'] . '</p>';
            $html .= '<p>Amount: ' . $claim['claim_amount'] . '</p>';
            $html .= '<p>Description: ' . $claim['description'] . '</p>';
            $html .= '<p>Status: ' . $claim['claim_status'] . '</p>';
            
            $pdf->writeHTML($html);
            $pdf->Output('claim_' . $claim_id . '.pdf', 'D');
        }
    } elseif ($_GET['action'] == 'all') {
        $claims_query = "SELECT * FROM claims WHERE user_id = '$userID'";
        $claims_result = mysqli_query($db, $claims_query);
        
        $html = '<h1>All Claims Report</h1>';
        $html .= '<h2>User Information</h2>';
        $html .= '<p>Name: ' . $user['user_firstName'] . ' ' . $user['user_lastName'] . '</p>';
        $html .= '<p>Email: ' . $user['user_email'] . '</p>';
        $html .= '<p>Mobile: ' . $user['user_mobile'] . '</p>';
        $html .= '<h2>Claims History</h2>';
        $html .= '<table border="1"><tr><th>ID</th><th>Date</th><th>Type</th><th>Amount</th><th>Status</th></tr>';
        
        while ($claim = mysqli_fetch_assoc($claims_result)) {
            $html .= '<tr>';
            $html .= '<td>' . $claim['claim_id'] . '</td>';
            $html .= '<td>' . $claim['claim_date'] . '</td>';
            $html .= '<td>' . $claim['claim_type'] . '</td>';
            $html .= '<td>' . $claim['claim_amount'] . '</td>';
            $html .= '<td>' . $claim['claim_status'] . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        
        $pdf->writeHTML($html);
        $pdf->Output('all_claims_report.pdf', 'D');
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claims Report - TruSecure Insurance</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #4203a9, #90bafc); min-height: 100vh; color: #333; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        h1 { color: #4203a9; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        th { background: #f5f5f5; }
        .btn { padding: 8px 15px; background: #4203a9; color: white; text-decoration: none; border-radius: 5px; }
        .btn:hover { background: #35028c; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Claims Report</h1>
        <table>
            <tr>
                <th>Claim ID</th>
                <th>Date</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
            $claims_query = "SELECT * FROM claims WHERE user_id = '$userID'";
            $claims_result = mysqli_query($db, $claims_query);
            while ($claim = mysqli_fetch_assoc($claims_result)) {
                echo '<tr>';
                echo '<td>' . $claim['claim_id'] . '</td>';
                echo '<td>' . $claim['claim_date'] . '</td>';
                echo '<td>' . $claim['claim_type'] . '</td>';
                echo '<td>' . $claim['claim_amount'] . '</td>';
                echo '<td>' . $claim['claim_status'] . '</td>';
                echo '<td>';
                if ($claim['claim_status'] == 'Approved') {
                    echo '<a href="claims_check.php?action=single&claim_id=' . $claim['claim_id'] . '" class="btn">Download PDF</a>';
                } else {
                    echo 'N/A';
                }
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </table>
        <p><a href="claims_check.php?action=all" class="btn">Download Claim Report</a></p><br/><br/>
        <p><a href="cdashboard.php" class="btn">Back to Dashboard</a></p>
    </div>
</body>
</html>
<?php mysqli_close($db); ?>