<?php
class UserRelation{
    public $Id;
    public $User_From;
    public $User_To;
}

interface UserRelationDAO{
    public function Insert(UserRelation $U);
    public function Delete(UserRelation $U);
    public function GetFollowing($Id);
    public function GetFollowers($Id);
    public function IsFollowing($Id1, $Id2);
}