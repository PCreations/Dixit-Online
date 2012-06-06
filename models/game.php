<?php

function insertNewGame($deck, $userID, $nom, $pwd, $joueurs, $points)
{
	global $db;

	$query = $db->prepare('INSERT INTO games(de_id, us_id, ga_name, ga_creation_date, ga_password, ga_nb_players, ga_points_limit)
						VALUES(:de_id, :us_id, :ga_name, NOW(), :ga_password, :ga_nb_players, :ga_points_limit)');
	return $query->execute(array('de_id'=>$deck, 'us_id'=>$userID, 'ga_name'=>$nom, 'ga_password'=>$pwd, 'ga_nb_players'=>$joueurs, 
'ga_points_limit'=>$points));
}

function getLastGameID() {
	global $db;
	return $db->lastInsertId();
}

function filterGames($name, $nbplayers, $nbpoints, $deck, $public) {
	global $db;
	
	$prequery='';
	if (!empty($name)){
		$prequery.=' AND ga.ga_name LIKE :name';
	}
	if (!empty($nbplayers)){
		$prequery.=' AND ga.ga_nb_players = :nbplayers';
	}
	if (!empty($nbpoints)){
		$prequery.=' AND ga.ga_points_limit <= :nbpoints';
	}
	if ($deck!=-1){
		$prequery.=' AND de.de_id = :deck';
	}
	if ($public=='on'){
		$prequery.=' AND ga.ga_password=""';
	}
	$query = $db->prepare('SELECT us.us_name, ga.ga_id, ga.ga_name, ga.us_id, ga.ga_creation_date, ga.ga_password, ga.ga_nb_players, ga.ga_points_limit, de.de_name, de.de_id, total.nbTotalPlayer as nbPlayersInGame
						FROM games as ga
						INNER JOIN decks as de
						ON de.de_id = ga.de_id
						INNER JOIN users as us
						ON ga.us_id = us.us_id
						LEFT JOIN total_players_in_game as total
						ON total.ga_id = ga.ga_id
						WHERE (total.nbTotalPlayer < ga.ga_nb_players)
						'.$prequery);
	if (!empty($name)){
		$name = '%'.$name.'%';
		$query->bindParam(':name', $name, PDO::PARAM_STR);
	}
	if (!empty($nbplayers)){
		$query->bindParam(':nbplayers', $nbplayers, PDO::PARAM_INT);
	}
	if (!empty($nbpoints)){
		$query->bindParam(':nbpoints', $nbpoints, PDO::PARAM_INT);
	}
	if ($deck!=-1){
		$query->bindParam(':deck', $deck, PDO::PARAM_INT);
	}

	$query->execute();
						
	return $query->fetchAll(PDO::FETCH_ASSOC);
}


function getGameInfos($gameID, $fields = array('*')) {
	global $db;

	$fields = implode(',', $fields);

	$query = $db->prepare('SELECT ' . $fields . '
						FROM games
						WHERE ga_id = ?');
	$query->execute(array($gameID));
	
	return $query->fetch(PDO::FETCH_ASSOC);
}

function getWaitingGames() {
	global $db;


	$result = $db->query('SELECT us.us_name, ga.ga_id, ga.ga_name, ga.us_id, ga.ga_creation_date, ga.ga_password, ga.ga_nb_players, ga.ga_points_limit, de.de_name, de.de_id, total.nbTotalPlayer as nbPlayersInGame

						FROM games as ga
						INNER JOIN decks as de
						ON de.de_id = ga.de_id
						INNER JOIN users as us
						ON ga.us_id = us.us_id
						LEFT JOIN total_players_in_game as total
						ON total.ga_id = ga.ga_id
						WHERE total.nbTotalPlayer < ga.ga_nb_players');
	return $result->fetchAll(PDO::FETCH_ASSOC);
}

/* Récupère la liste des parties en cours */
function getGameInProgressForUser($userID) {
	global $db;

	$query = $db->prepare('SELECT p.ga_id, g.ga_name
						FROM plays as p
						INNER JOIN games as g
						ON g.ga_id = p.ga_id				
						WHERE p.us_id = :userID
						AND p.ga_id NOT IN (SELECT ga_id FROM users_xp WHERE us_id = :userID)');
	$query->execute(array('userID' => $userID));
	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function addPlayerInGame($gameID, $userID) {
	global $db;

	$query = $db->prepare('INSERT INTO plays(us_id, ga_id, pl_last_action)
						VALUES(:userID, :gameID, :time)');
	return $query->execute(array('userID' => $userID,
						  	 'gameID' => $gameID,
						  	 'time' => time()));
}

function setPlayerStatus($gameID, $userID, $status) {
	global $db;

	$query = $db->prepare('UPDATE plays
						SET pl_status = :status
						WHERE ga_id = :gameID
						AND us_id = :userID');
	$query->execute(array('status' => $status,
						'gameID' => $gameID,
						'userID' => $userID));
}

function getPlayerStatus($gameID, $userID) {
	global $db;

	$query = $db->prepare('SELECT pl_status
						FROM plays
						WHERE ga_id = :gameID
						AND us_id = :userID');
	$query->execute(array('gameID' => $gameID,
						'userID' => $userID));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function getUserLastActionTime($gameID, $userID) {
	global $db;

	$query = $db->prepare('SELECT pl_last_action
						FROM plays
						WHERE ga_id = :gameID
						AND us_id = :userID');
	$query->execute(array('gameID' => $gameID,
						'userID' => $userID));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function updatePlayerActionTime($gameID, $userID) {
	global $db;

	$query = $db->prepare('UPDATE plays
						SET pl_last_action = :time
						WHERE ga_id = :gameID
						AND us_id = :userID');
	$query->execute(array('gameID' => $gameID,
						'userID' => $userID,
						'time' => time()));
}

function removePlayerFromGame($gameID, $userID) {
	global $db;

	$query = $db->prepare('DELETE FROM plays
						WHERE ga_id = :gameID
						AND us_id = :userID');
	return $query->execute(array('gameID' => $gameID,
						'userID' => $userID));
}

function getPlayersInGame($gameID) {
	global $db;

	$query = $db->prepare('SELECT us_id
						FROM plays
						NATURAL JOIN users
						WHERE ga_id = ?
						ORDER BY pl_position');
	$query->execute(array($gameID));
	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function countPlayersInGame($gameID) {
	global $db;

	$query = $db->prepare('SELECT nbTotalPlayer
						FROM total_players_in_game
						WHERE ga_id = ?');
	$query->execute(array($gameID));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function isInGame($gameID, $userID) {
	global $db;

	$query = $db->prepare('SELECT us_id
						FROM plays
						WHERE ga_id = :gameID
						AND us_id = :userID');
	$query->execute(array('gameID' => $gameID,
						'userID' => $userID));
	return is_array($query->fetch(PDO::FETCH_ASSOC));
}

/**
 * Vérifie que la partie n'est pas complète. Retourne true s'il reste des places et false sinon
**/
function checkPlayersInGame($gameID) {
	global $db;

	$query = $db->prepare('SELECT total.nbTotalPlayer as nbPlayersInGame, games.ga_nb_players as nbPlayersMax
						FROM total_players_in_game as total
						INNER JOIN games
						ON games.ga_id = total.ga_id
						WHERE games.ga_id = ?');
	$query->execute(array($gameID));
	$result = $query->fetch(PDO::FETCH_ASSOC);
	return $result['nbPlayersInGame'] < $result['nbPlayersMax'];
}

function getDeck($gameID) {
	global $db;

	$query = $db->prepare('SELECT cards.ca_id
						FROM cards
						INNER JOIN cards_decks
						ON cards_decks.ca_id = cards.ca_id
						INNER JOIN games
						ON games.de_id = cards_decks.de_id
						WHERE games.ga_id = ?');
	$query->execute(array($gameID));
	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function definePlayPosition($gameID, $userID, $position) {
	global $db;

	$query = $db->prepare('UPDATE plays
						SET pl_position = :position
						WHERE ga_id = :gameID
						AND us_id = :userID');
	$query->execute(array('position' => $position,
						'gameID' => $gameID,
						'userID' => $userID));
	$query->closeCursor();
}

function addTurn($gameID, $userID) {
	global $db;

	$query = $db->prepare('INSERT INTO turns(ga_id, us_id, tu_date_start)
						VALUES(:gameID, :userID, NOW())');
	$query->execute(array('gameID' => $gameID,
						'userID' => $userID));

	return $db->lastInsertId();
}

function getCurrentGameTurn($gameID) {
	global $db;

	$query = $db->prepare('SELECT *
						FROM turns
						WHERE ga_id = ?
						ORDER BY tu_id DESC
						LIMIT 1');
	$query->execute(array($gameID));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function getTurnInfos($turnID, $fields = array('*')) {
	global $db;

	$fields = implode(',', $fields);

	$query = $db->prepare('SELECT '.$fields.'
						FROM turns
						WHERE tu_id = ?');
	$query->execute(array($turnID));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function getTurnsVote($turnID) {
	global $db;

	$query = $db->prepare('SELECT *
						FROM votes
						WHERE tu_id = ?');
	$query->execute(array($turnID));
	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getGameUserPosition($gameID, $userID) {
	global $db;

	$query = $db->prepare('SELECT pl_position
						FROM plays
						WHERE ga_id = :gameID
						AND us_id = :userID');
	$query->execute(array('gameID' => $gameID,
						'userID' => $userID));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function getUserByPosition($gameID, $position) {
	global $db;

	$query = $db->prepare('SELECT us_id
						FROM plays
						WHERE ga_id = :gameID
						AND pl_position = :position');
	$query->execute(array('gameID' => $gameID,
						'position' => $position));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function getOrderUserInfos($gameID, $fields = array('*')) {
	global $db;

	$fields = implode(',', $fields);

	$query = $db->prepare('SELECT '.$fields.'
						FROM users as u
						INNER JOIN plays as pl
						ON u.us_id = pl.us_id
						INNER JOIN games as g
						ON g.ga_id = pl.ga_id
						WHERE pl.ga_id = ?
						ORDER BY pl.pl_position');
	$query->execute(array($gameID));

	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getUsersPointsInTurn($userID, $turnID) {
	global $db;

	$query = $db->prepare('SELECT points
						FROM earned_points
						WHERE us_id = :userID
						AND tu_id = :turnID');
	$query->execute(array('userID' => $userID,
						'turnID' => $turnID));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function getTotalUserPointsInGame($gameID, $userID) {
	global $db;

	$query = $db->prepare('SELECT SUM(points) as nbPoints
						FROM earned_points as ep
						INNER JOIN turns as t
						ON t.tu_id = ep.tu_id
						INNER JOIN games as g
						ON g.ga_id = t.ga_id
						WHERE g.ga_id = :gameID
						AND ep.us_id = :userID');
	$query->execute(array('gameID' => $gameID, 
						'userID' => $userID));

	return $query->fetch(PDO::FETCH_ASSOC);
}

function getClassement($gameID) {
	global $db;

	$query = $db->prepare('SELECT SUM(points) as points, us.us_pseudo, us.us_id
						FROM earned_points as ep
						NATURAL JOIN users as us
						INNER JOIN turns as tu
						ON tu.tu_id = ep.tu_id
						INNER JOIN games as ga
						ON ga.ga_id = tu.ga_id
						WHERE ga.ga_id = ?
						GROUP BY ep.us_id
						ORDER BY points DESC');
	$query->execute(array($gameID));

	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function addPoints($userID, $turnID, $points) {
	global $db;

	$query = $db->prepare('INSERT into earned_points(us_id, tu_id, points)
						VALUES(:userID, :turnID, :points)');
	$query->execute(array('userID' => $userID,
						'turnID' => $turnID,
						'points' => $points));
}

function getTotalDealedPointsInTurn($turnID) {
	global $db;

	$query = $db->prepare('SELECT SUM(points) as total
						FROM earned_points
						WHERE tu_id = ?');
	$query->execute(array($turnID));

	return $query->fetch(PDO::FETCH_ASSOC);
}

function addXPtoPlayer($userID, $xp, $position, $gameID) {
	global $db;

	$query = $db->prepare('INSERT INTO users_xp(us_id,us_xp,ga_id,ga_position)
						VALUES(:userID, :xp, :gameID, :position)');
	try {
		$query->execute(array('userID' => $userID,
							'xp' => $xp,
							'gameID' => $gameID,
							'position' => $position));
	}
	catch(Exception $e) {
		/* do something */
	}
}

function lockTables() {
	global $db;

	$query = $db->query('LOCK TABLE turns WRITE, pick WRITE, plays WRITE, plays as pl WRITE, games as ga WRITE, games as g WRITE, users as u WRITE, hands WRITE');
	$query->closeCursor();
}

function unlockTables() {
	global $db;

	$query = $db->query('UNLOCK tables');
	$query->closeCursor();
}