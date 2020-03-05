<?php
namespace Framework\Blog\Model;

use Framework\Blog\Model\SPDO;
use PDO;

class UserManager
{
    public function userAuthentification($login)
    {
        $req= SPDO::getInstance()->prepare('SELECT id, email, password, role, firstname, lastname, username FROM users 
        WHERE email = :login');
        $req->bindValue(':login', $login, PDO::PARAM_STR);
        $req->execute();
        $user = $req->fetchAll();
        return $user;
    }

    public function getUsers()
    {
        $req = SPDO::getInstance()->query(
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

    public function userCreation($login, $password, $firstname, $username, $lastname)
    {
        $req= SPDO::getInstance()->prepare('INSERT INTO users (email, password, role, firstname, lastname, username)
        VALUES (:login, :password, :role, :firstname, :lastname, :username )');
        $req->bindValue(':login', $login, PDO::PARAM_STR);
        $req->bindValue(':password', $password, PDO::PARAM_STR);
        $req->bindValue(':role', 'visitor', PDO::PARAM_STR);
        $req->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $req->bindValue(':lastname', $lastname, PDO::PARAM_STR);
        $req->bindValue(':username', $username, PDO::PARAM_STR);
        $req->execute();
    }

    public function superUserCreation($login, $password, $firstname, $lastname, $username, $role)
    {
        $req= SPDO::getInstance()->prepare('INSERT INTO users (email, password, role, firstname, lastname, username)
        VALUES (:login, :password, :role, :firstname, :lastname, :username )');
        $req->bindValue(':login', $login, PDO::PARAM_STR);
        $req->bindValue(':password', $password, PDO::PARAM_STR);
        $req->bindValue(':role', $role, PDO::PARAM_STR);
        $req->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $req->bindValue(':lastname', $lastname, PDO::PARAM_STR);
        $req->bindValue(':username', $username, PDO::PARAM_STR);
        $req->execute();
    }
}
