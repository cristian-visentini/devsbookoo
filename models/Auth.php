<?php
require_once 'dao/UserDaoMysql.php';

class Auth {
    private $pdo;
    private $Base;
    private $Dao;

    public function __construct(PDO $pdo, $Base){
        $this->pdo = $pdo;
        $this->Base = $Base;
        $this->Dao = new UserDaoMysql($this->pdo);
    }

    public function CheckToken(){
        if(!empty($_SESSION['token'])){
            $Token = $_SESSION['token'];
            
            
            $User = $this->Dao->FindByToken($Token);
            
            if($User){
                return $User;
            }
        }

        header('Location: '.$this->Base.'/login.php');
        exit;
    }

    public function ValidateLogin($Email, $Password){
        
        $User = $this->Dao->FindByEmail($Email);
        
        if($User){
            
            if(password_verify($Password, $User->Password)){
                //ok
                $Token = md5(time().rand(0, 999));
                $_SESSION['token'] = $Token;
                $User->Token = $Token;
                //ok
                $this->Dao->Update($User);

                return true;
            }
        }

        return false;
    }

    public function EmailExists($Email){
       
        
        return $this->Dao->FindByEmail($Email) ? true : false;
    }

    public function UserRegister($Name, $Email, $Password, $Birthdate){
        

        $Hash = password_hash($Password, PASSWORD_DEFAULT);
        $Token = md5(time().rand(0, 999));

        $NewUser = new User();
        $NewUser->Name = $Name;
        $NewUser->Email = $Email;
        $NewUser->Password = $Hash;
        $NewUser->BirthDate = $Birthdate;
        $NewUser->Token = $Token;

        $this->Dao->Insert($NewUser);

        $_SESSION['token'] = $Token;
    }

}