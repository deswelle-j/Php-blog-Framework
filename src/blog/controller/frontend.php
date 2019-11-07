<?php
use Framework\Blog\Model\PostManager;
use Framework\Blog\Model\CommentManager;
use Framework\Blog\Model\UsersManager;

function home($twig)
{

    echo $twig->render('home.html.twig');
}

function listPosts($twig)
{
    $postManager = new PostManager();
    $posts = $postManager->getPosts();
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

function supprimeSession() {
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

function authentification($twig, $role ){

    if ($role && $role == 'admin'){
        $postManager = new PostManager();
        $posts = $postManager->getPosts();
        $commentManager = new CommentManager();
        $comments = $commentManager->getCommentsList();
        echo $twig->render('administrationView.html.twig', 
        [
            'posts' => $posts,
            'comments' => $comments 
        ]);
    }else {
        header('Location: index.php');
    }
}

function userConnection($twig, $email = false, $password = false){
    
    if ($email != false && $password != false){
        $login = trim($email);
        $password =trim($password);
        var_dump($login);
        var_dump($password);
        if (filter_var($login, FILTER_VALIDATE_EMAIL) && !empty($password)) {
            $userManager = new UsersManager();
            $user = $userManager->userAuthentification($login);
            $count = count($user);
            if (count($user) > 0 && password_verify($password, $user[0]['password'])) {
                
                $_SESSION['user'] = $user[0]['id'];
                $_SESSION['user_fullname'] = $user[0]['firstname'] . ' ' . $user[0]['lastname'];
                $_SESSION['user_role'] = $user[0]['role'];
                authentification($twig, $_SESSION['user_role']);
            }else{
                throw new Exception('Utilisateur non trouvé ou mot de passe incorrect');
            }
        }else {
            throw new Exception('Information de connexion incorrectes');
        }
    }else{
        echo $twig->render('connectionView.html.twig');
    }
}

function userLogOut(){
    session_destroy();
    header('Location: index.php');
}

function userCreation($twig, $email = false, $password = false, $firstname = false, $lastname = false){
    
    if ($email != false && $password != false && $firstname != false && $lastname != false){
        $login = trim($email);
        $password =trim($password);
        $firstname = trim($firstname);
        $lastname = trim($lastname);
        if (filter_var($login, FILTER_VALIDATE_EMAIL) && !empty($password) && !empty($firstname) && 
            !empty($lastname)) {
            $userManager = new UsersManager();
            $password = password_hash($password, PASSWORD_DEFAULT);
            $user = $userManager->userCreation($login, $password, $firstname, $lastname);
            header('Location: index.php');
        }else {
            throw new Exception('Information de connexion incorrectes');
        }
    }else{
        echo $twig->render('signUpView.html.twig');
    }
}