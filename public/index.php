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

$isConnected = new \Twig\TwigFunction('isConnected', function () {
    if (isset($_SESSION['username'])){
        return true;
    }
    return false;
});

$twig->addFunction($isConnected);
$isGranted= new \Twig\TwigFunction('isGranted', function () {
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' ) {
        return true;
    }
    return false;
});
$twig->addFunction($isGranted);
$isDisplayComments= new \Twig\TwigFunction('isDisplayComments', function () {
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'contributor') {
        return true;
    }
    return false;
});
$twig->addFunction($isDisplayComments);
if (isset($_SESSION['username'])) {
    $twig->addGlobal('session', $_SESSION['username']);
    $twig->addGlobal('user', $_SESSION['user']);
}
$twig->addExtension(new \Twig\Extension\DebugExtension());

$frontend =  new Frontend();
$backend =  new Backend();

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(6));
}

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
            if (isset($_GET['token']) && $_GET['token'] == $_SESSION['token']) {
                $backend->editPost($twig, $_GET['id']);
            } else {
                throw new Exception('Erreur : identifiant de token non envoyé');
            } 
        }
        if ($_GET['action'] == 'save-post') {
            if (isset(
                $_POST['inputTitle'],
                $_POST['inputKicker'],
                $_POST['inputContent'],
                $_POST['token']
                ) && $_POST['token'] == $_SESSION['token']
                ) {
                $backend->savePost($twig, $_POST['inputTitle'], $_POST['inputKicker'], $_POST['inputContent'], $_GET['id']);
            } else {
                throw new Exception('Erreur : champs de billet ou identifiant de billet non envoyé');
            }
        }
        if ($_GET['action'] == 'publish-post') {
            if (isset($_GET['id'], $_GET['token']) && $_GET['id'] >=0 && $_GET['token'] == $_SESSION['token']) {
                $backend->publishPost($twig, $_GET['id']);
            } else {
                throw new Exception('Erreur : identifiant identifiant de billet ou token non envoyé');
            } 
        }
        if ($_GET['action'] == 'delete-post') {
            if (isset($_GET['id'], $_GET['token']) && $_GET['id'] >=0 && $_GET['token'] == $_SESSION['token']) {
                $backend->deletePost($twig, $_GET['id']);
            } else {
                throw new Exception('Erreur : identifiant identifiant de billet ou token non envoyé');
            }
        }
        if ($_GET['action'] == 'publish-comment') {
            if (isset($_GET['id'], $_GET['token']) && $_GET['id'] >=0 && $_GET['token'] == $_SESSION['token']) {
                $backend->publishComment($twig, $_GET['id']);
            } else {
                throw new Exception('Erreur : identifiant identifiant de billet ou token non envoyé');
            } 
        }
        if ($_GET['action'] == 'authentification') {
            if (isset($_SESSION['user']) && $_SESSION['user_role']) {
                $backend->listPost($twig, $_SESSION['user_role']);
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
                $_POST['inputUsername'],
                $_POST['inputLastname']
            )) {
                    $frontend->userCreation(
                        $twig,
                        $_POST['inputEmail'],
                        $_POST['inputPassword'],
                        $_POST['inputFirstname'],
                        $_POST['inputUsername'],
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
                $_POST['inputUsername'],
                $_POST['inputRole']
            )) {
                    $backend->superUserCreation(
                        $twig,
                        $_POST['inputEmail'],
                        $_POST['inputPassword'],
                        $_POST['inputFirstname'],
                        $_POST['inputLastname'],
                        $_POST['inputUsername'],
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
