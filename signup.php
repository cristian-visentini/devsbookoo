<?php
require 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Devsbook Cadastre-se</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1"/>
    <link rel="stylesheet" href="<?=$Base;?>/assets/css/login.css" />
</head>
<body>
    <header>
        <div class="container">
            <a href="<?=$Base;?>"><img src="<?=$Base;?>/assets/images/devsbook_logo.png" /></a>
        </div>
    </header>
    <section class="container main">
        <form method="POST" action="<?=$Base;?>/signup_action.php">
            <?php if(!empty($_SESSION['flash'])):?>
                <?=$_SESSION['flash'];?>
                <?php $_SESSION['flash']= '';?>
            <?php endif;?>
            <input placeholder="Nome Completo" class="input" type="text" name="name" />

            <input placeholder="Digite seu e-mail" class="input" type="email" name="email" />

            <input placeholder="Digite sua senha" class="input" type="password" name="password" />

            <input id="birthdate" placeholder="Data de nascimento" class="input" type="text" name="birthdate" />

            <input class="button" type="submit" value="Cadastrar" />

            <a href="<?=$Base;?>/login.php">Já tem uma conta? Faça login</a>
        </form>
    </section>
    <script src="https://unpkg.com/imask"> </script>
    <script >
    IMask(
        document.getElementById('birthdate'),
        {mask:'00/00/0000'}
    );
    </script>
</body>
</html>