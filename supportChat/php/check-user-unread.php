<?php
session_start();
include("../../config/connection.php");

if(isset($_POST['username'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    
    $sql = "SELECT COUNT(*) as unread 
            FROM chat_messages 
            WHERE username = ? 
            AND is_read = 0 
            AND is_admin = 1";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    echo json_encode(['unread' => intval($row['unread'])]);
}
?> 