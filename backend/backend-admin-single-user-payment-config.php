<?php
session_start();


if(!empty($_POST["btn-config"]) && $_POST["btn-config"] == "clicked"){
    include("../config/connection.php");
    $paymentmethods = $_POST['payment-method'];
    $user = $_POST['user'];
    if(!empty($user)){

        $bkash = "false";
        $nagad = "false";
        
        if (!empty($paymentmethods)){
            if(in_array("bkash",$paymentmethods)){
                $bkash = "true";
            }
            if(in_array("nagad",$paymentmethods)){
                $nagad = "true";

            }
        }
        
        

        $sql = "UPDATE users SET bkash = '$bkash', nagad = '$nagad' WHERE username = '{$user}'";

        $result = mysqli_query($conn,$sql) or die("Query Faild.");
        
        header("Location: ../views/admin/admin-single-user-rate-config.php?user={$user}");
        exit();
                
               
    }else{
        $_SESSION['error'] = "Fill In All The Information";
        header("Location: ../views/admin/admin-single-user-rate-config.php?user={$user}");
        exit();
    }

}else{
    $_SESSION['error'] = "Something went wrong";
    header("Location: ../views/admin/admin-single-user-rate-config.php?user={$user}");
    exit();
}