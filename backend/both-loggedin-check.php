<?php
session_start();

if (isset($_SESSION["username"]) && $_SESSION["superuser"]){

$username = $_SESSION['username'];
$superuser = $_SESSION['superuser'];
}


if (!empty($username)){
    if($superuser =="true"){
        header("Location: views/admin/admin-panel.php", true, 303);
        exit();
    }elseif($superuser =="false"){
        header("Location: views/user/user-dashboard.php", true, 303);
        exit();
    }
}




?>