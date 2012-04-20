<?php

include_once(ROOT . DS . 'models' . DS . 'user.php');

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
				$_SESSION['users']['us_pseudo'] = $us_pseudo;
				$_SESSION['users']['us_mail'] = $us_mail;
				$_SESSION['users']['us_password'] = $us_password;
				$_SESSION['users']['us_id'] = getLastUserID();
				setMessage('Enregistrement réussi. Vous êtes maintenant connecté', FLASH_SUCCESS);
				redirect('users', 'account', array($_SESSION['users']['us_id']));
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
	if(!isset($_SESSION['users']['us_id'])) {
		if(isset($_POST['login'])) {
			extract($_POST);
			$userInfos = checkLogin($pseudo, encrypt($password));
			if(!empty($userInfos)) {
				$_SESSION['users'] = $userInfos;
				setMessage('Vous êtes maintenant connecté.', FLASH_SUCCESS);
				redirect('users', 'account', array($_SESSION['users']['us_id']));
			}
			else {
				setMessage('Erreur d\'authentification. Le mot de passe et le pseudo ne coîncident pas', FLASH_ERROR);
				render('login', $_POST);
			}
		}
		else {
			render('login');
		}
	}
	else {
		setMessage('Vous êtes déjà connecté !', FLASH_INFOS);
		redirect('users', 'account', array($_SESSION['users']['us_id']));
	}
}

function account($id = null) {
	render('account');
}

function logout($id = null) {
	if(isset($_SESSION['users']['us_id'])) {
		unset($_SESSION['users']);
		setMessage('Vous êtes maintenant déconnecté', FLASH_SUCCESS);
	}
	else {
		setMessage('Vous n\'êtes pas connecté !', FLASH_ERROR);
	}
	redirect('users', 'login');
}