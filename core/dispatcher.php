<?php

/**
 * \brief Défini le contrôleur et l'action à appeler. A utiliser uniquement dans la page \b index.php à la racine de votre projet
 *
 * \author Pierre Criulanscy
 * \since 0.1.1
 * \param $defaultController Contrôleur par défaut pour rediriger la page d'accueil vers autre chose que l'action home du contrôleur Pages
 * \param $defaultAction Action par défaut du contrôleur par défaut à appeler dans le cas cité ci-dessus
 * \param $rewrite Booléen qui permet de spécifier si l'url doit être réécrite (i.e une redirection s'effectue) dans l'url ou non. Dans le cas d'un appel d'un contrôleur par défaut, il est intéressant de spécifier ce paramètre à true pour des soucis de référencement
 *
 * \details Par défaut si l'utilisation est sur la page d'accueil du site (par exemple www.monsite.com) alors la fonction renvoie l'utilisateur directement à l'action "home" du contrôleur Pages qui est le contrôleur qui gère les pages statiques. Si vous souhaitez appelé l'action d'un contrôleur personnalisé afin d'avoir une page d'accueil dynamique, spécifiez alors les paramètres nécessaires. Exemple : Vous souhaitez que l'adresse www.monsite.com corresponde à l'action "index" du contrôleur "News" pour que la page d'accueil soit une liste des news par exemple, alors appelé la fonction dispatch dans index.php avec les paramètres suivant : dispatch('news', 'index', false); Mettre le paramètre à false permet d'avoir comme url de la page "www.monsite.com", si vous le spécifier à true en accedant à www.monsite.com l'utilisateur serait redirigé vers www.monsite.com/news/.
 */
function dispatch($defaultController = null, $defaultAction = INDEX_ACTION, $rewrite = false) {
	if(isset($_GET[GET_VAR_NAME]) && !empty($_GET[GET_VAR_NAME])) {
		$url = $_GET[GET_VAR_NAME];
	}
	else {
		if ($defaultController != null) {
			if($rewrite)
				redirect($defaultController, $defaultAction);
			else
				$url = $defaultController . '/' . $defaultAction;
		}
		else {
			$url = "pages/home";
		}
		
	}

	parseURL($url);
}


/**
 * \brief Parse une url en extrayant le contrôleur, l'action et les paramètres demandés. Mets à jour les fichiers .css et .js associés à l'action du contrôleur demandé. Cette fonction est appelée directement depuis la fonction dispatch(). Ne pas l'appeler en dehors.
 *
 * \author Pierre Criulanscy
 * \since 0.1.1
 * \param $url Contient les informations sur l'url, soit ici une chaine de caractère de la forme "controller/action/[params1/params2/...]" [] = facultatif
 */
function parseURL($url) {
	global $currentController; //récupération de la variable qui stockera le contrôleur ocurant
	global $currentAction;
	global $currentParams;
	global $CSS_FILES; //récupération de la liste des fichiers .css
	global $JS_FILES; //récupération de la liste des fichiers .js
	global $pageTitle; //récupération du titre

	
	/* suppression des '/' en fin d'url et extraction sous forme de tableau des différents éléments */
	$url = rtrim($url, '/');
	$params = explode('/', $url);

	/* récupération du nom du contrôleur courant */
	$currentController = $params[0]; 

	/* si aucune action n'est spécifiée on redirige vers l'action index du contrôleur */
	$action = (isset($params[1])) ? $params[1] : INDEX_ACTION; 

	/* les paramètres sont créer à partir du sous tableau commencant à l'indice 2 du tableau $params (i.e si $params = array
								[0] => 'controller',
								[1] => 'action',
								[2] => 'params1',
								[3] => 'params2') 
								alors après array_slice($params, 2) $params vaudra juste :
								array  
								[0] => 'params1',
								[1] => 'params2')*/
	$params = array_slice($params, 2); 

	/* Inclusion du controller demandé, si la fonction renvoie false c'est que le contrôleur n'existe pas, on redirige alors vers le contrôleur qui gère les erreurs 404 */
	$controllerFilePath = getControllerFilePath($currentController);
	if($controllerFilePath === false)
		redirect(HTTP_ERR_CONTROLLER, HTTP_404_ACTION, array(), 404);
	else
		require_once($controllerFilePath);

	/* reset des fichiers CSS et JS pour qu'ils correspondent à ceux par défaut du controller */
	if (isset($defaultCSS)) {
		if(is_array($defaultCSS)) {
			$CSS_FILES = $defaultCSS;
		}
	}
	if (isset($defaultJS)) {
		if(is_array($defaultJS)) {
			$JS_FILES = $defaultJS;
		}
	}

	/* Si on a spécifié un prefix pour le titre des pages dans le controller alors on le défini comme titre */
	if(isset($pageTitlePrefix))
		$pageTitle = $pageTitlePrefix;

	/* vérification de l'existence et de la visibilité de l'action, si elle n'existe pas on redirige vers l'action par défaut du contrôleur (la fonction index par configuration de base) */
	if (!function_exists($action) || (strpos($action, '_') === 0 && !isPost())) {
		$action = INDEX_ACTION;
		if (!function_exists($action))
			redirect(HTTP_ERROR_CONTROLLER, HTTP_404_ACTION, array(), 404);
	}
	
	/* Appel de l'action demandée pour le controller demandé */
	call_user_func_array($action, $params);
}

/**
 * \brief Recupère le chemin vers le contrôleur demandé. Fonction appelée uniquement par parseURL(), ne pas appeler en dehors.
 *
 * \author Pierre Criulanscy
 * \since 0.1.1
 * \param $controller nom du contrôleur
 * \return mixed Le chemin vers le contrôleur si celui-ci existe sinon renvoie false
 *
 * \details Si le contrôleur existe bien (on teste ici l'existence du fichier) on returne le chemin vers celui-ci sinon on défini le chemin comme celui pointant vers le contrôleur qui gère les erreurs 404
 */
function getControllerFilePath($controller) {
	$file = 'controllers/' . $controller . '_controller.php';
	if(is_file($file))
		return $file;
	else
		return false;
}