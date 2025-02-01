<?php
session_start();


$username = $_SESSION['username'];
$superuser = $_SESSION['superuser'];

if (empty($username)){
    
    header("Location: ../../", true, 303);
    exit();

}
if($superuser =="true"){
    header("Location: ../../views/admin/admin-panel.php", true, 303);
    exit();
}else{
    if($superuser !="false"){
        header("Location: ../../public/error.php", true, 303);
        exit();
    }
}



?>