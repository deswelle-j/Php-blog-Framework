<?php

namespace Framework\Blog\Model;

use Framework\Blog\Model\Post;

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

    public function getAdminPosts( $username = false)
    {
        $db = $this->dbConnect();
        if ($username !== false) {
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
        } else {
            $req = $db->query(
                'SELECT posts.id, title, content, kicker, username, published, 
                DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr,
                DATE_FORMAT(modification_date, \'%d/%m/%Y à %Hh%imin%ss\') AS modification_date_fr 
                FROM posts 
                LEFT JOIN users ON posts.author = users.id
                WHERE published = 1 AND username = 
                ORDER BY creation_date 
                DESC 
                LIMIT 0, 5'
            );
            $req->execute(array($username));
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
            'SELECT id,
            title,
            content,
            DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr 
            FROM posts 
            WHERE id = ?'
        );
        $req->execute(array($postId));
        $post = $req->fetch();
        return new Post(
            $post['id'],
            $post['title'],
            $post['content'],
            $post['creation_date_fr']
        );
    }
}
