<?php

namespace Framework\Blog\Model;

use Framework\Blog\Model\Post;

class PostManager extends Manager
{
    public function getPosts()
    {
        $db = $this->dbConnect();
        $req = $db->query(
            'SELECT id, title, content, 
            DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr 
            FROM posts 
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
                $reqPost['content'],
                $reqPost['creation_date_fr']
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
