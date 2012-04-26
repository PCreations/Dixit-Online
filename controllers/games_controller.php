<?php

useModels(array('user', 'game', 'card', 'gameType'));

define('CARD_PER_PLAYER', 3); //pour tester
define('STORYTELLER_PHASE', 0);
define('BOARD_PHASE', 1);
define('VOTE_PHASE', 2);
define('POINTS_PHASE', 3);

//constante de statut
define('ACTION_IN_PROGRESS', 4);
define('ACTION_DONE', 5);

function index() {
	$partiesEnAttente = getWaintingGames();
	foreach($partiesEnAttente as &$partie) {
		if(isLogged()) {
			$userID = $_SESSION[USER_MODEL][USER_PK];
			$playersInGame = getSpecificArrayValues(getPlayersInGame($partie['ga_id']), 'us_id');
			if(!in_array($userID, $playersInGame)) {
				$partie['action'] = createLink('rejoindre', 'games', 'joinGame', array($partie['ga_id'], $userID), array('title' => 'Rejoindre la partie'));
			}
			else{
				$partie['action'] = createLink('quitter', 'games', 'quiteGame', array($partie['ga_id'], $userID), array('title' => 'Quitter la partie'));
			}
		}
		else {
			$partie['action'] = 'Aucune action possible. ' . createLink('connectez-vous', 'users', 'login', null, array('title' => 'connectez-vous')) . ' pour rejoindre une partie';
		}
	}
	$vars = array('partiesEnAttente' => $partiesEnAttente);
	render('index', $vars);
}

function joinGame($gameID, $userID) {
	if(!isLogged()) {
		setMessage('Vous devez être connecté pour rejoindre une partie', FLASH_ERROR);
		redirect('games');
	}
	else {
		if(checkPlayersInGame($gameID)) {
			if(in_array($userID, getPlayersInGame($gameID))) {
				setMessage('Vous êtes déjà dans cette partie', FLASH_ERROR);
				redirect('games');
			}
			if(!addPlayerInGame($gameID, $userID)) {
				setMessage('Impossible de rejoindre la partie, une erreur interne est survenue.', FLASH_ERROR);
				redirect('games');
			}
			else {
				if(!checkPlayersInGame($gameID)) {
					_startGame($gameID);
				}
				setMessage('Vous avez rejoint la partie', FLASH_SUCCESS);
				redirect('users', 'account');
			}
		}
		else {
			setMessage('Impossible de rejoindre la partie, le nombre maximum de joueurs à été atteint.', FLASH_ERROR);
			redirect('games');
		}
	}
}

function quiteGame($gameID, $userID) {
	if(!isLogged()) {
		setMessage('Vous devez être connecté pour quitter une partie', FLASH_ERROR);
		redirect('games');
	}
	else {
		if($_SESSION[USER_MODEL][USER_PK] != $userID) {
			setMessage('Vous ne pouvez pas faire quitter un autre joueur que vous', FLASH_ERROR);
			redirect('games');
		}
		else if(!isInGame($gameID, $userID)) {
			setMessage('Ce joueur ne joue pas dans cette partie', FLASH_ERROR);
			redirect('games');
		}
		else {
			if(removePlayerFromGame($gameID, $userID)) {
				setMessage('Vous avez bien quitté la partie', FLASH_SUCCESS);
				redirect('games');
			}
			else {
				setMessage('Impossible de quitter la partie, une erreur interne est survenue', FLASH_ERROR);
				redirect('games');
			}
		}
	}
}

function _startGame($gameID) {
	//Récupération des joueurs dans le jeu
	$playersIDS = getPlayersInGame($gameID);

	//Définition d'un ordre de jeu
	shuffle($playersIDS);

	foreach($playersIDS as $key => $player) {
		definePlayPosition($gameID, $player['us_id'], $key+1);
	}

	//Démarre le 1er tour
	$turnID = addTurn($gameID, $playersIDS[0]['us_id']);

	//Récupération du deck associé au type de la partie
	$deck = getDeck($gameID);

	//Distribution des cartes
	$hands = _dealCards($deck, CARD_PER_PLAYER, count($playersIDS));

	$i=0;
	foreach($playersIDS as $player) {
		foreach($hands[$i] as $hand) {
			addCardInHand($turnID, $hand['ca_id'], $player['us_id']);
		}
		$i++;
	}

	//Sauvegarde de la pioche
	foreach($deck as $card) {
		savePick($gameID, $card['ca_id']);
	}

}

function _dealCards(&$deck, $nbCards, $nbPlayers) {
	if($nbPlayers * $nbCards > count($deck)) {
		trigger_error('Erreur : Pas assez de cartes dans le deck');
		die();
	}
	$hands = array();
	shuffle($deck);

	for($i=0; $i<$nbPlayers; $i++) {
		$hands[] = array_slice($deck, $i*$nbCards, $nbCards);
	}

	$deck = array_slice($deck, $nbPlayers*$nbCards, $nbPlayers*$nbCards);

	return $hands;
}

function play($gameID) {

	if(!isLogged()) {
		setMessage('Vous devez être connecté pour rejoindre une partie', FLASH_ERROR);
		redirect('users', 'login');
	}
	else {
		$userID = $_SESSION[USER_MODEL][USER_PK];
		if(!isInGame($gameID, $userID)) {
			setMessage('Vous ne jouez pas dans cette partie', FLASH_ERROR);
			redirect('games');
		}
		else if(checkPlayersInGame($gameID)) {
			setMessage('Cette partie n\'a pas encore débutée', FLASH_ERROR);
			redirect('games');
		}
		else {
			$currentTurn = getCurrentGameTurn($gameID);
			$phase = _getActualGamePhase($gameID, $currentTurn['tu_id']);
			
			if($phase == POINTS_PHASE) {
				if(_notAlreadyDealsPoints($currentTurn['tu_id'])) {
					$gameOver = _dealPoints($currentTurn);
					if($gameOver) {
						$playersPoints = _getPlayersPoints($gameID);
						render('game-over', array($playersPoints));
					}
				}
				/*if(_checkIfPlayersAreReady($gameID))
					_startNewTurn($currentTurn['ga_id'], $currentTurn['us_id']);*/
			}

			$storyteller = ($_SESSION[USER_MODEL][USER_PK] == $currentTurn['us_id']); //permet de savoir si le joueur connecté est actuellement le conteur ou non
			


			$gameInfos = getGameInfos($gameID);
			$gameTypeInfos = getGameTypeInfos($gameInfos['gt_id'], array('gt_name'));
			$gameCreatorInfos = getUserInfos($gameInfos['us_id'], array('us_pseudo'));

			$actionStatus = _checkAction($phase, $_SESSION[USER_MODEL][USER_PK], $currentTurn['tu_id']);
			$playersInfos = _getPlayersInfos($gameID, $currentTurn['tu_id'], $currentTurn['us_id'], $phase);
			$currentTurn['storyteller'] = $playersInfos['storyteller'];

			unset($playersInfos['storyteller']);
			unset($currentTurn['ga_id']);
			unset($gameInfos['gt_id']);
			unset($gameInfos['us_id']);

			$gameInfos['host'] = $gameCreatorInfos['us_pseudo'];
			$currentTurn['game'] = $gameInfos;
			$currentTurn['players'] = $playersInfos;
			
			$vars = array();
			$vars['turn'] = $currentTurn;
			$vars['turn']['phase'] = _getPhaseInfos($storyteller, $phase, $actionStatus);
			$vars['actionStatus'] = $actionStatus;
			$vars['storyteller'] = $storyteller;
			//debug($vars);
			render('play', $vars);
		}
	}
}

function _getPlayersPoints($gameID) {
	$players = getPlayersInGame($gameID);
	foreach($players as &$player) {
		$player['points'] = getOneRowResult(getTotalUserPointsInGame($gameID, $player['us_id']), 'nbPoints');
	}
	return $players;
}

function _notAlreadyDealsPoints($turnID) {
	$points = getOneRowResult(getTotalDealedPointsInTurn($turnID), 'total');
	return $points == 0;
}

function _checkIfPlayersAreReady($gameID) {
	if(isPost())
		extract($_POST);

	$ready = true;
	$playersIDs = getSpecificArrayValues(getPlayersInGame($gameID), 'us_id');
	foreach($playersIDs as $playerID) {
		if(getOneRowResult(getPlayerStatus($gameID, $playerID), 'pl_status') == 'Attente') {
			$ready = false;
		}
	}
	if(isPost()) {
		echo ($ready) ? 'true' : 'false';
	}
	else {
		return $ready;
	}
}

function _getPlayersInfos($gameID = null, $currentTurnID = null, $storytellerID = null, $phase = null) {
	$i = 0;
	$storytellerInfos = array();
	if(isPost()) {
		extract($_POST);
	}

	foreach(getSpecificArrayValues(getPlayersInGame($gameID), 'us_id') as $playerID) {
		//Récupération des informations des joueurs
		$playersInfos[$i] = getUserInfos($playerID, array('us_id', 'us_pseudo'));
		$playersInfos[$i]['position'] = getOneRowResult(getGameUserPosition($gameID, $playerID), 'pl_position');
		$playersInfos[$i]['points'] = getOneRowResult(getTotalUserPointsInGame($gameID, $playerID), 'nbPoints');

		//Si l'id du joueur vaut celle définie dans la table turns c'est que le joueur est le conteur
		if ($playerID == $storytellerID) {
			$storytellerInfos = $playersInfos[$i];
			$playersInfos[$i]['role'] = 'conteur';
			$status = getOneRowResult(getPlayerStatus($gameID, $playerID), 'pl_status');
			$playersInfos[$i]['status'] = ($phase == STORYTELLER_PHASE || ($phase == POINTS_PHASE && $status != 'Prêt')) ? ACTION_IN_PROGRESS : ACTION_DONE;  
		}
		else {
			$playersInfos[$i]['role'] = 'joueur';
			$playersInfos[$i]['status'] = _checkAction($phase, $playerID, $currentTurnID, 'true');
		}

		//Alors on va récupérer sa main et son statut
		if($playerID == $_SESSION[USER_MODEL][USER_PK]) {
			$playersInfos[$i]['hand'] = getCardsInHand($_SESSION[USER_MODEL][USER_PK], $currentTurnID);
		}

		$i++;
	}

	

	if(isPost()) {
		echo "<table>";
			echo "<caption>Joueurs</caption>";
			echo "<tr>";
				echo "<th>Pseudo : </th>";
				echo "<th>Points : </th>";
				echo "<th>Statut : </th>";
			echo "</tr>";
			foreach($playersInfos as $player) {
				echo "<tr>";
					echo "<td>{$player['us_pseudo']} " . (($player['role'] == 'conteur') ? ' : conteur' : '') . "</td>";
					echo '<td>' . (($player['points'] != null) ? $player['points'] : '0') . '</td>';
					echo "<td>";
						_displayStatus($player['status'], $phase, $player['role']);
					echo "</td>";
				echo "</tr>";
			}
		echo "</table>";
	}
	else {
		$playersInfos['storyteller'] = $storytellerInfos;
		return $playersInfos;
	}
}

function _getPhaseInfos($storyteller = null, $phaseID = null, $actionStatus = null) {
	if(isPost()) {
		extract($_POST);
		$storyteller = ($storyteller == 'false') ? false : true;
	}
	$phase = array();
	if($storyteller) {
		switch($phaseID) {
			case STORYTELLER_PHASE:
				$phase['title'] = 'Tour du conteur';
				$phase['infos'] = 'Vous êtes le conteur ! Sélectionnez l\'une de vos cartes et donner un court indice sur son contenu';
				break;
			case BOARD_PHASE:
				$phase['title'] = 'Tour des joueurs';
				$phase['infos'] = 'Attendez que les joueurs aient choisi leur carte';
				break;
			case VOTE_PHASE:
				$phase['title'] = 'Phase de vote';
				$phase['infos'] = 'Attendez le résultat des votes';
				break;
			case POINTS_PHASE:
				$phase['title'] = 'Décompte des points';
				$phase['infos'] = ($actionStatus == ACTION_IN_PROGRESS) ? 'Vous devez indiquez que vous êtes prêt pour le prochain tour' : 'Attendez que tous les joueurs soient prêts';
				break;
		}
	}
	else {
		switch($phaseID) {
			case STORYTELLER_PHASE:
				$phase['title'] = 'Tour du conteur';
				$phase['infos'] = 'Attendez que le conteur ait choisi sa carte';
				break;
			case BOARD_PHASE:
				$phase['title'] = 'Tour des joueurs';
				$phase['infos'] = ($actionStatus == ACTION_IN_PROGRESS) ? 'Sélectionnez une carte de votre main qui correspond au mieux à la description du conteur' : 'Attendez que tous les joueurs ait choisi une carte';
				break;
			case VOTE_PHASE:
				$phase['title'] = 'Phase de vote';
				$phase['infos'] = ($actionStatus == ACTION_IN_PROGRESS) ? 'Votez pour la carte que vous pensez être celle du conteur !' : 'Attendez que tous les joueurs ait fini de voter';
				break;
			case POINTS_PHASE :
				$phase['title'] = 'Décompte des points';
				$phase['infos'] = ($actionStatus == ACTION_IN_PROGRESS) ? 'Vous devez indiquez que vous êtes prêt pour le prochain tour' : 'Attendez que tous les joueurs soient prêts';
				break;
		}
	}
	$phase['id'] = $phaseID;
	if(isPost()) {
		echo '<h1>Phase actuelle : ' . $phase['title'] . '</h1>';
		echo '<h2>' . $phase['infos'] . '</h2>';
	}
	else
		return $phase;
}

function _getActualGamePhase($gameID = null, $turnID = null) {
	$nbCards = count(getCardsInBoard($turnID));
	$nbPlayers = count(getPlayersInGame($gameID));
	if($nbCards == 0) {
		if(isPost())
			echo STORYTELLER_PHASE; 
		else
			return STORYTELLER_PHASE;
	}
	else if($nbCards < $nbPlayers) {
		if(isPost())
			echo BOARD_PHASE;
		else
			return BOARD_PHASE;
	}
	else if(count(getTurnsVote($turnID)) == ($nbPlayers - 1)) {
		if(isPost()) 
			echo POINTS_PHASE;
		else	
			return POINTS_PHASE;
	}
	else {
		if(isPost())
			echo VOTE_PHASE;
		else
			return VOTE_PHASE;
	}
}

function _getTurnComment() {
	if(isPost())
		extract($_POST);
	echo getOneRowResult(getTurnInfos($turnID, array('tu_comment')), 'tu_comment');
}

function _getCurrentGameTurn($gameID, $field) {
	if(isPost()) {
		echo getOneRowResult(getCurrentGameTurn($gameID), $field);
	}
}

//Distribue les points et retourne true si un joueur a atteint la limite de point pour cette partie
function _dealPoints($turn) {
	$gameTypeID = getOneRowResult(getGameInfos($turn['ga_id'], array('gt_id')), 'gt_id');
	$gameTypeInfos = getGameTypeInfos($gameTypeID);

	$storytellerCardID = getOneRowResult(getPlayerCardInBoard($turn['tu_id'], $turn['us_id']), 'ca_id');
	$cardsIDs = getSpecificArrayValues(getCardsInBoard($turn['tu_id']),'ca_id');
	$cards = array();

	foreach($cardsIDs as $cardID) {
		$cards[$cardID]['us_id'] = getOneRowResult(getCardOwner($cardID, $turn['tu_id']), 'us_id');
		$cards[$cardID]['total'] = getOneRowResult(getTotalCardVoteInTurn($cardID, $turn['tu_id']), 'total');
	}

	$players = getSpecificArrayValues(getPlayersInGame($turn['ga_id']), 'us_id');
	$playersPoints = array();
	foreach($players as $player) {
		$playersPoints[$player] = 0;
	}
	$nbPlayers = count($players);

	debug($players);
	debug($playersPoints);
	echo 'storyteller card : ' . $storytellerCardID;
	debug($cards);

	if(($cards[$storytellerCardID]['total'] == ($nbPlayers -1)) || ($cards[$storytellerCardID]['total'] == 0)) { //Tout le monde a trouvé ou personne n'a trouvé la carte du conteur
		unset($cards[$storytellerCardID]);
		foreach($cards as $card) {
			$playersPoints[$card['us_id']] += 2 + $card['total']; //On ajoute 2 points aux joueurs + 1 point par vote sur leur carte
		}
	}
	else {
		$userVotesIDs = getSpecificArrayValues(getCardVoteInTurn($storytellerCardID, $turn['tu_id']), 'us_id');
		debug($userVotesIDs);
		$playersPoints[$cards[$storytellerCardID]['us_id']] += 3; //Le conteur gagne 3 points
		foreach($userVotesIDs as $userID) { //Tous les joueurs qui ont trouvé la carte du conteur gagnent 3 points
			$playersPoints[$userID] += 3;
		}
		unset($cards[$storytellerCardID]);
		foreach($cards as $card) {
			$playersPoints[$card['us_id']] += $card['total'];
		}
	}

	debug($playersPoints);
	foreach($playersPoints as $userID => $points) {
		addPoints($userID, $turn['tu_id'], $points);
		if (getOneRowResult(getTotalUserPointsInGame($turn['ga_id'], $userID), 'nbPoints') >= $gameTypeInfos['gt_points_limit'])
			return true; //fin du jeu
	}

	return false;
}

//Fonction démarrant un nouveau tour
function _startNewTurn($gameID, $storytellerID) {
	if(isPost())
		extract($_POST);
	//maj date fin tour

	//Joueurs en jeu :
	$players = getSpecificArrayValues(getOrderUserInfos($gameID, array('u.us_id')), 'us_id');

	$storytellerPosition = getOneRowResult(getGameUserPosition($gameID, $storytellerID), 'pl_position');

	if($storytellerPosition < count($players))
		$nextStorytellerPosition = $storytellerPosition+1;
	else
		$nextStorytellerPosition = 1;

	$nextStorytellerID = getOneRowResult(getUserByPosition($gameID, $nextStorytellerPosition), 'us_id');
	
	$newTurnID = addTurn($gameID, $nextStorytellerID);
	foreach($players as $playerID) {
		_setPlayerStatus($gameID, $playerID, 0);
		_pickCard($newTurnID, $gameID, $playerID);
	}
	//redirect('games', 'play', array($currentTurn['ga_id']));
}

function _pickCard($turnID, $gameID, $userID) {
	$pick = getPick($gameID);
	if(empty($pick)) {
		//On sélectionne toutes les cartes qui ont déjà été posé pour cette partie
		$discardedCards = getSpecificArrayValues(getDiscardedCards($gameID), 'ca_id');

		//Réinsertion dans la pioche des cartes après les avoir mélangées
		shuffle($discardedCards);

		foreach($discardedCards as $cardID) {
			savePick($gameID, $cardID);
		}
	}

	//Défaussement de la pioche
	$cardID = shiftPick($gameID);
	addCardInHand($turnID, $cardID, $userID);
}

//Fonction permettant de tester si un utilisateur a joué ou pas dans une phase considérée.
function _checkAction($phase, $playerID, $turnID, $returnAction = 'true') {
	$action;

	switch($phase) {
		case BOARD_PHASE:
			//On tente de récupérer la carte que le joueur a joué pour ce tour. Si le résultat est un tableau c'est qu'une carte à été posée et donc que le joueur a joué pour cette phase
			$action = is_array(getPlayerCardInBoard($turnID, $playerID));
			break;
		case VOTE_PHASE:
			//Si l'id du joueur se trouve dans le tableau des joueurs ayant déjà voté alors l'action est effectuée
			if(!in_array($playerID, getSpecificArrayValues(getTurnsVote($turnID), 'us_id'))) {
				$action = false;
				//Si dans le tableau c'est que le joueur est le storyteller et donc qu'il n'a pas a voter
				if(in_array($playerID, getSpecificArrayValues(getTurnInfos($turnID, array('us_id')), 'us_id')))
					$action = true;
			}
			else
				$action = true;
			break;
		case STORYTELLER_PHASE:
			$action = false;
			break;
		case POINTS_PHASE:
			$gameID = getOneRowResult(getTurnInfos($turnID, array('ga_id')), 'ga_id');
			if(getOneRowResult(getPlayerStatus($gameID, $playerID), 'pl_status') == 'Attente')
				$action = false;
			else
				$action = true;
			break;
	}

	$action = $action ? ACTION_DONE : ACTION_IN_PROGRESS;
	if(isPost() && $returnAction != 'true') {
		echo $action;
	}
	else {
		return $action;
	} 
}


function _setPlayerStatus($gameID, $userID, $status) {
	switch($status) {
		case 0:
			$status = 'Attente';
			break;
		case 1:
			$status = 'Prêt';
			break;
		case 2:
			$status = 'Inactif';
			break;
	}
	if(isPost()) {
		extract($_POST);
		setPlayerStatus($gameID, $userID, $status);
	}
	else
		die();
}
//Permet d'afficher le tableau des cartes en fonction de la phase. Si la phase est BOARD_PHASE alors les cartes apparaissent face cachées et si c'est la VOTE_PHASE elles apparaissent face visible avec la possibilité de voter
function _displayBoard($phase = null, $gameID = null, $turn = null, $storyteller = null, $actionStatus = null) {
	if(isPost()) {
		extract($_POST);
		$storyteller = ($storyteller == 'false') ? false : true;
	}

	$cardsIDs = getSpecificArrayValues(getCardsInBoard($turn['tu_id']),'ca_id');
	$cards = array();

	foreach($cardsIDs as $cardID) {
		$cards[] = getCardInfos($cardID);
	}

	if($phase == BOARD_PHASE) {
		//récupération de la carte du joueur
		foreach($cards as $card) {
			echo '<img src="' . IMG_DIR . 'cards/back.jpg" alt="card back" title="Carte face cachée"/>';
			echo "\n";
		}
	}
	else if($phase == VOTE_PHASE) {
		//$userCardID = getOneRowResult(getPlayerCardInBoard($gameID, $_SESSION[USER_MODEL][USER_PK]), 'ca_id');
		shuffle($cards);
		if($actionStatus == ACTION_IN_PROGRESS && !$storyteller) {
			echo '<form method="post" action="' . BASE_URL . 'cards/vote">';
			foreach($cards as $card) {
				echo '<label for="' . $card['ca_id'] . '"><img src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="' . $card['ca_id'] . '" /></label><input type="radio" id="' . $card['ca_id'] .'" name="cardID" value="' . $card['ca_id'] . '" />';
			}
				echo '<input type="hidden" name="gameID" value="' . $gameID . '" />';
				echo '<input type="hidden" name="turnID" value="' . $turn['tu_id'] . '" />';
				echo '<input type="submit" value="voter" />';
			echo '</form>';
		}
		else {
			foreach($cards as $card) {
				echo '<img src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="' . $card['ca_id'] . '" />';
			}
		}
	}
	else if($phase == STORYTELLER_PHASE){
		echo 'Le conteur n\'a pas encore choisi sa carte';
	}
	else {
		$storytellerCardID = getOneRowResult(getPlayerCardInBoard($turn['tu_id'], $turn['us_id']), 'ca_id');

		foreach($cards as $card) {
			$userVotesIDs = getCardVoteInTurn($card['ca_id'], $turn['tu_id']);
			echo '<table>';
				echo '<caption>Votes</caption>';
				echo '<tr>';
					echo '<td>';
					foreach($userVotesIDs as $user) {
						$userVoteInfos = getUserInfos($user['us_id'], array('us_id', 'us_pseudo'));
						echo $userVoteInfos['us_pseudo'] .'<br />';
					}
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>TOTAL : '. count($userVotesIDs) .'</td>';
				echo '</tr>';
				echo '<tr>';
					$style = ($card['ca_id'] == $storytellerCardID) ? 'style="border: 2px solid red;"' : '';
					echo '<td><img '. $style .' src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="' . $card['ca_id'] . '" /></td>';
				echo '</tr>';
			echo '</table>';
		}
		echo '<input type="button" id="readyForNextTurn" name="readyForNextTurn" value="Prêt pour le prochain tour" onclick="readyForNextTurn();" />';
	}
}

function _displayHand($phase = null, $userID = null, $gameID = null, $turnID = null, $storyteller = null, $actionStatus = null) {
	if(isPost()) {
		extract($_POST);
		$storyteller = ($storyteller == 'false') ? false : true;
	}

	$hand = getCardsInHand($userID, $turnID);

	switch($phase) {
		case STORYTELLER_PHASE:
			if($storyteller) {
				echo '<form method="post" action="' . BASE_URL . 'cards/addStorytellerCard">';
				echo '<label for="comment">Indice : </label><input type="text" name="comment" id="comment" />';
				foreach($hand as $card) {
					echo '<label for="' . $card['ca_id'] . '"><img src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="' . $card['ca_id'] . '" /></label><input type="radio" id="' . $card['ca_id'] .'" name="cardID" value="' . $card['ca_id'] . '" />';
				}	
					echo '<input type="hidden" name="gameID" value="' . $gameID . '" />';
					echo '<input type="hidden" name="turnID" value="' . $turnID . '" />';
					echo '<input type="submit" value="Valider" />';
				echo '</form>';
			}
			else {
				foreach($hand as $card) {
					echo '<img src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="' . $card['ca_id'] . '" />';
				}		
			}
			break;
		case BOARD_PHASE:
			foreach($hand as $card) {
				if(!$storyteller) {
					//die(var_dump($actionStatus));
					if($actionStatus == ACTION_IN_PROGRESS)
						echo l('<img src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="' . $card['ca_id'] . '" />', 'cards', 'addCard', array($gameID, $turnID, $card['ca_id']));
					else
						echo '<img src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="' . $card['ca_id'] . '" />';
				}
				else {
					echo '<img src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="' . $card['ca_id'] . '" />';
				}
			}
			break;
		case VOTE_PHASE:
		case POINTS_PHASE:
			foreach($hand as $card) {
				echo '<img src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="' . $card['ca_id'] . '" />';
			}
			break;
	}
}

function _displayStatus($status, $phase, $role) {
	if($phase == POINTS_PHASE) {
		switch($status) {
			case ACTION_IN_PROGRESS:
				echo 'Pas prêt pour le prochain tour';
				break;
			case ACTION_DONE:
				echo 'Prêt';
				break;
		}
	}
	else {
		switch($role) {
			case 'conteur':
				switch($phase) {
					case STORYTELLER_PHASE:
						echo 'Choix de la carte';
						break;
					case BOARD_PHASE:
					case VOTE_PHASE:
						echo 'En attente des joueurs';
						break;
				}
				break;
			case 'joueur':
				switch($phase) {
					case STORYTELLER_PHASE:
						echo 'En attente du conteur';
						break;
					case BOARD_PHASE:
						echo ($status == ACTION_IN_PROGRESS) ? 'Doit sélectionner une carte' : 'En attente des autres joueurs';
						break;
					case VOTE_PHASE:
						echo ($status == ACTION_IN_PROGRESS) ? 'Doit voter' : 'A voté';
						break;
				}
				break;
		}
	}
}