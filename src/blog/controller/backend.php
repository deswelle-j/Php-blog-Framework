<?php

namespace Framework\Blog\Controller;

use Framework\Blog\Model\PostManager;
use Framework\Blog\Model\CommentManager;
use Framework\Blog\Model\UserManager;
use Framework\Blog\Utils\Session;

class Backend
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


    public function listPost($twig, $role)
    {
        if ($role !== 'visitor') {
            $posts = null;
            if ($role === 'editor' || $role === 'admin') {
                $posts = $this->_postManager->getAdminPosts();
            } else {
                $posts = $this->_postManager->getAdminPosts($_SESSION['user']);
            }
            $comments = $this->_commentManager->getCommentsList();
            echo $twig->render(
                '@admin/administrationView.html.twig',
                [
                    'posts' => $posts,
                    'comments' => $comments,
                    'token' => $_SESSION['token']
                ]
            );
        } else {
            header('Location: index.php');
        }
    }

    public function userLogOut()
    {
        Session::supprimeSession();
        header('Location: index.php');
    }

    public function userConnection($twig, $email = false, $password = false)
    {
        if ($email != false && $password != false) {
            $login = trim($email);
            $password =trim($password);
            if (filter_var($login, FILTER_VALIDATE_EMAIL) && !empty($password)) {
                $user = $this->_userManager->userAuthentification($login);
                $count = count($user);
                if (count($user) > 0 && password_verify($password, $user[0]['password'])) {
                    $_SESSION['user'] = $user[0]['id'];
                    $_SESSION['user_fullname'] = $user[0]['firstname'] . ' ' . $user[0]['lastname'];
                    $_SESSION['user_role'] = $user[0]['role'];
                    $_SESSION['username'] = $user[0]['username'];
                    unset($_SESSION['token']);
                    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(6));
                    $this->listPost($twig, $_SESSION['user_role']);
                } else {
                    echo $twig->render('connectionView.html.twig', [
                        'error' => 'Utilisateur non trouvé ou mot de passe incorrect'
                    ]);
                }
            } else {
                echo $twig->render('connectionView.html.twig', [
                    'error' => 'Information de connexion incorrectes'
                ]);
            }
        } else {
            echo $twig->render('connectionView.html.twig');
        }
    }

    public function superUserList($twig, $role)
    {
        if ($role === 'admin') {
            $users = $this->_userManager->getUsers();
            echo $twig->render('@admin/adminSuperuserView.html.twig', ['users' => $users]);
        }
    }

    public function superUserCreation(
        $twig,
        $email = false,
        $password = false,
        $firstname = false,
        $lastname = false,
        $username = false,
        $role = false
    ) {
        if ($email != false
            && $password != false
            && $firstname != false
            && $lastname != false
            && $role != false
            && $username != false
        ) {
            $login = trim($email);
            $password =trim($password);
            $firstname = trim($firstname);
            $lastname = trim($lastname);
            $username = trim($username);
            if (filter_var($login, FILTER_VALIDATE_EMAIL) && !empty($password) && !empty($firstname) &&
                !empty($lastname) && !empty($role)) {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $user = $this->_userManager->superUserCreation($login, $password, $firstname, $lastname, $username, $role);
                header('Location: Location: index.php?action=authentification');
            } else {
                throw new Exception('Information de connexion incorrectes');
            }
        } else {
            echo $twig->render('@admin/administrationView.html.twig');
        }
    }

    public function editPost($twig, $postid = false)
    {
        if ($postid !== false) {
            if ($_SESSION['user_role'] === 'contributor') {
                $author = $this->_postManager->getPostsAuthor($postid);
                if ($_SESSION['user'] !== $author['author']) {
                    echo $twig->render('@admin/adminEditPostView.html.twig', [
                        'token' => $_SESSION['token']
                    ]);
                    return;
                }
            } else {
                $post = $this->_postManager->getPost($postid);
                echo $twig->render('@admin/adminEditPostView.html.twig', [
                    'post' => $post,
                    'token' => $_SESSION['token']
                ]);
            }
        } else {
            echo $twig->render('@admin/adminEditPostView.html.twig', [
                'token' => $_SESSION['token']
            ]);
        }
    }

    public function publishPost($twig, $postid)
    {
        if (isset($postid)) {
            $post = $this->_postManager->updatePulicationPost($postid);
            header("Location: index.php?action=authentification");
        }
    }

    public function publishComment($twig, $commentId)
    {
        if (isset($commentId)) {
            $comment = $this->_commentManager->updatePulicationComment($commentId);
            header("Location: index.php?action=authentification");
        }
    }

    public function savePost($twig, $title, $kicker, $content, $postid = false)
    {
        if ($postid) {
            $post = $this->_postManager->updatePost($postid, $title, $kicker, $content, $_SESSION['user']);
        } else {
            $post = $this->_postManager->insertPost($title, $kicker, $content, $_SESSION['user']);
        }
        header('Location: index.php?action=authentification');
    }

    public function deletePost($twig, $postid)
    {
        if (isset($postid)) {
            $this->_postManager->removePost($postid);
            header('Location: index.php?action=authentification');
        }
    }
}
