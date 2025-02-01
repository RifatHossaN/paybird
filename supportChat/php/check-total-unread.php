<?php
session_start();
include("../../config/connection.php");

$sql = "SELECT COUNT(*) as total 
        FROM chat_messages 
        WHERE is_read = 0 AND is_admin = 0";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

echo json_encode(['total' => intval($row['total'])]);
?> 