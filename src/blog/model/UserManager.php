<?php
namespace Framework\Blog\Model;

use Framework\Blog\Model\SPDO;
use PDO;

class UserManager
{
    public function userAuthentification($login)
    {
        $req= SPDO::getInstance()->prepare('SELECT id, email, password, role, firstname, lastname, username, status FROM users 
        WHERE email = :login AND status = 1' );
        $req->bindValue(':login', $login, PDO::PARAM_STR);
        $req->execute();
        $user = $req->fetchAll();
        return $user;
    }

    public function getUsers()
    {
        $req = SPDO::getInstance()->query(
            'SELECT id, email, users.role, firstname, lastname, status
            FROM users 
            WHERE users.role = \'editor\' 
            OR users.role = \'admin\' 
            OR users.role = \'contributor\'
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
                $reqUser['lastname'],
                $reqUser['status']
            );
            array_push($userAll, $user);
        }
        $req->closeCursor();
        return $userAll;
    }

    public function userCreation($login, $password, $firstname, $username, $lastname)
    {
        $req= SPDO::getInstance()->prepare('INSERT INTO users (email, password, role, firstname, lastname, username, status)
        VALUES (:login, :password, :role, :firstname, :lastname, :username, :status)');
        $req->bindValue(':login', $login, PDO::PARAM_STR);
        $req->bindValue(':password', $password, PDO::PARAM_STR);
        $req->bindValue(':role', 'visitor', PDO::PARAM_STR);
        $req->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $req->bindValue(':lastname', $lastname, PDO::PARAM_STR);
        $req->bindValue(':username', $username, PDO::PARAM_STR);
        $req->bindValue(':status', 1, PDO::PARAM_INT);
        $req->execute();
    }

    public function superUserCreation($login, $password, $firstname, $lastname, $username, $role)
    {
        $req= SPDO::getInstance()->prepare('INSERT INTO users (email, password, role, firstname, lastname, username, status)
        VALUES (:login, :password, :role, :firstname, :lastname, :username, :status )');
        $req->bindValue(':login', $login, PDO::PARAM_STR);
        $req->bindValue(':password', $password, PDO::PARAM_STR);
        $req->bindValue(':role', $role, PDO::PARAM_STR);
        $req->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $req->bindValue(':lastname', $lastname, PDO::PARAM_STR);
        $req->bindValue(':username', $username, PDO::PARAM_STR);
        $req->bindValue(':status', 0, PDO::PARAM_STR);
        $req->execute();
    }

    public function updateUserStatus($userId) {
        $req = SPDO::getInstance()->prepare(
            'SELECT id, status
            FROM users
            WHERE id = :id'
        );
        $req->bindValue(':id', $userId, PDO::PARAM_INT);
        $req->execute();
        $user = $req->fetch();
        $req->closeCursor();
        if ($user['status'] === '1') {
            $status = 0;
        } else {
            $status = 1;
        }
        $req = SPDO::getInstance()->prepare(
            'UPDATE users
            SET status = :status 
            WHERE id = :id'
        );
        $req->bindValue(':id', $userId, PDO::PARAM_INT);
        $req->bindValue(':status', $status, PDO::PARAM_INT);
        $req->execute();
        $req->closeCursor();
        return;
    }
}
