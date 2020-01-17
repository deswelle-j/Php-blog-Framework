<?php

namespace Framework\Blog\Model;

use Framework\Blog\Model\Post;
use \PDO;

class PostManager extends Manager
{
    public function lastPosts()
    {
        $db = $this->dbConnect();
        $req = $db->query(
            'SELECT posts.id, title, content, kicker, username, published, 
            DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr, 
            DATE_FORMAT(modification_date, \'%d/%m/%Y à %Hh%imin%ss\') AS modification_date_fr 
            FROM posts
            LEFT JOIN users ON posts.author = users.id
            WHERE published = 1
            ORDER BY creation_date  
            DESC 
            LIMIT 0, 4'
        );
        $req->execute();
        $postsReq = $req->fetchAll();
        $postAll = [];
        foreach ($postsReq as $reqPost) {
            $post  = new Post(
                $reqPost['id'],
                $reqPost['title'],
                $reqPost['kicker'],
                $reqPost['username'],
                $reqPost['content'],
                $reqPost['creation_date_fr'],
                $reqPost['modification_date_fr'],
                $reqPost['published']
            );
            array_push($postAll, $post);
        }
        $req->closeCursor();
        return $postAll;
    }
    
    public function getPosts()
    {
        $db = $this->dbConnect();
        $req = $db->query(
            'SELECT posts.id, title, content, kicker, username, published, 
            DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr,
            DATE_FORMAT(modification_date, \'%d/%m/%Y à %Hh%imin%ss\') AS modification_date_fr 
            FROM posts 
            LEFT JOIN users ON posts.author = users.id
            WHERE published = 1
            ORDER BY creation_date 
            DESC 
            LIMIT 0, 5'
        );
        $req->execute();
        $postsReq = $req->fetchAll();
        $postAll = [];
        foreach ($postsReq as $reqPost) {
            $post  = new Post(
                $reqPost['id'],
                $reqPost['title'],
                $reqPost['kicker'],
                $reqPost['username'],
                $reqPost['content'],
                $reqPost['creation_date_fr'],
                $reqPost['modification_date_fr'],
                $reqPost['published']
            );
            array_push($postAll, $post);
        }
        $req->closeCursor();
        return $postAll;
    }

    public function getAdminPosts( $user = false)
    {
        $db = $this->dbConnect();
        if ($user === false) {
            $req = $db->query(
                'SELECT posts.id, title, content, kicker, username, published, 
                DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr,
                DATE_FORMAT(modification_date, \'%d/%m/%Y à %Hh%imin%ss\') AS modification_date_fr 
                FROM posts 
                LEFT JOIN users ON posts.author = users.id
                ORDER BY creation_date 
                DESC 
                LIMIT 0, 5'
            ); 
            $req->execute();
        } else {
            $req = $db->prepare(
                'SELECT posts.id, title, content, kicker, username, published, 
                DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr,
                DATE_FORMAT(modification_date, \'%d/%m/%Y à %Hh%imin%ss\') AS modification_date_fr 
                FROM posts 
                LEFT JOIN users ON posts.author = users.id
                WHERE  users.id = :user 
                ORDER BY creation_date_fr 
                DESC 
                LIMIT 0, 5'
            );
            $req->bindValue(':user', $user, PDO::PARAM_INT);
            $req->execute();
        }
        $postsReq = $req->fetchAll();
        $postAll = [];
        foreach ($postsReq as $reqPost) {
            $post  = new Post(
                $reqPost['id'],
                $reqPost['title'],
                $reqPost['kicker'],
                $reqPost['username'],
                $reqPost['content'],
                $reqPost['creation_date_fr'],
                $reqPost['modification_date_fr'],
                $reqPost['published']
            );
            array_push($postAll, $post);
        }
        $req->closeCursor();
        return $postAll;
    }

    public function getPost($postId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare(
            'SELECT posts.id, title, content, kicker, username, published, 
            DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr,
            DATE_FORMAT(modification_date, \'%d/%m/%Y à %Hh%imin%ss\') AS modification_date_fr 
            FROM posts 
            LEFT JOIN users ON posts.author = users.id
            WHERE   posts.id = :id'
        );
        $req->bindValue(':id', $postId, PDO::PARAM_INT);
        $req->execute();
        $post = $req->fetch();
        return new Post(
            $post['id'],
            $post['title'],
            $post['kicker'],
            $post['username'],
            $post['content'],
            $post['creation_date_fr'],
            $post['modification_date_fr'],
            $post['published']
        );
    }

    public function updatePost($postid, $title, $kicker, $content)
    {
        $db = $this->dbConnect();
        $req = $db->prepare(
            'UPDATE posts
            SET title = :title, kicker = :kicker, content = :content
             WHERE id = :id'
        );
        $req->bindValue(':id', $postid, PDO::PARAM_INT);
        $req->bindValue(':title', $title, PDO::PARAM_STR);
        $req->bindValue(':kicker', $kicker, PDO::PARAM_STR);
        $req->bindValue(':content', $content, PDO::PARAM_STR);
        $req->execute();
        return;
    }

    public function removePost($postid) {
        $db = $this->dbConnect();
        $req = $db->prepare(
            'DELETE FROM posts WHERE id = :id'
        );
        $req->bindValue(':id', $postid, PDO::PARAM_INT);
        $req->execute();
        return;
    }
}
