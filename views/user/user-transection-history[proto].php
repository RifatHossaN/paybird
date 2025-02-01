<?php include("../../backend/user-check.php") ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transection History</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body onload="onload()" class="light-mode">
    <?php include("../../includes/sidebars/user-sidebar.php"); ?>   

    <!-- Main Content -->
    <div class="main-content">
    <?php include("../../includes/headers/header.php"); ?>
            <!-- Transaction History -->
            <br><br>
            <h2>Transaction History</h2>
            <div class="data-tables">
                <table>
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Sender</th>
                            <th>Number</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th> <!-- Added Action column -->
                        </tr>
                    </thead>
                    <tbody>




<?php

$sql = "SELECT * FROM transections WHERE username = '$username'";

include("../../config/connection.php");

// Execute query and check if successful
$result = mysqli_query($conn, $sql) or die("Query Failed.");

// Check if there are any rows returned
if (mysqli_num_rows($result) > 0) {
    // Loop through each row in the result
    while ($row = mysqli_fetch_assoc($result)) {

        $status_bg = null;

        if(isset($row["status"]) && $row["status"] == "Pending"){
            $status_bg = "yellow-bg";
        }else if(isset($row["status"]) && $row["status"] == "Accepted"){
            $status_bg = "green-bg";
        }elseif(isset($row["status"]) && $row["status"] == "Rejected"){
            $status_bg = "red-bg";
        }elseif(isset($row["status"]) && $row["status"] == "Canceled"){
            $status_bg = "gray-bg";
        }

        echo"
                        <tr>
                            <td>#".$row["transID"]."</td>
                            <td>".$row["username"]."</td>
                            <td>".$row["number"]."</td>
                            <td>".$row["amount"]."</td>
                            <td><div><p class=".$status_bg." transac-status'>".$row['status']."</p></div></td>
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

    <script src="../../assets/js/darkMode.js"></script>
</body>
</html>
