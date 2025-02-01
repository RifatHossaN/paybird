<?php

session_start();

error_log(0);


if(!empty($_POST["btn-user-update"]) && $_POST["btn-user-update"] == "clicked"){
    include("../config/connection.php");
    $name = ucwords(filter_var($_POST["name"], FILTER_SANITIZE_STRING));
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);

    if(!empty($name) && !empty($username) && !empty($password)){
        $name = mysqli_real_escape_string($conn,$name);
        $username = mysqli_real_escape_string($conn,$username);
        $password = mysqli_real_escape_string($conn,$password);

        $sql = "SELECT username FROM users WHERE username = '{$username}'";

        $result = mysqli_query($conn,$sql) or die("Query Faild.");

        if(mysqli_num_rows($result) != 0){
            $_SESSION['error'] = "username is already in use";
            header("Location: ../views/admin/admin-user-update.php");
            exit();
        }else{
            $sql = "UPDATE users SET name = '$name', password = '$password', username = '$username' WHERE username = '$_SESSION[oldusername]'";

            mysqli_query($conn,$sql) or die("failed");

            header("Location: ../views/admin/admin-user-list.php?target=users&operation=update&success=true");
            exit();
        }
    }else{
        $_SESSION['error'] = "Fill In All The Information";
        header("Location: ../views/admin/admin-user-update.php");
        exit();
    }

}else{
    $_SESSION['error'] = "Something went wrong";
    header("Location: ../views/admin/admin-user-update.php");
    exit();
}