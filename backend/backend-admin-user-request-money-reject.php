<?php

error_log(0);
session_start();



if(!empty($_POST["btn-req-money-reject"]) && $_POST["btn-req-money-reject"] == "clicked"){
    include("../config/connection.php");
    $transID = filter_var($_POST["transID"], FILTER_SANITIZE_STRING);
    $reason = filter_var($_POST["reason"], FILTER_SANITIZE_STRING);

    if(!empty($transID) && !empty($reason)){
        $transID = mysqli_real_escape_string($conn,$transID);
        $reason = mysqli_real_escape_string($conn,$reason);

        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Update transaction status
            $sql = "UPDATE transections SET status = 'Rejected', lastnumber = '$reason' WHERE transID = '$transID'";
            mysqli_query($conn, $sql);

            // Get transaction details for notification
            $sql = "SELECT username, amount, number FROM transections WHERE transID = '$transID'";
            $result = mysqli_query($conn, $sql);
            $transaction = mysqli_fetch_assoc($result);

            // Create notification
            $sql = "INSERT INTO notifications (username, transID, number, status, lastnumber) 
                   VALUES ('{$transaction['username']}', '$transID', '{$transaction['number']}', 'Rejected', '$reason')";
            mysqli_query($conn, $sql);

            // Commit transaction
            mysqli_commit($conn);

            //send notification
            $sql = "SELECT token FROM notification_tokens WHERE username = '{$transaction['username']}'";
            $result = mysqli_query($conn, $sql) or die("Query Failed.");
            if (mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_assoc($result)){
                    $parameters = array(
                        "pvKey" => "pvKey.json",
                        "project_name" => "testapp-db6f5",
                        "token" => $row['token'],
                        "notification_title" => "PayBird: Transection was Rejected!",
                        "notification_body" => "Amount : ".$transaction['amount']." TK\tStatus : Rejected\nReason : ".$reason."",
                        "notification_image" => "https://img.icons8.com/?size=100&id=63688&format=png&color=000000", //success.png
                        "notification_link" => "http://localhost/money%20exchange/views/user/user-transection-details.php?transID=$transID"
                    );
                    
                    include ("notification_sender/send.php");

                }
            }
            
            header("Location: ../views/admin/admin-reject-transection-success.php?transID=$transID");
            exit();
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $_SESSION['error'] = "Something went wrong";
            header("Location: ../views/admin/admin-reject-transection.php?transID=$transID");
            exit();
        }

        
    }else{
        $_SESSION['error'] = "Fill In All The Information";
        header("Location: ../views/admin/admin-reject-transection.php?transID=$transID");
        exit();
    }

}else{
    $_SESSION['error'] = "Something went wrong";
    header("Location: ../views/admin/admin-reject-transection.php?transID=$transID");
    exit();
}