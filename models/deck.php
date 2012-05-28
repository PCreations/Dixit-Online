<?php

function addDeck($userID, $deckName, $deckStatus) {
	global $db;

	$query = $db->prepare('INSERT INTO decks(us_id, de_name, de_status)
						VALUES(:userID, :deckName, :deckStatus)');
	$query->execute(array('userID' => $userID,
						'deckName' => $deckName,
						'deckStatus' => $deckStatus));
}

function getAllDecks($fields = array('*')) {
	global $db;
	$fields = implode(',', $fields);

	$query = $db->query('SELECT '.$fields.' 
						FROM decks');
										
	return $query->fetchAll(PDO::FETCH_ASSOC);
}

// A tester
function getDeckInfos($id, $fields = array('*')) {
	global $db;
	$fields = implode(',', $fields);

	$query = $db->query('SELECT '.$fields.' 
						FROM decks
						WHERE decks.de_id='.$id);
										
	return $query->fetch(PDO::FETCH_ASSOC);
}

function nbCartes($id)
{
	global $db;
	var_dump($id); voilà le pb ? hum
	$query = $db->prepare('SELECT COUNT(ca_id) as nbCartes FROM cards_decks WHERE cards_decks.de_id=?');
	$query->execute(array($id));
	debug($query->fetch(PDO::FETCH_ASSOC), true);
	return $query->fetch(PDO::FETCH_ASSOC);
}