<?php

include("../config/connection.php");

$sql = "SELECT * FROM `transections` WHERE `send_notification`='0' AND TIMESTAMPDIFF(SECOND, time, NOW()) > 30 and status != 'Canceled'";
$unsent_notificaion = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($unsent_notificaion)) {

    $username = $row['username'];
    $transID = $row['transID'];
    $amount = $row['amount'];
    $banking = $row['banking'];
    $number = $row['number'];


    //adding notification to db
    $insert_notitifation = "INSERT INTO `admin_transection_notifications`( `username`, `transID`, `amount`, `banking`, `number`, `is_read`) VALUES ('$username','$$transID', '$amount', '$banking','$number','0')";
    mysqli_query($conn, $insert_notitifation);

    //send notification to admin
    $sql = "SELECT token FROM notification_tokens WHERE username = 'admin'";
    $result = mysqli_query($conn, $sql) or die("Query Failed.");
    if (mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_assoc($result)){
            $parameters = array(
                "pvKey" => "pvKey.json",
                "project_name" => "testapp-db6f5",
                "token" => $row['token'],
                "notification_title" => "PayBird: Money Request.",
                "notification_body" => "User: ".$username."Amount : ".$amount."\nNumber:".$number." TK. Banking : ".$banking."",
                "notification_image" => "https://img.icons8.com/?size=100&id=DDLGU9cwERZ7&format=png&color=000000", //success.png
                "notification_link" => "https://paybird.online/views/admin/admin-transection-details.php?transID=".$transID
            );
            
            include ("../backend/notification_sender/send.php");

        }
    }

    echo $username." ".$transID." ".$amount." ".$banking." ".$number."\n";

    //updating sent status
    $update_trans_table = "UPDATE transections SET send_notification = '1' WHERE transID = '$transID'";
    mysqli_query($conn, $update_trans_table);

    

}
