<p>Vous êtes connecté en tant que : <?php echo $_SESSION[USER_MODEL]['us_pseudo'];?></p>

<div id="phaseInfos">
	<h1>Phase actuelle : <?php echo $turn['phase']['title'];?></h1>
	<h2><?php echo $turn['phase']['infos'];?></h2>
</div>

<h3>Conteur : <?php echo $turn['storyteller']['us_pseudo'];?></h3>
<p id="turnComment"><?php echo $turn['tu_comment'];?></p>

<div id="players">
	<table>
		<caption>Joueurs</caption>
		<tr>
			<th>Pseudo : </th>
			<th>Points : </th>
			<th>Statut : </th>
		</tr>
		<?php foreach($turn['players'] as $player): ?>
		<tr>
			<td><?php echo $player['us_pseudo'] . (($player['role'] == 'conteur') ? ' : conteur' : '');?></td>
			<td><?php echo ($player['points'] != null) ? $player['points'] : '0';?></td>
			<td><?php _displayStatus($player['status'], $turn['phase']['id'], $player['role']);?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
<h3>Table</h3>
<div id="table">
	<?php
	_displayBoard($turn['phase']['id'], $turn['game']['ga_id'], $turn, $storyteller, $actionStatus);
	?>
</div>
<h3>Votre main</h3>
<div id="hand">
	<?php
	_displayHand($turn['phase']['id'], $_SESSION[USER_MODEL][USER_PK], $turn['game']['ga_id'], $turn['tu_id'], $storyteller, $actionStatus);
	?>
</div>

<script type="text/javascript">

	//Par défaut le tour courant et la phase courante sont ceux défini en PHP (i.e le premier tour et la première phase)
	currentTurnID = <?php echo $turn['tu_id'];?>;
	storytellerID = <?php echo $turn['us_id'];?>;
	phaseID = <?php echo $turn['phase']['id'];?>;
	actionStatus = <?php echo $actionStatus;?>;
	gameID = <?php echo $turn['game']['ga_id'];?>;
	userID = <?php echo $_SESSION[USER_MODEL][USER_PK];?>;
	storyteller = <?php echo ($storyteller) ? 'true' : 'false';?>;
	BASE_URL = '<?php echo BASE_URL;?>';

	function readyForNextTurn() {
		//récupération du tour actuel
		$.post(BASE_URL+"games/_getCurrentGameTurn/"+gameID+"/tu_id", function(data) {
	   		currentTurnID = data;

	   		//récupération du storyteller actuel
		 	$.post(BASE_URL+"games/_getCurrentGameTurn/"+gameID+"/us_id", function(data) {
		 		storytellerID = data;
		 		console.log("STORYTELLER ID = "+storytellerID);
				$.post(BASE_URL+"games/_setPlayerStatus/"+gameID+"/"+userID+"/1", function(data) {
					//maj infos phase
					$('#phaseInfos').load(BASE_URL+"games/_getPhaseInfos", { 'actionStatus': actionStatus, 'phaseID': phaseID, 'storyteller': storyteller},function() {
				  		console.log('infos phase OK');
					});

					//maj infos joueurs
					$('#players').load(BASE_URL+"games/_getPlayersInfos", { 'gameID': gameID, 'currentTurnID': currentTurnID, 'storytellerID': storytellerID, 'phase': phaseID } ,function() {
					  console.log('players infos OK');
					});

					//maj turn comment
					$('#turnComment').load(BASE_URL+"games/_getTurnComment", {'turnID': currentTurnID}, function(){
						console.log('turnComment OK');
					});

					$('#readyForNextTurn').remove();
				});
		 	});

		 });

	}
	setInterval(function(){
		//récupération du tour actuel
		$.post(BASE_URL+"games/_getCurrentGameTurn/"+gameID+"/tu_id", function(data) {
	   		currentTurnID = data;
	   		console.log("currentTurnID = "+currentTurnID);

	   		//récupération du storyteller actuel
		 	$.post(BASE_URL+"games/_getCurrentGameTurn/"+gameID+"/us_id", function(data) {
		   		storytellerID = data;
		   		console.log("storytellerID = "+storytellerID);
		   		storyteller = (storytellerID == userID) ? true : false;
		   		console.log("Storyteller = "+storyteller);

		   		//récupération de la phase actuelle
				$.post(BASE_URL+"games/_getActualGamePhase/"+gameID+"/"+currentTurnID, function(data) {
			   		oldPhase = phaseID;
			   		phaseID = data;

			   		//récupération du status du joueur dans la phase actuelle
			   		$.post(BASE_URL+"games/_checkAction/"+phaseID+"/"+userID+"/"+currentTurnID+"/false", function(data) {
			   			actionStatus = data;

			   			//Chargement du commentaire lié au tour
						$('#turnComment').load(BASE_URL+"games/_getTurnComment", {'turnID': currentTurnID}, function(){
							console.log('turnComment OK');
						});

			   			//Chargement des infos de la phase
			   			$('#phaseInfos').load(BASE_URL+"games/_getPhaseInfos", { 'actionStatus': actionStatus, 'phaseID': phaseID, 'storyteller': storyteller},function() {
					  		console.log('infos phase OK');
						});

						if(phaseID == '1') {
							$('#table').load(BASE_URL+"games/_displayBoard", { 'phase': phaseID, 'gameID': gameID, 'turn': {'tu_id': currentTurnID, 'us_id': storytellerID}, 'storyteller': storyteller, 'actionStatus': actionStatus}, function() { 
				   				console.log('display board ok');
				   			});
						}
						if(phaseID == '3') {
							//On vérifie si les joueurs sont tous prêts
							$.post(BASE_URL+"games/_checkIfPlayersAreReady/"+gameID, function(data) {
								if(data == 'true') {
									//On vérifie si la partie n'est pas terminée
									$.post(BASE_URL+"games/_isGameOver/"+gameID, function(data) {
										//Si la partie est terminée on redirige vers la page de fin de partie
										if(data == 'true') {
											$(location).attr('href',BASE_URL+"games/gameOver/"+gameID);
										}
										else { //sinon on lance un nouveau tour
											$.post(BASE_URL+"games/_startNewTurn/"+gameID+"/"+storytellerID);
										}
									});
									
								}
							});
						}

			   			if(phaseID != oldPhase) {
				   			changePhaseNotification(phaseID);

				   			$('#table').load(BASE_URL+"games/_displayBoard", { 'phase': phaseID, 'gameID': gameID, 'turn': {'tu_id': currentTurnID, 'us_id': storytellerID}, 'storyteller': storyteller, 'actionStatus': actionStatus}, function() { 
				   				console.log('display board ok');
				   			});
				   			$('#hand').load(BASE_URL+"games/_displayHand", {'phase': phaseID, 'userID': userID, 'gameID': gameID, 'turnID': currentTurnID, 'storyteller': storyteller, 'actionStatus': actionStatus}, function() { 
				   				console.log('display hand ok');
				   			});
				   		}
				   		console.log("data phaseID = "+phaseID);
				   		$('#players').load("<?php echo BASE_URL;?>games/_getPlayersInfos", { 'gameID': gameID, 'currentTurnID': currentTurnID, 'storytellerID': storytellerID, 'phase': phaseID } ,function() {
						  console.log('OK');
						});

			   		});

			 	});

		 	});
	 	});
	}, 1000);

	function changePhaseNotification(phaseID) {
		var STORYTELLER_PHASE = '0';
		var BOARD_PHASE = '1';
		var VOTE_PHASE = '2';
		var POINTS_PHASE = '3';
		var phase;

		console.log(phaseID);
		switch(phaseID) {
			case STORYTELLER_PHASE:
				phase = 'Un nouveau tour commence : tour du conteur';
				break;
			case BOARD_PHASE:
				phase = 'Tour des joueurs';
				break;
			case VOTE_PHASE:
				phase = 'Phase de vote';
				break;
			case POINTS_PHASE:
				phase = 'Décompte des points';
				break;
			default:
				phase = 'Erreur';
				break;
		}

		alert(phase);
	}

</script>
