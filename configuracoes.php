<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserDaoMysql.php';

$Auth = new Auth($pdo, $Base);

$UserInfo = $Auth->CheckToken();
$ActiveMenu = "config";

$UserDao = new UserDaoMysql($pdo);




require 'partials/header.php';
require 'partials/menu.php';
?>


<section class="feed mt-10">
    <h1>Configurações</h1>

    <form method="POST" class="config-form" enctype="multipart/form-data" action="configuracoes_action.php">
        <label>
            Novo Avatar:<br>
            <input type="file" name="avatar"><br>
            <img class="mini" src="<?=$Base;?>/media/avatars/<?=$UserInfo->Avatar?>">
        </label>

        <label>
            Nova Capa:<br>
            <input type="file" name="cover"><br>
            <img class="mini" src="<?=$Base;?>/media/covers/<?=$UserInfo->Cover?>">
        </label>

        <hr>

        <label>
            Nome Completo:<br>
            <input type="text" name="name" value="<?=$UserInfo->Name;?>">
        </label>

        <label>
            Email:<br>
            <input type="email" name="email" value="<?=$UserInfo->Email;?>">
        </label>

        <label>
            Data de Nascimento:<br>
            <input type="text" id="birthdate" name="birthdate" value="<?=date('d/m/Y', strtotime($UserInfo->BirthDate));?>">
        </label>

        <label>
            Cidade:<br>
            <input type="text" name="city" value="<?=$UserInfo->City;?>">
        </label>

        <label>
            Trabalho:<br>
            <input type="text" name="work" value="<?=$UserInfo->Work;?>">
        </label>

        <hr>

        <label>
            Nova Senha:<br>
            <input type="password" name="password">
        </label>

        <label>
            Repita a Nova Senha:<br>
            <input type="password" name="password_confirmation">
        </label>

        <button class="button">Salvar</button>


    </form>
</section>

<script src="https://unpkg.com/imask"> </script>
    <script >
    IMask(
        document.getElementById('birthdate'),
        {mask:'00/00/0000'}
    );
    </script>

<?php
require 'partials/footer.php';
?>