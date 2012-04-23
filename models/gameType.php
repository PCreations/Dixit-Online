<?php

function getGameTypeInfos($gameTypeID, $fields=array('*')) {
	global $db;

	$fields = implode(',', $fields);
	
	$query = $db->prepare('SELECT '.$fields.'
						FROM game_types
						WHERE gt_id = ?');
	$query->execute(array($gameTypeID));
	return $query->fetch(PDO::FETCH_ASSOC);
}