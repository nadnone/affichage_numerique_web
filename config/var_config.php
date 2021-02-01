<?php

$info_mainPage = "<i>VERSION DE TEST</i>"; // informations what will be displayed on the index.php, false if you don't want it


$mysql_host = "localhost";
$mysql_port = "3306";
$mysql_database = "dev_immeuble";
$mysql_username = "root";
$mysql_password = "";

$mail_server_host = "";
$mail_server_user = "";
$mail_server_password = "";
$mail_server_SMTP_port = 587;

$log_file_path = "../tmp/logs"; // your logs folder
$time_lock_account = 30*60; //time the account is locked in seconds
$max_connect_try = 4; //number of try before the account is locked

$test_account = true;
$test_account_name = "testeur";

$data_zip_path_folder = "../tmp/data"; // your data folder (to temporally stock user data)

error_reporting(0);

$conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_database;port=$mysql_port;charset=utf8", $mysql_username, $mysql_password);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

?> 