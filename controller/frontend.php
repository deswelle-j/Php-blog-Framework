
<?php

require_once('model/PostManager.php');

require_once('model/CommentManager.php');

use \PhpBlogFramework\Blog\Model\PostManager;

use \PhpBlogFramework\Blog\Model\CommentManager;



function listPosts()
{
    $postManager = new PostManager();

    $posts = $postManager->getPosts();

    require('view/frontend/listPostsView.php');
}

function post()
{
    $postManager = new PostManager();

    $commentManager = new CommentManager();

    $post = $postManager->getPost($_GET['id']);

    $comments = $commentManager->getComments($_GET['id']);

    require('view/frontend/postView.php');
}

function addComment($postId, $author, $comment)
{
    $commentManager = new CommentManager();

    $affectedLines = $commentManager->postComment($postId, $author, $comment);

    if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le commentaire !');
    }
    else {
        header('Location: index.php?action=post&id=' . $postId);
    }
}

function edit()
{
    // 2 options : no data from the form or the data is present

    if(isset($_POST['modif']))
    {
        
        $commentManager = new CommentManager();

        $edit = $commentManager->editComment($_GET['id'],$_POST['modif']);

        header("Location:index.php?action=post&id=".$_GET['post_id']);
    }

    else
    {
        require('view/frontend/editView.php');
    }
}