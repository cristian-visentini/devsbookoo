<script>
window.onload = function() {
    document.querySelectorAll('.like-btn').forEach(item=>{
        item.addEventListener('click', ()=>{
            let id = item.closest('.feed-item').getAttribute('data-id');
            let count = parseInt(item.innerText);
            if(item.classList.contains('on') === false) {
                item.classList.add('on');
                item.innerText = ++count;
            } else {
                item.classList.remove('on');
                item.innerText = --count;
            }

            fetch('ajax_like.php?id='+id);
        });
    });

    document.querySelectorAll('.fic-item-field').forEach(item=>{
        item.addEventListener('keyup', async (e)=>{
            if(e.keyCode == 13) {
                let id = item.closest('.feed-item').getAttribute('data-id');
                let txt = item.value;
                item.value = '';

                let Data = new FormData();

                Data.append('id', id);
                Data.append('txt', txt);

                let req = await fetch('ajax_comment.php', {
                    method: 'POST',
                    body: Data
                });
                let json = await req.json();

                if(json.error == '') {
                    let html = '<div class="fic-item row m-height-10 m-width-20">';
                    html += '<div class="fic-item-photo">';
                    html += '<a href="'+json.link+'"><img src="'+json.avatar+'" /></a>';
                    html += '</div>';
                    html += '<div class="fic-item-info">';
                    html += '<a href="'+json.link+'">'+json.name+'</a>';
                    html += json.body;
                    html += '</div>';
                    html += '</div>';

                    item.closest('.feed-item')
                        .querySelector('.feed-item-comments-area')
                        .innerHTML += html;
                }

            }
        });
    });
};
</script>