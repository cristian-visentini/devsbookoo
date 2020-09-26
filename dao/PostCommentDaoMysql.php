<?php
require_once "models/PostComment.php";
require_once "dao/UserDaoMysql.php";

class PostCommentDaoMysql implements PostCommentDAO{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }


    public function GetComment($Id_Post){
        $Array = [];

        $sql = $this->pdo->prepare('SELECT * FROM postcomments WHERE id_post = :id_post');
        $sql->bindValue(':id_post', $Id_Post);
        $sql->execute();

        if($sql->rowCount() > 0){
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $UserDao = new UserDaoMysql($this->pdo);

            foreach($data as $item){
                $CommentItem = new PostComment();

                $CommentItem->Id = $item['id'];
                $CommentItem->Id_Post = $item['id_post'];
                $CommentItem->Id_User = $item['id_user'];
                $CommentItem->Body = $item['body'];
                $CommentItem->Created_At = $item['created_at'];
                $CommentItem->User = $UserDao->FindById($item['id_user']);

                $Array[] = $CommentItem;
            }
        }


        return $Array;
    }


    public function AddComment(PostComment $Pc){
        $sql = $this->pdo->prepare('INSERT INTO postcomments (id_post, id_user, body, created_at)
        VALUES (:id_post, :id_user, :body, :created_at)');

        $sql->bindValue(':id_post', $Pc->Id_Post);
        $sql->bindValue(':id_user', $Pc->Id_User);
        $sql->bindValue(':body', $Pc->Body);
        $sql->bindValue(':created_at', $Pc->Created_At);
        $sql->execute();

    }

}