# Php-blog-Framework

## Require

 * [php 7.2](https://www.php.net/downloads.php#v7.2.27)
 * [mysql](https://dev.mysql.com/downloads/installer/)
 * [composer](https://getcomposer.org/doc/00-intro.md)
 * [apache](http://httpd.apache.org/docs/2.4/fr/install.html)

## Project Installation 

It's easy to install 
Clone the repo in your server and enter the following command :
```bash
composer update
```

To create the database your
Run the script database.sql

and create a env.php file in conf directory with 
```bash
define('HOST', ''); 
define('USER', ''); 
define('PASS', ''); 
define('DB', ''); 
define('TO_EMAIL', '');
```

and put your datebase and SMTP informations in each variable 

The page can be then be accessed with the following URL:
https://localhost/index.php

## How it works ?

After entering this command ```git clone https://github.com/deswelle-j/Php-blog-Framework.git ```, you should have a directory which 
have an other directory named src, here you can develop without problems, each modification should be
reflected into the site.

## Code Quality ?

code quality was checked by adding codeclimate to the project you can see the last analis 
[here at codeclimate](https://codeclimate.com/github/deswelle-j/Php-blog-Framework/issues?category%5B%5D=complexity&category%5B%5D=style&status%5B%5D=&status%5B%5D=open&status%5B%5D=confirmed&engine_name%5B%5D=structure&engine_name%5B%5D=duplication&engine_name%5B%5D=phpcodesniffer&language_name%5B%5D=PHP)

<a href="https://codeclimate.com/github/deswelle-j/Php-blog-Framework/maintainability"><img src="https://api.codeclimate.com/v1/badges/1244ac181c7d2bccb580/maintainability" /></a>
