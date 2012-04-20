<?php

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
						WHERE ga_id = ?');
	$query->execute(array($gameID));
	$result = $query->fetchAll(PDO::FETCH_ASSOC);
	$result = (!$result) ? array() : $result;
	return $result;
}

/**
 * Vérifie que la partie n'est pas complète
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

	$query = $db->prepare('SELECT cards.ca_id, cards.ca_name, cards.ca_image
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