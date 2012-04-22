<?php

useModels(array('user', 'game'));
define('CARD_PER_PLAYER', 2); //pour tester

function index() {
	$partiesEnAttente = getWaintingGames();
	foreach($partiesEnAttente as &$partie) {
		if(isLogged()) {
			$userID = $_SESSION[USER_MODEL][USER_PK];
			if(!in_array($userID, getPlayersInGame($partie['ga_id']))) {
				$partie['action'] = createLink('rejoindre', 'games', 'joinGame', array($partie['ga_id'], $userID), array('title' => 'Rejoindre la partie'));
			}
			else{
				$partie['action'] = createLink('quitter', 'games', 'quiteGame', array($partie['ga_id'], $userID), array('title' => 'Quitter la partie'));
			}
		}
		else {
			$partie['action'] = 'Aucune action possible. ' . createLink('connectez-vous', 'users', 'login', null, array('title' => 'connectez-vous')) . ' pour rejoindre une partie';
		}
	}
	$vars = array('partiesEnAttente' => $partiesEnAttente);
	render('index', $vars);
}

function joinGame($gameID, $userID) {
	if(!isLogged()) {
		setMessage('Vous devez être connecté pour rejoindre une partie', FLASH_ERROR);
		redirect('games');
	}
	else {
		if(checkPlayersInGame($gameID)) {
			if(in_array($userID, getPlayersInGame($gameID))) {
				setMessage('Vous êtes déjà dans cette partie', FLASH_ERROR);
				redirect('games');
			}
			if(!addPlayerInGame($gameID, $userID)) {
				setMessage('Impossible de rejoindre la partie, une erreur interne est survenue.', FLASH_ERROR);
				redirect('games');
			}
			else {
				if(checkPlayersInGame($gameID)) {
					_startGame($gameID);
				}
				setMessage('Vous avez rejoint la partie', FLASH_SUCCESS);
				redirect('users', 'account');
			}
		}
		else {
			setMessage('Impossible de rejoindre la partie, le nombre maximum de joueurs à été atteint.', FLASH_ERROR);
			redirect('games');
		}
	}
}

function quiteGame($gameID, $userID) {
	if(!isLogged()) {
		setMessage('Vous devez être connecté pour quitter une partie', FLASH_ERROR);
		redirect('games');
	}
	else {
		if($_SESSION[USER_MODEL][USER_PK] != $userID) {
			setMessage('Vous ne pouvez pas faire quitter un autre joueur que vous', FLASH_ERROR);
			redirect('games');
		}
		else if(!in_array($userID, getPlayersInGame($gameID))) {
			setMessage('Ce joueur ne joue pas dans cette partie', FLASH_ERROR);
			redirect('games');
		}
		else {
			if(removePlayerFromGame($gameID, $userID)) {
				setMessage('Vous avez bien quitté la partie', FLASH_SUCCESS);
				redirect('games');
			}
			else {
				setMessage('Impossible de quitter la partie, une erreur interne est survenue', FLASH_ERROR);
				redirect('games');
			}
		}
	}
}

function _startGame($gameID) {
	//Récupération du deck associé au type de la partie
	$deck = getDeck($gameID);

	//Récupération des joueurs dans le jeu
	$playersIDS = getPlayersInGame($gameID);

	//Distribution des cartes
	$hands = _dealCards($deck, CARD_PER_PLAYER, count($playersIDS));

	$i=0;
	foreach($playersIDS as $player) {
		foreach($hands[$i] as $hand) {
			saveHand($gameID, $player['us_id'], $hand['ca_id']);
		}
		$i++;
	}

	//Sauvegarde de la pioche
	foreach($deck as $card) {
		savePick($gameID, $card['ca_id']);
	}

	//Définition d'un ordre de jeu
	shuffle($playersIDS);
	foreach($playersIDS as $key => $player) {
		definePlayPosition($gameID, $player['us_id'], $key+1);
	}

}

function _dealCards(&$deck, $nbCards, $nbPlayers) {
	if($nbPlayers * $nbCards > count($deck)) {
		trigger_error('Erreur : Pas assez de cartes dans le deck');
		die();
	}
	$hands = array();
	shuffle($deck);

	for($i=0; $i<$nbPlayers; $i++) {
		$hands[] = array_slice($deck, $i*$nbCards, $nbCards);
	}

	$deck = array_slice($deck, $nbPlayers*$nbCards, $nbPlayers*$nbCards);

	return $hands;
}

function play($gameID) {

}
