<?php
session_start();
include("../config/connection.php");

if (!isset($_SESSION['username'])) {
    echo json_encode(['unread' => 0]);
    exit;
}

$username = $_SESSION['username'];

// Get unread count
$countSql = "SELECT COUNT(*) as unread FROM notifications 
             WHERE username = '$username' 
             AND is_read = 0";

$countResult = mysqli_query($conn, $countSql);
$unreadCount = mysqli_fetch_assoc($countResult)['unread'];

$countSql = "SELECT COUNT(*) as unread FROM deposit_notifications 
             WHERE username = '$username' 
             AND is_read = 0";

$countResult = mysqli_query($conn, $countSql);
$unreadCount = $unreadCount + mysqli_fetch_assoc($countResult)['unread'];

echo json_encode(['unread' => $unreadCount]); 