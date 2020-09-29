<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';

$Auth = new Auth($pdo, $Base);

$UserInfo = $Auth->CheckToken();

$Id = filter_input(INPUT_GET, 'id');

if($Id){
    $PostDao = new PostDaoMysql($pdo);

    $PostDao->Delete($Id, $UserInfo->Id);
}

header("Location: ".$Base);
exit;