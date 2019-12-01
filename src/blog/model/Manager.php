<?php

namespace Framework\Blog\Model;

use \PDO;

class Manager
{
    protected function dbConnect()
    {
        $db_options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // On affiche des warnings pour les erreurs, à commenter en prod (valeur par défaut PDO::ERRMODE_SILENT)
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
        );
        $db = new PDO(
            'mysql:host=' . HOST . ';
            dbname=' . DB,
            USER,
            PASS,
            $db_options
        );
        return $db;		
    }
}
