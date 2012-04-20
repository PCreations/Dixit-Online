<?php

useModels(array('user', 'game'));

function index() {
	$partiesEnAttente = getWaintingGames();
	foreach($partiesEnAttente as $partie) {

	}
	$vars = array('partiesEnAttente' => $partiesEnAttente);
	render('index', $vars);
}