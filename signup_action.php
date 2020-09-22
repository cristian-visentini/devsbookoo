<?php
require 'config.php';
require 'models/Auth.php';

$Name =  filter_input(INPUT_POST, 'name');
$Email =  filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$Password = filter_input(INPUT_POST, 'password');
$Birthdate = filter_input(INPUT_POST, 'birthdate'); // dd/mm/aaaa

if($Name && $Email && $Password && $Birthdate){
    $Auth = new Auth($pdo, $Base);

    $Birthdate = explode('/', $Birthdate);

    if(count($Birthdate) !=3){
        $_SESSION['flash']= 'Data de Nascimento inválida';
        header('Location: '.$Base.'/signup.php');
        exit;
    }
    $Birthdate = $Birthdate[2].'-'.$Birthdate[1].'-'.$Birthdate[0];
    if(strtotime($Birthdate) === false){
        $_SESSION['flash'] = 'Data de Nascimento inválida';
        header('Location: '.$Base.'/signup.php');
        exit;
    }

    if($Auth->EmailExists($Email) === false){
        $Auth->UserRegister($Name, $Email, $Password, $Birthdate);
        header('Location: '.$Base);
        exit;
    }else{
        $_SESSION['flash'] = 'Email já Cadastrado';
        header('Location: '.$Base.'/signup.php');
        exit;
    }
}

$_SESSION['flash'] = 'Email e/ou senha incorretos';
header('Location: '.$Base.'/login.php');
exit;