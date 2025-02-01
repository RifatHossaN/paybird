<?php include("../../backend/user-check.php") ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Money Transfer Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body onload="onload()" class="light-mode">

<?php include("../../includes/headers/header.php"); ?>
<?php include("../../includes/sidebars/sidebar.php"); ?>

    <!-- Main Content -->
    <div class="main-content">


<?php 
include("../../config/connection.php");

$username = $_SESSION['username'];

$sql = "SELECT rate FROM users WHERE username = '$username'";


// Execute query and check if successful
$result = mysqli_query($conn, $sql) or die("Query Failed.");



$userData = mysqli_fetch_assoc($result);
$rate= $userData['rate'];

$_SESSION['rate']= $rate;

?>



        <!-- Exchange Rate Banner -->
        <div class="exchange-rate-banner">
            <div class="exchange-rate-content">
                <span class="exchange-icon">💱</span>
                <p>Current Exchange Rate: <br><span class="rate">1 MYR = <?php echo "$rate"; ?>TK</span></p>
            </div>
        </div>

        <!-- Dashboard Section -->
        <main class="dashboard">
            <!-- Summary Cards -->
            <section class="summary-cards">
                <a href="user-transection-history.php">
                <div class="card">
                    <h2>Transection History</h2>
                    <p>123</p>
                </div>
                </a>
                
                <a href="#">
                    <div class="card">
                        <h2>Balance</h2>
                        <p>5,000Tk</p>
                    </div>
                </a>

                <a href="#">
                    <div class="card">
                        <h2>Total Sent</h2>
                        <p>4,500000Tk</p>
                    </div>
                </a>
                
            </section>

            <!-- Action Buttons -->
            <section class="actions">
                <a href="user-request-money.php"><button class="action-button request">Request Money</button></a>
            </section>

            <!-- Transaction History -->
            <h2>Pending Money Requests</h2>
            <div class="data-tables">
                <table>
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Username</th>
                            <th>Number</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th> <!-- Added Action column -->
                        </tr>
                    </thead>
                    <tbody>
   

<?php

$sql = "SELECT * FROM transections WHERE username = '$username' and status = 'pending'";

include("../../config/connection.php");

// Execute query and check if successful
$result = mysqli_query($conn, $sql) or die("Query Failed.");

// Check if there are any rows returned
if (mysqli_num_rows($result) > 0) {
    // Loop through each row in the result
    while ($row = mysqli_fetch_assoc($result)) {

        echo"
                        <tr>
                            <td>#".$row["transID"]."</td>
                            <td>".$row["username"]."</td>
                            <td>".$row["number"]."</td>
                            <td>".$row["amount"]."</td>
                            <td><div><p class='yellow-bg transac-status'>Pending</p></div></td>
                            <td>
                                <a href='user-transection-details.php?transID=$row[transID]'><button class='btn-small-blue'>view</button></a>
                            </td>
                        </tr>
        ";

    }
} else {
    echo "No results found.";
}

?>

                        
                    </tbody>
                </table>
            </div>
            
        </main>
    </div>

    <!-- Popup Modal -->
    <div id="successPopup" class="popup-container" style="display: none;">
        <div class="center-flex-div">
            <div class="popup-content">
                
                <h2>Success!!</h2><br>
                <hr><br><br>
                <p id="popup-msg" >Money request added successfully!</p>
                <br><br><br>
                <button class="btn-small-blue close" id="closePopup">close</button>


            </div>
        </div>
        
    </div>


    <script src="../../assets/js/popup.js"></script>

    <script src="../../assets/js/darkMode.js"></script>
</body>
</html>
