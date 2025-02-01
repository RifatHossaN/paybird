<?php
session_start();
include('../../../config/connection.php');

$filterOption = isset($_GET['filterOption']) ? $_GET['filterOption'] : 'all';
$filterVal = isset($_GET['filterVal']) ? mysqli_real_escape_string($conn, $_GET['filterVal']) : '';
$startDate = isset($_GET['startDate']) ? mysqli_real_escape_string($conn, $_GET['startDate']) : '';
$endDate = isset($_GET['endDate']) ? mysqli_real_escape_string($conn, $_GET['endDate']) : '';
$username = isset($_GET['username']) ? mysqli_real_escape_string($conn, $_GET['username']) : '';

$baseQuery = "SELECT t.* FROM transections t 
              LEFT JOIN users u ON t.username = u.username 
              WHERE TIMESTAMPDIFF(SECOND, t.time, NOW()) > 30";

// Add username filter if provided (for user transaction history)
if (!empty($username)) {
    $baseQuery .= " AND t.username = '$username'";
}

switch($filterOption) {
    case 'transID':
        $baseQuery .= " AND t.transID LIKE '%$filterVal%'";
        break;
    case 'username':
        $baseQuery .= " AND t.username LIKE '%$filterVal%'";
        break;
    case 'name':
        $baseQuery .= " AND u.name LIKE '%$filterVal%'";
        break;
    case 'number':
        $baseQuery .= " AND t.number LIKE '%$filterVal%'";
        break;
    case 'banking':
        $baseQuery .= " AND t.banking LIKE '%$filterVal%'";
        break;
    case 'status':
        if (!empty($filterVal)) {
            $baseQuery .= " AND t.status = '$filterVal'";
        }
        break;
    case 'date':
        if(!empty($startDate) && !empty($endDate)) {
            $baseQuery .= " AND DATE(t.time) BETWEEN '$startDate' AND '$endDate'";
        }
        break;
}

$baseQuery .= " ORDER BY t.time DESC";

$result = mysqli_query($conn, $baseQuery);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?> 