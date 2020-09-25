<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';


$Auth = new Auth($pdo, $Base);

$UserInfo = $Auth->CheckToken();
$ActiveMenu = 'photos';

$User = [];
$Feed = [];

$Id = filter_input(INPUT_GET, 'id');

if (!$Id) {
    $Id = $UserInfo->Id;
}

if ($Id != $UserInfo->Id) {
    echo "<script>alert('Segundo if');</script>";
    exit;
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

                    <div class="full-user-photos">

                        <?php foreach ($User->Photos as $Key => $Item) : ?>

                            <div class="user-photo-item">
                                <a href="#modal-<?=$Key;?>" rel="modal:open">
                                    <img src="<?= $Base; ?>/media/uploads/<?= $Item->Body; ?>">
                                </a>
                                <div id="modal-<?=$Key;?>" style="display:none">
                                    <img src="<?= $Base; ?>/media/uploads/<?= $Item->Body; ?>">
                                </div>
                            </div>

                        <?php endforeach; ?>

                        <?php if (count($User->Photos) === 0) : ?>

                            Não há fotos desse Usuário!

                        <?php endif;?>


                    </div>


                </div>
            </div>

        </div>

    </div>

</section>

<?php
require 'partials/footer.php';
?>