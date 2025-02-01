<?php
session_start();

if (isset($_SESSION["canceltrans"])){
    $canceltrans = $_SESSION["canceltrans"];
    include("../config/connection.php");
    $sql = "UPDATE transections SET status = 'Canceled' WHERE transID = '$canceltrans'";
    $result = mysqli_query($conn, $sql);
    header("Location: ../views/user/user-dashboard.php?operation=transcancel&success=true");
    exit();
}