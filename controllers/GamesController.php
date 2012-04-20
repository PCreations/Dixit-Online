<?php

useModels(array('user', 'game'));

function index() {
	$partiesEnAttente = getWaintingGames();
	foreach($partiesEnAttente as &$partie) {
		if(isLogged()) {
			$userID = $_SESSION[USER_MODEL][USER_PK];
			if(!in_array($userID, getPlayersInGames($partie['ga_id']))) {
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
			if(in_array($userID, getPlayersInGames($gameID))) {
				setMessage('Vous êtes déjà dans cette partie', FLASH_ERROR);
				redirect('games');
			}
			if(!addPlayerInGame($gameID, $userID)) {
				setMessage('Impossible de rejoindre la partie, une erreur interne est survenue.', FLASH_ERROR);
				redirect('games');
			}
			else {
				if(checkPlayersInGame($gameID)) {
					startGame($gameID);
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
		else if(!in_array($userID, getPlayersInGames($gameID))) {
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