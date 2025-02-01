<?php 
include("../../backend/admin-check.php") ;

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body onload="onload()" class="light-mode">
    <!-- Sidebar -->
    <?php include("../../includes/sidebars/admin-sidebar.php"); ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        
<?php 
include("../../includes/headers/header.php");
include("../../config/connection.php");

$username = $_SESSION['username'];

$sql = "SELECT rate FROM users WHERE username = '$username'";


// Execute query and check if successful
$result = mysqli_query($conn, $sql) or die("Query Failed.");



$userData = mysqli_fetch_assoc($result);
$rate= $userData['rate'];

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
                <a href="admin-all-transection-history.php"><div class="card">
                    <h2>Transection History</h2>
                    <p>1458</p>
                </div></a>
                <a href="#"><div class="card">
                    <h2>Total Recives </h2>
                    <p>5,000000Tk</p>
                </div></a>
                <a href="#"><div class="card">
                    <h2>Total Sent</h2>
                    <p>4,500000Tk</p>
                </div></a>
            </section>

            <!-- Summary Cards for confiq and users -->
            <section class="summary-cards">
                <a href="admin-payment-config.php"><div class="card">
                    <h2>Configer</h2>
                    <p></p>
                </div></a>
                <a href="admin-user-list.php"><div class="card">
                    <h2>Users</h2>
                    <p>48</p>
                </div></a>
                <a href="#"><div class="card">
                    <h2>Daily Stats</h2>
                    <p>4875</p>
                </div></a>
            </section>

            <!-- Action Buttons -->
            <section class="actions">
                <a href="admin-user-send-money-list.php"><button class="action-button send">Send Money</button></a>
            </section>

            <h2>Pending Requests</h2>
            <div class="data-tables">
                <table>
                    <thead>
                        <tr>
                            <th class="tb-tr-id">Transaction ID</th>
                            <th class="tb-sender">Sender</th>
                            <th class="tb-number">Number</th>
                            <th class="tb-amount">Amount</th>
                            <th class="tb-action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>


<?php

$sql = "SELECT * FROM transections WHERE status = 'pending'";

include("../../config/connection.php");

// Execute query and check if successful
$result = mysqli_query($conn, $sql) or die("Query Failed.");

// Check if there are any rows returned
if (mysqli_num_rows($result) > 0) {
    // Loop through each row in the result
    while ($row = mysqli_fetch_assoc($result)) {

        echo "
            <tr>
                <td>#".$row["transID"]."</td>
                <td>".$row["username"]."</td>
                <td>".$row["number"]."</td>
                <td>".$row["amount"]."</td>
                <td>
                    <button onclick='openPopupAccept(".$row["transID"].")' class='btn-small-green'>Accept</button>
                    <button onclick='openPopupReject(".$row["transID"].")' class='btn-small-red'>Reject</button>
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


    <!-- Popup Modal

    <div id="clickPopupAccept" class="popup-container" style="display: none;">
        <div class="center-flex-div">
            <div class="popup-content">
                
                <h2>Accept</h2>
                <br>
                <hr><br>
                <p id="popup-msg" >Enter The last 3 Digit To Accept!</p>
                <br>
                <div class="input-group">
                    <input type="number" max="99999" name="lastnumber" style="height: 30px; width:200px">
                </div>
                
                <button class="btn-small-blue close" id="closePopup" onclick="closePopupAccept()">close</button>
                <br>


            </div>
        </div>
        
    </div>

    <div id="clickPopupReject" class="popup-container" style="display: none;">
        <div class="center-flex-div">
            <div class="popup-content">
                
                <h2>Reject</h2>
                <br>
                <hr><br>
                <p id="popup-msg" >Enter The Reason Of Rejection!</p>
                <br>
                <div class="input-group">
                    <input type="text" name="reject-text" style="height: 60px; width:200px">
                </div>
                
                <button class="btn-small-blue close" id="closePopup" onclick="closePopupReject()">close</button>
                <br>


            </div>
        </div>
        
    </div> -->

    <!-- <script>
        function openPopupAccept() {
            document.getElementById("clickPopupAccept").style.display = "flex";
        }

        function closePopupAccept() {
            document.getElementById("clickPopupAccept").style.display = "none";
        }



        function openPopupReject() {
            document.getElementById("clickPopupReject").style.display = "flex";
        }

        function closePopupReject() {
            document.getElementById("clickPopupReject").style.display = "none";
        }
    </script> -->

    

    <div id="clickPopupAccept" class="popup-container" style="display: none;">
        <div class="center-flex-div">
            <div class="popup-content">
                <form action="../../backend/backend-admin-user-request-money-accept.php" method="POST">
                    <h2>Accept</h2>
                    <br>
                    <hr><br>
                    <p >Enter The Last 3 Digits to Accept!</p>
                    <br>
                    <div class="input-group">
                        <input type="hidden" id="acceptRequestId" name="transID" value="">
                        <input type="hidden" name="status" value="Accepted">
                        <input type="number" max="9999" name="lastnumber" required style="height: 30px; width:200px">
                    </div>
                    <br>
                    <p style = "color:red"><?php echo $error; ?></p>
                    <button type="button" class="btn-small-blue close" onclick="closePopupAccept()">Close</button>
                    <button type="submit" class="btn-small-green close" name="btn-req-money-accept" value="clicked">Accept</button>
                </form>
            </div>
        </div>
    </div>

    <div id="clickPopupReject" class="popup-container" style="display: none;">
        <div class="center-flex-div">
            <div class="popup-content">
                <form action="../../backend/backend-admin-user-request-money-reject.php" method="POST">
                    <h2>Reject</h2>
                    <br>
                    <hr><br>
                    <p >Enter the Reason for Rejection!</p>
                    <br>
                    <div class="input-group">
                        <input type="hidden" id="rejectRequestId" name="transID" value="">
                        <input type="hidden" name="status" value="Rejected">
                        <input type="text" name="reason" required style="height: 60px; width:200px">
                    </div>
                    <br>
                    <p style = "color:red"><?php echo $error; ?></p>
                    <button type="button" class="btn-small-blue close" onclick="closePopupReject()">Close</button>
                    <button type="submit" class="btn-small-red close" name="btn-req-money-reject" value="clicked">Reject</button>
                </form>
            </div>
        </div>
    </div>


    <div id="successPopup" class="popup-container" style="display: none;">
        <div class="center-flex-div">
            <div class="popup-content">
                
                <h2>Success!!</h2><br>
                <hr><br><br>
                <p id="popup-msg" >successfully!</p>
                <br><br><br>
                <button class="btn-small-blue close" id="closePopup">close</button>


            </div>
        </div>
        
    </div>





    <script>
        function openPopupAccept(id) {
            document.getElementById("clickPopupAccept").style.display = "block";
            document.getElementById("acceptRequestId").value = id;
        }

        function closePopupAccept() {
            document.getElementById("clickPopupAccept").style.display = "none";
        }

        function openPopupReject(id) {
            document.getElementById("clickPopupReject").style.display = "block";
            document.getElementById("rejectRequestId").value = id;
        }

        function closePopupReject() {
            document.getElementById("clickPopupReject").style.display = "none";
        }

    </script>





    <script src="../../assets/js/popup.js"></script>
    <script src="../../assets/js/darkMode.js"></script>
</body>
</html>
