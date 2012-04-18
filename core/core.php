<?php
require_once("core/config.php");
require_once("core/libCore.php");
require_once("core/dispatcher.php");
require_once("core/database.config.php");


/* Connexion à la base de données */
try
{
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$pdo_options[1002] = 'SET NAMES utf8'; //1002 = PDO::MYSQL_ATTR_INIT_COMMAND qui peut ne pas être définie selon les serveurs

	$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
}
catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}
