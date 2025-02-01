<?php
session_start();
include("../../config/connection.php");

if(isset($_POST['username'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    
    $sql = "UPDATE chat_messages 
            SET is_read = 1 
            WHERE username = ? 
            AND is_admin = 1";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    
    echo json_encode(['success' => true]);
}
?> 