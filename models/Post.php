<?php

class Post{
    public $Id;
    public $Id_User;
    public $Type; //text or photo
    public $Created_At; //Não esta retornando corretamento do BD
    public $Body;
}

interface PostDAO{
    public function Insert(Post $P);
    public function Delete($Id, $Id_User);
    public function GetHomeFeed($Id_User);
    public function GetUserFeed($Id_User);
    public function GetPhotosFrom($Id_User);
}