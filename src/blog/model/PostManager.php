<?php

namespace Framework\Blog\Model;

use Framework\Blog\Model\SPDO;
use Framework\Blog\Model\Post;
use \PDO;

class PostManager
{

    public function lastPosts()
    {
        $req = SPDO::getInstance()->query(
            'SELECT posts.id, title, content, kicker, username, published, 
            DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr, 
            DATE_FORMAT(modification_date, \'%d/%m/%Y à %Hh%imin%ss\') AS modification_date_fr 
            FROM posts
            LEFT JOIN users ON posts.author = users.id
            WHERE published = 1
            ORDER BY creation_date  
            DESC 
            LIMIT 0, 3'
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
        $req = SPDO::getInstance()->query(
            'SELECT posts.id, title, content, kicker, username, published, 
            DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr,
            DATE_FORMAT(modification_date, \'%d/%m/%Y à %Hh%imin%ss\') AS modification_date_fr 
            FROM posts 
            LEFT JOIN users ON posts.author = users.id
            WHERE published = 1
            ORDER BY creation_date 
            DESC'
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

    public function getAdminPosts($user = false)
    {
        if ($user === false) {
            $req = SPDO::getInstance()->query(
                'SELECT posts.id, title, content, kicker, username, published, 
                DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr,
                DATE_FORMAT(modification_date, \'%d/%m/%Y à %Hh%imin%ss\') AS modification_date_fr 
                FROM posts 
                LEFT JOIN users ON posts.author = users.id
                ORDER BY creation_date 
                DESC'
            );
            $req->execute();
        } else {
            $req = SPDO::getInstance()->prepare(
                'SELECT posts.id, title, content, kicker, username, published, 
                DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr,
                DATE_FORMAT(modification_date, \'%d/%m/%Y à %Hh%imin%ss\') AS modification_date_fr 
                FROM posts 
                LEFT JOIN users ON posts.author = users.id
                WHERE  users.id = :user 
                ORDER BY creation_date_fr 
                DESC'
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
        $req = SPDO::getInstance()->prepare(
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
        $req->closeCursor();
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

    public function updatePost($postid, $title, $kicker, $content, $author)
    {
        $req = SPDO::getInstance()->prepare(
            'UPDATE posts SET title = :title,
             kicker = :kicker, 
             author = :author, 
             content = :content, 
             modification_date = NOW(), 
             published = 0 
             WHERE id = :id
            '
        );
        $req->bindValue(':id', intval($postid), PDO::PARAM_INT);
        $req->bindValue(':title', $title, PDO::PARAM_STR);
        $req->bindValue(':kicker', $kicker, PDO::PARAM_STR);
        $req->bindValue(':author', intval($author), PDO::PARAM_INT);
        $req->bindValue(':content', $content, PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
        return;
    }

    public function insertPost($title, $kicker, $content, $author)
    {
        $req = SPDO::getInstance()->prepare(
            'INSERT INTO posts (title, kicker, content, author, creation_date, modification_date, published) 
            VALUES ( :title, :kicker, :content, :author, NOW(), NOW(), 0)'
        );
        $req->bindValue(':title', $title, PDO::PARAM_STR);
        $req->bindValue(':kicker', $kicker, PDO::PARAM_STR);
        $req->bindValue(':author', intval($author), PDO::PARAM_INT);
        $req->bindValue(':content', $content, PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
        return;
    }

    public function getPostsAuthor($postId)
    {
        $req = SPDO::getInstance()->prepare(
            'SELECT author 
            FROM posts
            WHERE   id = :id'
        );
        $req->bindValue(':id', $postId, PDO::PARAM_INT);
        $req->execute();
        $author = $req->fetch();
        $req->closeCursor();
        return $author;
    }

    public function updatePulicationPost($postid)
    {
        $req = SPDO::getInstance()->prepare(
            'SELECT id, published
            FROM posts
            WHERE id = :id'
        );
        $req->bindValue(':id', $postid, PDO::PARAM_INT);
        $req->execute();
        $post = $req->fetch();
        $req->closeCursor();
        if ($post['published'] === '1') {
            $publication = 0;
        } else {
            $publication = 1;
        }
        $req = SPDO::getInstance()->prepare(
            'UPDATE posts
            SET published = :published 
            WHERE id = :id'
        );
        $req->bindValue(':id', $postid, PDO::PARAM_INT);
        $req->bindValue(':published', $publication, PDO::PARAM_INT);
        $req->execute();
        $req->closeCursor();
        return;
    }

    public function removePost($postid)
    {
        $req = SPDO::getInstance()->prepare(
            'DELETE FROM posts WHERE id = :id'
        );
        $req->bindValue(':id', $postid, PDO::PARAM_INT);
        $req->execute();
        $req->closeCursor();
        return;
    }
}
