<?php

namespace Framework\Blog\Controller;

use Framework\Blog\Model\PostManager;
use Framework\Blog\Model\CommentManager;
use Framework\Blog\Model\UserManager;
use Framework\Blog\Utils\Session;

class Backend
{
    public function authentification($twig, $role)
    {
        if ($role !== 'visitor') {
            $postManager = new PostManager();
            $posts = null;
            if ($role === 'editor' || $role === 'admin') {
                $posts = $postManager->getAdminPosts();
            } else {
                $posts = $postManager->getAdminPosts($_SESSION['user']);
            }
            
            $commentManager = new CommentManager();
            $comments = $commentManager->getCommentsList();
            echo $twig->render(
                '@admin/administrationView.html.twig',
                [
                    'posts' => $posts,
                    'comments' => $comments
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
                $userManager = new UserManager();
                $user = $userManager->userAuthentification($login);
                $count = count($user);
                if (count($user) > 0 && password_verify($password, $user[0]['password'])) {
                    $_SESSION['user'] = $user[0]['id'];
                    $_SESSION['user_fullname'] = $user[0]['firstname'] . ' ' . $user[0]['lastname'];
                    $_SESSION['user_role'] = $user[0]['role'];
                    $_SESSION['username'] = $user[0]['username'];
                    $this->authentification($twig, $_SESSION['user_role']);
                } else {
                    throw new Exception('Utilisateur non trouvé ou mot de passe incorrect');
                }
            } else {
                throw new Exception('Information de connexion incorrectes');
            }
        } else {
            echo $twig->render('connectionView.html.twig');
        }
    }

    public function superUserList($twig, $role)
    {
        if ($role === 'admin') {
            $userManager = new UserManager();
            $users = $userManager->getUsers();
            echo $twig->render('@admin/adminSuperuserView.html.twig',
            [
                'users' => $users
            ]);
        }
    }

    public function superUserCreation($twig, $email = false, $password = false, $firstname = false, $lastname = false, $role = false)
    {
        if ($email != false && $password != false && $firstname != false && $lastname != false && $role != false) {
            $login = trim($email);
            $password =trim($password);
            $firstname = trim($firstname);
            $lastname = trim($lastname);
            if (filter_var($login, FILTER_VALIDATE_EMAIL) && !empty($password) && !empty($firstname) &&
                !empty($lastname) && !empty($role)) {
                $userManager = new UserManager();
                $password = password_hash($password, PASSWORD_DEFAULT);
                $user = $userManager->superUserCreation($login, $password, $firstname, $lastname, $role);
                header('Location: Location: index.php?action=authentification');
            } else {
                throw new Exception('Information de connexion incorrectes');
            }
        } else {
            echo $twig->render('@admin/administrationView.html.twig');
        }
    }

    public function edit($twig)
    {
        // 2 options : no data from the form or the data is present
        if (isset($_POST['modif'])) {
            $postManager = new PostManager();
            $edit = $postManager->editPost($_GET['id'], $_POST['modif']);
            header("Location:index.php?action=post&id=".$_GET['post_id']);
        } else {
            require('../src/blog/view/frontend/editView.php');
        }
    }

    public function editPost($twig, $postid)
    {
        if (isset($postid)) {
            $postManager = new PostManager();
            $post = $postManager->getPost($_GET['id']);
            echo $twig->render('@admin/adminEditPostView.html.twig', [
                'post' => $post
            ]);
        }
    }

    public function savePost($twig, $id, $title, $kicker, $content)
    {
        $postManager = new PostManager();
        $post = $postManager->updatePost($id, $title, $kicker, $content);
        header('Location: index.php?action=authentification');
    }

    public function deletePost($twig, $postid) {
        if (isset($postid)) {
            $postManager = new PostManager();
            $postManager->removePost($postid);
            header('Location: index.php?action=authentification');
        }
    }
}