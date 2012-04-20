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

function addPlayersInGames($gameID, $userID) {
	global $db;

	$db->prepare('INSERT INTO plays(us_id, ga_id)
				VALUES(:userID, :gameID)');
	$result = $db->execute(array('userID' => $userID,
								'gameID' => $gameID));
	return $result->fetchAll(PDO::FETCH_ASSOC);
}

function getPlayersInGames($gameID) {
	global $db;

	$db->prepare('SELECT us_id
				FROM plays
				WHERE ga_id = ?');
	$result = $db->execute($gameID);

	return $result->fetchAll(PDO::FETCH_ASSOC);
}