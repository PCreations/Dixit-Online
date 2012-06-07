<?php

useModels(array('user', 'card', 'deck'));
define('SEND_INVITATION', 2);
define('ACCEPT_INVITATION', 1);
define('DECLINE_INVITATION', 0);

function check_date(){
	switch ($_POST['month']){
		case 1: case 3: case 5: case 7: case 8: case 10: case 12:
			return (0 < $_POST['day'] && 0 < $_POST['year']);
		case 4: case 6: case 9: case 11:
			return(0 < $_POST['day'] && $_POST['day'] <= 30 && 0 < $_POST['year']);
		case 2:
			return(0 < $_POST['day'] && 0 < $_POST['year'] && ($_POST['day'] <= 28 || ($_POST['day'] == 29 && ((($_POST['year'] % 4) == 0 && ($_POST['year'] % 100) != 0) || ($_POST['year'] % 400) == 0))));
	}
}

function register() {
	if(isset($_POST['register'])) {
		extract($_POST);
		
		/* validation du formulaire */
		if(encrypt($us_password) != encrypt($passConfirm)) {
			setMessage('Les mots de passe ne coïncident pas', FLASH_ERROR);
			render('register-form', $_POST);
		}
		else if (check_date()==0) {
			setMessage("La date de naissance n'est pas valide", FLASH_ERROR);
			render('register-form', $_POST);
		}
		else {
			$us_password = encrypt($us_password);
			
			$us_birthdate=$_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'];
			if(addUser($us_name,
					   $us_lastname,
					   $us_pseudo,
					   $us_password,
					   $us_mail,
					   $us_birthdate)) {
				$_SESSION[USER_MODEL][USER_PK] = getLastUserID();
				$_SESSION[USER_MODEL]['us_pseudo'] = $us_pseudo;
				$_SESSION[USER_MODEL]['us_mail'] = $us_mail;
				$_SESSION[USER_MODEL]['us_password'] = $us_password;
				setMessage('Enregistrement réussi. Vous êtes maintenant connecté', FLASH_SUCCESS);
				redirect('users', 'account', array($_SESSION[USER_MODEL][USER_PK]));
			} 
			else {
				setMessage('Erreur lors de l\'enregistrement. Merci de réessayer.', FLASH_ERROR);
				render('register-form', $_POST);
			}
		}
	}
	else {
		render('register-form');
	}
}

function login() {
	if(!isLogged()) {
		if(isset($_POST['login'])) {
			extract($_POST);
			$userInfos = checkLogin($pseudo, encrypt($password));
			if(!empty($userInfos)) {
				$_SESSION[USER_MODEL] = $userInfos;
				setMessage('Vous êtes maintenant connecté.', FLASH_SUCCESS);
				/*if(isset($_SESSION['redirectBack']))
					redirect($_SESSION['redirectBack']['controller'], $_SESSION['redirectBack']['controller'], $_SESSION['redirectBack']['params']);
				else*/
				redirect('users', 'account', array($_SESSION[USER_MODEL][USER_PK]));
			}
			else {
				setMessage('Erreur d\'authentification. Veuillez réessayer.', FLASH_ERROR);
				render('login', $_POST);
			}
		}
		else {
			render('login');
		}
	}
	else {
		setMessage('Vous êtes déjà connecté !', FLASH_INFOS);
		redirect('users', 'account', array($_SESSION[USER_MODEL][USER_PK]));
	}
}

function account($id = null) {
	global $JS_FILES;
	global $CSS_FILES;
	$JS_FILES[] = 'script_users.js';
	$JS_FILES[] = 'flexcroll.js';
	$CSS_FILES[] = 'flexcrollstyles.css';
	$userID = $_SESSION[USER_MODEL][USER_PK];
	
	/* Récupération des decks de l'utilisateur */
	$userDecks = getUserDecks($userID, array('de_id', 'de_name'));
	if($userDecks != NULL){
		foreach($userDecks as &$deck){
			$deck['de_status'] = getOneRowResult(getDeckInfos($deck['de_id'], array('de_status')), 'de_status');
			if($deck['de_status'] == 0){
				$deck['de_status']= 'Privé';
			}else{
				$deck['de_status']= 'Public';
			}
			$deck['nbCards'] = getOneRowResult(nbCartes($deck['de_id']), 'nbCartes');
			$deck['cardsInDeckInfo'] = getCardsInDeckInfo($deck['de_id']);
		}
		
	}else{
		$result = "";
	}
	
	/* Récupération des cartes ajoutées par l'utilisateur */
	$userCards = getUserCards($userID);

	if(isset($_POST['update'])) { //Formulaire de changement de données
			extract($_POST);
			updateUser($userID, $name, $lastname, $birthdate, $mail);
			setMessage('Vos changements ont été pris en compte', FLASH_SUCCESS);
			redirect('users', 'account', array( $userID));
	}
	
	if(isset($_POST['updatePwd'])) { //Formulaire de changement de mot de passe
			extract($_POST);
			$userInfos = checkLogin($pseudo, encrypt($oldPass));
			debug($userInfos);
			if(encrypt($password) != encrypt($passConfirm)) {
				setMessage('Les mots de passe ne coïncident pas', FLASH_ERROR);
				render('account');
			}
			$password = encrypt($password);
			updatePwd($id, $password);
			setMessage('Vos changements ont été pris en compte', FLASH_SUCCESS);
			redirect('users', 'account', array( $userID));
	}
	
	if(isset($_POST['deck'])) { //Formulaire de création d'un deck
			extract($_POST);
			if(!empty($deck_name)){
				if(!isset($public)){
					$public='0';
				}	
			addDeck($userID, $deck_name, $public);
			setMessage('Votre nouveau deck est prêt', FLASH_SUCCESS);
			redirect('users', 'account', array( $userID));
			}
			else{
				setMessage('Vous devez donner un nom à votre deck.', FLASH_ERROR);
				redirect('users', 'account', array( $userID));
			}
			
	}
	if(isset($_POST['card'])) { //Formulaire d'upload d'image
			extract($_POST);
			print_r($_FILES);
			/*Erreurs*/
			if($_FILES['userfile']['error'] == '1'){
				setMessage('Votre image dépasse le poids autorisée', FLASH_ERROR);
			}
			if($_FILES['userfile']['error'] == '3' OR $_FILES['userfile']['error'] == '4' ){
				setMessage('Le fichier n\'a pas été téléchargé, ou alors seulement partiellement', FLASH_ERROR);
			}
			if($_FILES['userfile']['error'] == '5' ){
				setMessage('Aucun fichier n\'a été téléchargé', FLASH_ERROR);
			}
			if($_FILES['userfile']['error'] == '6'){
				setMessage('Erreur interne', FLASH_ERROR);
			}
			/*Vérifications*/
			if(isset($_FILES['userfile'])){
				if(!empty($card_name)){
					if ($_FILES['userfile']['error'] == '2'){
						setMessage('Votre image dépasse le poids autorisée', FLASH_ERROR);
					}
					$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );//Extensions
					$extension_upload = strtolower(  substr(  strrchr($_FILES['userfile']['name'], '.')  ,1)  );
					if ( in_array($extension_upload,$extensions_valides) ){
						if ( !empty($_FILES['userfile']['tmp_name'] )){
							$image_sizes = getimagesize($_FILES['userfile']['tmp_name']);//Taille
							if (($image_sizes[0] == '329') AND ($image_sizes[1] == '500')){
								$path ='C:/wamp/www/dixit/views/themes/default/img/cards/'.basename($_FILES['userfile']['name']);
								if (move_uploaded_file($_FILES['userfile']['tmp_name'],$path)){//Alors Upload
									addCardIn($userID, $card_name, $_FILES['userfile']['name']);
									setMessage('Votre image a été correctement ajoutée', FLASH_SUCCESS);
								}else{
									setMessage('Le transfert à échoué. Veuillez réessayer.', FLASH_ERROR);
								}
							}else{
								setMessage('Votre image n\'a pas les bonnes dimensions', FLASH_ERROR);
							}	
						}else{
							setMessage('Votre image dépasse le poids autorisée', FLASH_ERROR);
						}
					}else{
						setMessage('Votre image n\'a pas un format valide', FLASH_ERROR);
					}
				}else{
					setMessage('Veuillez donner un nom à votre image', FLASH_ERROR);
				}
			}else{
				setMessage('Veuillez sélectionner un fichier', FLASH_ERROR);
			}
	}
	$user = getUserInfos($id);
	$reelFriends = getReelFriends($id);
	$askedFriends = getAskedFriends($id);
	$invitations = getFriendsWhoAskedMe($id);
	if (is_array($invitations)){ //gérer les invitations venant d'autres utilisateurs
		foreach($invitations as &$invitation){
			setMessage('Vous avez reçu une demande d\'amis', FLASH_INFOS);
			$invitation['accept'] = createLink('Accepter', 'users', 'newFriend', array($invitation['us_id'], ACCEPT_INVITATION));
			$invitation['refuse'] = createLink('Refuser', 'users', 'newFriend', array($invitation['us_id'], DECLINE_INVITATION));
		}
	}
	$nbFriends = countFriends($id);
	$vars = array(	'user' => $user,
					'reelFriends' => $reelFriends,
					'askedFriends' => $askedFriends,
					'invitations' => $invitations,
					'nbFriends' => $nbFriends,
					'userDecks' => $userDecks,
					);
	render('account', $vars);
	$JS_FILES = array_pop($JS_FILES);
	$JS_FILES = array_pop($JS_FILES);
	$CSS_FILES = array_pop($CSS_FILES);
}

function research(){
	 //Formulaire de recherche d'ami
		$userID = $_SESSION[USER_MODEL][USER_PK];
			extract($_POST);
			$results = approchSearchUser($loginSearch);
			// affichage d'un message "pas de résultats"
			if( empty($results))
			{
				echo ('<h3 style="text-align:center; margin:10px 0;">Pas de résultats pour cette recherche</h3>');
			}else{
				$user = getUserInfos($userID);
				$reelfriends = getReelFriends($userID);
				$askedfriends = getSpecificArrayValues(getAskedFriends($userID), 'us_pseudo');
				$whoAskedMe = getSpecificArrayValues(getFriendsWhoAskedMe($userID), 'us_pseudo');
				foreach($results as &$result){
					if(!in_array($result['us_pseudo'], $reelfriends)) {
						if(!in_array($result['us_pseudo'], $askedfriends)) {
							if(!in_array($result['us_pseudo'], $whoAskedMe)) {
								if($result['us_pseudo'] != $user['us_pseudo']){
									$result['action'] = createLink('Envoyer une demande', 'users', 'newFriend', array($result['us_id'], '2')).'</br>'.createLink('Voir', 'users', 'visitFriend', array($result['us_pseudo']));
								}
								else{
									$result['action'] = 'C\'est vous !';
								}
							}
							else{
								$result['action'] = 'Cette personne vous a demandé en amis</br>'.createLink('Voir', 'users', 'visitFriend', array($result['us_pseudo']));
							}
						}
						else{
							$result['action'] = 'Vous avez déjà invité cette personne</br>'.createLink('Voir', 'users', 'visitFriend', array($result['us_pseudo']));
						}
					}
					else{
						$result['action'] = 'Vous êtes déjà amis</br>'.createLink('Voir', 'users', 'visitFriend', array($result['us_pseudo']));
					}
					}
							foreach($results as $result){
									echo ('<div class="result"
									<p><strong>'.$result['us_pseudo'].'</strong>
									&nbsp;&nbsp;<i>'.$result['us_name'].'
									'.$result['us_lastname'].'</i></br>
									'.$result['action'].'</p></div>');
								}
				}
			
}

function displayDeck(){
	$userID = $_SESSION[USER_MODEL][USER_PK];
	extract($_POST);
	
	$cards = getCardsInDeckInfo($de_id);
	if(!empty($cards)){
		foreach($cards as $card){
				echo('<img class="image_carte" src="'.IMG_DIR.'cards/'.$card['ca_image'].'" alt="'.$card['ca_name'].'"/>');
		}
	}else{
		echo('Ce deck ne contient aucune carte');
	}
}

function visitFriend($login){
	global $JS_FILES;
	global $CSS_FILES;
	$JS_FILES[] = 'script_users.js';
	$JS_FILES[] = 'flexcroll.js';
	$CSS_FILES[] = 'flexcrollstyles.css';
	
	$userID = $_SESSION[USER_MODEL][USER_PK];
	$user = getUserInfos($userID);
	if(isLogged()) {
		//Infos sur le profil que l'on visite
		$id = getOneRowResult(exactSearchUser($login), 'us_id');
		$friend = getUserInfos($id, array('us_name', 'us_lastname', 'us_birthdate', 'us_pseudo'));
		$reelfriends = getReelFriends($id);
		$usersReelfriends = getReelFriends($userID);
		$nbCommuns = '0';
		
		//On cherche les amis en commun
		if(!empty($usersReelfriends)){
			foreach($usersReelfriends as $usersFriend){
				if(in_array($usersFriend['us_pseudo'], $reelfriends)) {
					$communs = $usersReelfriends;
					$nbCommuns += '1';

				}
				if(empty($communs)){
					$communs = "";
					$nbCommuns = 'aucun';
				}
			}
		}else{
					$communs = "";
					$nbCommuns = 'aucun';
		}

		//On récupère les decks de l'utilisateur
		$decks = getUserPublicDecks($id, array('de_id', 'de_name'));
		if(!empty($decks)){
			foreach($decks as &$deck){
				$deck['cardsInDeckInfo'] = getCardsInDeckInfo($deck['de_id']);
			}
		}else{
			$decks = "";
		}
		//On cherche si l'utilisateur est ami avec le profil visité
		if(in_array($login, $usersReelfriends)) {
			$result='<img src="'.IMG_DIR.'notif_success.png" alt="#"/><p>Amis</p>'.createLink('Retour', 'users', 'account', array($user['us_id'])).'
		</div>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Actions</strong></p>
		<div class="action">
			<img src="'.IMG_DIR.'message.png" alt="#"/>
			<p>Envoyer un message</p>
			<img src="'.IMG_DIR.'notif_erreur.png" alt="#"/>
			<p>'.createLink('Retirer de vos amis', 'users', 'deleteFriend', array($id)).'</p>
		</div>';
			}else{
				$result='<img src="'.IMG_DIR.'notif_erreur.png" alt="#"/><p>Vous n\'êtes pas amis</p>'.createLink('Retour', 'users', 'account', array($user['us_id'])).'
		</div>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Actions</strong></p>
		<div class="action">
			<img src="'.IMG_DIR.'message.png" alt="#"/>
			<p>Envoyer un message</p>
			<img src="'.IMG_DIR.'notif_success.png" alt="#"/>
			<p>'.createLink('Ajouter à vos amis', 'users', 'newFriend', array($id, SEND_INVITATION)).'</p>
		</div>';
			}

		
		$vars = array('friend' => $friend,
						'user' => $user,
						'reelfriends' => $reelfriends,
						'decks' => $decks,
						'result' => $result,
						'nbCommuns' => $nbCommuns,
						'communs' => $communs);
		render('visit', $vars);
	}else{
		setMessage('Vous n\'êtes pas connecté !', FLASH_ERROR);
		redirect('users', 'account', array($userID));
	}
	
	$JS_FILES = array_pop($JS_FILES);
	$JS_FILES = array_pop($JS_FILES);
	$CSS_FILES = array_pop($CSS_FILES);
}
function deleteFriend($fr_id){
	$userID = $_SESSION[USER_MODEL][USER_PK];
		if(isLogged()) {
		deleteFriendship($userID, $fr_id);
		setMessage('Vos changements ont été pris en compte', FLASH_SUCCESS);
		}else{
		setMessage('Vous n\'êtes pas connecté !', FLASH_ERROR);
	}
	redirect('users', 'account', array($userID));
}

function newFriend($fr_id, $action){
	$userID = $_SESSION[USER_MODEL][USER_PK];
	
	if(isLogged()) {
		switch($action) {
			case SEND_INVITATION:
				invitFriend($fr_id, $userID);
				setMessage('Votre invitation a bien été envoyée', FLASH_SUCCESS);
				break;
			case ACCEPT_INVITATION:
				acceptFriend($fr_id, $userID);
				setMessage('Vous avez accepté l\'invitation', FLASH_SUCCESS);
				break;
			case DECLINE_INVITATION:
				refuseFriend($fr_id, $userID);
				setMessage('Vos changements ont été pris en compte', FLASH_SUCCESS);
				break;
		}
	}
	else{
		setMessage('Vous n\'êtes pas connecté !', FLASH_ERROR);
	}
	redirect('users', 'account', array($userID));
}

function logout() {
	if(isLogged()) {
		unset($_SESSION[USER_MODEL]);
		setMessage('Vous êtes maintenant déconnecté', FLASH_SUCCESS);
	}
	else {
		setMessage('Vous n\'êtes pas connecté !', FLASH_ERROR);
	}
	
	redirect('users', 'login');
}

function _getUserDecks($userID) {
	$userDecks = getUserDecks($userID);

	/* Pour chaque deck récupérer les cartes */
	foreach($userDecks as &$deck) {
		$deck['cards'] = getSpecificArrayValues(getCardsInDeck($deck['de_id']), 'ca_id');
	}

	return $userDecks;
}