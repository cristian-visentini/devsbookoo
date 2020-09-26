<?php

class PostLike{
    public $Id;
    public $Id_Post;
    public $Id_User;
    public $Created_At;
    
}


interface PostLikeDAO{
    public function GetLikeCount($Id_Post);
    public function IsLiked($Id_Post, $Id_User);
    public function LikeToggle($Id_Post, $Id_User);
}