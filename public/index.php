<?php
require_once('../conf/database.php');
require('../vendor/autoload.php');

use Framework\Blog\Controller\Frontend;

session_start();
$loader = new \Twig\Loader\FilesystemLoader('../src/blog/view/frontend');
$twig = new \Twig\Environment($loader, [
    'debug' => true
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

$frontend =  new Frontend();

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
        if ($_GET['action'] == 'edit') {
            if (isset($_GET['id']) && $_GET['id'] >=0 && isset($_GET['post_id']) && $_GET['post_id'] >=0) {
                $frontend->edit();
            } else {
                throw new Exception('Erreur : identifiant de commentaire ou identifiant de billet non envoyé');
            }
        }
        if ($_GET['action'] == 'authentification') {
            if (isset($_SESSION['user']) && $_SESSION['user_role']) {
                $frontend->authentification($twig, $_SESSION['user_role']);
            } elseif (isset($_POST['inputEmail'], $_POST['inputPassword'])) {
                $frontend->userConnection($twig, $_POST['inputEmail'], $_POST['inputPassword']);
            } else {
                $frontend->userConnection($twig);
            }
        }
        if ($_GET['action'] == 'logOut') {
            $frontend->userLogOut();
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
        if ($_GET['action'] == 'createsuperuser') {
            if (isset(
                $_POST['inputEmail'],
                $_POST['inputPassword'],
                $_POST['inputFirstname'],
                $_POST['inputLastname'],
                $_POST['inputRole']
            )) {
                    $frontend->superUserCreation(
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
    } else {
        $frontend->home($twig);
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    require('../src/blog/view/frontend/errorView.php');
}
