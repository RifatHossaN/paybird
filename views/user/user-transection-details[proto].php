<?php 
include("../../backend/user-check.php") ;

include("../../config/connection.php");

$transID = mysqli_real_escape_string($conn, $_GET['transID']);


$sql = "SELECT * FROM transections WHERE transID = '$transID'";


// Execute query and check if successful
$result = mysqli_query($conn, $sql) or die("Query Failed.");



$transData = mysqli_fetch_assoc($result);

$_SESSION['canceltrans']= $transData['transID'];



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Details</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    
</head>
<body onload="onload()" class="light-mode">
<?php include("../../includes/sidebars/user-sidebar.php"); ?>
    <div class="main-content">
    <?php include("../../includes/headers/header.php"); ?>
        <div class="col-flex-div">
            <div class="section-container">
            <h2>Transaction Details</h2>
                <hr>
                <div class="transaction-info">
                    <p><strong>Trans.ID:</strong> #<?php echo $transData['transID']; ?></p>
                    <p><strong>Sender:</strong> <?php echo $transData['username']; ?></p>
                    <p><strong>Amount:</strong> <?php echo $transData['amount']; ?>Tk</p>
                    <p><strong>Banking:</strong> <?php echo $transData['banking']; ?></p>
                    <p><strong>Method:</strong> <?php echo $transData['method']; ?></p>
                    <p><strong>Time:</strong> <?php echo $transData['time']; ?></p>
                    <p><strong>Status:</strong> <?php echo $transData['status']; ?></p>
                </div>
                <div class="btn-container">
                    <button class="btn-small-blue" onclick="window.history.back();">Back </button>
<?php

if ($transData['status'] == 'Pending') {

                echo "<a href='../../backend/backend-user-request-money-cancel.php'><button class='btn-small-red'>Cancel</button></a>";


                    
}
?>
                </div>
            </div>
        </div>
    </div>
    <script src="../../assets/js/darkMode.js"></script>
</body>
</html>
