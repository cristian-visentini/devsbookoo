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
             <div class="feed-new-photo">
                 <img src="<?=$Base;?>/assets/images/photo.png" />
                 <input type="file" name="photo" class="feed-new-file" accept="image/png, image/jpeg, image/jpg">
             </div>

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
     let feedPhoto = document.querySelector('.feed-new-photo');
    let feedFile = document.querySelector('.feed-new-file');

     feedPhoto.addEventListener('click', function(){
         feedFile.click();
     });

     feedFile.addEventListener('change', async function(){
    let photo = feedFile.files[0];
    let formData = new FormData();

    formData.append('photo', photo);
    let req = await fetch('ajax_upload.php', {
        method: 'POST',
        body: formData
    });
    let json = await req.json();

    if(json.error != '') {
        alert(json.error);
    }

    window.location.href = window.location.href;
});

     FeedSubmit.addEventListener('click', function(){
         let Value = FeedInput.innerText.trim();
         FeedForm.querySelector('input[name=body]').value = Value;
         FeedForm.submit();
     });
 </script>