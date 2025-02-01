<?php
session_start();

$username = $_SESSION['username'];
$superuser = $_SESSION['superuser'];


if (empty($username)){
    
    header("Location: ../../", true, 303);
    exit();

}

if($superuser =="false"){
    header("Location: ../../views/user/user-dashboard.php", true, 303);
    exit();
}else{
    if($superuser =!"true"){
        header("Location: ../../public/error.php", true, 303);
        exit();
    }
}


    


?>