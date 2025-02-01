<?php
session_start();


if(!empty($_POST["btn-config"]) && $_POST["btn-config"] == "clicked"){
    include("../config/connection.php");
    $rate = filter_var($_POST["rate"], FILTER_SANITIZE_STRING);
    $user = filter_var($_POST["user"], FILTER_SANITIZE_STRING);

    if(!empty($rate) && !empty($user)){
        
       

        $rate = mysqli_real_escape_string($conn,$rate);
        $user = mysqli_real_escape_string($conn,$user);
        

        $sql = "UPDATE users SET rate = '$rate' WHERE username = '$user' ";

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