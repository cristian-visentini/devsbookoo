<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserDaoMysql.php';

$Auth = new Auth($pdo, $Base);

$UserInfo = $Auth->CheckToken();
$ActiveMenu = "search";

$UserDao = new UserDaoMysql($pdo);

$SearchTerm = filter_input(INPUT_GET, 's');

if (empty($SearchTerm)) {
    header("Location: ./");
    exit;
}

$UserList = $UserDao->FindByName($SearchTerm);



require 'partials/header.php';
require 'partials/menu.php';


//Implementar no futuro busca por posts, e por imagens e videos.

?>



<section class="feed mt-10">
    <div class="row">
        <div class="column pr-5">
            <h2>Pesquisa por: <?= $SearchTerm; ?></h2>

            <div class="full-friend-list">

                <?php foreach ($UserList as $Item) : ?>
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
                    <a href=""><img src="https://alunos.b7web.com.br/media/courses/php-nivel-1.jpg" /></a>
                    <a href=""><img src="https://alunos.b7web.com.br/media/courses/laravel-nivel-1.jpg" /></a>
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