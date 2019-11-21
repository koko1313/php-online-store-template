<?php
$server = "localhost";
$user = "root";
$password = "";
$db_name = "templatedb";

$db = mysqli_connect($server, $user, $password, $db_name);

mysqli_set_charset($db,"utf8");
?>