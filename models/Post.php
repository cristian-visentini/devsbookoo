<?php

class Post{
    public $Id;
    public $Id_User;
    public $Type; //text or photo
    public $Created_At;
    public $Body;
}

interface PostDAO{
    public function Insert(Post $P);
}