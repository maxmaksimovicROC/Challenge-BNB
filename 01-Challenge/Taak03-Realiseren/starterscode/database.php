<?php
$db_host     = 'localhost'; // meestal localhost
$db_name        = 'cottagerentals'; // de naam zoals ie in de database staat
$db_user   = 'root'; // username meestal root
$db_passwd  = ''; // user password meestal empty als je het als local draait 

$conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_passwd);
?>