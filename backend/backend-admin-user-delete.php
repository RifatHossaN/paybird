<?php
session_start();

if (isset($_SESSION["deleteusername"])){
    $deleteusername = $_SESSION["deleteusername"];
    include("../config/connection.php");
    $usernameToDelete = mysqli_real_escape_string($conn, $deleteusername);
    $sql = "DELETE FROM users WHERE username = '$deleteusername'";
    $result = mysqli_query($conn, $sql);
    header("Location: ../views/admin/admin-user-list.php?target=users&operation=delelte&success=true");
    exit();
}