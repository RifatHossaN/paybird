<?php
include("../../config/connection.php");

if(isset($_POST['chat_user'])) {
    $chat_user = mysqli_real_escape_string($conn, $_POST['chat_user']);
    
    // Mark all messages from this user as read
    $sql = "UPDATE chat_messages 
            SET is_read = 1 
            WHERE username = ? AND is_admin = 0 AND is_read = 0";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $chat_user);
    mysqli_stmt_execute($stmt);
}
?> 