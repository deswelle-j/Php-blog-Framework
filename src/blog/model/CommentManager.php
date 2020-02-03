<?php

namespace Framework\Blog\Model;
use \PDO;

class CommentManager extends Manager
{
    public function getComments($postId)
    {
        $db = $this->dbConnect();
        $comments = $db->prepare(
            'SELECT id, post_id,author, comment, published,
            DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin%ss\') AS comment_date_fr 
            FROM comments 
            WHERE post_id = ? 
            ORDER BY comment_date DESC'
        );
        $comments->execute(array($postId));
        $commentsReq = $comments->fetchAll();
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
        $comments->closeCursor();
        return $commentAll;
    }

    public function getCommentsList()
    {
        $db = $this->dbConnect();
        $req = $db->query(
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
        $db = $this->dbConnect();
        $comments = $db->prepare('INSERT INTO comments(post_id, author, comment, comment_date) VALUES(?, ?, ?, NOW())');
        $affectedLines = $comments->execute(array($postId, $author, $comment));
        return $affectedLines;
    }

    public function editComment($commentId, $newComment)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE comments SET comment = :newComment , comment_date = NOW() WHERE id = :id ');
        $req -> execute(array('id' => $commentId , 'newComment' => $newComment ));
    }

    public function updatePulicationComment($commentId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare(
            'SELECT id, published
            FROM comments
            WHERE id = :id'
        );
        $req->bindValue(':id', $commentId, PDO::PARAM_INT);
        $req->execute();
        $comment = $req->fetch();
        $req->closeCursor();
        if ($comment['published'] === '1' ) {
            $publication = 0;
        } else {
            $publication = 1;
        }
        $req = $db->prepare(
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
