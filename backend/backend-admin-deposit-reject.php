<?php
session_start();

if(!empty($_POST["btn-deposit-reject"]) && $_POST["btn-deposit-reject"] == "clicked"){
    include("../config/connection.php");
    $depositID = filter_var($_POST["depositID"], FILTER_SANITIZE_STRING);

    if(!empty($depositID)){
        $depositID = mysqli_real_escape_string($conn, $depositID);

        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Get deposit details
            $sql = "SELECT username, amount FROM deposits WHERE depositID = '$depositID'";
            $result = mysqli_query($conn, $sql);
            $deposit = mysqli_fetch_assoc($result);

            // Update deposit status and reason
            $sql = "UPDATE deposits SET status = 'Rejected' WHERE depositID = '$depositID'";
            mysqli_query($conn, $sql);

            // Create notification
            $sql = "INSERT INTO deposit_notifications (username, depositID, status) 
            VALUES ('{$deposit['username']}', '$depositID', 'Rejected')";
            mysqli_query($conn, $sql);

            // Commit transaction
            mysqli_commit($conn);

            // Send notification
            $sql = "SELECT token FROM notification_tokens WHERE username = '{$deposit['username']}'";
            $result = mysqli_query($conn, $sql) or die("Query Failed.");
            if (mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_assoc($result)){
                    $parameters = array(
                        "pvKey" => "pvKey.json",
                        "project_name" => "testapp-db6f5",
                        "token" => $row['token'],
                        "notification_title" => "PayBird: Deposit was Rejected!",
                        "notification_body" => "Amount : ".$deposit['amount']." TK\nStatus : Rejected",
                        "notification_image" => "https://img.icons8.com/?size=100&id=63688&format=png&color=000000",
                        "notification_link" => "http://localhost/money%20exchange/views/user/user-deposit-details.php?id=$depositID"
                    );
                    
                    include ("notification_sender/send.php");
                }
            }

            header("Location: ../views/admin/admin-reject-deposit-success.php?id=$depositID");
            exit();
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $_SESSION['error'] = "Something went wrong err. db";
            header("Location: ../views/admin/admin-deposit-details.php?id=$depositID");
            exit();
        }
    } else {
        $_SESSION['error'] = "Fill in all the information";
        header("Location: ../views/admin/admin-deposit-details.php?id=$depositID");
        exit();
    }
} else {
    $_SESSION['error'] = "Something went wrong";
    header("Location: ../views/admin/admin-deposit-details.php?id=$depositID");
    exit();
} 