<?php
session_start();


if(!empty($_POST["btn-config"]) && $_POST["btn-config"] == "clicked"){
    include("../config/connection.php");
    $rate = filter_var($_POST["rate"], FILTER_SANITIZE_STRING);

    if(!empty($rate)){

        $rate = mysqli_real_escape_string($conn,$rate);
        

        $sql = "UPDATE users SET rate = '$rate' ";

        $result = mysqli_query($conn,$sql) or die("Query Faild.");
        
        header("Location: ../views/admin/admin-payment-config.php?success=true");
        exit();
                
               
    }else{
        $_SESSION['error'] = "Fill In All The Information";
        header("Location: ../views/admin/admin-payment-config.php");
        exit();
    }

}else{
    $_SESSION['error'] = "Something went wrong";
    header("Location: ../views/admin/admin-payment-config.php");
    exit();
}