<p>Vous êtes connecté en tant que : <?php echo $_SESSION[USER_MODEL]['us_pseudo'];?></p>
<h1>Phase actuelle : <?php echo $turn['phase']['title'];?></h1>
<h2><?php echo $turn['phase']['infos'];?></h2>

<h3>Conteur : <?php echo $turn['storyteller']['us_pseudo'];?></h3>
<p><?php echo $turn['tu_comment'];?></p>

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
<?php
_displayBoard($turn['phase']['id'], $turn['game']['ga_id'], $turn, $storyteller, $actionStatus);
?>

<h3>Votre main</h3>
<?php
_displayHand($turn['phase']['id'], $_SESSION[USER_MODEL][USER_PK], $turn['game']['ga_id'], $turn['tu_id'], $storyteller, $actionStatus);
?>

<script type="text/javascript">
	setInterval(function(){
		//Par défaut le tour courant et la phase courante sont ceux défini en PHP (i.e le premier tour et la première phase)
		currentTurnID = <?php echo $turn['tu_id'];?>;
		storytellerID = <?php echo $turn['us_id'];?>;
		phaseID = <?php echo $turn['game']['ga_id'];?>;
		gameID = <?php echo $turn['game']['ga_id'];?>;

		//récupération du tour actuel
		$.post("<?php echo BASE_URL;?>games/_getCurrentGameTurn/"+gameID+"/tu_id", function(data) {
	   		currentTurnID = data;
	   		console.log("currentTurnID = "+currentTurnID);

	   		//récupération du storyteller actuel
		 	$.post("<?php echo BASE_URL;?>games/_getCurrentGameTurn/"+gameID+"/us_id", function(data) {
		   		storytellerID = data;
		   		console.log("storytellerID = "+storytellerID);

		   		//récupération de la phase actuelle
				$.post("<?php echo BASE_URL;?>games/_getActualGamePhase/"+gameID+"/"+currentTurnID, function(data) {
			   		phaseID = data;
			   		console.log("data phaseID = "+phaseID);

			   		$('#players').load("<?php echo BASE_URL;?>games/_getPlayersInfos", { 'gameID': gameID, 'currentTurnID': currentTurnID, 'storytellerID': storytellerID, 'phase': phaseID } ,function() {
					  console.log('OK');
					});
			 	});

		 	});
	 	});
	}, 10000);

</script>