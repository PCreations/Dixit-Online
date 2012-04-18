<?php
/** Nom de la variable qui contient l'url */
define('GET_VAR_NAME', 'page');

/** Nom de l'action d'index des contrôleurs. C'est vers cette action que sera redirigé l'internaute si aucune action n'est spécifiée */
define('INDEX_ACTION', 'index');

/**  Url de base du site */
define('BASE_URL', 'http://localhost/mvc/');

/**  Alias de DIRECTORY_SEPARATOR */
define('DS', DIRECTORY_SEPARATOR);

/**  Défini la racine du projet */
define('ROOT', dirname(dirname(__FILE__))); 

/**  Défini le nom du répertoire où peut être stocké le dossier uploads/ par exemple ou les différentes ressources externes (non utilisé dans cette version) */
define('WEBROOT_DIR', 'www');

/**  Défini le chemin vers ce dossier */
define('WWW_ROOT', ROOT . DS . WEBROOT_DIR);

/**  Défini le chemin vers le dossier du thème */
define('THEME_PATH', ROOT . DS . 'views' . DS . 'themes' . DS . $theme);

/**  Défini le chemin vers le dossier css/ du thème */
define('CSS_DIR', BASE_URL . 'views/themes/' . $theme . '/css/');

/**  Défini le chemin vers le dossier img/ du thème */
define('IMG_DIR', BASE_URL . 'views/themes/' . $theme . '/img/');

/**  Défini le chemin vers le dossier js/ du thème */
define('JS_DIR', BASE_URL . 'views/themes/' . $theme . '/js/');

/** Défini le nom du contrôleur qui gerera les erreurs HTTP (comme l'erreur 404 par exemple) */
define('HTTP_ERR_CONTROLLER', 'errors');

/** Défini le nom de l'action à déclencher en cas d'erreur 404 */
define('HTTP_404_ACTION', 'notFound');

/** Défini le nom de la constante de message flash d'erreur **/
define('FLASH_SUCCESS',  0);

/** Défini le nom de la constante de message flash de succès **/
define('FLASH_ERROR',  1);

/** Défini le nom de la constante de message flash  d'information **/
define('FLASH_INFO', 2);