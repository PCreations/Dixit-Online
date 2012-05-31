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

function getUserDecks($userID, $fields = array('*')) {
	global $db;
	$fields = implode(',', $fields);
	$query = $db->prepare('SELECT '.$fields.' 
						FROM decks
						WHERE us_id = ?');
	$query->execute(array($userID));
	return $query->fetchAll(PDO::FETCH_ASSOC);
}

// A tester
function getDeckInfos($id, $fields = array('*')) {
	global $db;
	$fields = implode(',', $fields);

	$query = $db->prepare('SELECT '.$fields.' 
						FROM decks
						WHERE decks.de_id=?');
	$query->execute(array($id));
	return $query->fetch(PDO::FETCH_ASSOC);
}

function getCardsInDeck($deckID) {
	global $db;

	$query = $db->prepare('SELECT ca_id 
						FROM cards_decks
						WHERE de_id = ?');
	$query->execute(array($deckID));
	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getCardsInDeckInfo($deckID) {
	global $db;

	$query = $db->prepare('SELECT cards_decks.ca_id, ca_name, ca_image 
						FROM cards_decks, cards
						WHERE cards_decks.ca_id = cards.ca_id AND de_id = ?');
	$query->execute(array($deckID));
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
	$query = $db->prepare('SELECT COUNT(ca_id) as nbCartes FROM cards_decks WHERE cards_decks.de_id=?');
	$query->execute(array($id));
	return $query->fetch(PDO::FETCH_ASSOC);
}