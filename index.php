<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';

$Auth = new Auth($pdo, $Base);

$UserInfo = $Auth->CheckToken();
$ActiveMenu = "home";

//Pegar informações de paginação
$Page = intval(filter_input(INPUT_GET, 'p'));

if ($Page < 1) {
    $Page = 1;
}

$PostDao = new PostDaoMysql($pdo);
$Info = $PostDao->GetHomeFeed($UserInfo->Id, $Page);
$Feed = $Info['feed'];
$Pages = $Info['pages'];
$CurrentPage = $Info['currentpage'];


require 'partials/header.php';
require 'partials/menu.php';
?>


<section class="feed mt-10">
    <div class="row">
        <div class="column pr-5">

            <?php require 'partials/feed-editor.php'; ?>

            <?php foreach ($Feed as $Item) : ?>
                <?php require 'partials/feed-item.php'; ?>
            <?php endforeach; ?>

            <div class="feed-pagination">
                <?php for ($q = 0; $q < $Pages; $q++) : ?>
                    <a class="<?= ($q + 1 == $CurrentPage) ? 'active' : ''; ?>" href="<?= $Base; ?>/?p=<?= $q + 1; ?>"><?= $q + 1; ?></a>
                <?php endfor; ?>
            </div>

        </div>
        <div class="column side pl-5">
            <div class="box banners">
                <div class="box-header">
                    <div class="box-header-text">Patrocinios</div>
                    <div class="box-header-buttons">

                    </div>
                </div>
                <div class="box-body">
                    <a href="http://github.com/cristian-visentini" target="_blank"><img src="https://cdn.pixabay.com/photo/2017/08/05/11/24/logo-2582757_960_720.png" /></a>
                    <a href="https://www.linkedin.com/in/cristian-visentini/" target="_blank"><img src="https://cdn.pixabay.com/photo/2016/11/18/11/16/social-1834011_960_720.png" /></a>
                </div>
            </div>
            <div class="box">
                <div class="box-body m-10">
                    Criado com ❤️ por Cristian
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require 'partials/footer.php';
?>