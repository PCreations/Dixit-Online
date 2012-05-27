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
	$query = $db->prepare('	SELECT COUNT(us_friend_id) AS nbFriends 
							FROM users_friends 
							WHERE (us_id = :id OR us_friend_id = :id) 
							AND uf_status = 1');
	$query->execute(array('id' => $id));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function getReelFriends($id){
	global $db;
	$query = $db->prepare('	SELECT us_pseudo FROM users_friends, users
							WHERE users_friends.us_id = users.us_id AND us_friend_id = :id AND uf_status = 1
							UNION
							SELECT us_pseudo
							FROM users_friends, users
							WHERE us_friend_id = users.us_id AND users_friends.us_id = :id  AND uf_status = 1');
	$query->execute(array('id' => $id));
	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getAskedFriends($id){
	global $db;
	$query = $db->prepare('	SELECT us_pseudo
							FROM users_friends, users
							WHERE us_friend_id = users.us_id AND users_friends.us_id = :id  AND uf_status = 0');
	$query->execute(array('id' => $id));
	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getFriendsWhoAskedMe($id){
	global $db;
	$query = $db->prepare('	SELECT us_pseudo FROM users_friends, users
							WHERE users_friends.us_id = users.us_id AND us_friend_id = :id AND uf_status = 0');
	$query->execute(array('id' => $id));
	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function invitFriend($fr_id, $us_id){
	global $db;
	$query = $db->prepare('INSERT INTO users_friends(us_id, us_friend_id, uf_date, uf_status) VALUES ( :us_id, :fr_id, NOW(), 0)');
	$query->execute(array('fr_id' => $fr_id,
							'us_id' => $us_id));
}

function acceptFriend($fr_id, $us_id){
	global $db;
	$query = $db->prepare('UPDATE users_friends
						SET uf_status = 1
						WHERE us_id = :fr_id AND us_friend_id = :us_id');
	$query->execute(array('fr_id' => $fr_id,
							'us_id' => $us_id));
}

function refuseFriend($fr_id, $us_id){
	global $db;
	$query = $db->prepare('DELETE FROM users_friends WHERE us_id = :fr_id AND us_friend_id = :us_id');
	$query->execute(array('fr_id' => $fr_id,
							'us_id' => $us_id));
}

function approchSearchUser($login){
	global $db;
	$query = $db->prepare("	SELECT us_id, us_pseudo, us_name, us_lastname
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