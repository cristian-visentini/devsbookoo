<?php
require_once 'models/Post.php';

class PostDaoMysql implements PostDAO{
    private $pdo;


    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    public function Insert(Post $P){
        $sql = $this->pdo->prepare('INSERT INTO posts (
            id_user, type, created_at, body
        ) VALUES (:id_user, :type, :created_at, :body)');

        $sql->bindValue(':id_user', $P->Id_User);
        $sql->bindValue(':type', $P->Type);
        $sql->bindValue(':created_at', $P->Created_At);
        $sql->bindValue(':body', $P->Body);
        $sql->execute();
        

    }
    
}