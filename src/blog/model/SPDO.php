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
     */ 
    private $PDOInstance = null;

    /**
     * Instance de la classe SPDO
     *
     * @var SPDO
     * @access private
     * @static
     */ 
    private static $instance = null;

    /**
     * Constructeur
     *
     * @param void
     * @return void
     * @see PDO::__construct()
     * @access private
     */
    private function __construct()
    {
        $db_options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // On affiche des warnings pour les erreurs, à commenter en prod (valeur par défaut PDO::ERRMODE_SILENT)
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        );

        $this->PDOInstance = new PDO(
            'mysql:dbname='.DEFAULT_SQL_DTB.';
            host='.DEFAULT_SQL_HOST,
            DEFAULT_SQL_USER,
            DEFAULT_SQL_PASS,
            $db_options
        );    
    }

    /**
    * Crée et retourne l'objet SPDO
    *
    * @access public
    * @static
    * @param void
    * @return SPDO $instance
    */
    public static function getInstance()
    {  
        if(is_null(self::$instance))
            {
                self::$instance = new SPDO();
            }
        return self::$instance;
    }

    /**
     * Exécute une requête SQL avec PDO
     *
     * @param string $query La requête SQL
     * @return PDOStatement Retourne l'objet PDOStatement
     */
    public function query($query)
    {
        return $this->PDOInstance->query($query);
    }

    /**
     * Exécute une requête SQL avec PDO
     *
     * @param string $prepare La requête SQL
     * @return PDOStatement Retourne l'objet PDOStatement
     */
    public function prepare($prepare)
    {
        return $this->PDOInstance->prepare($prepare);
    }
}
