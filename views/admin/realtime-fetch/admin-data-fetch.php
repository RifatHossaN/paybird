<?php
session_start();

include("../../../config/connection.php");




#rate
$username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : '';

$sql = "SELECT username FROM users WHERE username = '{$username}' AND superuser = 'true'";

$result = mysqli_query($conn,$sql) or die("Query Faild.");



if (!empty($username) && mysqli_num_rows($result) != 0){
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql) or die("Query Failed.");
    $userData = mysqli_fetch_assoc($result);
    $rate = $userData['rate'];
    
    #getting total user
    $users_sql = "SELECT COUNT(*) as count FROM users WHERE superuser = 'false'";
    $users_result = mysqli_query($conn, $users_sql);
    $users_count = mysqli_fetch_assoc($users_result)['count'];
    
    
    #total sent of today
    $today = date('Y-m-d');
    $sql = "SELECT SUM(amount) as total_amount, SUM(amount/rate) as total_myr FROM transections WHERE DATE(time)  = '$today' AND status = 'Accepted'";                                    
    $result = mysqli_query($conn, $sql) or die("Query Failed.");
    $row = mysqli_fetch_assoc($result);
    
    #bdt
    $total_amount = $row['total_amount'];
    
    #myr
    $total_myr = $row['total_myr'];
    
    
    #total pendings
    $pending_sql = "SELECT COUNT(*) as count FROM transections WHERE status = 'Pending' AND TIMESTAMPDIFF(SECOND, time, NOW()) > 30";
    $pending_result = mysqli_query($conn, $pending_sql);
    $pending_count = mysqli_fetch_assoc($pending_result)['count'];
    
    
    echo json_encode(['rate' => $rate,
                            'total_user' => $users_count, 
                            'todays_sent_bdt' => $total_amount,
                            'todays_sent_myr' => $total_myr,
                            'pending_count' => $pending_count]);

}

?> 