<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserDaoMysql.php';

$Auth = new Auth($pdo, $Base);

$UserInfo = $Auth->CheckToken();


$UserDao = new UserDaoMysql($pdo);

$Name =  filter_input(INPUT_POST, 'name');
$Email =  filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$Birthdate = filter_input(INPUT_POST, 'birthdate');
$City = filter_input(INPUT_POST, 'city');
$Work = filter_input(INPUT_POST, 'work');
$Password = filter_input(INPUT_POST, 'password');
$Password_Confirmation = filter_input(INPUT_POST, 'password_confirmation');

if ($Name && $Email) {
    $UserInfo->Name = $Name;
    $UserInfo->City = $City;
    $UserInfo->Work = $Work;

    //Email

    if ($UserInfo->Email != $Email) {
        if ($UserDao->FindByEmail($Email) === false) {
            $UserInfo->Email = $Email;
        } else {
            $_SESSION['flash'] = "Email Já Existente";
            header('Location: ' . $Base . '/configuracoes.php');
            exit;
        }
    }

    //Birthdate
    $Birthdate = explode('/', $Birthdate);

    if (count($Birthdate) != 3) {
        $_SESSION['flash'] = 'Data de Nascimento inválida';
        header('Location: '.$Base.'/configuracoes.php');
        exit;
    }

    $Birthdate = $Birthdate[2].'-'.$Birthdate[1].'-'.$Birthdate[0];

    if (strtotime($Birthdate) === false) {
        $_SESSION['flash'] = 'Data de Nascimento inválida';
        header('Location: ' . $Base . '/configuracoes.php');
        exit;
    }

    $UserInfo->BirthDate = $Birthdate;

    //Password

    if (!empty($Password)) {
        if ($Password === $Password_Confirmation) {
            $Hash = password_hash($Password, PASSWORD_DEFAULT);
            $UserInfo->Password = $Hash;
        } else {
            $_SESSION['flash'] = 'As senhas não coicidem';
            header('Location: ' . $Base . '/configuracoes.php');
            exit;
        }
    }



    $UserDao->Update($UserInfo);
}


header('Location: ' . $Base . '/configuracoes.php');
exit;
