<?php
$notification_array = [
    "pvKey" => "pvKey.json",
    "project_name" => "te6f5",
    "token" => "##",
    "notification_title" => "hello",
    "notification_body" => "hehehe",
    "notification_image" => "https://cdn.shopify.com/s/files/1/1061/1924/files/Sunglasses_Emoji.png?2976903553660223024",
    "notification_link" => "https://google.com"
    ];
$notification_json = json_encode($notification_array, JSON_PRETTY_PRINT);
$notification_sender_json = fopen("notification_sender.json","w");

fwrite($notification_sender_json, $notification_json);
fclose($notification_sender_json);

include ("send.php");