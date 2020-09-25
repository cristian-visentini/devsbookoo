<?php
require 'config.php';
require 'models/Auth.php';
require 'dao/PostDaoMysql.php';


$Auth = new Auth($pdo, $Base);

$UserInfo = $Auth->CheckToken();
$ActiveMenu = "profile";

$User = [];
$Feed = [];

$Id = filter_input(INPUT_GET, 'id');

if (!$Id) {
    $Id = $UserInfo->Id;
}

if ($Id != $UserInfo->Id) {
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

//Pegar o FEED do usuario

$Feed = $PostDao->GetUserFeed($Id);

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

        <div class="column side pr-5">

            <div class="box">
                <div class="box-body">

                    <div class="user-info-mini">
                        <img src="<?= $Base; ?>/assets/images/calendar.png" />
                        <?= date('d/m/Y', strtotime($User->BirthDate)) ?> (<?= $User->AgeYears ?> Anos)
                    </div>

                    <?php if (!empty($User->City)) : ?>
                        <div class="user-info-mini">
                            <img src="<?= $Base; ?>/assets/images/pin.png" />
                            <?= $User->City; ?>
                        </div>
                    <?php endif ?>

                    <?php if (!empty($User->Work)) : ?>
                        <div class="user-info-mini">
                            <img src="<?= $Base; ?>/assets/images/work.png" />
                            <?= $User->Work; ?>
                        </div>
                    <?php endif ?>

                </div>
            </div>

            <div class="box">
                <div class="box-header m-10">
                    <div class="box-header-text">
                        Seguindo
                        <span><?= count($User->Following); ?></span>
                    </div>
                    <div class="box-header-buttons">
                        <a href="<?= $Base; ?>/amigos.php?id=<?= $User->Id; ?>">ver todos</a>
                    </div>
                </div>
                <div class="box-body friend-list">

                    <?php if (count($User->Following) > 0) : ?>
                        <?php foreach ($User->Following as $Item) : ?>
                            <div class="friend-icon">
                                <a href="<?= $Base; ?>/perfil.php?id=<?= $Item->Id ?>">
                                    <div class="friend-icon-avatar">
                                        <img src="<?= $Base; ?>/media/avatars/<?= $Item->Avatar ?>" />
                                    </div>
                                    <div class="friend-icon-name">
                                        <?= $Item->Name ?>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>


                </div>
            </div>

        </div>
        <div class="column pl-5">

            <div class="box">
                <div class="box-header m-10">
                    <div class="box-header-text">
                        Fotos
                        <span>(<?= count($User->Photos); ?>)</span>
                    </div>
                    <div class="box-header-buttons">
                        <a href="<?= $Base; ?>/fotos.php?id=<?= $User->Id; ?>">ver todos</a>
                    </div>
                </div>
                <div class="box-body row m-20">


                    <?php if (count($User->Photos) > 0) : ?>
                        <?php foreach ($User->Photos as $Item) : ?>

                            <div class="user-photo-item">
                                <a href="#modal-1" rel="modal:open">
                                    <img src="media/uploads/1.jpg" />
                                </a>
                                <div id="modal-1" style="display:none">
                                    <img src="<?= $Base; ?>/media/uploads/1.jpg" />
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php endif; ?>



                </div>

            </div>

            <?php if ($Id == $UserInfo->Id) : ?>
                <?php require "partials/feed-editor.php"; ?>
            <?php endif; ?>



            <?php if (count($Feed) > 0) : ?>
                <?php foreach ($Feed as $Item) : ?>
                    <?php require "partials/feed-item.php"; ?>
                <?php endforeach; ?>
            <?php else : ?>
                Não há postagens desse Usuário ainda!
            <?php endif; ?>


</section>

<?php
require 'partials/footer.php';
?>