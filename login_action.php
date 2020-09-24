<?php
require 'config.php';
require 'models/Auth.php';

$Email =  filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$Password = filter_input(INPUT_POST, 'password');

if($Email && $Password){
    $Auth = new Auth($pdo, $Base);

    if($Auth->ValidateLogin($Email, $Password)){
        header('Location: '.$Base);
        exit;
    }
}

$_SESSION['flash'] = 'Email e/ou senha incorretos';
header('Location: '.$Base.'/login.php');
exit; //ok