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
        header('Location: ' . $Base . '/configuracoes.php');
        exit;
    }

    $Birthdate = $Birthdate[2] . '-' . $Birthdate[1] . '-' . $Birthdate[0];

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

    //Avatar

    if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])) {
        $NewAvatar = $_FILES['avatar'];
        if (in_array($NewAvatar['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {
            $AvatarWidth = 200;
            $CoverHeight = 200;


            list($WidthOrig, $HeighOrig) = getimagesize($NewAvatar['tmp_name']);
            $Ratio = $WidthOrig / $HeighOrig;

            $NewWidth = $AvatarWidth;
            $NewHeight = $NewWidth / $Ratio;

            if ($NewHeight < $CoverHeight) {
                $NewHeight = $CoverHeight;
                $NewWidth = $CoverHeight * $Ratio;
            }



            $X = $AvatarWidth - $NewWidth;
            $Y = $CoverHeight - $NewHeight;
            $X = $X < 0 ? $X / 2 : $X;
            $Y = $Y < 0 ? $Y / 2 : $Y;

            $FinalImage = imagecreatetruecolor($AvatarWidth, $CoverHeight);

            switch ($NewAvatar['type']) {
                case 'image/jpeg':
                case 'image/jpg':
                    $Image = imagecreatefromjpeg($NewAvatar['tmp_name']);
                    break;
                case 'image/png':
                    $Image = imagecreatefrompng($NewAvatar['tmp_name']);
                    break;
            }

            imagecopyresampled(
                $FinalImage, $Image,
                $X, $Y, 0, 0,
                $NewWidth, $NewHeight,
                $WidthOrig, $HeighOrig
            );

            $AvatarName = md5(time().rand(0, 999).'.jpg');

            imagejpeg($FinalImage, './media/avatars/'.$AvatarName, 100);

            $UserInfo->Avatar = $AvatarName;

        }
    }

     //Cover
    
     if (isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'])) {
        $NewCover= $_FILES['cover'];
        if (in_array($NewCover['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {
            $CoverWidth = 850;
            $CoverHeight = 313;


            list($WidthOrig, $HeighOrig) = getimagesize($NewCover['tmp_name']);
            $Ratio = $WidthOrig / $HeighOrig;

            $NewWidth = $CoverWidth;
            $NewHeight = $NewWidth / $Ratio;

            if ($NewHeight < $CoverHeight) {
                $NewHeight = $CoverHeight;
                $NewWidth = $CoverHeight * $Ratio;
            }



            $X = $CoverWidth - $NewWidth;
            $Y = $CoverHeight - $NewHeight;
            $X = $X < 0 ? $X / 2 : $X;
            $Y = $Y < 0 ? $Y / 2 : $Y;

            $FinalImage = imagecreatetruecolor($CoverWidth, $CoverHeight);

            switch ($NewCover['type']) {
                case 'image/jpeg':
                case 'image/jpg':
                    $Image = imagecreatefromjpeg($NewCover['tmp_name']);
                    break;
                case 'image/png':
                    $Image = imagecreatefrompng($NewCover['tmp_name']);
                    break;
            }

            imagecopyresampled(
                $FinalImage, $Image,
                $X, $Y, 0, 0,
                $NewWidth, $NewHeight,
                $WidthOrig, $HeighOrig
            );

            $CoverName = md5(time().rand(0, 999).'.jpg');

            imagejpeg($FinalImage, './media/covers/'.$CoverName, 100);

            $UserInfo->Cover = $CoverName;

        }
    }


    $UserDao->Update($UserInfo);
}


header('Location: ' . $Base . '/configuracoes.php');
exit;
