<p>Vous êtes connecté en tant que : <?php echo $_SESSION[USER_MODEL]['us_pseudo'];?></p>

<div id="phaseInfos">
	<h1>Phase actuelle : <?php echo $turn['phase']['title'];?></h1>
	<h2><?php echo $turn['phase']['infos'];?></h2>
</div>

<h3 id="storyteller">Conteur : <?php echo $turn['storyteller']['us_pseudo'];?></h3>
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
			<td><?php echo $player['status'];?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
<h3>Table</h3>
<div id="table">
	<?php
	echo _getBoard($turn['phase']['id'], $turn['game']['ga_id'], $turn, $storyteller, $actionStatus);
	?>
</div>
<h3>Votre main</h3>
<div id="hand">
	<?php
	echo _getHand($turn['phase']['id'], $_SESSION[USER_MODEL][USER_PK], $turn['game']['ga_id'], $turn['tu_id'], $storyteller, $actionStatus);
	?>
</div>

<h3>Chat</h3>
<div id="chatMessages">
	<?php 
	echo _getGameMessages($turn['game']['ga_id']);
	?>
</div>
<form id="gameChat" action="<?php echo BASE_URL;?>chats/addGameMessage" method="POST">
	<textarea name="gameMsg" id="gameMsg">
	</textarea>
	<input type="submit" value="Envoyer" id="sendGameMsg"/>
</form>

<script type="text/javascript">

	//Par défaut le tour courant et la phase courante sont ceux défini en PHP (i.e le premier tour et la première phase)
	phaseID = <?php echo $turn['phase']['id'];?>;
	gameID = <?php echo $turn['game']['ga_id'];?>;
	userID = <?php echo $_SESSION[USER_MODEL][USER_PK];?>;
	turnID = <?php echo $turn['tu_id'];?>;

	BASE_URL = '<?php echo BASE_URL;?>';

	$('#gameChat').submit(function() {
		var message = $('#gameMsg').val();
		$.post(BASE_URL+"chats/_addGameMessage",{gameID: gameID, userID: userID, message: message}, function(data) {
			$.post(BASE_URL+"games/_getGameMessages/"+gameID, function(data) {
				$('#chatMessages').empty();
				$('#chatMessages').html(data);
			});
		}, "json");
		return false;
	})

	function readyForNextTurn() {
		//$('#readyForNextTurn').click();
		$.ajax({
		  url: BASE_URL+"games/_setPlayerStatus/"+gameID+"/"+userID+"/1",
		  async: false,
		  type: "POST",
		  success: function(data) {
				$('#readyForNextTurn').remove();
		  }
		});
		/*$.post(BASE_URL+"games/_setPlayerStatus/"+gameID+"/"+userID+"/1", function(data) {
			$.post(BASE_URL+"games/_ajaxData/"+gameID+"/"+phaseID+"/"+turnID, function(json) {
				
			});
		});*/
	}

	setInterval(function(){
		$.post(BASE_URL+"games/_getGameMessages/"+gameID, function(data) {
			$('#chatMessages').empty();
			$('#chatMessages').html(data);
		});
	}, 500);
	/*setInterval(function(){
		$.post(BASE_URL+"games/_ajaxData/"+gameID+"/"+phaseID+"/"+turnID, function(json) {
			var oldPhase = phaseID;
			var result = parseJSON(json);
			console.log(result);
			phaseID = result.phaseID;
			turnID = result.turnID;
			console.log("PHASE ID = "+phaseID);
			if(phaseID != oldPhase) {
				changePhaseNotification(phaseID);
			}
		});
	}, 5000);*/

	function parseJSON(json) {
		var obj = $.parseJSON(json);
		text = '';
		$.each(obj, function(key, val) {
			text +=key + '>' + val + '\n';
		});
		displayPhaseInfos($.parseJSON(obj.phaseInfos));
		$("#storyteller").html("Conteur : "+obj.storyteller);
		$("#turnComment").html(obj.turnComment);
		displayPlayersInfos($.parseJSON(obj.playersInfos));
		if(obj.board != '') {
			$("#table").html(obj.board);
		}
		if(obj.hand != '') {
			$("#hand").html(obj.hand);
		}
		return {'phaseID': obj.phase, 'turnID': obj.turnID};
	}

	function displayPhaseInfos(phaseInfos) {
		text = '';
		$.each(phaseInfos, function(key, val) {
			text +=key + '>' + val + '\n';
		});
		console.log(text);
		$("#phaseInfos").html("<h1>Phase actuelle : "+phaseInfos.title+"</h1><h2>"+phaseInfos.infos+"</h2>");
	}

	function displayPlayersInfos(playersInfos) {
		text = '';
		$.each(playersInfos, function(key, val) {
			playersInfos.key = $.parseJSON(playersInfos.val);
		});
		
		$("#players").html("<table>"
							+"<caption>Joueurs</caption>"
								+"<tr>"
									+"<th>Pseudo : </th>"
									+"<th>Points : </th>"
									+"<th>Statut : </th>"
								+"</tr>");
		$.each(playersInfos, function(key, player) {
			console.log(player);
			$("#players table").append("<tr>"
										+"<td>"+player.us_pseudo+((player.role == 'conteur') ? ' : conteur' : '')+"</td>"
										+"<td>"+((player.points != null) ? player.points : '0')+"</td>"
										+"<td>"+player.status+"</td>"
									+"</tr>");
		});
	}

	function changePhaseNotification(phaseID) {
		var STORYTELLER_PHASE = 0;
		var BOARD_PHASE = 1;
		var VOTE_PHASE = 2;
		var POINTS_PHASE = 3;
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
