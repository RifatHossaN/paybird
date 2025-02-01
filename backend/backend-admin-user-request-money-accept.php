<?php

// error_log(0);
session_start();



if(!empty($_POST["btn-req-money-accept"]) && $_POST["btn-req-money-accept"] == "clicked"){
    include("../config/connection.php");
    $transID = filter_var($_POST["transID"], FILTER_SANITIZE_STRING);
    $lastnumber = filter_var($_POST["lastnumber"], FILTER_SANITIZE_STRING);

    if(!empty($transID) && !empty($lastnumber)){
        $transID = mysqli_real_escape_string($conn,$transID);
        $lastnumber = mysqli_real_escape_string($conn,$lastnumber);

        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Get transaction details
            $sql = "SELECT username, number, amount FROM transections WHERE transID = '$transID'";
            $result = mysqli_query($conn, $sql);
            $transaction = mysqli_fetch_assoc($result);

            // Update transaction status
            $sql = "UPDATE transections SET status = 'Accepted', lastnumber = '$lastnumber' WHERE transID = '$transID'";
            mysqli_query($conn, $sql);

            // Update user balance (adding the transaction amount)
            $sql = "UPDATE users SET balance = balance - {$transaction['amount']} WHERE username = '{$transaction['username']}'";
            mysqli_query($conn, $sql);

            // Create notification
            $sql = "INSERT INTO notifications (username, transID, number, status, lastnumber) 
                   VALUES ('{$transaction['username']}', '$transID', '{$transaction['number']}', 'Accepted', '$lastnumber')";
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
                        "notification_title" => "PayBird: Transection was successful!",
                        "notification_body" => "Amount : ".$transaction['amount']." TK\tStatus : Accepted\nLastnumber : ".$lastnumber."",
                        "notification_image" => "https://img.icons8.com/?size=100&id=59850&format=png&color=40C057", //success.png
                        "notification_link" => "http://localhost/money%20exchange/views/user/user-transection-details.php?transID=$transID"
                    );
                    
                    include ("notification_sender/send.php");

                }
            }

            

            header("Location: ../views/admin/admin-accept-transection-success.php?transID=$transID");
            exit();
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $_SESSION['error'] = "Something went wrong";
            header("Location: ../views/admin/admin-accept-transection.php?transID=$transID");
            exit();
        }

        
    }else{
        $_SESSION['error'] = "Fill In All The Information";
        header("Location: ../views/admin/admin-accept-transection.php?transID=$transID");
        exit();
    }

}else{
    $_SESSION['error'] = "Something went wrong";
    header("Location: ../views/admin/admin-accept-transection.php?transID=$transID");
    exit();
}