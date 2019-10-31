<?php

namespace Framework\Blog\Model;

use \PDO;
require_once('database.php');

class Manager
{
	protected function dbConnect()
	{
		$db_options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING // On affiche des warnings pour les erreurs, à commenter en prod (valeur par défaut PDO::ERRMODE_SILENT)
        );

		$db = new PDO(
			'mysql:host=' . HOST . ';dbname=' . DB, USER, PASS,
			$db_options);

		return $db;		
	}
}
