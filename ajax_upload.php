<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';

$Auth = new Auth($pdo, $Base);

$UserInfo = $Auth->CheckToken();

$MaxWidth = 800;
$MaxHeight = 800;

$Array = ['error' => ''];

$PostDao = new PostDaoMysql($pdo);

if (isset($_FILES['photo']) && !empty($_FILES['photo']['tmp_name'])) {
    $photo = $_FILES['photo'];
    if (in_array($photo['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {

        list($WidthOrigin, $HeightOrigin) = getimagesize($photo['tmp_name']);
        $Ratio = $WidthOrigin / $HeightOrigin;

        $NewWidth = $MaxWidth;
        $NewHeight = $NewWidth / $Ratio;

        if ($NewHeight < $MaxHeight) {
            $NewHeight = $MaxHeight;
            $NewWidth = $NewHeight * $Ratio;
        }

        $FinalImage = imagecreatetruecolor($NewWidth, $NewHeight);

        switch ($photo['type']) {
            case 'image/jpeg':
            case 'image/jpg':
                $Image = imagecreatefromjpeg($photo['tmp_name']);
                break;
            case 'image/png':
                $Image = imagecreatefrompng($photo['tmp_name']);
        }

        imagecopyresampled(
            $FinalImage,
            $Image,
            0,
            0,
            0,
            0,
            $NewWidth,
            $NewHeight,
            $WidthOrigin,
            $HeightOrigin
        );

        $PhotoName = md5(time() . rand(0, 9999) . '.jpg');
        imagejpeg($FinalImage, 'media/uploads/' . $PhotoName);

        $NewPost = new Post();
        $NewPost->Id_User = $UserInfo->Id;
        $NewPost->Type = 'photo';
        $NewPost->Created_At = date('Y-m-d H:i:s');
        $NewPost->Body = $PhotoName;

        $PostDao->Insert($NewPost);

    } else {
        $Array = ['error' => 'Formato de imagem invalido!'];
    }

} else {
    $Array = ['error' => 'Nenhuma Imagem Enviada!'];
}



header("Content-Type: application/json");
echo json_encode($Array);
exit;
