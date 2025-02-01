<?php
// Access POST data
$token = $_POST['tokenKey'];
$username = $_POST['username'];
$superuser = $_POST['superuser'];

echo $superuser."\n".$username."\n".$token;



if(!empty($token) && !empty($username) && !empty($superuser)){
    echo "blabla";

    include("../config/connection.php");

    $token = mysqli_real_escape_string($conn,$token);
    $username = mysqli_real_escape_string($conn,$username);
    $superuser = mysqli_real_escape_string($conn,$superuser);

    $sql = "SELECT token FROM notification_tokens WHERE token = '{$token}'";

    $result = mysqli_query($conn,$sql) or die("Query Faild.");

    if(mysqli_num_rows($result) != 0){
        echo "token already exits";
    }else{
        $sql = "INSERT INTO notification_tokens (token, username, superuser) VALUES ('$token','$username','$superuser')";

        mysqli_query($conn,$sql) or die("failed");

        echo "done";

    }
}else{
    echo "all info arent avilable";
}

?>
