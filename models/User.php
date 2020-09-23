<?php
class User{
    public $Id;
    public $Email;
    public $Password;
    public $Name;
    public $BirthDate;
    public $City;
    public $Work;
    public $Avatar;
    public $Cover;
    public $Token;
}

interface UserDAO{
    public function FindByToken($token);
    public function FindByEmail($email);
    public function Update(User $u);
    public function Insert(User $U);
}