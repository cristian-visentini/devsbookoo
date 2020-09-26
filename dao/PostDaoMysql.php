<?php
require_once 'dao/UserRelationDaoMysql.php';
require_once 'models/Post.php';
require_once 'dao/UserDaoMysql.php';
require_once 'dao/PostLikeDaoMysql.php';


class PostDaoMysql implements PostDAO
{
    private $pdo;


    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function Insert(Post $P)
    {
        $sql = $this->pdo->prepare('INSERT INTO posts (
            id_user, type, created_at, body
        ) VALUES (:id_user, :type, :created_at, :body)');

        $sql->bindValue(':id_user', $P->Id_User);
        $sql->bindValue(':type', $P->Type);
        $sql->bindValue(':created_at', $P->Created_At);
        $sql->bindValue(':body', $P->Body);
        $sql->execute();
    }

    public function GetHomeFeed($Id_User)
    {
        $Array = [];
        //1 Lista dos usuarios que o usuario segue.

        $UrDao = new UserRelationDaoMysql($this->pdo);
        $UserList = $UrDao->GetFollowing($Id_User);
        $UserList[] = $Id_User;

        //2 Pegar os Posts em ordem cronológica

        $sql = $this->pdo->query("SELECT * FROM posts WHERE
            id_user IN (".implode(',', $UserList).")
            ORDER BY created_at DESC");

            if($sql->rowCount() >0){
                $Data = $sql->fetchAll(PDO::FETCH_ASSOC);

                //3 Transformar o resultado em objetos

                $Array = $this->_PostListToObjects($Data, $Id_User);
            }

        

        return $Array;
    }

    public function GetUserFeed($Id_User)
    {
        $Array = [];
        

        //2 Pegar os Posts em ordem cronológica

        $sql = $this->pdo->prepare("SELECT * FROM posts WHERE
            id_user = :id_user
            ORDER BY created_at DESC");

            $sql->bindValue(':id_user', $Id_User);
            $sql->execute();

            if($sql->rowCount() >0){
                $Data = $sql->fetchAll(PDO::FETCH_ASSOC);

                //3 Transformar o resultado em objetos

                $Array = $this->_PostListToObjects($Data, $Id_User);
            }

        

        return $Array;
    }


    public function GetPhotosFrom($Id_User){
        $Array = [];

        $sql = $this->pdo->prepare("SELECT * FROM posts WHERE
            id_user = :id_user AND type = 'photo'
            ORDER BY created_at DESC");

         $sql->bindValue(':id_user', $Id_User);
         $sql->execute();

         if($sql->rowCount() >0){
            $Data = $sql->fetchAll(PDO::FETCH_ASSOC);

            //3 Transformar o resultado em objetos

            $Array = $this->_PostListToObjects($Data, $Id_User);
        }


        return $Array;
    }

    private function _PostListToObjects($PostList, $Id_User){
        $Posts = [];
        $UserDao = new UserDaoMysql($this->pdo);
        $PostLikeDao = new PostLikeDaoMysql($this->pdo);

        foreach($PostList as $Post_Item){
            $NewPost = new Post();

            $NewPost->Id = $Post_Item['id'];
            $NewPost->Type = $Post_Item['type'];
            $NewPost->Created_At = $Post_Item['created_at'];
            $NewPost->Body = $Post_Item['body'];
            $NewPost->Mine = false;

            if($Post_Item['id_user'] == $Id_User){
                $NewPost->Mine = true; 
            }

            //Pegar informações do usuario.

            $NewPost->User = $UserDao->FindById($Post_Item['id_user']);

            //Informações sobre Like

            $NewPost->LikeCount = $PostLikeDao->GetLikeCount($NewPost->Id);
            $NewPost->Liked = $PostLikeDao->IsLiked($NewPost->Id, $Id_User);


            //Informações sobre coments

            $NewPost->Comments = [];

            $Posts[] = $NewPost;
        }

        return $Posts;
    }
}
