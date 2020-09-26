<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';

$Auth = new Auth($pdo, $Base);

$UserInfo = $Auth->CheckToken();

$Body = filter_input(INPUT_POST, 'body');

if($Body){
    $PostDao = new PostDaoMysql($pdo);

    $NewPost = new Post();

    $NewPost->Id_User = $UserInfo->Id;
    $NewPost->Type = 'text';
    $NewPost->Created_At = date('Y-m-d H:i:s'); // Ano mes e dia, hora minuto e segundo
    $NewPost->Body = $Body;

    $PostDao->Insert($NewPost);
}

header("Location: ".$Base);
exit;