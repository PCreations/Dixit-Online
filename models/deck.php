<?php

function addDeck($userID, $deckName, $deckStatus) {
	global $db;

	$query = $db->prepare('INSERT INTO decks(us_id, de_name, de_status)
						VALUES(:userID, :deckName, :deckStatus)');
	$query->execute(array('userID' => $userID,
						'deckName' => $deckName,
						'deckStatus' => $deckStatus));
	
}