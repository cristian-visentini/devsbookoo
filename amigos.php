<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';


$Auth = new Auth($pdo, $Base);

$UserInfo = $Auth->CheckToken();
$ActiveMenu = "friends";

$User = [];
$Feed = [];

$Id = filter_input(INPUT_GET, 'id');

if(!$Id) {
    $Id = $UserInfo->Id;
}

if($Id != $UserInfo->Id) {
    $ActiveMenu = "";
}

$PostDao = new PostDaoMysql($pdo);
$UserDao = new UserDaoMysql($pdo);

//Pegar informações do Usuario, antes verificar se o ID existe

$User = $UserDao->FindById($Id, true);

if (!$User) {
    header('Location: ' . $Base);
    exit;
}

$DateFrom = new DateTime($User->BirthDate);
$DateNow = new DateTime('today');
$User->AgeYears = $DateFrom->diff($DateNow)->y;

//Verificar se segue o usuario

/*
$PostDao = new PostDaoMysql($pdo);
$Feed = $PostDao->GetHomeFeed($UserInfo->Id);
*/

require 'partials/header.php';
require 'partials/menu.php';
?>

<section class="feed">

    <div class="row">
        <div class="box flex-1 border-top-flat">
            <div class="box-body">
                <div class="profile-cover" style="background-image: url('<?= $Base; ?>/media/covers/<?= $User->Cover; ?>');"></div>
                <div class="profile-info m-20 row">
                    <div class="profile-info-avatar">
                        <img src="<?= $Base; ?>/media/avatars/<?= $User->Avatar; ?>" />
                    </div>
                    <div class="profile-info-name">
                        <div class="profile-info-name-text"><?= $User->Name; ?></div>
                        <?php if (!empty($User->City)) : ?>
                            <div class="profile-info-location"><?= $User->City; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="profile-info-data row">
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?= count($User->Followers); ?></div>
                            <div class="profile-info-item-s">Seguidores</div>
                        </div>
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?= count($User->Following); ?></div>
                            <div class="profile-info-item-s">Seguindo</div>
                        </div>
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?= count($User->Photos); ?></div>
                            <div class="profile-info-item-s">Fotos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="column">

            <div class="box">
                <div class="box-body">

                    <div class="tabs">
                        <div class="tab-item" data-for="followers">
                            Seguidores
                        </div>
                        <div class="tab-item active" data-for="following">
                            Seguindo
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-body" data-item="followers">

                            <div class="full-friend-list">

                                <?php foreach ($User->Followers as $Item) : ?>

                                    <div class="friend-icon">
                                        <a href="<?=$Base;?>/perfil.php?=id<?=$Item->Id;?>">
                                            <div class="friend-icon-avatar">
                                                <img src="<?=$Base;?>/media/avatars/<?=$Item->Avatar;?>" />
                                            </div>
                                            <div class="friend-icon-name">
                                            <?=$Item->Name;?>
                                            </div>
                                        </a>
                                    </div>

                                <?php endforeach; ?>

                            </div>

                        </div>
                        <div class="tab-body" data-item="following">
                            <div class="full-friend-list">

                                <?php foreach ($User->Following as $Item) : ?>

                                    <div class="friend-icon">
                                        <a href="<?=$Base;?>/perfil.php?=id<?=$Item->Id;?>">
                                            <div class="friend-icon-avatar">
                                                <img src="<?=$Base;?>/media/avatars/<?=$Item->Avatar;?>" />
                                            </div>
                                            <div class="friend-icon-name">
                                            <?=$Item->Name;?>
                                            </div>
                                        </a>
                                    </div>

                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

</section>

<?php
require 'partials/footer.php';
?>