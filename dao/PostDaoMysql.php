<?php
require_once 'dao/UserRelationDaoMysql.php';
require_once 'models/Post.php';
require_once 'dao/UserDaoMysql.php';
require_once 'dao/PostLikeDaoMysql.php';
require_once 'dao/PostCommentDaoMysql.php';


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

    public function Delete($Id, $Id_User)
    {
        $PostLikeDao = new PostLikeDaoMysql($this->pdo);
        $PostCommentDao = new PostCommentDaoMysql($this->pdo);

        //Verificar se post existe (tipo)

        $sql = $this->pdo->prepare("SELECT * FROM posts WHERE id = :id AND id_user = :id_user");
        $sql->bindValue(':id', $Id);
        $sql->bindValue(':id_user', $Id_User);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $Post = $sql->fetch(PDO::FETCH_ASSOC);

            //deletar likes e Comemts

            $PostLikeDao->DeleteFromPost($Id);
            $PostCommentDao->DeleteFromPost($Id);

            //Se foto, deletar o arquivo

            if ($Post['type'] === 'photo') {
                $Img = 'media/uploads/' . $Post['body'];
                if (file_exists($Img)) {
                    unlink($Img);
                }
            }

            //deletar o post de fato


        }

        $sql = $this->pdo->prepare("DELETE FROM posts WHERE id = :id AND id_user = :id_user");
        $sql->bindValue(':id', $Id);
        $sql->bindValue(':id_user', $Id_User);
        $sql->execute();
    }

    public function GetHomeFeed($Id_User)
    {
        $Array = [];
        $PerPage = 5;

        $Page = intval(filter_input(INPUT_GET, 'p'));

        if ($Page < 1) {
            $Page = 1;
        }

        $OffSet = ($Page - 1) * $PerPage;

        //1 Lista dos usuarios que o usuario segue.

        $UrDao = new UserRelationDaoMysql($this->pdo);
        $UserList = $UrDao->GetFollowing($Id_User);
        $UserList[] = $Id_User;

        //2 Pegar os Posts em ordem cronológica

        $sql = $this->pdo->query("SELECT * FROM posts WHERE
            id_user IN (" . implode(',', $UserList) . ")
            ORDER BY created_at DESC, id DESC LIMIT $OffSet,$PerPage");

        if ($sql->rowCount() > 0) {
            $Data = $sql->fetchAll(PDO::FETCH_ASSOC);

            //3 Transformar o resultado em objetos

            $Array['feed'] = $this->_PostListToObjects($Data, $Id_User);
        }

        //4 pegar total de posts
        $sql = $this->pdo->query("SELECT COUNT(*) as c FROM posts WHERE
        id_user IN (" . implode(',', $UserList) . ")");

        $TotalData = $sql->fetch();
        $Total = $TotalData['c'];

        $Array['pages'] = ceil($Total / $PerPage);
        $Array['currentpage'] = $Page;

        return $Array;
    }

    public function GetUserFeed($Id_User)
    {
        $Array = ['feed'=>[]];

        $PerPage = 5;

        $Page = intval(filter_input(INPUT_GET, 'p'));

        if ($Page < 1) {
            $Page = 1;
        }

        $OffSet = ($Page - 1) * $PerPage;

        //2 Pegar os Posts em ordem cronológica

        $sql = $this->pdo->prepare("SELECT * FROM posts WHERE
            id_user = :id_user
            ORDER BY created_at DESC LIMIT $OffSet,$PerPage");

        $sql->bindValue(':id_user', $Id_User);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $Data = $sql->fetchAll(PDO::FETCH_ASSOC);

            //3 Transformar o resultado em objetos

            $Array['feed'] = $this->_PostListToObjects($Data, $Id_User);
        }

        $sql = $this->pdo->prepare("SELECT COUNT(*) as c FROM posts WHERE
            id_user = :id_user");
        $sql->bindValue(':id_user', $Id_User);
        $sql->execute();
        $TotalData = $sql->fetch();
        $Total = $TotalData['c'];

        $Array['pages'] = ceil($Total / $PerPage);
        $Array['currentpage'] = $Page;

        return $Array;
    }


    public function GetPhotosFrom($Id_User)
    {
        $Array = [];

        $sql = $this->pdo->prepare("SELECT * FROM posts WHERE
            id_user = :id_user AND type = 'photo'
            ORDER BY created_at DESC");

        $sql->bindValue(':id_user', $Id_User);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $Data = $sql->fetchAll(PDO::FETCH_ASSOC);

            //3 Transformar o resultado em objetos

            $Array = $this->_PostListToObjects($Data, $Id_User);
        }


        return $Array;
    }

    private function _PostListToObjects($PostList, $Id_User)
    {
        $Posts = [];
        $UserDao = new UserDaoMysql($this->pdo);
        $PostLikeDao = new PostLikeDaoMysql($this->pdo);
        $PostCommentDao = new PostCommentDaoMysql($this->pdo);

        foreach ($PostList as $Post_Item) {
            $NewPost = new Post();

            $NewPost->Id = $Post_Item['id'];
            $NewPost->Type = $Post_Item['type'];
            $NewPost->Created_At = $Post_Item['created_at'];
            $NewPost->Body = $Post_Item['body'];
            $NewPost->Mine = false;

            if ($Post_Item['id_user'] == $Id_User) {
                $NewPost->Mine = true;
            }

            //Pegar informações do usuario.

            $NewPost->User = $UserDao->FindById($Post_Item['id_user']);

            //Informações sobre Like

            $NewPost->LikeCount = $PostLikeDao->GetLikeCount($NewPost->Id);
            $NewPost->Liked = $PostLikeDao->IsLiked($NewPost->Id, $Id_User);


            //Informações sobre coments

            $NewPost->Comments = $PostCommentDao->GetComment($NewPost->Id);

            $Posts[] = $NewPost;
        }

        return $Posts;
    }
}
