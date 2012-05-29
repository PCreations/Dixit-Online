<?php
useModels(array('user'));

function index() {
	if(!isLogged()) {
		$home = ('<div class="lien">'.createLink('Connexion', 'users', 'login').'</div>');
	}
	else {
		$userID = $_SESSION[USER_MODEL][USER_PK];
		$user = getUserInfos($userID);
		$home = ('	<p>Bienvenue <b>'.createLink($user['us_pseudo'], 'users', 'account', array($userID)).'</b> !</p>
					<div class="lien">'.createLink('Se déconnecter', 'users', 'logout').'</div>');
	}
	$vars = array('home' => $home);			
	render('home', $vars);
}
