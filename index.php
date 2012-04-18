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

/**
 * Titre par défaut des pages. Peut-être surchargé par $pageTitlePrefix et $finalPageTitle
 */ 
$pageTitle = 'IMAC MVC'; 

/**
 * Tableau contenant la liste des fichiers .js à inclure
 */
$JS_FILES = array();

/**
 * Tableau contenant la liste des fichiers .css à inclure
 */
$CSS_FILES = array();


require_once('core/core.php');


/**********************/
// INCLURE ICI TOUTES LES PROCEDURES A EFFECTUER AVANT LE ROUTING (Comme par exemple vérifier les droits d'acces et eventuellement rediriger vers la page de login)
/**********************/


//routing
dispatch();

