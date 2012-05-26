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

function getUserInfos($id, $fields = array('*')) {
	global $db;
	$fields = implode(',', $fields);

	$query = $db->prepare('SELECT '.$fields.' 
						FROM users
						WHERE us_id = ?');
	$query->execute(array($id));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function updateUser($id, $name, $lastname, $mail){
	global $db;
	$query = $db->prepare('UPDATE users 
						SET us_name = :name,
						us_lastname =  :lastname,
						us_mail = : mail
						WHERE us_id = :id');
	$query->execute(array('id' => $id,
						'name'=> $name,
						'lastname' => $lastname,
						'mail' => $mail));
}
function countFriends($id){
	global $db;
	$query = $db->prepare('	SELECT COUNT(*) AS nbFriends
							FROM users_friends
							WHERE us_id = :id');
	$query->execute(array('id' => $id));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function getFriends($id){
	global $db;
	$query = $db->prepare('	SELECT us_pseudo 
							FROM users, users_friends 
							WHERE users_friends.use_us_id = users.us_id AND users_friends.us_id = :id');
	$query->execute(array('id' => $id));
	return $query->fetchAll(PDO::FETCH_ASSOC);
}
function addFriend($fr_id, $us_id){
	global $db;
	$query = $db->query('INSERT INTO users_friends VALUES ( \'$us_id\', \'$fr_id\', CURRENT_DATE() , \'0\')');
}

function approchSearchUser($login){
	global $db;
	$query = $db->prepare("	SELECT us_pseudo, us_name, us_lastname
							FROM users 
							WHERE us_pseudo 
							LIKE '%$login%' ");				
	$query->execute(array($login));
	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function exactSearchUser($login){
	global $db;
	$query = $db->prepare("	SELECT us_id
							FROM users 
							WHERE us_pseudo = :login ");				
	$query->execute(array('login' => $login));
	return $query->fetch(PDO::FETCH_ASSOC);
}