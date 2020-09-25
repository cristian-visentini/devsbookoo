<aside class="mt-10">
    <nav>
        <a href="<?= $Base;?>">
            <div class="menu-item <?=$ActiveMenu =='home'?'active':'';?>">
                <div class="menu-item-icon">
                    <img src="<?=$Base;?>/assets/images/home-run.png" width="16" height="16" />
                </div>
                <div class="menu-item-text">
                    Home
                </div>
            </div>
        </a>
        <a href="<?= $Base;?>/perfil.php">
            <div class="menu-item <?=$ActiveMenu=='profile'?'active':'';?>">
                <div class="menu-item-icon">
                    <img src="<?= $Base; ?>/assets/images/user.png" width="16" height="16" />
                </div>
                <div class="menu-item-text">
                    Meu Perfil
                </div>
            </div>
        </a>
        <a href="<?=$Base;?>/amigos.php">
            <div class="menu-item <?=$ActiveMenu=='friends'?'active':'';?>">
                <div class="menu-item-icon">
                    <img src="<?= $Base; ?>/assets/images/friends.png" width="16" height="16" />
                </div>
                <div class="menu-item-text">
                    Amigos
                </div>

            </div>
        </a>
        <a href="<?= $Base; ?>/fotos.php">
            <div class="menu-item <?=$ActiveMenu =='photos'?'active':'';?>">
                <div class="menu-item-icon">
                    <img src="<?= $Base; ?>/assets/images/photo.png" width="16" height="16" />
                </div>
                <div class="menu-item-text">
                    Fotos
                </div>
            </div>
        </a>
        <div class="menu-splitter"></div>
        <a href="<?=$Base;?>/configuracoes.php">
            <div class="menu-item <?= $ActiveMenu == 'config' ? 'active' : '' ?>">
                <div class="menu-item-icon">
                    <img src="<?= $Base; ?>/assets/images/settings.png" width="16" height="16" />
                </div>
                <div class="menu-item-text">
                    Configurações
                </div>
            </div>
        </a>
        <a href="<?=$Base;?>/logout.php">
            <div class="menu-item">
                <div class="menu-item-icon">
                    <img src="<?= $Base; ?>/assets/images/power.png" width="16" height="16" />
                </div>
                <div class="menu-item-text">
                    Sair
                </div>
            </div>
        </a>
    </nav>
</aside>