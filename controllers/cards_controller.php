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

function generateDeck() {
	for($i=1; $i<=31; $i++) {
		$cardID = addCardIn($i, "$i.png");
		addCardInDeck($cardID, 4);
	}
	echo 'okok';
}

function addCardInDB() {

	//Vérification si l'utilisateur est admin

	//Si le formulaire n'a pas été posté : afficher le formulaire

	//Si le formulaire a été posté : on traite les données

		//Vérifier l'intégrité des données (image non vide, etc.)

			//Si intègre

				//On demande au modèle d'enregistrer la carte
				//On affiche un message de succès
				//On redirige

			//Si non

				//On affiche un message d'erreur
				//On réaffiche la vue en passant en paramètre les éventuels paramètres déjà indiqué dans le formulaire
	

}

function addCard($gameID, $turnID, $cardID) {
	if(!isLogged()) {
		setMessage('Vous n\'êtes pas connecté', FLASH_ERROR);
		redirect('users', 'login');
	}
	else {
		$userID = $_SESSION[USER_MODEL][USER_PK];

		if(!_isOwnedBy($cardID, $userID, $turnID)) { //Vérification si la carte appartient bien au joueur
			setMessage('Vous essayer d\'ajouter une carte qui ne vous appartient pas', FLASH_ERROR);
			redirect('games', 'play', array($gameID));
		}
		else if(isAlreadyInBoard($cardID, $turnID)) { //Vérification si la carte n'est pas déjà dans le board
			setMessage('Vous avez déjà sélectionné cette carte', FLASH_ERROR);
			redirect('games', 'play', array($gameID));
		}
		else { //Sinon tout à l'air bon on peut ajouter la carte
			_addCardInBoard($cardID, $turnID, $userID);
			//setMessage('Votre carte a bien été ajoutée', FLASH_SUCCESS);
			redirect('games', 'play', array($gameID));
		}
	}
}

function _isOwnedBy($cardID, $userID, $turnID) {
	$cardsIDs = getSpecificArrayValues(getCardsInHand($userID, $turnID), 'ca_id');
	return in_array($cardID, $cardsIDs);
}

function _addCardInBoard($cardID, $turnID, $userID) {
	if(updatePlayedTurn($cardID, $userID, $turnID)) {
		addCardInBoard($cardID, $turnID);
	}
	else {
		trigger_error('Impossible de supprimer la carte de la main');
		die();
	}
}

function addStorytellerCard() {
	if(!isPost()) {
		trigger_error('Vous n\'avez pas accès à cette page', FLASH_ERROR);
		die();
	}
	if(!isLogged()) {
		setMessage('Vous n\'êtes pas connecté');
		redirect('users', 'login');
	}
	else {
		if(!empty($_POST['comment']) && isset($_POST['cardID'])) {
			extract($_POST);
			addTurnComment($turnID, $comment);
			updatePlayedTurn($cardID, $_SESSION[USER_MODEL][USER_PK], $turnID);
			addCardInBoard($cardID, $turnID);
			//setMessage('Votre carte a bien été ajoutée.', FLASH_SUCCESS);
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

function vote() {
	if(!isPost()) {
		trigger_error('Vous n\'avez pas accès à cette page');
		die();
	}
	if(!isLogged()) {
		setMessage('Vous n\'êtes pas connecté', FLASH_ERROR);
		redirect('users', 'login');
	}
	else {
		if(!isset($_POST['cardID'])) {
			setMessage('Vous devez sélectionner une carte', FLASH_ERROR);
			redirect('games', 'play', array($_POST['gameID']));
		}
		else if($_POST['cardID'] == getOneRowResult(getPlayerCardInBoard($_POST['turnID'], $_SESSION[USER_MODEL][USER_PK]), 'ca_id')) {
			setMessage('Vous ne pouvez pas voter pour votre propre carte (cardID = '.$_POST['cardID'].'<br /> Carte choisie id = ' . getOneRowResult(getPlayerCardInBoard($_POST['turnID'], $_SESSION[USER_MODEL][USER_PK]), 'ca_id'), FLASH_ERROR);
			redirect('games', 'play', array($_POST['gameID']));
		}
		else {
			extract($_POST);
			//setMessage('Votre vote a bien été pris en compte', FLASH_SUCCESS);
			addGameVote($_SESSION[USER_MODEL][USER_PK], $cardID, $turnID);
			redirect('games', 'play', array($_POST['gameID']));
		}
	}

}