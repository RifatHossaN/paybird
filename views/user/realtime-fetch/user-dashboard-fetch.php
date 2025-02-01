<?php
session_start();
$username = $_SESSION['username'];

// fetch-data.php
include ('../../../config/connection.php'); // Adjust to your database connection file
$query = "SELECT * FROM transections WHERE username = '$username' ORDER BY time DESC LIMIT 10"; // Replace 'your_table' with your table name
$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
