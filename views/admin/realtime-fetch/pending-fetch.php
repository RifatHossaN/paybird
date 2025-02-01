<?php
session_start();
include('../../../config/connection.php');

$filterOption = isset($_GET['filterOption']) ? $_GET['filterOption'] : 'all';
$filterVal = isset($_GET['filterVal']) ? mysqli_real_escape_string($conn, $_GET['filterVal']) : '';
$startDate = isset($_GET['startDate']) ? mysqli_real_escape_string($conn, $_GET['startDate']) : '';
$endDate = isset($_GET['endDate']) ? mysqli_real_escape_string($conn, $_GET['endDate']) : '';
$page = isset($_GET['page']) ? $_GET['page'] : 'panel';

$baseQuery = "SELECT t.* FROM transections t 
              LEFT JOIN users u ON t.username = u.username 
              WHERE TIMESTAMPDIFF(SECOND, t.time, NOW()) > 30 
              AND t.status = 'Pending'";

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
    case 'date':
        if(!empty($startDate) && !empty($endDate)) {
            $baseQuery .= " AND DATE(t.time) BETWEEN '$startDate' AND '$endDate'";
        }
        break;
}

$baseQuery .= " ORDER BY t.time DESC";

if($page === 'panel') {
    $baseQuery .= " LIMIT 5";
}

$result = mysqli_query($conn, $baseQuery);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>
