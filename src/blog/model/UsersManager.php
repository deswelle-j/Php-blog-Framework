<?php
namespace Framework\Blog\Model;

use PDO;

class UsersManager extends Manager
{
    public function userAuthentification($login)
    {
        $db = $this->dbConnect();
        $req= $db->prepare('SELECT id, email, password, role, firstname, lastname FROM users WHERE email = :login');
        $req->bindValue(':login', $login, PDO::PARAM_STR);
        $req->execute();
        $user = $req->fetchAll();
        return $user;
    }

    public function userCreation($login, $password, $firstname, $lastname)
    {
        $db = $this->dbConnect();
        $req= $db->prepare('INSERT INTO users (email, password, role, firstname, lastname)
        VALUES (:login, :password, :role, :firstname, :lastname )');
        $req->bindValue(':login', $login, PDO::PARAM_STR);
        $req->bindValue(':password', $password, PDO::PARAM_STR);
        $req->bindValue(':role', 'visitor', PDO::PARAM_STR);
        $req->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $req->bindValue(':lastname', $lastname, PDO::PARAM_STR);
        $req->execute();  
    }

    public function superUserCreation($login, $password, $firstname, $lastname , $role)
    {
        $db = $this->dbConnect();
        $req= $db->prepare('INSERT INTO users (email, password, role, firstname, lastname)
        VALUES (:login, :password, :role, :firstname, :lastname )');
        $req->bindValue(':login', $login, PDO::PARAM_STR);
        $req->bindValue(':password', $password, PDO::PARAM_STR);
        $req->bindValue(':role', $role, PDO::PARAM_STR);
        $req->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $req->bindValue(':lastname', $lastname, PDO::PARAM_STR);
        $req->execute();
    }
}
