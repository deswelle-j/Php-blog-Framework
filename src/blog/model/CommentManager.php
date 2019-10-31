<?php

namespace Framework\Blog\Model;

class CommentManager extends Manager

{

	public function getComments($postId)

	{
		$db = $this->dbConnect();

		$comments = $db->prepare('SELECT id,post_id,author,comment,publish,DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin%ss\') AS comment_date_fr FROM comments WHERE post_id = ? ORDER BY comment_date DESC');

		$comments->execute(array($postId));

		$commentsReq = $comments->fetchAll();
		$commentAll = [];
		foreach($commentsReq as $comment){
			$comment  = new Comment($comment['id'], $comment['post_id'], $comment['author'], $comment['comment'], $comment['publish'] , $comment['comment_date_fr']);
			array_push($commentAll, $comment);
		}
		$comments->closeCursor();
		return $commentAll;
	}

	public function getCommentsList(){
		$db = $this->dbConnect();
		$req = $db->query('SELECT id,post_id,author,comment,publish,DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin%ss\') AS comment_date_fr FROM comments ORDER BY comment_date DESC');

		$req->execute();
		$commentsReq = $req->fetchAll();
		$commentAll = [];
		foreach($commentsReq as $comment){
			$comment  = new Comment($comment['id'], $comment['post_id'], $comment['author'], $comment['comment'], $comment['publish'] , $comment['comment_date_fr']);
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
}
