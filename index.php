<?php
/**
 * Thème global de l'application
 */
$theme = 'default';

/**
 * Nom du layout par défaut
 */
$layout = 'layout';
/**
 * Contrôleur courant utiliser pour inclure les vues correspondantes
 */
$currentController;

$referer;

/**
 * Titre par défaut des pages. Peut-être surchargé par $pageTitlePrefix et $finalPageTitle
 */ 
$pageTitle = 'Dixit Online'; 

/**
 * Tableau contenant la liste des fichiers .js à inclure
 */
$JS_FILES = array('jquery-1.7.2.min.js', 'jquery-ui-1.8.20.custom.min.js', 'script.js');

/**
 * Tableau contenant la liste des fichiers .css à inclure
 */
$CSS_FILES = array('style_home.css', 'style_users.css', 'style_games.css', 'style_visitFriend.css');

define('AVATAR_WIDTH', 120);
require_once('core/core.php');


/**********************/
// INCLURE ICI TOUTES LES PROCEDURES A EFFECTUER AVANT LE ROUTING (Comme par exemple vérifier les droits d'acces et eventuellement rediriger vers la page de login)
/**********************/

//routing
dispatch();

?>