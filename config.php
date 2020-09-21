<?php
session_start();
$Base = "http://localhost/devsbookoo";

$Db_Name = "devsbook";
$Db_Host = "localhost";
$Db_User = "root";
$Db_Passw = "";

$pdo = new PDO("mysql:dbname=".$Db_Name.";host=".$Db_Host, $Db_User, $Db_Passw);