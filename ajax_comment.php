<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostCommentDaoMysql.php';

$Auth = new Auth($pdo, $Base);

$UserInfo = $Auth->CheckToken();

$Id = filter_input(INPUT_POST, 'id');
$Txt = filter_input(INPUT_POST, 'txt');


$Array = [];

if($Id && $Txt){
    $PostCommentDAo = new PostCommentDaoMysql($pdo);

    $NewComment = new PostComment();

    $NewComment->Id_Post = $Id;
    $NewComment->Id_User = $UserInfo->Id;
    $NewComment->Body = $Txt;
    $NewComment->Created_At = date("Y-m-d H:i:s");

    $PostCommentDAo->AddComment($NewComment);

    $Array = [
        'error' => '',
        'link' => $Base.'/perfil.php?id='.$UserInfo->Id,
        'avatar' => $Base.'/media/avatars/'.$UserInfo->Avatar,
        'name' => $UserInfo->Name,
        'body' => $Txt
    ];
}

header("Content-Type: application/json");
echo json_encode($Array);
exit;