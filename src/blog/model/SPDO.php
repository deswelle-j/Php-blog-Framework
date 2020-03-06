<?php

namespace Framework\Blog\Model;

use \PDO;
 
class SPDO
{
    /**
     * Instance de la classe PDO
     *
     * @var PDO
     * @access private
     * @static
     */ 
    private static $instance = null;

    /**
    * Crée et retourne l'objet PDO
    *
    * @access public
    * @static
    * @param void
    * @return PDO $instance
    */
    public static function getInstance()
    {  
        if(is_null(self::$instance))
            {
                $db_options = array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    // On affiche des warnings pour les erreurs, à commenter en prod (valeur par défaut PDO::ERRMODE_SILENT)
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
                );
                self::$instance = new PDO(
                    'mysql:dbname='.DEFAULT_SQL_DTB.';
                    host='.DEFAULT_SQL_HOST,
                    DEFAULT_SQL_USER,
                    DEFAULT_SQL_PASS,
                    $db_options
                );
            }
        return self::$instance;
    }
}
