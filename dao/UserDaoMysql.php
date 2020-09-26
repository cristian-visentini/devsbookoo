<?php
require_once 'models/User.php';
require_once 'dao/UserRelationDaoMysql.php';
require_once 'dao/PostDaoMysql.php';


class UserDaoMysql implements UserDAO{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    private function GenerateUser($array, $Full = false){

        $U = new User();
        $U->Id = $array['id'] ?? 0;
        $U->Email = $array['email'] ?? '';
        $U->Password = $array['password'] ?? '';
        $U->Name = $array['name'] ?? '';
        $U->BirthDate = $array['birthdate'] ?? '';
        $U->City = $array['city'] ?? '';
        $U->Work = $array['work'] ?? '';
        $U->Avatar = $array['avatar'] ?? '';
        $U->Cover = $array['cover'] ?? '';
        $U->Token = $array['token'] ?? '';

        if($Full){
            $UrDaoMysql = new UserRelationDaoMysql($this->pdo);
            $PostDaoMysql = new PostDaoMysql($this->pdo);
            //Followers = Quem segue o usuario

            $U->Followers = $UrDaoMysql->GetFollowers($U->Id);

            foreach($U->Followers as $Key => $Follower_Id){
                $NewUser = $this->FindById($Follower_Id);
                $U->Followers[$Key] = $NewUser;
            }

            //Following = Quem o usuario segue

            $U->Following = $UrDaoMysql->GetFollowing($U->Id);

            foreach($U->Following as $Key => $Follower_Id){
                $NewUser = $this->FindById($Follower_Id);
                $U->Following[$Key] = $NewUser;
            }

            //Fotos

            $U->Photos = $PostDaoMysql->GetPhotosFrom($U->Id);
        }

        return $U;
    }

    public function FindByToken($token){
        if(!empty($token)){
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE token = :token");
            $sql->bindValue(':token', $token);
            $sql->execute();
             //chega ate aqui
            if($sql->rowCount() > 0){
                //não esta entrando nesse if portanto retorna false levando de volta a tela de login

                $data = $sql->fetch(PDO::FETCH_ASSOC);
                $user = $this->GenerateUser($data);
                return $user;
            }
        }
        return false;
    }

    public function FindByEmail($email){
        if(!empty($email)){
            
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
            $sql->bindValue(":email", $email);
            $sql->execute();

            if($sql->rowCount() > 0){
                
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                $user = $this->GenerateUser($data);
                
                return $user; //ok
            }
        }
        return false;
    }


    public function FindById($Id, $Full = false){
        if(!empty($Id)){
        
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
            $sql->bindValue(":id", $Id);
            $sql->execute();

            if($sql->rowCount() > 0){
                
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                $user = $this->GenerateUser($data, $Full);
                
                return $user; //ok
            }
        }
        return false;

    }


    public function FindByName($Name){
        $Array = [];
        if(!empty($Name)){
            
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE name  LIKE :name");
            $sql->bindValue(":name", '%'.$Name.'%');
            $sql->execute();

            if($sql->rowCount() > 0){
                
                $data = $sql->fetchAll(PDO::FETCH_ASSOC);

                foreach($data as $item){
                    $Array[] = $this->GenerateUser($item);
                }
            }
        }
        return $Array;

    }


    public function Update(User $u){
        $sql = $this->pdo->prepare('UPDATE users SET
        email = :email,
        password = :password,
        name = :name,
        birthdate = :birthdate,
        city = :city,
        work = :work,
        avatar = :avatar,
        cover = :cover,
        token = :token
        WHERE id = :id');

        $sql->bindValue(':email', $u->Email);
        $sql->bindValue(':password', $u->Password);
        $sql->bindValue(':name', $u->Name);
        $sql->bindValue(':birthdate', $u->BirthDate);
        $sql->bindValue(':city', $u->City);
        $sql->bindValue(':work', $u->Work);
        $sql->bindValue(':avatar', $u->Avatar);
        $sql->bindValue(':cover', $u->Cover);
        $sql->bindValue(':token', $u->Token);
        $sql->bindValue(':id', $u->Id);
        $sql->execute();

        return true;
    }

    public function Insert(User $U){
        $sql = $this->pdo->prepare('INSERT INTO users (email, password, name, birthdate, token) VALUES 
        (:email, :password, :name, :birthdate, :token)');

        $sql->bindValue(':email', $U->Email);
        $sql->bindValue(':password', $U->Password);
        $sql->bindValue(':name', $U->Name);
        $sql->bindValue(':birthdate', $U->BirthDate);
        $sql->bindValue(':token', $U->Token);
        $sql->execute();

        return true;
    }
}