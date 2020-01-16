<?php
require_once('../conf/env.php');
require('../vendor/autoload.php');

use Framework\Blog\Controller\Frontend;
use Framework\Blog\Controller\Backend;

session_start();
$loader = new \Twig\Loader\FilesystemLoader('../src/blog/view/frontend');
$loader->addPath('../src/blog/view/backend', 'admin');
$twig = new \Twig\Environment($loader, [
    'debug' => true
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

$frontend =  new Frontend();
$backend =  new Backend();

try {
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'listPosts') {
            $frontend->listPosts($twig);
        }
        if ($_GET['action'] == 'post') {
            if (isset($_GET['id']) && $_GET['id'] >= 0) {
                $frontend->post($twig);
            } else {
                throw new Exception('Aucun identifiant de billet envoyé');
            }
        }
        if ($_GET['action'] == 'addComment') {
            if (isset($_GET['id']) && $_GET['id'] >= 0) {
                if (!empty($_POST['author']) && !empty($_POST['comment'])) {
                    $frontend->addComment($_GET['id'], $_POST['author'], $_POST['comment']);
                } else {
                    throw new Exception('Tous les champs ne sont pas remplis !');
                }
            } else {
                throw new Exception('Aucun identifiant de billet envoyé');
            }
        }
        if ($_GET['action'] == 'edit-post') {
            if (isset($_GET['id']) && $_GET['id'] >=0 && isset($_GET['post_id']) && $_GET['post_id'] >=0) {
                $backend->editPost();
            } else {
                throw new Exception('Erreur : identifiant de commentaire ou identifiant de billet non envoyé');
            }
        }
        if ($_GET['action'] == 'delete-post') {
            if (isset($_GET['id']) && $_GET['id'] >=0) {
                $backend->deletePost($twig, $_GET['id']);
            } else {
                throw new Exception('Erreur : identifiant de commentaire ou identifiant de billet non envoyé');
            }
        }
        if ($_GET['action'] == 'authentification') {
            if (isset($_SESSION['user']) && $_SESSION['user_role']) {
                $backend->authentification($twig, $_SESSION['user_role']);
            } elseif (isset($_POST['inputEmail'], $_POST['inputPassword'])) {
                $backend->userConnection($twig, $_POST['inputEmail'], $_POST['inputPassword']);
            } else {
                $backend->userConnection($twig);
            }
        }
        if ($_GET['action'] == 'logOut') {
            $backend->userLogOut();
        }
        if ($_GET['action'] == 'signup') {
            if (isset(
                $_POST['inputEmail'],
                $_POST['inputPassword'],
                $_POST['inputFirstname'],
                $_POST['inputLastname']
            )) {
                    $frontend->userCreation(
                        $twig,
                        $_POST['inputEmail'],
                        $_POST['inputPassword'],
                        $_POST['inputFirstname'],
                        $_POST['inputLastname']
                    );
            } else {
                $frontend->userCreation($twig);
            }
        }
        if ($_GET['action'] == 'superuserlist') {
            if (isset($_SESSION['user']) && $_SESSION['user_role']) {
                $backend->superUserList($twig, $_SESSION['user_role']);
            }
        }
        if ($_GET['action'] == 'createsuperuser') {
            if (isset(
                $_POST['inputEmail'],
                $_POST['inputPassword'],
                $_POST['inputFirstname'],
                $_POST['inputLastname'],
                $_POST['inputRole']
            )) {
                    $backend->superUserCreation(
                        $twig,
                        $_POST['inputEmail'],
                        $_POST['inputPassword'],
                        $_POST['inputFirstname'],
                        $_POST['inputLastname'],
                        $_POST['inputRole']
                    );
            } else {
                header('Location: index.php?action=authentification');
            }
        }
        if ($_GET['action'] == 'mail') {
            if (isset(
                $_POST['fname'],
                $_POST['fname'],
                $_POST['email'],
                $_POST['subject'],
                $_POST['message']
            )) {
                    $frontend->sendContactMail(
                        $twig,
                        $_POST['fname'],
                        $_POST['lname'],
                        $_POST['email'],
                        $_POST['subject'],
                        $_POST['message']
                    );
            } else {
                header('Location: index.php?action=authentification');
            }
        }
    } else {
        $frontend->home($twig);
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    require('../src/blog/view/frontend/errorView.php');
}
