<?php
require_once 'feed_item_script.php';

$ActionPhrase = '';

switch ($Item->Type) {
    case 'text':
        $ActionPhrase = 'Fez um Post';
        break;
    case 'photo':
        $ActionPhrase = 'Postou uma foto';
        break;
}


?>

<div class="box feed-item" data-id="<?= $Item->Id; ?>">
    <div class="box-body">
        <div class="feed-item-head row mt-20 m-width-20">
            <div class="feed-item-head-photo">
                <a href="<?= $Base; ?>/perfil.php?id=<?= $Item->User->Id; ?>"><img src="<?= $Base; ?>/media/avatars/<?= $Item->User->Avatar; ?>" /></a>
            </div>
            <div class="feed-item-head-info">
                <a href="<?= $Base; ?>/perfil.php?id=<?= $Item->User->Id; ?>"><span class="fidi-name"><?= $Item->User->Name; ?></span></a>
                <span class="fidi-action"><?= $ActionPhrase; ?></span>
                <br />
                <span class="fidi-date"><?= date('d/m/Y', strtotime($Item->Created_At)); ?></span>
            </div>
            <div class="feed-item-head-btn">
                <img src="<?= $Base; ?>/assets/images/more.png" />
            </div>
        </div>
        <div class="feed-item-body mt-10 m-width-20">

            <?php
            switch ($Item->Type) {
                case 'text':
                    nl2br($Item->Body);
                    break;
                case 'photo':
                    echo '<img src="'.$Base.'/media/uploads/'.$Item->Body.'">';
                    break;
            }

            ?>



           
        </div>
        <div class="feed-item-buttons row mt-20 m-width-20">
            <div class="like-btn <?= $Item->Liked ? 'on' : ''; ?>"><?= $Item->LikeCount; ?></div>
            <div class="msg-btn"><?= count($Item->Comments); ?></div>
        </div>
        <div class="feed-item-comments">

            <div class="feed-item-comments-area">
                <?php foreach ($Item->Comments as $Comments) : ?>
                    <div class="fic-item row m-height-10 m-width-20">
                        <div class="fic-item-photo">
                            <a href="<?= $Base; ?>/perfil.php?id=<?= $Comments->Id_User; ?>"><img src="<?= $Base; ?>/media/avatars/<?= $Comments->User->Avatar; ?>" /></a>
                        </div>
                        <div class="fic-item-info">
                            <a href="<?= $Base; ?>/perfil.php?id=<?= $Comments->Id_User; ?>"><?= $Comments->User->Name; ?></a>
                            <?= $Comments->Body; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>


            <div class="fic-answer row m-height-10 m-width-20">
                <div class="fic-item-photo">
                    <a href="<?= $Base; ?>/perfil.php"><img src="<?= $Base; ?>/media/avatars/<?= $UserInfo->Avatar; ?>" /></a>
                </div>
                <input type="text" class="fic-item-field" placeholder="Escreva um comentário" />
            </div>

        </div>
    </div>
</div>