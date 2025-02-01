<?php
session_start();
include_once "../../config/connection.php";

if(isset($_SESSION['username'])) {
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $is_admin = $_SESSION['superuser'] === 'true';
    
    if($is_admin) {
        // For admin: insert message for selected user
        $chat_user = mysqli_real_escape_string($conn, $_POST['chat_user']);
        $username = $chat_user;
    } else {
        // For user: insert their own message
        $username = mysqli_real_escape_string($conn, $_SESSION['username']);
    }

    if(!empty($message)) {
        $sql = "INSERT INTO chat_messages (username, message, is_admin, created_at) 
                VALUES (?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $username, $message, $is_admin);
        
        if(mysqli_stmt_execute($stmt)) {
            if ($is_admin){
                //send notification to user
                $sql = "SELECT token FROM notification_tokens WHERE username = '{$username}'";
                $result = mysqli_query($conn, $sql) or die("Query Failed.");
                if (mysqli_num_rows($result) > 0){
                    while ($row = mysqli_fetch_assoc($result)){
                        $parameters = array(
                            "pvKey" => "pvKey.json",
                            "project_name" => "testapp-db6f5",
                            "token" => $row['token'],
                            "notification_title" => "PayBird!",
                            "notification_body" => "Support : {$message}",
                            "notification_image" => "https://img.icons8.com/?size=100&id=118377&format=png&color=000000", //success.png
                            "notification_link" => "http://localhost/money%20exchange/views/user/user-support-chat.php"
                        );
                        
                        include ("notification_sender/send.php");
    
                    }
                }
                
            }else{
                //send notification to admin
                $sql = "SELECT token FROM notification_tokens WHERE username = 'admin'";
                $result = mysqli_query($conn, $sql) or die("Query Failed.");
                if (mysqli_num_rows($result) > 0){
                    while ($row = mysqli_fetch_assoc($result)){
                        $parameters = array(
                            "pvKey" => "pvKey.json",
                            "project_name" => "testapp-db6f5",
                            "token" => $row['token'],
                            "notification_title" => "PayBird!",
                            "notification_body" => "{$username} : {$message}",
                            "notification_image" => "https://img.icons8.com/?size=100&id=118377&format=png&color=000000", //success.png
                            "notification_link" => "http://localhost/money%20exchange/views/admin/admin-chat.php"
                        );
                        
                        include ("notification_sender/send.php");
    
                    }
                }

            }
            echo "success";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>