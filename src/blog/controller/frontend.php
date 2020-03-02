<?php

namespace Framework\Blog\Controller;

use Framework\Blog\Model\PostManager;
use Framework\Blog\Model\CommentManager;
use Framework\Blog\Model\UserManager;
use Framework\Blog\Utils\Session;

class Frontend
{
    private $_postManager;
    private $_commentManager;
    private $_userManager;

    public function __construct()
    {
        $this->_postManager = new PostManager();
        $this->_commentManager = new CommentManager();
        $this->_userManager = new UserManager();
    }

    public function home($twig)
    {
        $posts = $this->_postManager->lastPosts();
        echo $twig->render('home.html.twig', ['posts' => $posts]);
    }

    public function listPosts($twig)
    {
        $posts = $this->_postManager->getPosts();
        echo $twig->render('listPosts.html.twig', ['posts' => $posts ]);
    }

    public function post($twig)
    {
        $post = $this->_postManager->getPost($_GET['id']);
        $comments = $this->_commentManager->getComments($_GET['id']);
        echo $twig->render('postView.html.twig', [
            'post' => $post,
            'comments' => $comments
            ]);
    }

    public function addComment($postId, $author, $comment)
    {
        $affectedLines = $this->_commentManager->postComment($postId, $author, $comment);
        if ($affectedLines === false) {
            throw new Exception('Impossible d\'ajouter le commentaire !');
        } else {
            header('Location: index.php?action=post&id=' . $postId);
        }
    }

    public function userCreation(
        $twig,
        $email = false,
        $password = false,
        $firstname = false,
        $lastname = false,
        $username = false
    ) {
        
        if ($email != false && $password != false && $firstname != false && $lastname != false && $username != false) {
            $login = trim($email);
            $password =trim($password);
            $firstname = trim($firstname);
            $lastname = trim($lastname);
            if (filter_var($login, FILTER_VALIDATE_EMAIL) && !empty($password) && !empty($firstname) &&
                !empty($lastname)) {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $user = $this->_userManager->userCreation($login, $password, $firstname, $lastname, $username);
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
