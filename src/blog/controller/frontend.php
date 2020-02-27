<?php

namespace Framework\Blog\Controller;

use Framework\Blog\Model\PostManager;
use Framework\Blog\Model\CommentManager;
use Framework\Blog\Model\UserManager;
use Framework\Blog\Utils\Session;

class Frontend
{
    public function home($twig)
    {
        $postManager = new PostManager();
        $posts = $postManager->lastPosts();
        echo $twig->render('home.html.twig', ['posts' => $posts]);
    }

    public function listPosts($twig)
    {
        $postManager = new PostManager();
        $posts = $postManager->getPosts();
        echo $twig->render('listPosts.html.twig', ['posts' => $posts ]);
    }

    public function post($twig)
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

    public function addComment($postId, $author, $comment)
    {
        $commentManager = new CommentManager();
        $affectedLines = $commentManager->postComment($postId, $author, $comment);
        if ($affectedLines === false) {
            throw new Exception('Impossible d\'ajouter le commentaire !');
        } else {
            header('Location: index.php?action=post&id=' . $postId);
        }
    }

    public function userCreation($twig, $email = false, $password = false, $firstname = false, $lastname = false, $username = false)
    {
        
        if ($email != false && $password != false && $firstname != false && $lastname != false && $username != false) {
            $login = trim($email);
            $password =trim($password);
            $firstname = trim($firstname);
            $lastname = trim($lastname);
            if (filter_var($login, FILTER_VALIDATE_EMAIL) && !empty($password) && !empty($firstname) &&
                !empty($lastname)) {
                $userManager = new UserManager();
                $password = password_hash($password, PASSWORD_DEFAULT);
                $user = $userManager->userCreation($login, $password, $firstname, $lastname, $username);
                header('Location: index.php');
            } else {
                throw new Exception('Information de connexion incorrectes');
            }
        } else {
            echo $twig->render('signUpView.html.twig');
        }
    }
    public function sendContactMail(
        $twig,
        $fname = false,
        $lname = false,
        $email = false,
        $subject = false,
        $message = false
    ) {
        if ($fname !== false && $lname !== false && $email !== false && $subject !== false && $message !== false) {
            $to_email = TO_EMAIL ;
            $subject = $subject . ' From: '. $email;
            mail($to_email, $subject, $message);
            header('Location: index.php');
        } else {
            header('Location: index.php');
        }
    }
}
