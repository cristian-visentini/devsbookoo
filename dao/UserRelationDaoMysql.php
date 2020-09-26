<?php
require_once 'models/UserRelation.php';


class UserRelationDaoMysql implements UserRelationDAO{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    public function Insert(UserRelation $U){
        $sql = $this->pdo->prepare('INSERT INTO userrelations (user_from, user_to) VALUES 
        (:user_from, :user_to)');
        $sql->bindValue(':user_from', $U->User_From);
        $sql->bindValue(':user_to', $U->User_To);
        $sql->execute();
        
    }

    public function Delete(UserRelation $U){
        $sql = $this->pdo->prepare('DELETE FROM userrelations WHERE user_from =  :user_from AND 
        user_to = :user_to');
        $sql->bindValue(':user_from', $U->User_From);
        $sql->bindValue(':user_to', $U->User_To);
        $sql->execute();

    }

    public function GetFollowing($Id){
        $Users = [];

        $sql = $this->pdo->prepare('SELECT user_to FROM userrelations WHERE user_from = :user_from');
        $sql->bindValue(':user_from', $Id); //provavel bug nesta linha falta :
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

    public function IsFollowing($Id1, $Id2){
        $sql = $this->pdo->prepare('SELECT * FROM userrelations WHERE
        user_from = :user_from AND user_to = :user_to');
        
        $sql->bindValue(':user_from', $Id1);
        $sql->bindValue(':user_to', $Id2);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }

 }