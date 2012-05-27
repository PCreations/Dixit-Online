<?php

useModels(array('chat'));

function _addGameMessage() {
	if(isPost()) {
		extract($_POST);
		addGameMessage($gameID, $userID, $message);
	}
	else {
		trigger_error('Vous n\'avez pas accès à cette page');
	}
}