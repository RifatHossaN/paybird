<?php

session_start();
$rate = $_SESSION['rate'];

error_log(0);

if(!empty($_POST["btn-req-money"]) && $_POST["btn-req-money"] == "clicked"){
    include("../config/connection.php");
    $banking = ucwords(filter_var($_POST["banking"], FILTER_SANITIZE_STRING));
    $method = ucwords(filter_var($_POST["method"], FILTER_SANITIZE_STRING));
    $number = filter_var($_POST["number"], FILTER_SANITIZE_STRING);
    $amount = filter_var($_POST["amount"], FILTER_SANITIZE_STRING);
    $note = filter_var($_POST["note"], FILTER_SANITIZE_STRING);
    
    if (2000000000<$number || $number<1000000000) {
        $_SESSION['error'] = "Invalid number";
        header("Location: ../views/user/user-request-money.php");
        exit();
    }


    if(!empty($banking) && !empty($method) && !empty($number) && !empty($amount)){


        if(empty($note)){
            $note = "Not given";
        }

        $banking = mysqli_real_escape_string($conn,$banking);
        $mathod = mysqli_real_escape_string($conn,$mathod);
        $number = mysqli_real_escape_string($conn,$number);
        $amount = mysqli_real_escape_string($conn,$amount);
        $rate = $_SESSION['rate'];
        $amountMYR = round(($amount/$rate), 3);
        $note = mysqli_real_escape_string($conn,$note);
        $username = $_SESSION['username'];


        $sql = "INSERT INTO transections (username, number, banking, method, amount, note, status, rate, lastnumber, amountYMR, send_notification) VALUES ('$username','$number','$banking','$method','$amount','$note','Pending','$rate','N/A','$amountMYR','0')";

        mysqli_query($conn,$sql) or die("failed");

        header("Location: ../views/user/user-request-money-success.php");
        exit();
        
    }else{
        $_SESSION['error'] = "Fill In All The Information";
        header("Location: ../views/user/user-request-money.php");
        exit();
    }

}else{
    $_SESSION['error'] = "Something went wrong";
    header("Location: ../views/user/user-request-money.php");
    exit();
}