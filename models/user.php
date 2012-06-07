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

function updateUser($id, $name, $lastname, $birthdate, $mail){
	global $db;
	$query = $db->prepare('UPDATE users
						SET us_name = :name,
						us_lastname = :lastname,
						us_birthdate = :birthdate,
						us_mail = :mail
						WHERE us_id= :id');
	$query->execute(array('id' => $id,
						'name'=> $name,
						'lastname' => $lastname,
						'mail' => $mail,
						'birthdate' => $birthdate));
}

function updatePwd($id, $password){
	global $db;
	$query = $db->prepare('UPDATE users
						SET us_password = :password
						WHERE us_id= :id');
	$query->execute(array('id' => $id,
						'password' => $password));
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
	$query = $db->prepare('	SELECT us_pseudo, us_name, us_lastname, us_birthdate  FROM users_friends, users
							WHERE users_friends.us_id = users.us_id AND us_friend_id = :id AND uf_status = 1
							UNION
							SELECT us_pseudo, us_name, us_lastname, us_birthdate
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
	$query = $db->prepare('	SELECT users.us_id, users.us_pseudo FROM users_friends, users
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

function deleteFriendship($fr_id, $us_id){
	global $db;
	$query = $db->prepare('DELETE FROM users_friends WHERE (us_id = :fr_id AND us_friend_id = :us_id) OR (us_id = :us_id AND us_friend_id = :fr_id)');
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

function getUsersTotalWinGames($userID) {
	global $db;
	$query = $db->prepare('SELECT COUNT(us_id) as nbWins
						FROM users_xp
						WHERE ga_position = 1
						AND us_id = ?');
	$query->execute(array($userID));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function getUsersTotalPlayedGames($userID) {
	global $db;
	$query = $db->prepare('SELECT COUNT(ga_id) as nbGames
						FROM users_xp
						WHERE us_id = ?');
	$query->execute(array($userID));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function getUserXP($userID) {
	global $db;
	$query = $db->prepare('SELECT SUM(us_xp) as xp
						FROM users_xp
						WHERE us_id = ?');
	$query->execute(array($userID));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function getUserClassement($userID) {
	global $db;

	$db->query('SET @numLine := 0');
	$query = $db->prepare('SELECT classement FROM 
						(SELECT @numLine := @numLine+1 as classement, xp, id 
							FROM
							(SELECT SUM(us_xp) as xp, users_xp.us_id as id
								FROM users_xp
								GROUP BY id
								ORDER BY xp DESC) 
							as total) 
						as playerClassement
						WHERE id = ?');
	$query->execute(array($userID));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function getDixitClassement() {
	global $db;
	$query = $db->query('SELECT SUM(us_xp) as xp, u.us_pseudo
					FROM users_xp
					NATURAL JOIN users as u
					GROUP BY u.us_id
					ORDER BY xp DESC');
	return $query->fetchAll(PDO::FETCH_ASSOC);
}