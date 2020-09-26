<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserRelationDaoMysql.php';
require_once 'dao/UserDaoMysql.php';

$Auth = new Auth($pdo, $Base);

$UserInfo = $Auth->CheckToken();

$Id = filter_input(INPUT_GET, 'id');

if ($Id) {

    $UserRelation = new UserRelationDaoMysql($pdo);
    $UserDao = new UserDaoMysql($pdo);

    $Relation = new UserRelation();
    $Relation->User_From = $UserInfo->Id;
    $Relation->User_To = $Id;

    if ($UserDao->FindById($Id)) {

        if ($UserRelation->IsFollowing($UserInfo->Id, $Id)) {
            //unfollow

            $UserRelation->Delete($Relation);
        } else {
            //follow

            $UserRelation->Insert($Relation);
        }

        header("Location: perfil.php?id=" . $Id);
        exit;
    }
}

header("Location: " . $Base);
exit;