<?php

useModels(array('user'));

function register() {
	if(isset($_POST['register'])) {
		extract($_POST);
		
		/* validation du formulaire */
		if(encrypt($us_password) != encrypt($passConfirm)) {
			setMessage('Les mots de passe ne coïncident pas', FLASH_ERROR);
			render('register-form', $_POST);
		}
		else {
			$us_password = encrypt($us_password);
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
				setMessage('Erreur d\'authentification. Le mot de passe et le pseudo ne coïncident pas', FLASH_ERROR);
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
	render('account');
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