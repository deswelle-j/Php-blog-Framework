<?php
namespace Framework\Blog\Model;

use PDO;

class UserManager extends Manager
{
    public function userAuthentification($login)
    {
        $db = $this->dbConnect();
        $req= $db->prepare('SELECT id, email, password, role, firstname, lastname, username FROM users WHERE email = :login');
        $req->bindValue(':login', $login, PDO::PARAM_STR);
        $req->execute();
        $user = $req->fetchAll();
        return $user;
    }

    public function getUsers()
    {
        $db = $this->dbConnect();
        $req = $db->query(
            'SELECT id, email, role, firstname, lastname 
            FROM users 
            ORDER BY email 
            DESC 
            '
        );
        $req->execute();
        $usersReq = $req->fetchAll();
        $userAll = [];
        foreach ($usersReq as $reqUser) {
            $user  = new User(
                $reqUser['id'],
                $reqUser['email'],
                $reqUser['role'],
                $reqUser['firstname'],
                $reqUser['lastname']
            );
            array_push($userAll, $user);
        }
        $req->closeCursor();
        return $userAll;
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

    public function superUserCreation($login, $password, $firstname, $lastname, $role)
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
