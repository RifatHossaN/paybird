<?php

session_start();

error_log(0);

if(!empty($_POST["btn-modify-trans"]) && $_POST["btn-modify-trans"] == "clicked"){
    include("../config/connection.php");
    
    // Get and sanitize all form inputs
    $transID = mysqli_real_escape_string($conn, $_POST["transID"]);
    $banking = ucwords(filter_var($_POST["banking"], FILTER_SANITIZE_STRING));
    $method = ucwords(filter_var($_POST["method"], FILTER_SANITIZE_STRING));
    $number = filter_var($_POST["number"], FILTER_SANITIZE_STRING);
    $amount = filter_var($_POST["amount"], FILTER_SANITIZE_STRING);
    $amountYMR = filter_var($_POST["amountYMR"], FILTER_SANITIZE_STRING);
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);

    
    if (2000000000 < $number || $number < 1000000000) {
        $_SESSION['error'] = "Invalid number";
        header("Location: ../views/admin/admin-modify-transection.php?transID=" . $transID);
        exit();
    }

    // Check if required fields are not empty
    if(!empty($banking) && !empty($method) && !empty($number) && !empty($amount) && !empty($amountYMR)){
        
        // Escape all variables for SQL
        $banking = mysqli_real_escape_string($conn, $banking);
        $method = mysqli_real_escape_string($conn, $method);
        $number = mysqli_real_escape_string($conn, $number);
        $amount = mysqli_real_escape_string($conn, $amount);
        $amountYMR = mysqli_real_escape_string($conn, $amountYMR);
        $username = mysqli_real_escape_string($conn, $username);

        // Update transaction in database
        $sql = "UPDATE transections SET 
                banking = '$banking',
                method = '$method',
                number = '$number',
                amount = '$amount',
                amountYMR = '$amountYMR'
                WHERE transID = '$transID'";

        if(mysqli_query($conn, $sql)){
            $_SESSION['success'] = "Transaction updated successfully";
            header("Location: ../views/admin/admin-transection-details.php?transID=" . $transID);
            exit();
        } else {
            $_SESSION['error'] = "Database update failed: " . mysqli_error($conn);
            header("Location: ../views/admin/admin-modify-transection.php?transID=" . $transID);
            exit();
        }
        
    } else {
        $_SESSION['error'] = "Fill In All The Required Information";
        header("Location: ../views/admin/admin-modify-transection.php?transID=" . $transID);
        exit();
    }

} else {
    $_SESSION['error'] = "Invalid request";
    header("Location: ../views/admin/admin-modify-transection.php?transID=" . $transID);
    exit();
}