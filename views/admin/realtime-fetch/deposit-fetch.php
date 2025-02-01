<?php
session_start();
include('../../../config/connection.php');

$filterOption = isset($_GET['filterOption']) ? $_GET['filterOption'] : 'all';
$filterVal = isset($_GET['filterVal']) ? mysqli_real_escape_string($conn, $_GET['filterVal']) : '';
$startDate = isset($_GET['startDate']) ? mysqli_real_escape_string($conn, $_GET['startDate']) : '';
$endDate = isset($_GET['endDate']) ? mysqli_real_escape_string($conn, $_GET['endDate']) : '';
$username = isset($_GET['username']) ? mysqli_real_escape_string($conn, $_GET['username']) : '';

$baseQuery = "SELECT t.depositID, 
    t.username, 
    t.amount, 
    t.amountMYR, 
    t.currency, 
    t.payment_method, 
    t.reference_no, 
    t.status, 
    t.admin_note, 
    t.created_at, 
    t.payment_date FROM deposits t 
              LEFT JOIN users u ON t.username = u.username 
              WHERE TIMESTAMPDIFF(SECOND, t.created_at, NOW()) > 30";

// Add username filter if provided (for user transaction history)
if (!empty($username)) {
    $baseQuery .= " AND t.username = '$username'";
}

switch($filterOption) {
    case 'depositID':
        $baseQuery .= " AND t.depositID LIKE '%$filterVal%'";
        break;
    case 'username':
        $baseQuery .= " AND t.username LIKE '%$filterVal%'";
        break;
    case 'ref-no':
        $baseQuery .= " AND t.reference_no LIKE '%$filterVal%'";
        break;
    case 'status':
        if (!empty($filterVal)) {
            $baseQuery .= " AND t.status = '$filterVal'";
        }
        break;
    case 'date':
        if(!empty($startDate) && !empty($endDate)) {
            $baseQuery .= " AND DATE(t.created_at) BETWEEN '$startDate' AND '$endDate'";
        }
        break;
}

$baseQuery .= " ORDER BY t.created_at DESC";

$result = mysqli_query($conn, $baseQuery);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $row['created_at'] = date('Y-m-d H:i', strtotime($row['created_at']));
    $data[] = $row;
}

echo json_encode($data);
?> 