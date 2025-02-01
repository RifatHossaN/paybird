<?php
session_start();
include_once "../../config/connection.php";

if(isset($_SESSION['username'])) {
    $output = array();
    $is_admin = $_SESSION['superuser'] === 'true';
    
    if($is_admin) {
        $chat_user = mysqli_real_escape_string($conn, $_POST['chat_user']);
        $sql = "SELECT * FROM chat_messages WHERE username = ? ORDER BY created_at ASC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $chat_user);
    } else {
        $username = mysqli_real_escape_string($conn, $_SESSION['username']);
        $sql = "SELECT * FROM chat_messages WHERE username = ? ORDER BY created_at ASC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $output[] = array(
                'message' => $row['message'],
                'is_admin' => $row['is_admin'],
                'time' => date('h:i A', strtotime($row['created_at']))
            );
        }
    }
    echo json_encode($output);
}
?>