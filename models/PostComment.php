<?php

class PostComment{
    public $Id;
    public $Id_Post;
    public $Id_User;
    public $Created_At;
    
}


interface PostCommentDAO{
    public function GetComment($Id_Post);
    public function AddComment(PostComment $Pc);
}