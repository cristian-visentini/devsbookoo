<?php
$FirstName = current(explode(' ', $UserInfo->Name));
?>
<div class="box feed-new">
     <div class="box-body">
         <div class="feed-new-editor m-10 row">
             <div class="feed-new-avatar">
                 <img src="<?=$Base;?>/media/avatars/<?=$UserInfo->Avatar;?>" />
             </div>
             <div class="feed-new-input-placeholder">O que você está pensando, <?=$FirstName;?>?</div>
             <div class="feed-new-input" contenteditable="true"></div>
             <div class="feed-new-send">
                 <img src="<?=$Base;?>/assets/images/send.png" />
             </div>
             <form class="feed-new-form" method="POST" action="<?=$Base;?>/feed_editor_action.php">
                    <input type="hidden" name="body">
             </form>
         </div>
     </div>
 </div>

 <script>
     let FeedInput = document.querySelector('.feed-new-input');
     let FeedSubmit = document.querySelector('.feed-new-send');
     let FeedForm = document.querySelector('.feed-new-form');

     FeedSubmit.addEventListener('click', function(){
         let Value = FeedInput.innerText.trim();
         FeedForm.querySelector('input[name=body]').value = Value;
         FeedForm.submit();
     });
 </script>