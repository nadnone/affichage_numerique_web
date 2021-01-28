<?php

$mysql_host = "localhost";
$mysql_port = "3306";
$mysql_database = "dev_immeuble";
$mysql_username = "root";
$mysql_password = "";

$mail_server_host = "";
$mail_server_user = "";
$mail_server_password = "";
$mail_server_SMTP_port = 587;


$conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_database;port=$mysql_port;charset=utf8", $mysql_username, $mysql_password);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

?>