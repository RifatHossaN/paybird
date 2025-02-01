<?php
session_start();

include("../../../config/connection.php");




$username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : '';

$sql = "SELECT username FROM users WHERE username = '{$username}' AND superuser = 'false'";

$result = mysqli_query($conn,$sql) or die("Query Faild.");



if (!empty($username) && mysqli_num_rows($result) != 0){

    #rate
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql) or die("Query Failed.");
    $userData = mysqli_fetch_assoc($result);

    $rate = $userData['rate'];

    #balance
    $balance = $userData['balance'];

    #todays sent
    $today = date('Y-m-d');
    $sql = "SELECT SUM(amount) as total_amount, SUM(amountYMR) as total_myr FROM transections WHERE DATE(time) = '$today' AND username = '$username' AND status = 'Accepted'";

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    #bdt
    $total_amount = $row['total_amount'];

    #myr
    $total_myr = $row['total_myr'];
    
    
    #pending count
    $pending_sql = "SELECT COUNT(*) as count FROM transections WHERE username = '$username' AND status = 'Pending'";
    $pending_result = mysqli_query($conn, $pending_sql);
    $pending_count = mysqli_fetch_assoc($pending_result)['count'];
    
    
    echo json_encode(['rate' => $rate,
                            'balance' => $balance, 
                            'todays_sent_bdt' => $total_amount,
                            'todays_sent_myr' => $total_myr,
                            'pending_count' => $pending_count]);

}

?> 