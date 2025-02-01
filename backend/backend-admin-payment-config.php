<?php
session_start();


if(!empty($_POST["btn-config"]) && $_POST["btn-config"] == "clicked"){
    include("../config/connection.php");
    $paymentmethods = $_POST['payment-method'];
    

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
        

        $sql = "UPDATE users SET bkash = '$bkash', nagad = '$nagad'";

        $result = mysqli_query($conn,$sql) or die("Query Faild.");
        
        header("Location: ../views/admin/admin-payment-config.php");
        exit();
                
               

}else{
    $_SESSION['error'] = "Something went wrong";
    header("Location: ../views/admin/admin-payment-config.php");
    exit();
}