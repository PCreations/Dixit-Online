<div id="content">
	<div id="left">
		<div id="conteur">
			<img id="profil_conteur" src="<?php echo IMG_DIR;?>profil.png">
			<p id="turnComment"><?php echo $turn['storyteller']['us_pseudo'];?><br><?php echo $turn['tu_comment'];?></p>
		</div>
		<div id="table">
			<?php
			echo _getBoard($turn['phase']['id'], $turn['game']['ga_id'], $turn, $storyteller, $actionStatus);
			?>
		</div>
		<div id="main">
			<img id="label_main" src="<?php echo IMG_DIR;?>votre_main.png">
			<div id="hand">	
				<?php
				echo _getHand($turn['phase']['id'], $_SESSION[USER_MODEL][USER_PK], $turn['game']['ga_id'], $turn['tu_id'], $storyteller, $actionStatus);
				?>
			</div>
			<img id="label_tour" src="<?php echo IMG_DIR;?>tour_en_cours.png">
			<p><?php echo $turn['phase']['infos'];?> Le prochain conteur est Thomas Demenat</p>
		</div>
	</div>
	<div id="sidebar">
		<div id="players">
			<img id="label_joueurs" src="<?php echo IMG_DIR;?>joueurs.png">
			<?php foreach($turn['players'] as $player): ?>
			<div class="joueur">
				<img class="profil_joueur" src="<?php echo IMG_DIR;?>profil.png">
				<p class="infos_joueur"><?php echo $player['us_pseudo'] . (($player['role'] == 'conteur') ? ' : conteur' : '');?><br /><?php echo ($player['points'] != null) ? $player['points'] : '0';?> points<br /><?php echo $player['status'];?></p>
			</div>
			<?php endforeach; ?>
		</div>
		<div id="chat">
			<img id="label_chat" src="<?php echo IMG_DIR;?>chat.png">
			<div id="chatMessages">
				<?php 
				echo _getGameMessages($turn['game']['ga_id']);
				?>
			</div>
			<form id="gameChat" action="<?php echo BASE_URL;?>chats/addGameMessage" method="POST">
				<textarea name="gameMsg" id="gameMsg"></textarea>
				<input type="submit" value="Envoyer" id="sendGameMsg"/>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">

	//Par défaut le tour courant et la phase courante sont ceux défini en PHP (i.e le premier tour et la première phase)
	phaseID = <?php echo $turn['phase']['id'];?>;
	gameID = <?php echo $turn['game']['ga_id'];?>;
	userID = <?php echo $_SESSION[USER_MODEL][USER_PK];?>;
	turnID = <?php echo $turn['tu_id'];?>;

	BASE_URL = '<?php echo BASE_URL;?>';
	IMG_DIR = '<?php echo IMG_DIR;?>';

	$('#gameChat').submit(function() {
		var message = $('#gameMsg').val();
		$.post(BASE_URL+"chats/_addGameMessage",{gameID: gameID, userID: userID, message: message}, function(data) {
			$.post(BASE_URL+"games/_getGameMessages/"+gameID, function(data) {
				$('#chatMessages').empty();
				$('#chatMessages').html(data);
				$('#gameMsg').val('');
				$('#chatMessages').animate({scrollTop: $('#chatMessages').prop('scrollHeight')}, 500);
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

	$(document).ready(function() {
		$('.handCardLabel').each(function(i) { 
			$(this).click(function() {
				chooseSTcard($(this).attr('for'));
			});
		});
		$('.boardCardLabel').each(function(i) { 
			$(this).click(function() {
				voteForCard($(this).attr('for'));
			});
		});
		$('#readyForNextTurn').click(function() {
			console.log('ok');
			readyForNextTurn();
		})
	});

	function voteForCard(id) {
		$('input[name=cardID]').val([id]);

		var card = $('input[name="cardID"]:checked').val();
		$('#boardForm').submit();
	}

	function chooseSTcard(id) {
		// On coche Non
		$('input[name=cardID]').val([id]);

		var card = $('input[name="cardID"]:checked').val();
		$('#handForm').submit();
	}
	setInterval(function(){
		$.post(BASE_URL+"games/_getGameMessages/"+gameID, function(data) {
			var $elem = $('#chatMessages');
			$('#chatMessages').empty();
			$('#chatMessages').html(data);
			// $('#chatMessages').scrollTop($('#chatMessages').prop('scrollHeight'));
			$('#chatMessages').animate({scrollTop: $('#chatMessages').prop('scrollHeight')}, 500);
		});
	}, 2000);

	setInterval(function(){
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
	}, 5000);

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
		
		$("#players").html('<img id="label_joueurs" src="'+IMG_DIR+'joueurs.png">');

		$.each(playersInfos, function(key, player) {
			console.log(player);
			$("#players").append('<div class="joueur">'
									+'<img class="profil_joueur" src="'+IMG_DIR+'profil.png">'
									+'<p class="infos_joueur">'+player.us_pseudo+((player.role == 'conteur') ? ' : conteur' : '')+'<br />'+((player.points != null) ? player.points : '0')+'points<br />'+player.status+'</p>'
								+'</div>');
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
