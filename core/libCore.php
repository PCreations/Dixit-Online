<?php
/**
 * \brief Rend la vue demandée en y affectant les différentes variables définies dans le contrôleur et génération des liens vers les fichiers .css et .js. A utiliser depuis le \b contrôleur
 *
 * \author Pierre Criulanscy
 * \since 0.1.1
 * \param $view nom de la vue à rendre
 * \param $vars variables à faire passer à la vue sous la forme d'un tableau associatif. La clé représente le nom de la variable qui pourra être utilisée dans la vue, sa valeur correspond à la valeur de la clé dans le tableau. Si $vars = array("var1" => "toto", "var2" => "titi"); alors dans la vue seront accessibles les variables $var1 et $var2 avec comme valeur respective "toto" et "titi"
 */
function render($requestedView, $vars = array()) {
	global $currentController; //récupération de la variable qui stockera le contrôleur courant
	global $CSS_FILES; //récupération de la liste des fichiers .css
	global $JS_FILES; //récupération de la liste des fichiers .js
	global $pageTitle; //récupération du titre
	global $layout; //récupération du layout
	$messageFlash = isset($_SESSION['messageFlash']) ? $_SESSION['messageFlash'] : null; //récupération du message flash

	$jsList = '';
	$cssList = '';

	if(is_array($JS_FILES)) {
		foreach ($JS_FILES as $js) {
			$jsList .= "<script type=\"text/javascript\" src=\"" . JS_DIR . "$js\"></script>\n";
		}
	}
	else
		$jsList = "<script type=\"text/javascript\" src=\"" . JS_DIR . "$js\"></script>\n";
	if(is_array($CSS_FILES)) {
		foreach ($CSS_FILES as $css) {
			$cssList .= "<link rel=\"stylesheet\" href=\"" . CSS_DIR . "$css\" />";
		}	
	}
	else
		$cssList = "<link rel=\"stylesheet\" href=\"" . CSS_DIR . "$css\" />";
	

	/* récupération du titre de la page */
	$finalPageTitle = (isset($vars['pageTitle'])) ? $pageTitle .$vars['pageTitle'] : $pageTitle;

	/* récupération du layout */
	if (isset($vars['layout'])) {
		$finalLayout = $vars['layout'];
		unset($vars['layout']);
	}
	else
		$finalLayout = $layout;

	extract($vars);
	$pageTitle = $finalPageTitle;
	
	/* Tamporisation de sortie pour inclure la vue. Tout ce que l'on écrit avec ob_start() est "enregistré" dans un buffer. Le contenu de ce buffer (ici c'est à dire le contenu du fichier layout.php) peut-être récupéré dans une variable avec la fonction ob_get_clean() */
	ob_start();
	if (is_array($messageFlash)) {
		require_once("views/elements/{$messageFlash['msgView']}.php");
		unset($_SESSION['messageFlash']);
	}
	require_once("views/$currentController/$requestedView.php"); //on bufferise le contenu de la vue
	$contentForLayout = ob_get_clean(); //qu'on stocke dans la variable $contentForLayout
	require_once(THEME_PATH . DS . $finalLayout . '.php'); //au final le layout est affiché avec en son sein le contenu de la vue
	exit(); //stoppe l'exécution du script après le rendu
}

/**
 * \brief Ajoute un fichier .js dans le tableau global des fichiers à inclure. A utiliser depuis le \b contrôleur
 *
 * \author Pierre Criulanscy
 * \since 0.1.1
 * \param $js nom du fichier .js (avec l'extension)
 */
function addJS($js) {
	global $JS_FILES; //liste des fichiers .js

	if (is_array($js)) {
		foreach($js as $jsName) {
			if(!in_array($JS_FILES, $jsName)) {
				$JS_FILES[] = $js;
			}
		}
	}
	else {
		$JS_FILES[] = $js;
	}
}

/**
 * \brief Ajoute un fichier .css dans le tableau global des fichiers à inclure. A utiliser depuis le \b contrôleur
 *
 * \author Pierre Criulanscy
 * \since 0.1.1
 * \param $css nom du fichier .css (avec l'extension)
 */
function addCSS($css) {
	global $CSS_FILES; //liste des fichiers .css

	if (is_array($css)) {
		foreach($css as $cssName) {
			if(!in_array($CS_FILES, $cssName)) {
				$CSS_FILES[] = $css;
			}
		}
	}
	else {
		$CSS_FILES[] = $css;
	}
}


/**
 * \brief Redirige l'internaute vers l'action du contrôleur demandé. A utiliser depuis le \b contrôleur
 *
 * \author Pierre Criulanscy
 * \since 0.1.1
 * \param $controller nom du contrôleur
 * \param $action nom de l'action
 * \param $params paramètres éventuels à passer à l'action
 * \param $code code HTTP que le serveur doit renvoyer pour la page
 */
function redirect($controller, $action = INDEX_ACTION, $params = array(), $code = 200) {
	global $currentController;
	$httpCodes = array(
				100 => 'Continue', 101 => 'Switching Protocols',
				200 => 'OK', 201 => 'Created', 202 => 'Accepted',
				203 => 'Non-Authoritative Information', 204 => 'No Content',
				205 => 'Reset Content', 206 => 'Partial Content',
				300 => 'Multiple Choices', 301 => 'Moved Permanently',
				302 => 'Found', 303 => 'See Other',
				304 => 'Not Modified', 305 => 'Use Proxy', 307 => 'Temporary Redirect',
				400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required',
				403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed',
				406 => 'Not Acceptable', 407 => 'Proxy Authentication Required',
				408 => 'Request Time-out', 409 => 'Conflict', 410 => 'Gone',
				411 => 'Length Required', 412 => 'Precondition Failed',
				413 => 'Request Entity Too Large', 414 => 'Request-URI Too Large',
				415 => 'Unsupported Media Type', 416 => 'Requested range not satisfiable',
				417 => 'Expectation Failed', 500 => 'Internal Server Error',
				501 => 'Not Implemented', 502 => 'Bad Gateway',
				503 => 'Service Unavailable', 504 => 'Gateway Time-out'
			);
	/* si le code indiqué n'est pas valide en spécifie le code à 200 par défaut */
	if(!array_key_exists($code, $httpCodes)) {
		$code = '200';
	}
	header("HTTP/1.0 $code " . $httpCodes[$code]);
	$listeParams = '';

	if(isset($params)) {
		foreach($params as $param) {
			$listeParams .= "/$param";
		}
	}
	header('Location: ' . BASE_URL . $controller . '/' . $action . $listeParams);
	exit();
}

/**
 * \brief Création d'un lien au format prédéfini dans l'application (par défaut controller/action/[param1/params2]...). A utiliser depuis le \b contrôleur ou directement depuis la \b vue
 *
 * \author Pierre Criulanscy
 * \since 0.1.1
 * \param $textLink texte du lien
 * \param $controller nom du contrôleur
 * \param $action nom de l'action
 * \param $params paramètres éventuels à passer à l'action sous forme de tableau
 * \param $attrs attributs éventuels du lien (title, alt, etc.). La clé est le nom de l'attribut et sa valeur la valeur de celui-ci
 * \return le lien formaté vers l'action demandée
 */
function createLink($textLink, $controller, $action, $params = array(), $attrs = array()) {
	
	$listeParams = '';
	$link = '<a href="';

	if(isset($params)) {
		foreach($params as $param) {
			$listeParams .= "/$param";
		}
	}

	$link .= BASE_URL . $controller . '/' . $action . $listeParams. '"';

	foreach($attrs as $key => $value) {
		$link .= " $key=\"$value\"";
	}

	$link .= ">$textLink</a>";
	return ($textLink !== false) ? $link : BASE_URL . $controller . '/' . $action . $listeParams;
}


/**
 * \brief Alias de createLink(). A utiliser depuis le \b contrôleur ou directement depuis la \b vue
 *
 * \author Pierre Criulanscy
 * \since 0.1.1
 * \param $controller nom du contrôleur
 * \param $action nom de l'action
 * \param $params paramètres éventuels à passer à l'action sous forme de tableau
 * \return le lien absolu vers l'action demandée
 */
function l($textLink, $controller, $action, $params = array(), $attrs = array()) {
	return createLink($textLink, $controller, $action, $params, $attrs);
}

/**
 * \brief Envoie un message flash à la vue
 *
 * \author Pierre Criulanscy
 * \since 0.1.2
 * \param $msg contenu texte du message
 * \param $type type du message. Peut prendre les valeurs FLASH_INFO pour un message informatif, FLASH_SUCCESS pour une nofitication de succès et FLASH_ERROR pour une notification d'erreur
 * \details Le message flash est un petit message qui sera affiché juste avant l'inclusion de votre vue, très utile pour des notifications
 */
function setMessage($msg, $type = FLASH_INFOS) {
	$msgView;
	switch($type) {
		case FLASH_ERROR:
			$msgView = 'flash-error';
			break;
		case FLASH_SUCCESS:
			$msgView = 'flash-success';
			break;
		case FLASH_INFOS:
		default:
			$msgView = 'flash-infos';
			break;
	}
	$_SESSION['messageFlash'] = array('msgView' => $msgView,
					      			  'msg' => $msg);
}

/**
 * \brief Crypte une chaîne de caractère à l'aide de la clé de sécurité
 *
 * \author Pierre Criulanscy
 * \since 0.1.3
 * \param $data contenu texte du message
 * \return la chaine cryptée
 */
function encrypt($string) {
	if(!is_string($string)) {
		die(trigger_error('La fonction encrypt() ne peut crypter que des chaînes de caractères'));
	}
	return sha1(md5($string.SECURITY_KEY));
}

function debug($data, $die = false) {
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	if ($die) die();
}

function useModels($models) {
	foreach($models as $model) {
		include_once(ROOT . DS . 'models' . DS . $model . '.php');
	}
}

function isLogged() {
	return isset($_SESSION[USER_MODEL][USER_PK]);
}

function isPost() {
	return ($_SERVER['REQUEST_METHOD'] == 'POST') ? true : false;
}

function getSpecificArrayValues($array, $key) {
	$cleanedArray = array();
	foreach($array as $data) {
		$cleanedArray[] = $data[$key];
	}
	return $cleanedArray;
}

function getOneRowResult($array, $key) {
	return $array[$key];
}