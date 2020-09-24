<?php
class UserRelation{
    public $Id;
    public $User_From;
    public $User_To;
}

interface UserRelationDAO{
    public function Insert(UserRelation $U);
    public function GetRelationsFrom($Id);
}