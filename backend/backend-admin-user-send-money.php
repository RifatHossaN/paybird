<?php

session_start();

error_log(0);

if(!empty($_POST["btn-send-money"]) && $_POST["btn-send-money"] == "clicked"){
    include("../config/connection.php");
    $banking = ucwords(filter_var($_POST["banking"], FILTER_SANITIZE_STRING));
    $method = ucwords(filter_var($_POST["method"], FILTER_SANITIZE_STRING));
    $number = filter_var($_POST["number"], FILTER_SANITIZE_STRING);
    $amount = filter_var($_POST["amount"], FILTER_SANITIZE_STRING);
    $amountYMR = filter_var($_POST["amountYMR"], FILTER_SANITIZE_STRING);
    $note = filter_var($_POST["note"], FILTER_SANITIZE_STRING);
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);

    if (2000000000 < $number || $number < 1000000000) {
        $_SESSION['error'] = "Invalid number";
        header("Location: ../views/admin/admin-user-send-money.php");
        exit();
    }

    if(!empty($banking) && !empty($method) && !empty($number) && !empty($amount) && !empty($amountYMR) && !empty($username)){

        if(empty($note)){
            $note = "Not given";
        }

        $banking = mysqli_real_escape_string($conn, $banking);
        $method = mysqli_real_escape_string($conn, $method);
        $number = mysqli_real_escape_string($conn, $number);
        $amount = mysqli_real_escape_string($conn, $amount);
        $amountYMR = mysqli_real_escape_string($conn, $amountYMR);
        $note = mysqli_real_escape_string($conn, $note);
        $username = mysqli_real_escape_string($conn, $username);

        $sql = "SELECT rate FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql) or die("Query Failed.");
        $userData = mysqli_fetch_assoc($result);

        $rate = $userData['rate'];

        $sql = "INSERT INTO transections (username, number, banking, method, amount, amountYMR, note, status, rate, send_notification) VALUES ('$username','$number','$banking','$method','$amount','$amountYMR','$note','Pending','$rate', '2')";

        mysqli_query($conn, $sql) or die("failed");

        header("Location: ../views/admin/admin-panel.php?operation=transadd&success=true");
        exit();
        
    } else {
        $_SESSION['error'] = "Fill In All The Information";
        header("Location: ../views/admin/admin-user-send-money.php");
        exit();
    }

} else {
    $_SESSION['error'] = "Something went wrong";
    header("Location: ../views/admin/admin-user-send-money.php");
    exit();
}