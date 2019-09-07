<?php

require ('../vendor/autoload.php');

require('../src/blog/controller/frontend.php');

try
{

    if(isset($_GET['action']))
    {

        if($_GET['action'] == 'listPosts')
        {
            listPosts();
        }

        if($_GET['action'] == 'post')
        {
            if(isset($_GET['id']) && $_GET['id'] >= 0)
            {
                post();
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
    }

    else
    {
        listPosts();
    }

}
catch(Exception $e)
{
    $errorMessage = $e->getMessage();
    
    require('view/errorView.php');
}


