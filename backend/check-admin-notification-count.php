<?php
session_start();
include("../config/connection.php");


// Get unread count
$countSqlDeposit = "SELECT COUNT(*) as unread FROM admin_deposit_notifications 
             WHERE is_read = 0";



$countResultDeposit = mysqli_query($conn, $countSqlDeposit);
$unreadCountDeposit = mysqli_fetch_assoc($countResultDeposit)['unread'];



$countSqlTransection = "SELECT COUNT(*) as unread FROM admin_transection_notifications 
             WHERE is_read = 0";



$countResultTransection = mysqli_query($conn, $countSqlTransection);

$totalUnreadCount = $unreadCountDeposit + mysqli_fetch_assoc($countResultTransection)['unread'];

// echo $unreadCountDeposit;
// echo $unreadCountTransection;

// echo $totalUnreadCount;

echo json_encode(['unread' => $totalUnreadCount]); 