<?php
error_log(0);
session_start();


if(!empty($_POST["btn-login"]) && $_POST["btn-login"] == "clicked"){
    include("../config/connection.php");
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);

    if(!empty($username) && !empty($password)){
        $username = mysqli_real_escape_string($conn,$username);
        $password = mysqli_real_escape_string($conn,$password);

        $sql = "SELECT userID, name, username, superuser FROM users WHERE username = '{$username}' AND password = '{$password}'";

        $result = mysqli_query($conn,$sql) or die("Query Faild.");

        if(mysqli_num_rows($result) != 0){
            while($row = mysqli_fetch_assoc($result)){
                
                
                
                $_SESSION['userID'] = $row['userID'];
                $_SESSION['name'] = $row['name'];
                $_SESSION["username"] = $row['username'];
                $_SESSION['superuser'] = $row['superuser'];
                if($row['superuser'] == 1){
                    header("Location: ../views/admin/admin-panel.php");
                    exit();
                }else{
                    header("Location: ../views/user/user-dashboard.php");
                    exit();
                }
                
            }
            

        }else{
            $_SESSION['error'] = "Username and Password didn't match!";
            header("Location: ../public/login.php");
            exit();
        }
    }else{
        $_SESSION['error'] = "Fill In All The Information";
        header("Location: ../public/login.php");
        exit();
    }

}else{
    $_SESSION['error'] = "Something went wrong";
    header("Location: ../public/login.php");
    exit();
}