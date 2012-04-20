<?php

function getLastUserID() {
	global $db;

	return $db->lastInsertId();
}

function addUser($name, $lastName, $pseudo, $password, $mail, $birthdate) {
	global $db;

	$query = $db->prepare('INSERT INTO users(us_name, us_lastname, us_pseudo, us_password, us_mail, us_birthdate, us_signin_date, us_last_connexion)
						   VALUES(:name, :lastName, :pseudo, :password, :mail, :birthdate, NOW(), NOW())');
	if($query->execute(array(
					'name' => $name,
					'lastName' => $lastName,
					'pseudo' => $pseudo,
					'password' => $password,
					'mail' => $mail,
					'birthdate' => $birthdate))) {
		return true;
	}

	return false;
}

function checkLogin($pseudo, $password) {
	global $db;

	$query = $db->prepare('SELECT us_id, us_pseudo, us_password, us_mail
						   FROM users
						   WHERE us_pseudo = :pseudo
						   AND us_password = :password');
	$query->execute(array(
					'pseudo' => $pseudo,
					'password' => $password));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function getUserInfos($id) {
	global $db;

	$query = $db->prepare('SELECT *
						FROM users
						WHERE us_id = ?');
	$query->execute(array($id));
	return $query->fetch(PDO::FETCH_ASSOC);
}