<?php
session_start();
include("../../config/connection.php");

$username = $_SESSION['username'];
$where = "username = '$username'";

if(!empty($_GET["btn-filter"]) && $_GET["btn-filter"]=="clicked") {
    $filterOption = mysqli_real_escape_string($conn, $_GET['filterOption']);
    $filterVal = mysqli_real_escape_string($conn, $_GET['filter-val']);

    switch($filterOption) {
        case 'depositID':
            $where .= " AND depositID LIKE '%$filterVal%'";
            break;
        case 'payment_method':
            $where .= " AND payment_method LIKE '%$filterVal%'";
            break;
        case 'status':
            $where .= " AND status LIKE '%$filterVal%'";
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

$sql = "SELECT * FROM deposits WHERE $where ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$deposits = array();
while($row = mysqli_fetch_assoc($result)) {
    $deposits[] = $row;
}

header('Content-Type: application/json');
echo json_encode($deposits);
?> 