<?php
$FirstName = current(explode(' ', $UserInfo->Name));
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title></title>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1"/>
    <link rel="stylesheet" href="<?=$Base;?>/assets/css/style.css" />
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="<?=$Base;?>"><img src="<?=$Base;?>/assets/images/devsbook_logo.png" /></a>
            </div>
            <div class="head-side">
                <div class="head-side-left">
                    <div class="search-area">
                        <form method="GET" action="<?=$Base;?>/search.php">
                            <input type="search" placeholder="Pesquisar" name="s" />
                        </form>
                    </div>
                </div>
                <div class="head-side-right">
                    <a href="<?=$Base;?>/perfil.php" class="user-area">
                        <div class="user-area-text"><?=$FirstName;?></div>
                        <div class="user-area-icon">
                            <img src="<?=$Base;?>/media/avatars/<?=$UserInfo->Avatar;?>" />
                        </div>
                    </a>
                    <a href="<?=$Base;?>/logout.php" class="user-logout">
                        <img src="<?=$Base;?>/assets/images/power_white.png" />
                    </a>
                </div>
            </div>
        </div>
    </header>
    <section class="container main">