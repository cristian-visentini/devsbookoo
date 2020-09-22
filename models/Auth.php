<?php
require_once 'dao/UserDaoMysql.php';

class Auth {
    private $pdo;
    private $Base;

    public function __construct(PDO $pdo, $Base){
        $this->pdo = $pdo;
        $this->Base = $Base;
    }

    public function CheckToken(){
        if(!empty($_SESSION['token'])){
            $Token = $_SESSION['token'];
            
            $UserDao = new UserDaoMysql($this->pdo);
            $User = $UserDao->FindByToken($Token);
            
            if($User){
                return $User;
            }
        }

        header('Location: '.$this->Base.'/login.php');
        exit;
    }

    public function ValidateLogin($Email, $Password){
        $UserDao = new UserDaoMysql($this->pdo);
        $User = $UserDao->FindByEmail($Email);
       
        if($User){
            
            if(password_verify($Password, $User->Password)){
                //ok
                $Token = md5(time().rand(0, 999));
                $_SESSION['token'] = $Token;
                $User->Token = $Token;
                //ok
                $UserDao->Update($User);

                return true;
            }
        }

        return false;
    }

    public function EmailExists($Email){
        $UserDao = new UserDaoMysql($this->pdo);
        
        return $UserDao->FindByEmail($Email) ? true : false;
    }

    public function UserRegister($Name, $Email, $Password, $Birthdate){
        $UserDao = new UserDaoMysql($this->pdo);

        $Hash = password_hash($Password, PASSWORD_DEFAULT);
        $Token = md5(time().rand(0, 999));

        $NewUser = new User();
        $NewUser->Name = $Name;
        $NewUser->Email = $Email;
        $NewUser->Password = $Hash;
        $NewUser->BirthDate = $Birthdate;
        $NewUser->Token = $Token;

        $UserDao->Insert($NewUser);

        $_SESSION['token'] = $Token;
    }

}