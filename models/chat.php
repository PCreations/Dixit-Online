<?php

function getGameMessages($gameID) {
	global $db;

	$query = $db->prepare('SELECT ch.us_id, ch.ga_id, ch.ch_text, ch.ch_date, us.us_pseudo FROM chats as ch
						INNER JOIN users as us
						ON us.us_id = ch.us_id
						WHERE ga_id = ?
						ORDER BY ch.ch_date');
	$query->execute(array($gameID));

	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function addGameMessage($gameID, $userID, $message) {
	global $db;

	$query = $db->prepare('INSERT INTO chats(us_id,ga_id,ch_text,ch_date)
						VALUES(:userID, :gameID, :message, NOW())');
	$query->execute(array('userID' => $userID,
						'gameID' => $gameID,
						'message' => $message));
}