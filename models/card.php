<?php

function getCardsInBoard($gameID) {
	global $db;

	$query = $db->prepare('SELECT boards.ca_id
						FROM boards
						INNER JOIN turns
						ON turns.tu_id = boards.tu_id
						INNER JOIN games
						ON games.ga_id = turns.ga_id
						WHERE games.ga_id = ?');
	$query->execute(array($gameID));
	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getCardsInHand($userID, $gameID) {
	global $db;

	$query = $db->prepare('SELECT c.ca_id, c.ca_name, c.ca_image
						FROM cards as c
						INNER JOIN hands as h
						ON h.ca_id = c.ca_id
						WHERE h.us_id = :userID
						AND h.ga_id = :gameID');
	$query->execute(array('userID' => $userID,
						'gameID' => $gameID));

	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function isAlreadyInBoard($cardID, $turnID) {
	global $db;

	$query = $db->prepare('SELECT ca_id
						FROM boards
						WHERE ca_id = :cardID
						AND tu_id = :turnID');
	$query->execute(array('cardID' => $cardID,
						'turnID' => $turnID));
	return is_array($query->fetch(PDO::FETCH_ASSOC));
}

function removeCardFromHand($cardID, $userID, $gameID) {
	global $db;

	$query = $db->prepare('DELETE FROM hands
						WHERE ca_id = :cardID
						AND us_id = :userID
						AND ga_id = :gameID');
	return $query->execute(array('cardID' => $cardID,
						'userID' => $userID,
						'gameID' => $gameID));
}

function addCardInBoard($cardID, $turnID) {
	global $db;

	$query = $db->prepare('INSERT INTO boards(ca_id, tu_id)
						VALUES(:cardID, :turnID)');
	$query->execute(array('cardID' => $cardID,
						'turnID' => $turnID));
}

