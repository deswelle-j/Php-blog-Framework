<?php

namespace Framework\Blog\Model;

class Comment {
    protected $id;
    protected $postId;
    protected $author;
    protected $comment;
    protected $comment_date;
    protected $publish;

    public function __construct($id, $postId, $author, $comment, $publish ,$comment_date){
        $this->setId($id);
        $this->setPostId($postId);
        $this->setComment($comment);
        $this->setAuthor($author);
        $this->setCommentDate($comment_date);
        $this->setPublish($publish);
    }

    public function id() {return $this->id; }
    public function postId() {return $this->postId; }
    public function comment() {return $this->comment; }
    public function author() { return $this->author; }
    public function commentDate() {return $this->comment_date; }
    public function publish() { return $this->publish; }

    public function setId($id){
        $this->id = (int) $id;
    }

    public function setPostId($postId){
            $this->postId = (int) $postId;
    }

    public function setAuthor($author){
        if (is_string($author))
        {
            $this->author = $author;
        }
    }

    public function setComment($comment){
        if (is_string($comment))
        {
            $this->comment = $comment;
        }
    }

    public function setCommentDate ($comment_date){
            $this->comment_date = $comment_date;
    }

    public function setPublish($publish){
        $this->publish = $publish;
    }
}
