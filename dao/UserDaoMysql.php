<?php
require_once 'models/User.php';


class UserDaoMysql implements UserDAO{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    private function GenerateUser($array){

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
    }

    public function FindByToken($token){
        if(!empty($token)){
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE token = :token");
            $sql->bindValue(':token', $token);
            $sql->execute();

            if($sql->rowCount() > 0){
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
            $sql->bindValue(':email', $email);
            $sql->execute();

            if($sql->rowCount() > 0){
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                $user = $this->GenerateUser($data);
                return $user;
            }
        }
        return false;
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
        token = :token,
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
