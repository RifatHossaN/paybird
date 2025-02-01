<?php
/* 
https://fcm.googleapis.com/v1/projects/<YOUR-PROJECT-ID>/messages:send
Content-Type: application/json
Authorization: Bearer <YOUR-ACCESS-TOKEN>

{
  "message": {
    "token": "eEz-Q2sG8nQ:APA91bHJQRT0JJ...",
    "notification": {
      "title": "Background Message Title",
      "body": "Background message body"
    },
    "webpush": {
      "fcm_options": {
        "link": "https://dummypage.com"
      }
    }
  }
}
 */

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

require 'vendor/autoload.php';
include "../backend-admin-user-request-money-accept.php";

// $parameters = json_decode(file_get_contents(__DIR__."/notification_sender.json"), true);


$credential = new ServiceAccountCredentials(
    "https://www.googleapis.com/auth/firebase.messaging",
    json_decode(file_get_contents(__DIR__."/".$parameters["pvKey"]), true)
);

$token = $credential->fetchAuthToken(HttpHandlerFactory::build());

$ch = curl_init("https://fcm.googleapis.com/v1/projects/".$parameters["project_name"]."/messages:send");

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer '.$token['access_token']
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, '{
    "message": {
      "token": "'.$parameters["token"].'",
      "notification": {
        "title": "'.$parameters["notification_title"].'",
        "body": "'.$parameters["notification_body"].'",
        "image": "'.$parameters["notification_image"].'"
      },
      "webpush": {
        "fcm_options": {
          "link": "'.$parameters["notification_link"].'"
        }
      }
    }
  }');

  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "post");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  $response = curl_exec($ch);
  // curl_exec($ch);

  curl_close($ch);

  // echo $response;
