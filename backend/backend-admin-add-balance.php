header("Location: ../views/admin/admin-single-user-rate-config.php?user={$user}");<?php
error_log(0);
session_start();

if(!empty($_POST["btn-add-balance"]) && $_POST["btn-add-balance"] == "clicked"){
    include("../config/connection.php");
    $user = filter_var($_POST["user"], FILTER_SANITIZE_STRING);
    $balance = filter_var($_POST["balance"], FILTER_SANITIZE_STRING);

    if(!empty($user) && !empty($balance)){
        $user = mysqli_real_escape_string($conn, $user);
        $balance = mysqli_real_escape_string($conn, $balance);

        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Update user balance
            $sql = "UPDATE users SET balance = balance + $balance WHERE username = '$user'";
            mysqli_query($conn, $sql);

            // Commit transaction
            mysqli_commit($conn);
            
            header("Location: ../views/admin/admin-single-user-rate-config.php?user={$user}");
            exit();

        } catch (Exception $e) {
            mysqli_rollback($conn);
            $_SESSION['error'] = "Something went wrong";
            header("Location: ../views/admin/admin-single-user-rate-config.php?user=$user");
            exit();
        }

    } else {
        $_SESSION['error'] = "Fill In All The Information";
        header("Location: ../views/admin/admin-single-user-rate-config.php?user=$user");
        exit();
    }

} else {
    $_SESSION['error'] = "Something went wrong";
    header("Location: ../views/admin/admin-single-user-rate-config.php?user=$user");
    exit();
} 