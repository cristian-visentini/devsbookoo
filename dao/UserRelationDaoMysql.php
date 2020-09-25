<?php
require_once 'models/UserRelation.php';


class UserRelationDaoMysql implements UserRelationDAO{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    public function Insert(UserRelation $U){
        
    }

    public function GetFollowing($Id){
        $Users = [];

        $sql = $this->pdo->prepare('SELECT user_to FROM userrelations WHERE user_from = :user_from');
        $sql->bindValue('user_from', $Id);
        $sql->execute();

        if($sql->rowCount() >0){
            $data = $sql->fetchAll();
            foreach($data as $itens){
                $Users[] = $itens['user_to'];
            }
        }

        return $Users;
    }

    public function GetFollowers($Id){
        $Users = [];
      
        $sql = $this->pdo->prepare('SELECT user_from FROM userrelations WHERE user_to = :user_to');
        $sql->bindValue('user_to', $Id);
        $sql->execute();

        if($sql->rowCount() >0){
            $data = $sql->fetchAll();
            foreach($data as $itens){
                $Users[] = $itens['user_from'];
            }
        }

        return $Users;
    }

 }