<?php
use Framework\Blog\Model\PostManager;
use Framework\Blog\Model\CommentManager;

function listPosts($twig)
{
    $postManager = new PostManager();
    $posts = $postManager->getPosts($twig);
    echo $twig->render('listPosts.html.twig', ['posts' => $posts ]);
}
function post($twig)
{
    $postManager = new PostManager();
    $commentManager = new CommentManager();
    $post = $postManager->getPost($_GET['id']);
    $comments = $commentManager->getComments($_GET['id']);
    echo $twig->render('postView.html.twig', [
        'post' => $post,
        'comments' => $comments
         ]);
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
function edit($twig)
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
        require('../src/blog/view/frontend/editView.php');
    }
}