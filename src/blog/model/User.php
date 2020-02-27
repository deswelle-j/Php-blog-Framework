<?php

namespace Framework\Blog\Model;

class User
{
    protected $id;
    protected $email;
    protected $password;
    protected $role;
    protected $firstname;
    protected $lastnames;

    public function __construct($id, $email, $role, $firstname, $lastname)
    {
        $this->setId($id);
        $this->setEmail($email);
        $this->setRole($role);
        $this->setfirstname($firstname);
        $this->setLastname($lastname);
    }

    public function id()
    {
        return $this->id;
    }
    public function email()
    {
        return $this->email;
    }
    public function password()
    {
        return $this->password;
    }
    public function role()
    {
        return $this->role;
    }
    public function firstname()
    {
        return $this->firstname;
    }
    public function lastname()
    {
        return $this->lastname;
    }

    public function setId($id)
    {
        $this->id = (int) $id;
    }

    public function setEmail($email)
    {
        if (is_string($email)) {
            $this->email = $email;
        }
    }

    public function setPassword($password)
    {
        if (is_string($password)) {
            $this->password = $password;
        }
    }

    public function setRole($role)
    {
        if (is_string($role) === ('admin' || 'editor' || 'contributor')) {
            $this->role = $role;
        } else {
            $this->role = 'visitor';
        }
    }

    public function setFirstname($firstname)
    {
        if (is_string($firstname)) {
            $this->firstname = $firstname;
        }
    }
    
    public function setLastname($lastname)
    {
        if (is_string($lastname)) {
            $this->lastname = $lastname;
        }
    }
}
