<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostLikeDaoMysql.php';

$Auth = new Auth($pdo, $Base);

$UserInfo = $Auth->CheckToken();

$Id = filter_input(INPUT_GET, 'id');

if(!empty($Id)){
    $PostLikeDao = new PostLikeDaoMysql($pdo);

    $PostLikeDao->LikeToggle($Id, $UserInfo->Id);
}