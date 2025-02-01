<?php 

include("../../backend/user-check.php"); 

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
} else {
    $error = null;
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Money</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body onload="onload()" class="light-mode">
<?php include("../../includes/sidebars/user-sidebar.php"); ?>
    <div class="main-content">
    <?php include("../../includes/headers/header.php"); ?>
            <div class="col-flex-div">
            
            <div class="section-container">
            
                <h2>Request Money</h2>
                <form action="../../backend/backend-user-request-money.php" method="post">
                    <div class="input-group">
                        <select name="banking" id="banking">
                            <option value="bkash">Bkash</option>
                            <option value="nagad">Nagad</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <select name="method" id="method">
                            <option value="send-money">Send Money</option>
                            <option value="cash-out">Cash Out</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <input type="tel" id="number" name="number" pattern="^(01[3-9][0-9]{8})$" placeholder="Number" required>
                    </div>
                    <div class="input-group">
                        <input type="number" id="amount" max="50000" name="amount" placeholder="Amount (max. 50K)" required>
                    </div>
                    <div class="input-group">
                        <input type="text" id="note" name="note" placeholder="Note (Optional)">
                    </div>
                    <p style = "color:red"><?php echo $error; ?></p>
                    <button type="submit" class="submit-btn" name="btn-req-money" value="clicked">Request Money</button>
                </form>
                <p class="signup-link">Having problem? <a href="transection-details.php">Report</a></p>
            </div>

        </div>
    </div>

    
    <script src="../../assets/js/darkMode.js"></script>
</body>
</html>
