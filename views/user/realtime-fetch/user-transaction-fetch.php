<?php
session_start();
include("../../config/connection.php");

$username = $_SESSION['username'];
$where = "username = '$username'";

if(!empty($_GET["btn-filter"]) && $_GET["btn-filter"]=="clicked") {
    $filterOption = mysqli_real_escape_string($conn, $_GET['filterOption']);
    $filterVal = mysqli_real_escape_string($conn, $_GET['filter-val']);

    switch($filterOption) {
        case 'transID':
            $where .= " AND transID LIKE '%$filterVal%'";
            break;
        case 'number':
            $where .= " AND number LIKE '%$filterVal%'";
            break;
        case 'banking':
            $where .= " AND banking LIKE '%$filterVal%'";
            break;
        case 'date':
            if(!empty($_GET['start-date']) && !empty($_GET['end-date'])) {
                $startDate = mysqli_real_escape_string($conn, $_GET['start-date']);
                $endDate = mysqli_real_escape_string($conn, $_GET['end-date']);
                $where .= " AND DATE(created_at) BETWEEN '$startDate' AND '$endDate'";
            }
            break;
    }
}

$sql = "SELECT * FROM transactions WHERE $where ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$transactions = array();
while($row = mysqli_fetch_assoc($result)) {
    $row['time'] = date('h:i A', strtotime($row['created_at']));
    $row['amountYMR'] = number_format($row['amount'] / $_SESSION['rate'], 2);
    $transactions[] = $row;
}

header('Content-Type: application/json');
echo json_encode($transactions);
?> 