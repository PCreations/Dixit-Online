<?php

function getGameInfos($gameID, $fields = array('*')) {
	global $db;

	$fields = implode(',', $fields);

	$query = $db->prepare('SELECT ' . $fields . '
						FROM games
						WHERE ga_id = ?');
	$query->execute(array($gameID));

	return $query->fetch(PDO::FETCH_ASSOC);
}

function getWaintingGames() {
	global $db;

	$result = $db->query('SELECT ga.ga_id, ga.ga_name, gt.gt_name, gt.gt_nb_players, COUNT(pl.us_id) as nbPlayersInGame
						FROM games as ga
						LEFT JOIN game_types as gt
						ON gt.gt_id = ga.gt_id
						LEFT JOIN plays as pl
						ON pl.ga_id = ga.ga_id
						HAVING (nbPlayersInGame < gt.gt_nb_players)');
	return $result->fetchAll(PDO::FETCH_ASSOC);
}

function addPlayerInGame($gameID, $userID) {
	global $db;

	$query = $db->prepare('INSERT INTO plays(us_id, ga_id)
						VALUES(:userID, :gameID)');
	try {
		$query->execute(array('userID' => $userID,
						  	 'gameID' => $gameID));
	}
	catch(Exception $e) {
		return false;
	}

	return true;
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
						WHERE ga_id = ?
						ORDER BY pl_position');
	$query->execute(array($gameID));
	$result = $query->fetchAll(PDO::FETCH_ASSOC);
	$result = (!$result) ? array() : $result;
	return $result;
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

	$query = $db->prepare('SELECT COUNT(plays.us_id) as nbPlayersInGame, game_types.gt_nb_players as nbPlayersMax
						FROM plays
						INNER JOIN games
						ON games.ga_id = plays.ga_id
						INNER JOIN game_types
						ON games.gt_id = game_types.gt_id
						WHERE games.ga_id = ?');
	$query->execute(array($gameID));
	$result = $query->fetch(PDO::FETCH_ASSOC);
	return $result['nbPlayersInGame'] < $result['nbPlayersMax'];
}

function getDeck($gameID) {
	global $db;

	$query = $db->prepare('SELECT cards.ca_id
						FROM cards
						INNER JOIN deck
						ON deck.ca_id = cards.ca_id
						INNER JOIN game_types as gt
						ON gt.gt_id = deck.gt_id
						INNER JOIN games
						ON games.gt_id = gt.gt_id 
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

function saveHand($gameID, $userID, $cardID) {
	global $db;

	$query = $db->prepare('INSERT INTO hands(ca_id, us_id, ga_id)
						VALUES(:cardID, :userID, :gameID)');
	$query->execute(array('cardID' => $cardID,
						'userID' => $userID,
						'gameID' => $gameID));
	$query->closeCursor();
}

function savePick($gameID, $cardID) {
	global $db;

	$query = $db->prepare('INSERT INTO pick(ga_id, ca_id)
						VALUES(:gameID, :cardID)');
	$query->execute(array('cardID' => $cardID,
						'gameID' => $gameID));
	$query->closeCursor();
}

function addTurn($gameID, $userID) {
	global $db;

	$query = $db->prepare('INSERT INTO turns(ga_id, us_id, tu_date_start)
						VALUES(:gameID, :userID, NOW())');
	$query->execute(array('gameID' => $gameID,
						'userID' => $userID));
	$query->closeCursor();
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