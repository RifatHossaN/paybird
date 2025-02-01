<?php
session_start();
include("../../config/connection.php");

$sql = "SELECT username, name FROM users WHERE superuser = 'false' ORDER BY regdate DESC";
$result = mysqli_query($conn, $sql);
$users = array();

while($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

echo json_encode($users);
?> 