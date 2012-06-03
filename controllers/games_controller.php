<?php


useModels(array('user', 'game', 'card', 'chat', 'deck'));

define('CARD_PER_PLAYER', 6);
define('STORYTELLER_PHASE', 0);
define('BOARD_PHASE', 1);
define('VOTE_PHASE', 2);
define('POINTS_PHASE', 3);

//constante de statut
define('ACTION_IN_PROGRESS', 4);
define('ACTION_DONE', 5);

function index() {
	$deckInfos = getAllDecks(array('de_id', 'de_name'));

	if(!isPost()) {
		$partiesEnAttente = getWaitingGames();
		$vars_filtrage=array('name'=>'','nbpoints'=>'','deck'=>'','nbplayers'=>'');
	}
	else {
		$vars_filtrage=$_POST;
		extract($_POST);
		if(!isset($public)){
			$public='off';
		}
		$partiesEnAttente = filterGames($name, $nbplayers, $nbpoints, $deck, $public);
	}

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
		
		debug($partiesEnAttente);
		$vars = array('partiesEnAttente' => $partiesEnAttente , 'deckInfos' => $deckInfos, 'vars_filtrage' => $vars_filtrage);
		render('index', $vars);
}


function newGame() {
	if(!isPost()) {
		setMessage('Vous ne pouvez pas accéder à cette page, créez une partie avec le formulaire.', FLASH_ERROR);
		redirect('games');
	}
	else {
		extract($_POST);
		$deck_mini=$joueurs*((int)($points/10))*3;
		if ($deck_mini > nbCartes($deck))
		{
			setMessage('Le deck que vous avez sélectionné ne comporte pas assez de carte pour les paramètres choisi', FLASH_ERROR);
			redirect('games');
		}
		else if ($joueurs<3 || $joueurs>10)
		{
			setMessage('Le nombre de joueurs doit être compris entre 3 et 10', FLASH_ERROR);
			redirect('games');
		}
		else
		{
			if (!empty($pwd))
			{
				$_POST['pwd']=encrypt($_POST['pwd']);
			}
			extract($_POST);
			insertNewGame($deck, $_SESSION[USER_MODEL][USER_PK], $nom, $pwd, $joueurs, $points);
			joinGame(getLastGameID(),$_SESSION[USER_MODEL][USER_PK]);
		}
	}
}

function joinGame($gameID, $userID) {
	global $CSS_FILES;
	$CSS_FILES[] = 'style_users.css';
	if(!isLogged()) {
		setMessage('Vous devez être connecté pour rejoindre une partie', FLASH_ERROR);
		redirect('games');
	}
	else {
		if(checkPlayersInGame($gameID)) {
			$playersInGame = getSpecificArrayValues(getPlayersInGame($gameID), 'us_id');
			if(in_array($userID, $playersInGame)) {
				setMessage('Vous êtes déjà dans cette partie', FLASH_ERROR);
				redirect('games');
			}
			$gameInfos = getGameInfos($gameID, array('ga_password', 'us_id'));
			$allowAccess = true;
			if($gameInfos['us_id'] != $userID) {
				if($gameInfos['ga_password'] != '') {
					$allowAccess = false;
					if(isset($_POST['game_password'])) {
						if(encrypt($_POST['game_password']) == $gameInfos['ga_password'])
							$allowAccess = true;
						else {
							setMessage('Mauvais mot de passe', FLASH_ERROR);
							redirect('games', 'joinGame', array($gameID, $userID));
						}
					}
					else {
						render('game_access');
						echo "HAHAHA";
					}
				}
			}
			if($allowAccess) {
				if(!addPlayerInGame($gameID, $userID)) {
					setMessage('Impossible de rejoindre la partie, une erreur interne est survenue.', FLASH_ERROR);
					redirect('games');
				}
				else {
					if(!checkPlayersInGame($gameID)) {
						_startGame($gameID);
					}
					setMessage('Vous avez rejoint la partie', FLASH_SUCCESS);
					redirect('games', 'room', array($gameID));
				}
			}
		}
		else {
			setMessage('Impossible de rejoindre la partie, le nombre maximum de joueurs a été atteint.', FLASH_ERROR);
			redirect('games');
		}
	}

	$CSS_FILES[] = array_pop($CSS_FILES);
}

function room($gameID) {
	global $JS_FILES;
	$JS_FILES[] = 'room.js';
	if(!isLogged()) {
		setMessage('Vous devez être connecté pour accéder à cette page', FLASH_ERROR);
		redirect('users', 'login');
	}
	$userID = $_SESSION[USER_MODEL][USER_PK];

	if(!isInGame($gameID, $userID)) {
		setMessage('Vous ne jouez pas dans cette partie', FLASH_ERROR);
		redirect('games');
	}
	if(!checkPlayersInGame($gameID)) {
		setMessage('La partie commence !', FLASH_INFOS);
		redirect('games', 'play', array($gameID));
	}
	$gameInfos = getGameInfos($gameID, array('ga_id', 'ga_name', 'ga_password', 'us_id', 'de_id', 'ga_creation_date', 'ga_nb_players', 'ga_points_limit'));
	$gameInfos['host'] = getOneRowResult(getUserInfos($gameInfos['us_id']), 'us_pseudo');
	$gameInfos['ready'] = checkPlayersInGame($gameID);
	$usersInGame = getSpecificArrayValues(getPlayersInGame($gameID), 'us_id');

	unset($gameInfos['us_id']);
	debug($gameInfos);
	$vars = array('gameInfos' => $gameInfos,
				'usersInGame' => json_encode($usersInGame));
	render('room', $vars);
	$JS_FILES = array_pop($JS_FILES);
}

function test() {
	$pick = getPick(2);
	if(empty($pick)) {
		//On sélectionne toutes les cartes qui ont déjà été posée pour cette partie
		$discardedCards = getSpecificArrayValues(getDiscardedCards(2), 'ca_id');
		debug($discardedCards);

		//Réinsertion dans la pioche des cartes après les avoir mélangées
		shuffle($discardedCards);

		debug($discardedCards);

		foreach($discardedCards as $order => $cardID) {
			savePick(2, $order, $cardID);
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

	//Récupération du deck associé au jeu
	$deck = getDeck($gameID);

	//debug($deck);

	//Distribution des cartes
	$hands = _dealCards($deck, CARD_PER_PLAYER, count($playersIDS));

	$i=0;
	foreach($playersIDS as $player) {
		foreach($hands[$i] as $hand) {
			addCardInHand($turnID, $hand['ca_id'], $player['us_id']);
		}
		$i++;
	}

	shuffle($deck);

	//Sauvegarde de la pioche
	foreach($deck as $order => $card) {
		savePick($gameID, $order, $card['ca_id']);
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

	$deck = array_slice($deck, $nbPlayers*$nbCards);

	return $hands;
}

function play($gameID) {
	global $CSS_FILES;
	global $JS_FILES;

	$JS_FILES[] = 'fancybox2/source/jquery.fancybox.pack.js';
	$JS_FILES[] = 'fancybox2/source/helpers/jquery.fancybox-buttons.js';
	$JS_FILES[] = 'fancybox2/source/helpers/jquery.fancybox-thumbs.js';
	$JS_FILES[] = 'fancybox2/source/helpers/jquery.fancybox-media.js';


	$CSS_FILES[] = 'style_partie.css';
	$CSS_FILES[] = 'jquery.fancybox.css';
	$CSS_FILES[] = 'jquery.fancybox-buttons.css';
	$CSS_FILES[] = 'jquery.fancybox-thumbs.css';


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
					_dealPoints($currentTurn);
					
				}
				/*if(_checkIfPlayersAreReady($gameID))
					_startNewTurn($currentTurn['ga_id'], $currentTurn['us_id']);*/
				if(_checkIfPlayersAreReady($gameID)) {
					if(_isGameOver($gameID)) {
						redirect('games', 'gameOver', array($gameID));
					}
				}
			}

			$storyteller = ($_SESSION[USER_MODEL][USER_PK] == $currentTurn['us_id']); //permet de savoir si le joueur connecté est actuellement le conteur ou non
			
			$nextStorytellerID = _getNextStorytellerID($gameID, $currentTurn['us_id']);
			$nextStoryteller = getOneRowResult(getUserInfos($nextStorytellerID, array('us_pseudo')), 'us_pseudo');
			
			$gameInfos = getGameInfos($gameID);
			$gameCreatorInfos = getUserInfos($gameInfos['us_id'], array('us_pseudo'));

			$actionStatus = _checkAction($phase, $_SESSION[USER_MODEL][USER_PK], $currentTurn['tu_id']);
			$playersInfos = _getPlayersInfos($gameID, $currentTurn['tu_id'], $currentTurn['us_id'], $phase);
			$currentTurn['storyteller']['us_pseudo'] = getOneRowResult(getUserInfos($currentTurn['us_id'], array('us_pseudo')), 'us_pseudo');

			unset($playersInfos['storyteller']);
			unset($currentTurn['ga_id']);
			unset($gameInfos['us_id']);

			$gameInfos['host'] = $gameCreatorInfos['us_pseudo'];
			$currentTurn['game'] = $gameInfos;
			$currentTurn['players'] = $playersInfos;
			
			$vars = array();
			$vars['turn'] = $currentTurn;
			$vars['turn']['phase'] = _getPhaseInfos($storyteller, $phase, $actionStatus);
			$vars['actionStatus'] = $actionStatus;
			$vars['storyteller'] = $storyteller;
			$vars['nextStoryteller'] = $nextStoryteller;
			//debug($vars);
			render('play', $vars);
		}
	}
	
	for($i=0; $i<4; $i++) {
		$CSS_FILES = array_pop($CSS_FILES);
		$JS_FILES = array_pop($JS_FILES);
	}
	
}

function gameOver($gameID) {
	if(!isLogged()) {
		setMessage('Vous n\'êtes pas connecté', FLASH_ERROR);
		redirect('games');
	}
	else if(!_isGameOver($gameID)) {
		setMessage('Cette partie n\'est pas terminée', FLASH_ERROR);
		redirect('games');
	}
	else {
		$playersPoints = _getPlayersPoints($gameID);

		foreach($playersPoints as &$player) {
			$player['pseudo'] = getOneRowResult(getUserInfos($player['us_id'], array('us_pseudo')), 'us_pseudo');
		}

		$vars['playersPoints'] = $playersPoints;
		render('game-over', $vars);
	}
}

function _isGameOver($gameID = null) {
	if(isPost())
		extract($_POST);

	$boolean = false;
	$gamePointsLimit = getOneRowResult(getGameInfos($gameID, array('ga_points_limit')), 'ga_points_limit');

	$playersIDs = getSpecificArrayValues(getPlayersInGame($gameID), 'us_id');
	foreach($playersIDs as $playerID) {
		if(getOneRowResult(getTotalUserPointsInGame($gameID, $playerID), 'nbPoints') >= $gamePointsLimit) {
			$boolean = true;
		}
	}
	
	if(isPost())
		echo $boolean ? 'true' : 'false';
	else
		return $boolean;

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
	$ready = true;
	$playersIDs = getSpecificArrayValues(getPlayersInGame($gameID), 'us_id');
	foreach($playersIDs as $playerID) {
		if(getOneRowResult(getPlayerStatus($gameID, $playerID), 'pl_status') == 'Attente') {
			$ready = false;
		}
	}
	return $ready;
}

function _getPlayersInfos($gameID, $currentTurnID, $storytellerID, $phase) {
	$i = 0;

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
			$playersInfos[$i]['status'] = _getStatus(($phase == STORYTELLER_PHASE || ($phase == POINTS_PHASE && $status != 'Prêt')) ? ACTION_IN_PROGRESS : ACTION_DONE, $phase, $playersInfos[$i]['role']);
		}
		else {
			$playersInfos[$i]['role'] = 'joueur';
			$playersInfos[$i]['status'] = _getStatus(_checkAction($phase, $playerID, $currentTurnID, 'true'), $phase, $playersInfos[$i]['role']);
		}

		//Alors on va récupérer sa main et son statut
		if($playerID == $_SESSION[USER_MODEL][USER_PK]) {
			$playersInfos[$i]['hand'] = getCardsInHand($_SESSION[USER_MODEL][USER_PK], $currentTurnID, $gameID);
		}

		$i++;
	}

	return $playersInfos;
}

function _getPhaseInfos($storyteller = null, $phaseID = null, $actionStatus = null) {
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
	return $phase;
}

function _getActualGamePhase($gameID = null, $turnID = null) {
	$nbCards = count(getCardsInBoard($turnID));
	$nbPlayers = count(getPlayersInGame($gameID));
	if($nbCards == 0) {
			return STORYTELLER_PHASE;
	}
	else if($nbCards < $nbPlayers) {
			return BOARD_PHASE;
	}
	else if(count(getTurnsVote($turnID)) == ($nbPlayers - 1)) {	
			return POINTS_PHASE;
	}
	else {
			return VOTE_PHASE;
	}
}

function _getTurnComment() {
	if(isPost())
		extract($_POST);
	echo getOneRowResult(getTurnInfos($turnID, array('tu_comment')), 'tu_comment');
}

function _getCurrentGameTurn($gameID, $field) {
	return getOneRowResult(getCurrentGameTurn($gameID), $field);
}

//Distribue les points
function _dealPoints($turn) {
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

	if(($cards[$storytellerCardID]['total'] == $nbPlayers-1) || ($cards[$storytellerCardID]['total'] == 0)) { //Tout le monde a trouvé ou personne n'a trouvé la carte du conteur
		unset($cards[$storytellerCardID]);
		foreach($cards as $card) {
			$playersPoints[$card['us_id']] += 2+$card['total']; //On ajoute 2 points aux joueurs + 1 point par vote sur leur carte
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
	}
}

//Fonction démarrant un nouveau tour
function _startNewTurn($gameID, $storytellerID, $turnID) {
	//maj date fin tour

	$pick = getPick($gameID);
	$nbPlayersInGame = getOneRowResult(countPlayersInGame($gameID), 'nbTotalPlayer');

	if(count($pick) < $nbPlayersInGame) {
		//On sélectionne toutes les cartes qui ont déjà été posée pour cette partie
		$discardedCards = getSpecificArrayValues(getDiscardedCards($gameID, $turnID), 'ca_id');

		//Réinsertion dans la pioche des cartes après les avoir mélangées
		shuffle($discardedCards);

		foreach($discardedCards as $order => $cardID) {
			savePick($gameID, $order, $cardID);
		}
	}

	//Joueurs en jeu :
	$players = getSpecificArrayValues(getOrderUserInfos($gameID, array('u.us_id')), 'us_id');
	
	$nextStorytellerID = _getNextStorytellerID($gameID, $storytellerID);
	
	$newTurnID = addTurn($gameID, $nextStorytellerID);
	foreach($players as $playerID) {
		_setPlayerStatus($gameID, $playerID, 0);
		_pickCard($newTurnID, $gameID, $playerID);
	}

}
function _getNextStorytellerID($gameID, $storytellerID){

	$players = getSpecificArrayValues(getOrderUserInfos($gameID, array('u.us_id')), 'us_id');
	$storytellerPosition = getOneRowResult(getGameUserPosition($gameID, $storytellerID), 'pl_position');

	if($storytellerPosition < count($players))
		$nextStorytellerPosition = $storytellerPosition+1;
	else
		$nextStorytellerPosition = 1;

	return getOneRowResult(getUserByPosition($gameID, $nextStorytellerPosition), 'us_id');
}

function _pickCard($turnID, $gameID, $userID) {
	//Défaussement de la pioche
	$cardID = shiftPick($gameID);
	addCardInHand($turnID, $cardID, $userID);
}

//Fonction permettant de tester si un utilisateur a joué ou pas dans une phase considérée.
function _checkAction($phase, $playerID, $turnID) {
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
	return $action;
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

	setPlayerStatus($gameID, $userID, $status);

}
//Permet d'afficher le tableau des cartes en fonction de la phase. Si la phase est BOARD_PHASE alors les cartes apparaissent face cachées et si c'est la VOTE_PHASE elles apparaissent face visible avec la possibilité de voter
function _getBoard($phase, $gameID, $turn, $storyteller, $actionStatus) {
	
	$board = '<div id="cartes">';
	$cardsIDs = getSpecificArrayValues(getCardsInBoard($turn['tu_id']),'ca_id');
	$cards = array();

	foreach($cardsIDs as $cardID) {
		$cards[] = getCardInfos($cardID);
	}

	if($phase == BOARD_PHASE) {
		//récupération de la carte du joueur
		foreach($cards as $card) {
			$board .= '<div class="carte"><img class="image_carte hidden_fance" src="' . IMG_DIR . 'cards/back.jpg" alt="card back" title="Carte face cachée"/></div>';
			$board .= "\n";
		}
	}
	else if($phase == VOTE_PHASE) {
		$userCardID = getOneRowResult(getPlayerCardInBoard($turn['tu_id'], $_SESSION[USER_MODEL][USER_PK]), 'ca_id');
		shuffle($cards);
		if(!$storyteller) {
			$votedCardId = _getUserVotedCardInTurn($turn['tu_id'], $_SESSION[USER_MODEL][USER_PK]);
			$board .= '<form id="boardForm" method="post" action="' . BASE_URL . 'cards/vote">';
			foreach($cards as $card) {
				$buttonImg = '';
				if($userCardID != $card['ca_id']) {
					if($votedCardId != -1) {//i.e si le joueur a déjà voté
						$buttonImg = '<img class="bouton" id="btnCardID'. $card['ca_id'] .'" onclick="updateCard(\'cardID\', \'table\','. $card['ca_id'] .');alert(\'test\');" src="' . IMG_DIR . 'bouton.png" />';
					}
					else {
						$buttonImg = '<img class="bouton" id="btnCardID'. $card['ca_id'] .'" onclick="selectCard(\'cardID\', \'table\','. $card['ca_id'] .');" src="' . IMG_DIR . 'bouton.png" />';
					}
				}
				if($votedCardId == $card['ca_id']) {
					$buttonImg = '<img class="bouton" id="btnCardID'. $card['ca_id'] .'" src="' . IMG_DIR . 'bouton_dore.png" />';
				}
				$board .= '<div class="carte"><a class="fancybox" rel="board" href="' . IMG_DIR . 'cards/' . $card['ca_image'] . '"><img class="image_carte" src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="zoom" /></a>'. $buttonImg .'</div>';
			}
				$board .= '<input type="hidden" name="cardID" value="-1" />';
				$board .= '<input type="hidden" name="gameID" value="' . $gameID . '" />';
				$board .= '<input type="hidden" name="turnID" value="' . $turn['tu_id'] . '" />';
			$board .= '</form>';
		}
		else {
			foreach($cards as $card) {
				$board .= '<div class="carte"><a class="fancybox" rel="board" href="' . IMG_DIR . 'cards/' . $card['ca_image'] . '"><img class="image_carte" src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="zoom" /></a></div>';
			}
		}
	}
	else if($phase == STORYTELLER_PHASE){
		$board .= 'Le conteur n\'a pas encore choisi sa carte';
	}
	else { //fin de tour
		$storytellerCardID = getOneRowResult(getPlayerCardInBoard($turn['tu_id'], $turn['us_id']), 'ca_id');

		foreach($cards as $card) {
			$userVotesIDs = getCardVoteInTurn($card['ca_id'], $turn['tu_id']);
			$style = ($card['ca_id'] == $storytellerCardID) ? 'style="border: 2px solid white;border-radius: 5px;"' : '';
			
			$owner=getCardOwner($card['ca_id'], $turn['tu_id']);
			$back_content="Carte de<br>".$pseudo = getOneRowResult(getUserInfos($owner['us_id']), 'us_pseudo')."<br><br>Votée par <br>";
			
			$voters=getCardVoteInTurn($card['ca_id'], $turn['tu_id']);
			foreach($voters as $voter) {
				$pseudo=getOneRowResult(getUserInfos($voter['us_id']), 'us_pseudo');
				$back_content.=$pseudo."<br>";
			}
			
			$board .= '<div class="carte" id="'. $card['ca_id'] .'"><div class="back_carte"><img class="image_back_carte" src="' . IMG_DIR . 'cards/back_empty.jpg"/><p>'.$back_content.'</p></div><img '.$style.' class="image_carte_flip" id="'. $card['ca_id'] .'" src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" /></div>';
		}
		$board .= '<div id="stIndice"><input type="button" id="readyForNextTurn" onclick="readyForNextTurn();" name="readyForNextTurn" value="Prêt pour le prochain tour" /></div>';
	}

	$board .= "</div>";
	$board .= "</form>";
	return $board;
}

function _getHand($phase, $userID, $gameID, $turnID, $storyteller, $actionStatus) {
	$hand = getCardsInHand($userID, $turnID, $gameID);
	$handDisplay = '<div id="cartes">';
	switch($phase) {
		case STORYTELLER_PHASE:
			$handDisplay .= '<form method="post" action="' . BASE_URL . 'cards/addStorytellerCard">';
			if($storyteller) {
				foreach($hand as $card) {
					$handDisplay .= '<div class="carte"><a class="fancybox" rel="hand" href="' . IMG_DIR . 'cards/' . $card['ca_image'] . '"><img class="image_carte" src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="' . $card['ca_id'] . '" /></a><img id="btnCardID'. $card['ca_id'] .'" onclick="selectCard(\'cardID\', \'main\','. $card['ca_id'] .');" class="bouton" src="' . IMG_DIR . 'bouton.png" /></div>';
				}
				
					$handDisplay .= '<input type="hidden" name="cardID" value="-1" />';
					$handDisplay .= '<input type="hidden" name="gameID" value="' . $gameID . '" />';
					$handDisplay .= '<input type="hidden" name="turnID" value="' . $turnID . '" />';
					$handDisplay .= '<div id="stIndice">
										<label id="commentLabel" for="comment">Indice : </label><input type="text" name="comment" id="comment" />
										<input type="submit" value="Valider" />
									</div>';
				$handDisplay .= '</form>';
			}
			else {
				foreach($hand as $card) {
					$handDisplay .= '<div class="carte"><a class="fancybox" rel="hand" href="' . IMG_DIR . 'cards/' . $card['ca_image'] . '"><img class="image_carte" src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="zoom" /></a></div>';
				}		
			}
			break;
		case BOARD_PHASE:
			foreach($hand as $card) {
				if(!$storyteller) {
					//die(var_dump($actionStatus));
					if($actionStatus == ACTION_IN_PROGRESS)
						$handDisplay .= '<div class="carte"><a class="fancybox" rel="hand" href="' . IMG_DIR . 'cards/' . $card['ca_image'] . '"><img class="image_carte" src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="zoom" /></a>' . l('<img id="btnCardID'. $card['ca_id'] .'" class="bouton" src="' . IMG_DIR . 'bouton.png" />', 'cards', 'addCard', array($gameID, $turnID, $card['ca_id'])) . '</div>';
					else
						$handDisplay .= '<div class="carte"><a class="fancybox" rel="hand" href="' . IMG_DIR . 'cards/' . $card['ca_image'] . '"><img class="image_carte" src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="zoom" /></a></div>';
				}
				else {
					$handDisplay .= '<div class="carte"><a class="fancybox" rel="hand" href="' . IMG_DIR . 'cards/' . $card['ca_image'] . '"><img class="image_carte" src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="zoom" /></a></div>';
				}
			}
			break;
		case VOTE_PHASE:
		case POINTS_PHASE:
			foreach($hand as $card) {
				$handDisplay .= '<div class="carte"><a class="fancybox" rel="hand" href="' . IMG_DIR . 'cards/' . $card['ca_image'] . '"><img class="image_carte" src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="zoom" /></a></div>';
			}
			break;
	}

	$handDisplay .= '</div>';
	return $handDisplay;
}


function _getUserVotedCardInTurn($turnID, $userID) {
	$result = getUserVotedCardInTurn($turnID, $userID);
	if(!empty($result)) {
		return (int)$result['ca_id'];
	}
	else
		return -1;
}

function _getStatus($status, $phase, $role) {
	$statusTxt = '';
	if($phase == POINTS_PHASE) {
		switch($status) {
			case ACTION_IN_PROGRESS:
				$statusTxt = 'Pas prêt pour le prochain tour';
				break;
			case ACTION_DONE:
				$statusTxt = 'Prêt';
				break;
		}
	}
	else {
		switch($role) {
			case 'conteur':
				switch($phase) {
					case STORYTELLER_PHASE:
						$statusTxt = 'Choix de la carte';
						break;
					case BOARD_PHASE:
					case VOTE_PHASE:
						$statusTxt = 'En attente des joueurs';
						break;
				}
				break;
			case 'joueur':
				switch($phase) {
					case STORYTELLER_PHASE:
						$statusTxt = 'En attente du conteur';
						break;
					case BOARD_PHASE:
						$statusTxt = ($status == ACTION_IN_PROGRESS) ? 'Doit sélectionner une carte' : 'En attente des autres joueurs';
						break;
					case VOTE_PHASE:
						$statusTxt = ($status == ACTION_IN_PROGRESS) ? 'Doit voter' : 'A voté';
						break;
				}
				break;
		}
	}

	return $statusTxt;
}

function _getGameMessages($gameID, $json = false) {
	$messages = getGameMessages($gameID);
	$messagesTexts = '';
	foreach($messages as &$message) {
		$messagesTexts .= '<h4>' . $message['us_pseudo'] .'</h4><p>' . stripslashes($message['ch_text']) . '</p>';
	}
	if(isPost())
		if($json)
			return json_encode($messages);
		else
			echo $messagesTexts;
	else
		return $messagesTexts;
}

function _getUserPointsMsg() {
	$msg = '';
	$turnID = $_POST['turnID'];
	$userID = $_POST['userID'];
	$gameID = $_POST['gameID'];
	/*$turnID = 30;
	$gameID = 2;*/

	$storytellerID = _getCurrentGameTurn($gameID, 'us_id');

	/* Carte voté par le joueur */
	$userVoteCardID = _getUserVotedCardInTurn($turnID, $userID);

	/* Liste des votes pour chaque carte */
	$cardsVotes = getTurnsVote($turnID);
	/*debug($cardsVotes);*/

	/* Nombre de joueurs dans la partie */
	$playersInGame = (int)getOneRowResult(getGameInfos($gameID, array('ga_nb_players')), 'ga_nb_players');

	/* Carte du storyteller */
	$stCardID = getOneRowResult(getPlayerCardInBoard($turnID, $storytellerID), 'ca_id');
	/*echo "stCardID";
	var_dump($stCardID);*/


	/* Nombre de vote sur la carte du storyteller */
	$nbVoteOnStCard = getOneRowResult(countCardVoteInTurn($stCardID, $turnID), 'nbVotes');

	if($storytellerID == $userID) {
		if($nbVoteOnStCard == $playersInGame-1) {
			$msg = 'Tout le monde a trouvé votre carte ! Votre indice était trop simple.<br />Vous <strong>ne gagnez aucun point</strong>';
		}
		else if($nbVoteOnStCard == 0) {
			$msg = 'Personne n\'a trouvé votre carte ! Votre indice était trop difficile.<br />Vous <strong>ne gagnez aucun point</strong>';
		}
		else {
			$msg = 'Bravo ! Certains joueurs ont trouvés votre carte. <br />Vous <strong>gagnez 3 points </strong>';
		}
	}
	else {
		if($nbVoteOnStCard == $playersInGame-1) {
			$msg = 'Tous les joueurs ont trouvé la carte du conteur. <br />Vous <strong>gangez 2 points </strong>';
		}
		else {
			$usersPoints = (int)getOneRowResult(getUsersPointsInTurn($userID, $turnID), 'points');
			if($userVoteCardID == $stCardID) {
				$msg = 'Bravo ! Vous avez trouvé la carte du conteur. ';
				if($usersPoints-3 > 0) {
					$msg .= 'De plus, <strong>'.(($usersPoints-3 == 1) ? $usersPoints-3 . ' joueur a voté pour votre carte</strong>' : $usersPoints-3 . ' joueurs ont voté pour votre carte').'</strong><br />';
				}
				else {
					$msg .= 'Aucun joueur n\'a voté pour votre carte<br />';
				}
				$msg .= 'Vous <strong>gagnez '.$usersPoints.' points</strong>';
			}
			else {
				$msg = 'Vous n\'avez pas trouvé la carte du conteur. ';
				if($usersPoints-2 > 0) {
					$msg .= 'Mais <strong>'.(($usersPoints-2 == 1) ? $usersPoints-2 . ' joueur a voté pour votre carte' : $usersPoints-2 . ' joueurs ont voté pour votre carte').'</strong><br />';
				}
				else {
					$msg .= 'Aucun joueur n\'a voté pour votre carte<br />';
				}
				$msg .= ($usersPoints != 0) ? 'Vous <strong>gagnez '.$usersPoints.' points</strong>' : 'Vous <strong>ne gagnez pas de point</strong>';
			}
		}
	}

	echo $msg;
}

function _roomAjax() {
	$gameID = $_POST['gameID'];
	$oldUsersInGame = $_POST['usersInGame'];
	$startGame = (boolean)!checkPlayersInGame($gameID);
	$usersInGame = getSpecificArrayValues(getPlayersInGame($gameID), 'us_id');
	$joinGame = true;
	$diff = array();
	if(count($usersInGame) > count($oldUsersInGame)) { //Un ou plusieurs joueurs sont rentrés en jeu
		$joinGame = true;
		$diff = array_diff($usersInGame, $oldUsersInGame);
	}
	else if(count($oldUsersInGame) > count($usersInGame)) {
		$joinGame = false;
		$diff = array_diff($oldUsersInGame, $usersInGame);
	}
	$usersNames = (!empty($diff)) ? array() : -1;
	foreach($diff as $userID) {
		$usersNames[] = getOneRowResult(getUserInfos($userID), 'us_pseudo');
	}
	$gameMessages = _getGameMessages($gameID, true);
	echo json_encode(compact("oldUsersInGame", "startGame", "gameMessages", "usersNames", "joinGame", "usersInGame"));
}

function _ajaxData($gameID, $oldPhase, $oldTurnID) {

	$userID = $_SESSION[USER_MODEL][USER_PK];
	$turnID = _getCurrentGameTurn($gameID, 'tu_id');
	$storytellerID = _getCurrentGameTurn($gameID, 'us_id');
	$turnComment = _getCurrentGameTurn($gameID, 'tu_comment');
	$storyteller = getOneRowResult(getUserInfos($storytellerID, array('us_pseudo')), 'us_pseudo');
	$nextStoryteller = getOneRowResult(getUserInfos($storytellerID, array('us_pseudo')), 'us_pseudo');
	$phase = _getActualGamePhase($gameID, $turnID);
	$actionStatus = _checkAction($phase, $userID, $turnID);
	$phaseInfos = json_encode(_getPhaseInfos($userID == $storytellerID, $phase, $actionStatus));
	$playersInfos = json_encode(_getPlayersInfos($gameID, $turnID, $storytellerID, $phase));
	$board = '';
	$hand = '';

	if($phase == BOARD_PHASE) {
		$board = _getBoard(BOARD_PHASE, $gameID, array('tu_id' => $turnID,
														'us_id' => $storytellerID), $userID == $storytellerID, $actionStatus);	
	}
	if($phase == POINTS_PHASE) {
		if(_checkIfPlayersAreReady($gameID)) {
			if(_isGameOver($gameID)) {
				//Partie finie
			}
			else if($oldTurnID == $turnID) { /* Vérification si le nouveau tour n'a pas déjà commencé */
				_startNewTurn($gameID, $storytellerID, $oldTurnID);
			}
		}
	}
	if($phase != $oldPhase) {
		$board = _getBoard($phase, $gameID, array('tu_id' => $turnID,
												'us_id' => $storytellerID), $userID == $storytellerID, $actionStatus);
		$hand = _getHand($phase, $userID, $gameID, $turnID, $userID == $storytellerID, $actionStatus);
	}

	$result = compact("userID", "turnID", "storytellerID", "turnComment", "storyteller", "phase", "actionStatus", "phaseInfos", "playersInfos", "board", "hand");

	echo json_encode($result);
}