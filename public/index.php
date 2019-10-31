<?php

require ('../vendor/autoload.php');
$loader = new \Twig\Loader\FilesystemLoader('../src/blog/view/frontend');
$twig = new \Twig\Environment($loader, [
    'debug' => true
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

require('../src/blog/controller/frontend.php');

try
{

    if(isset($_GET['action']))
    {

        if($_GET['action'] == 'listPosts')
        {
            listPosts($twig);
        }

        if($_GET['action'] == 'post')
        {
            if(isset($_GET['id']) && $_GET['id'] >= 0)
            {
                post($twig);
            }
            else
            {
                throw new Exception('Aucun identifiant de billet envoyé');
            }
        }
        if($_GET['action'] == 'addComment')
        {
            if(isset($_GET['id']) && $_GET['id'] >= 0)
            {
                if (!empty($_POST['author']) && !empty($_POST['comment']))
                {
                    addComment($_GET['id'], $_POST['author'], $_POST['comment']);
                }
                else
                {
                    throw new Exception('Tous les champs ne sont pas remplis !');
                }
            }
            else
            {
                throw new Exception('Aucun identifiant de billet envoyé');
            }
        }
        if($_GET['action'] == 'edit')
        {
            if(isset($_GET['id']) && $_GET['id'] >=0 && isset($_GET['post_id']) && $_GET['post_id'] >=0 )
            {
                edit();
            }
            else
            {
                throw new Exception('Erreur : identifiant de commentaire ou identifiant de billet non envoyé');
            }
        }
        if ($_GET['action'] == 'authentification'){
            if (isset($_SESSION['user']) && $_SESSION['user_role']){
                    authentification($twig, $_SESSION['user_role']);
            }elseif (isset($_POST['inputEmail'], $_POST['inputPassword'])){
                userConnection($twig, $_POST['inputEmail'], $_POST['inputPassword'] );
            }else{
                userConnection($twig);
            }
        }if ($_GET['action'] == 'logOut'){
            userLogOut();
        }
    }   
    else
    {
        home($twig);
    }
}
catch(Exception $e)
{
    $errorMessage = $e->getMessage();
    require('../src/blog/view/frontend/errorView.php');
}


