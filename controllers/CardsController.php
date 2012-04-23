<?php

useModels(array('card'));

/*function chooseCard($turnID, $gameID) {
	global $referer;
	if(isPost()) {
		if(!isLogged()) {
			echo 'Erreur : vous n\'êtes pas connecté';
			return false;
		}
		addCardInBoard($cardID, $gameID);
	}
	else {
		setMessage('Vous n\'avez pas accès à cette page', FLASH_ERROR);
		redirect($referer['controller'], $referer['action'], $referer['params']);
	}
}*/

function addCard($gameID, $turnID, $cardID) {
	if(!isLogged()) {
		setMessage('Vous n\'êtes pas connecté', FLASH_ERROR);
		redirect('users', 'login');
	}
	else {
		$userID = $_SESSION[USER_MODEL][USER_PK];

		if(!_isOwnedBy($cardID, $userID, $gameID)) { //Vérification si la carte appartient bien au joueur
			setMessage('Vous essayer d\'ajouter une carte qui ne vous appartient pas', FLASH_ERROR);
			redirect('games', 'play', array($gameID));
		}
		else if(isAlreadyInBoard($cardID, $turnID)) { //Vérification si la carte n'est pas déjà dans le board
			setMessage('Vous avez déjà sélectionné cette carte', FLASH_ERROR);
			redirect('games', 'play', array($gameID));
		}
		else { //Sinon tout à l'air bon on peut ajouter la carte
			_addCardInBoard($cardID, $turnID, $gameID, $userID);
			setMessage('Votre carte a bien été ajoutée', FLASH_SUCCESS);
			redirect('games', 'play', array($gameID));
		}
	}
}

function _isOwnedBy($cardID, $userID, $gameID) {
	$cardsIDs = getSpecificArrayValues(getCardsInHand($userID, $gameID), 'ca_id');
	return in_array($cardID, $cardsIDs);
}

function _addCardInBoard($cardID, $turnID, $gameID, $userID) {
	if(changeHandCardStatus($cardID, $userID, $gameID)) {
		addCardInBoard($cardID, $turnID);
	}
	else {
		trigger_error('Impossible de supprimer la carte de la main');
		die();
	}
}

function addStorytellerCard() {
	if(!isPost()) {
		trigger_error('Vous n\'avez pas accès à cette page');
		die();
	}
	else {
		if(!empty($_POST['comment']) && isset($_POST['cardID'])) {
			extract($_POST);
			addTurnComment($turnID, $comment);
			changeHandCardStatus($cardID, $_SESSION[USER_MODEL][USER_PK], $gameID);
			addCardInBoard($cardID, $turnID);
			redirect('games', 'play', array($_POST['gameID']));
		}
		else {
			$errors;
			if(empty($_POST['comment'])) $errors .= 'Le commentaire associé à la carte ne peut pas être vide !<br />';
			if(!isset($_POST['cardID'])) $errors .= 'Vous devez sélectionner une carte';

			setMessage($errors, FLASH_ERROR);
			
			redirect('games', 'play', array($_POST['gameID']));
		}
	}
}