<?php
error_reporting(0);

// server connection setup 

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "money_exchange";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name, '3306');

