<?php
require_once "models/PostLike.php";

class PostLikeDaoMysql implements PostLikeDAO{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }


    public function GetLikeCount($Id_Post){
        $sql = $this->pdo->prepare('SELECT COUNT(*) as c FROM postlikes WHERE id_post = :id_post');
        $sql->bindValue(":id_post", $Id_Post);
        $sql->execute();

        $data = $sql->fetch();

        return $data['c'];

    }

    public function IsLiked($Id_Post, $Id_User){

        $sql = $this->pdo->prepare('SELECT * FROM postlikes WHERE id_post = :id_post AND id_user = :id_user');
        $sql->bindValue(":id_post", $Id_Post);
        $sql->bindValue(":id_user", $Id_User);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function LikeToggle($Id_Post, $Id_User){
        if($this->IsLiked($Id_Post, $Id_User)){
            //delete
            $sql = $this->pdo->prepare('DELETE FROM postlikes WHERE id_post = :id_post AND id_user = :id_user');
        }else{
            //insere
            $sql = $this->pdo->prepare('INSERT INTO postlikes (id_post, id_user, created_at) VALUES 
            (:id_post, :id_user, NOW())');
        }

        $sql->bindValue(":id_post", $Id_Post);
        $sql->bindValue(":id_user", $Id_User);
        $sql->execute();
    }

}