<?php
session_start();
include("../../config/connection.php");

$sql = "SELECT 
            cm.username,
            COUNT(CASE WHEN cm.is_read = 0 AND cm.is_admin = 0 THEN 1 END) as unread,
            MAX(UNIX_TIMESTAMP(cm.created_at)) as lastMessageTime
        FROM chat_messages cm
        GROUP BY cm.username";

$result = mysqli_query($conn, $sql);
$data = array();

while($row = mysqli_fetch_assoc($result)) {
    $data[$row['username']] = array(
        'unread' => intval($row['unread']),
        'lastMessageTime' => intval($row['lastMessageTime'])
    );
}

echo json_encode($data);
?> 