<?php

namespace Framework\Blog\Model;

use Framework\Blog\Model\DbManager;
use \PDO;

class CommentManager Extends DbManager
{
    private $_db;

    public function __construct()
    {
        $this->_db = $this->dbConnect();
    }
    
    public function getComments($postId)
    {
        $comments = $this->_db->prepare(
            'SELECT comments.id, post_id, username, comment, published,
            DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin%ss\') AS comment_date_fr 
            FROM comments 
            LEFT JOIN users ON comments.author = users.id
            WHERE post_id = ? AND published = 1
            ORDER BY comment_date DESC'
        );
        $comments->execute(array($postId));
        $commentsReq = $comments->fetchAll();
        $commentAll = [];
        foreach ($commentsReq as $comment) {
            $comment  = new Comment(
                $comment['id'],
                $comment['post_id'],
                $comment['username'],
                $comment['comment'],
                $comment['published'],
                $comment['comment_date_fr']
            );
            array_push($commentAll, $comment);
        }
        $comments->closeCursor();
        return $commentAll;
    }

    public function getCommentsList()
    {
        $req = $this->_db->query(
            'SELECT comments.id, post_id, username as author, comment, published,
            DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin%ss\') AS comment_date_fr 
            FROM comments 
            LEFT JOIN users ON comments.author = users.id
            ORDER BY comment_date DESC'
        );
        $req->execute();
        $commentsReq = $req->fetchAll();
        $commentAll = [];
        foreach ($commentsReq as $comment) {
            $comment  = new Comment(
                $comment['id'],
                $comment['post_id'],
                $comment['author'],
                $comment['comment'],
                $comment['published'],
                $comment['comment_date_fr']
            );
            array_push($commentAll, $comment);
        }
        $req->closeCursor();
        return $commentAll;
    }

    public function postComment($postId, $author, $comment)
    {
        $comments = $this->_db->prepare('INSERT INTO comments(post_id, author, comment, comment_date) VALUES(?, ?, ?, NOW())');
        $affectedLines = $comments->execute(array($postId, $author, $comment));
        return $affectedLines;
    }

    public function updatePulicationComment($commentId)
    {
        $req = $this->_db->prepare(
            'SELECT id, published
            FROM comments
            WHERE id = :id'
        );
        $req->bindValue(':id', $commentId, PDO::PARAM_INT);
        $req->execute();
        $comment = $req->fetch();
        $req->closeCursor();
        if ($comment['published'] === '1') {
            $publication = 0;
        } else {
            $publication = 1;
        }
        $req = $this->_db->prepare(
            'UPDATE comments
            SET published = :published 
            WHERE id = :id'
        );
        $req->bindValue(':id', $commentId, PDO::PARAM_INT);
        $req->bindValue(':published', $publication, PDO::PARAM_INT);
        $req->execute();
        return;
    }
}
