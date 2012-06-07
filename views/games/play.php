<div id="contentLeft">
	<img class="arrowGame" src="<?php echo IMG_DIR;?>arrow_right.png" />
	<div id="content_room">
		<div id="left_room">
			<div id="conteur_room">
				<div class="conteur_info">
					<h1>Salon d'attente pour la partie : <?php echo $gameInfos['ga_name'];?></h1>
				</div>
			</div>
			<div id="table_room">
				<?php if(!$gameIsStarted) { ?>
					<p><?php echo $gameInfos['action'];?></p>
					<div class="scroll">
						<table id="usersInfos" >
							<tr id="firstTR">
								<th>Joueur</th>
								<th>% parties gagnées</th>
								<th>Points d'expérience (classement)</th>
							</tr>
							<?php foreach($usersInGame as $user): ?>
							<tr>
								<td><?php echo $user['us_pseudo'];?></td>
								<td><?php echo $user['percentageWins'].'% ('.$user['nbWins'].'/'.$user['nbGames'].')';?></td>
								<td><?php echo $user['xp'].' ('.$user['classement'].')';?></td>
							</tr>
							<?php endforeach ?>
						</table>
					</div>
				<?php } if($gameIsOver) { ?>
					<div class="scroll">
						<table id="usersInfos" >
							<tr id="firstTR">
								<th></th>
								<th>Joueur</th>
								<th>Points dans la partie</th>
								<th>Points d'expérience gagnés</th>
							</tr>
							<?php $i=0;
							$classement = _getClassement($gameInfos['ga_id']);?>
						<?php foreach($classement as $player) : $i++; ?>
							<tr>
								<td><?php echo $i;?></td>
								<td><?php echo $player['us_pseudo'];?></td>
								<td><?php echo $player['points'];?> points</td>
								<td>+<?php echo $player['xp'];?> XP</td>
							</tr>
							<?php endforeach ?>
						</table>
					</div>
				<?php } ?>
			</div>
			<div id="deck_room">
				<div id="gallery">
					<div id="gallery_conteneur">
							<?php foreach($cards as $card): ?>
								<div class="carte">
									<img class="image_carte"  src="<?php echo IMG_DIR;?>cards/<?php echo $card['ca_image'];?>" alt="<?php echo $card['ca_name'];?>"/>
								</div>
							<?php endforeach;?>
					</div>
				</div>
			</div>
		</div>
		<div id="sidebar_room">
			<div id="players_room">
				<img id="label_joueurs_room" src="<?php echo IMG_DIR;?>joueurs.png">
				<?php foreach($usersInGame as $player): ?>
				<div class="joueur">
					<img class="profil_joueur" src="<?php echo IMG_DIR.$player['us_avatar'];?>" />
					<p class="infos_joueur"><?php echo $player['us_pseudo'] . (($player['us_id'] == $gameInfos['us_id']) ? ' : Hôte' : '');?></p>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<div id="contentRight">
	<img class="arrowRoom" src="<?php echo IMG_DIR;?>arrow_left.png" />
		<div id="content">
			<div id="left">
				<div id="conteur">
					<img id="profil_conteur" src="<?php echo IMG_DIR;?>profil.png"/>
					<div class="conteur_info">
						<p id="storyteller" ><b><?php echo $turn['storyteller']['us_pseudo'];?></b></p>
						<p id="turnComment"><b>«&nbsp;&nbsp;</b><?php echo $turn['tu_comment'];?><b>&nbsp;&nbsp;»</b></p>
					</div>
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
				</div>
			</div>
			<div id="sidebar">
				<div id="players">
					<img id="label_joueurs" src="<?php echo IMG_DIR;?>joueurs.png" />
					<?php foreach($turn['players'] as $player): ?>
					<div class="joueur">
						<img class="profil_joueur" src="<?php echo IMG_DIR.$player['us_avatar'];?>" />
						<p class="infos_joueur"><?php echo $player['us_pseudo'] . (($player['role'] == 'conteur') ? ' : conteur' : '');?><br /><?php echo ($player['points'] != null) ? $player['points'] : '0';?> points<br /><?php echo $player['status'];?></p>
					</div>
					<?php endforeach; ?>
				</div>
				<div id="chat">
					<img class="label_chat" src="<?php echo IMG_DIR;?>chat.png" />
					<div class="chatMessages">
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
</div>

<div id="ping">

</div>

<script type="text/javascript">

/*$("header").click(function() {
  $("#ping").html("<object type='audio/mpeg' width='100' height='40' data='<?php echo BASE_URL;?>views/themes/default/ping.mp3'><param name='filename' value='<?php echo BASE_URL;?>views/themes/default/ping.mp3' /><param name='autostart' value='true' /><param name='loop' value='false' /></object>");
 setTimeout("callback_ping()",500);
});

function callback_ping(){
	$("#ping").html("");
}*/

	// Positionnement des flèches

	w=screen.availWidth;
	h=screen.availHeight;
	contentw=900;
	$(".arrowRoom").css("top",(h/2)-100);
	$(".arrowGame").css("top",(h/2)-100);
	$(".arrowRoom").css("left",(w-contentw)/4-75);
	$(".arrowGame").css("left",w-((w-contentw)/4)-75);

	//Par défaut le tour courant et la phase courante sont ceux défini en PHP (i.e le premier tour et la première phase)
	gameIsStarted = <?php echo ($gameIsStarted) ? '1' : '0';?>;
	gameIsOver = <?php echo ($gameIsOver) ? '1' : '0';?>;
	gameID = <?php echo $turn['game']['ga_id'];?>;
	userID = <?php echo $_SESSION[USER_MODEL][USER_PK];?>;
	hostID = <?php echo $gameInfos['us_id'];?>;
	showedWindow = (gameIsStarted && !gameIsOver) ? 'play' : 'room';
	notificationGameStart = <?php echo ($gameIsStarted) ? 'false' : 'true';?>;

	BASE_URL = '<?php echo BASE_URL;?>';
	IMG_DIR = '<?php echo IMG_DIR;?>';
	FLASH_SUCCESS = '<?php echo FLASH_SUCCESS;?>';
	FLASH_ERROR = '<?php echo FLASH_ERROR;?>';
	FLASH_INFOS = '<?php echo FLASH_INFOS;?>';
	FLASH_MESSAGE = '<?php echo FLASH_MESSAGE;?>';

	swapWindow(showedWindow);
	phaseID = <?php echo (isset($turn['phase']['id'])) ? $turn['phase']['id'] : 0;?>;
	turnID = <?php echo (isset($turn['tu_id'])) ? $turn['tu_id'] : 0;?>;
	alertDelayInactivity = 0;
	
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
		$(".fancybox").fancybox();
	});

	function selectCard(inputName, divID, cardID) {
		console.log($('#'+divID+' input[name='+inputName+']').val());

		/* Parcours des cartes pour replacer le bouton rouge normal */
		$('#'+divID+' .bouton').each(function(i) { 
			$(this).attr('src', IMG_DIR+'bouton.png');
		});

		/* Bouton doré pour la carte sélectionnée */
		$('#'+divID+' #btnCardID'+cardID).attr('src', IMG_DIR+'bouton_dore.png');

		/* Modification de la valeur du champ hidden cardID en conséquence */
		$('#'+divID+' input[name='+inputName+']').val(cardID);

		console.log($('#'+divID+' input[name='+inputName+']').val());

		if(divID == 'table') {
			/* Ajout de la carte */
			$.post(BASE_URL+"games/vote/", {cardID: cardID, turnID: turnID, gameID: gameID}, function(data) {
				/* Redirection vers la bonne page */
				$(location).attr('href',BASE_URL+'games/play/'+gameID);
			});
		}
	}

	function updateCard(inputName, divID, cardID) {

		/* Parcours des cartes pour replacer le bouton rouge normal */
		$('#'+divID+' .bouton').each(function(i) { 
			$(this).attr('src', IMG_DIR+'bouton.png');
				buttonID = $(this).attr('id');
				currentCardID = buttonID.substring(9);
				console.log("ID carte bouton : "+currentCardID);
			$(this).attr('onclick', 'updateCard("'+inputName+'", "'+divID+'", '+currentCardID+')');
		});

		/* Bouton doré pour la carte sélectionnée */
		$('#'+divID+' #btnCardID'+cardID).attr('src', IMG_DIR+'bouton_dore.png');
		$('#'+divID+' #btnCardID'+cardID).attr('onclick', '');

		/* Modification de la valeur du champ hidden cardID en conséquence */
		$('#'+divID+' input[name='+inputName+']').val(cardID);

		console.log("updateCard");
		/* Update de la carte */
		$.post(BASE_URL+"games/updateVote/", {cardID: cardID, turnID: turnID, gameID: gameID}, function(data) {
			/* Update boutons */
			Dixit.alert('Votre vote à été mis à jour', Dixit.FLASH_SUCCESS);
			/*$(location).attr('href',BASE_URL+'games/play/'+gameID);*/
		});
	}

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
		if(gameIsStarted){
			if(notificationGameStart) {
				Dixit.alert('La partie commence !', Dixit.FLASH_INFOS);
				notificationGameStart = false;
				$(location).attr('href',BASE_URL+'games/play/'+gameID);
			}
			$.post(BASE_URL+"games/_ajaxData/"+gameID+"/"+phaseID+"/"+turnID, function(json) {
				var oldPhase = phaseID;
				var result = parseJSON(json);
				phaseID = result.phaseID;
				turnID = result.turnID;
				if(phaseID != oldPhase) {
					changePhaseNotification(phaseID);
				}
			});
		}
	}, 5000);
	
	function parseJSON(json) {
		var obj = $.parseJSON(json);
		text = '';
		$.each(obj, function(key, val) {
			text +=key + '>' + val + '\n';
		});
		displayPhaseInfos($.parseJSON(obj.phaseInfos));
		$("#storyteller").html("<b>"+obj.storyteller+"</b>");
		$("#turnComment").html("<b>«&nbsp;&nbsp;</b>"+obj.turnComment+"<b>&nbsp;&nbsp;»</b>");
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
			if(player.inactivityTime > Dixit.TIME_BEFORE_INACTIVE) {
				console.log("alertDelayInactivity = "+alertDelayInactivity);
				if(player.us_id == userID) {
					alertDelayInactivity++;
					if(alertDelayInactivity == 3) {
						Dixit.alert('Les joueurs vous attendent !', Dixit.FLASH_ALERT);
						alertDelayInactivity = 0;
					}
				}
			}
			$("#players").append('<div class="joueur">'
									+'<img class="profil_joueur" src="'+IMG_DIR+player.us_avatar+'">'
									+'<p class="infos_joueur">'+player.us_pseudo+((player.role == 'conteur') ? ' : conteur' : '')+'<br />'+((player.points != null) ? player.points : '0')+'points<br />'+player.status+'</p>'
								+'</div>');
		});
	}

	function changePhaseNotification(phaseID) {
		var STORYTELLER_PHASE = 0;
		var BOARD_PHASE = 1;
		var VOTE_PHASE = 2;
		var POINTS_PHASE = 3;
		var GAME_OVER = 4;
		var phase;

		console.log(phaseID);
		switch(phaseID) {
			case STORYTELLER_PHASE:
				phase = 'Un nouveau tour commence : tour du conteur';
				Dixit.alert(phase, FLASH_INFOS);
				break;
			case BOARD_PHASE:
				phase = 'Tour des joueurs';
				Dixit.alert(phase, FLASH_INFOS);
				break;
			case VOTE_PHASE:
				phase = 'Phase de vote';
				Dixit.alert(phase, FLASH_INFOS);
				break;
			case POINTS_PHASE:
				$.post(BASE_URL+"games/_getUserPointsMsg/", {userID: userID, turnID: turnID, gameID: gameID}, function(data) {
					phase = data;
					Dixit.alert(phase, FLASH_INFOS);
				});
				break;
			case GAME_OVER:
				phase = 'Fin de la partie';
				$(location).attr('href',BASE_URL+'games/play/'+gameID);
				Dixit.alert(phase, FLASH_INFOS);
				break;
			default:
				phase = 'Erreur';
				Dixit.alert(phase, FLASH_INFOS);
				break;
		}
		
		/*alert(phase);*/

	}
	
	// FlipCards
	$("#table .carte").hover(function(){
		$(this).children('.back_carte').css('-webkit-transform','translateZ(-10px) rotateY(360deg)');
		$(this).children('.back_carte').css('-moz-transform','translateZ(-10px) rotateY(360deg)');
		$(this).children('.back_carte').css('-o-transform','translateZ(-10px) rotateY(360deg)');
		$(this).children('.image_carte_flip').css('-webkit-transform','rotateY(180deg)');
		$(this).children('.image_carte_flip').css('-moz-transform','rotateY(180deg)');
		$(this).children('.image_carte_flip').css('-o-transform','rotateY(180deg)');
		
	}, 
	  function() {
		$(this).children('.back_carte').css('-webkit-transform','translateZ(-10px) rotateY(180deg)');
		$(this).children('.back_carte').css('-moz-transform','translateZ(-10px) rotateY(180deg)');
		$(this).children('.back_carte').css('-o-transform','translateZ(-10px) rotateY(180deg)');
		$(this).children('.image_carte_flip').css('-webkit-transform','rotateY(0deg)');
		$(this).children('.image_carte_flip').css('-moz-transform','rotateY(0deg)');
		$(this).children('.image_carte_flip').css('-o-transform','rotateY(0deg)');
	  });
	
	if(!gameIsStarted || gameIsOver) $('.arrowGame').hide();

	$('#gameChat').submit(function() {
		sendMsg();
		return false;
	})

	
	$('#gameMsg').keyup(function(e) {
      if(e.keyCode == 13) {
        sendMsg();
		return false;
       }
	});
	

	function sendMsg() {
		var message = $('#gameMsg').val();
		$.post(BASE_URL+"chats/_addGameMessage",{gameID: gameID, userID: userID, message: message}, function(data) {
			$.post(BASE_URL+"games/_getGameMessages/"+gameID, function(data) {
				$('.chatMessages').empty();
				$('.chatMessages').html(data);
				$('#gameMsg').val('');
				$('.chatMessages').animate({scrollTop: $('.chatMessages').prop('scrollHeight')}, 500);
			});
		}, "json");
	}

	setInterval(function(){
		$.post(BASE_URL+"games/_getGameMessages/"+gameID, function(data) {
			var $elem = $('.chatMessages');
			$('.chatMessages').empty();
			$('.chatMessages').html(data);
			// $('#chatMessages').scrollTop($('#chatMessages').prop('scrollHeight'));
			$('.chatMessages').animate({scrollTop: $('.chatMessages').prop('scrollHeight')}, 500);
		});
	}, 2000);

	usersIDs = <?php echo $jsonUsersInGame;?>;

	setInterval(function() {
		$.post(Dixit.BASE_URL+"games/_roomAjax",{gameID: gameID, usersIDs: usersIDs}, function(json) {
			var result = $.parseJSON(json);
			usersIDs = result.usersIDs;
			if(!gameIsStarted) {
				phaseID = result.phaseID;
				turnID = result.turnID;
			}
			gameIsStarted = result.startGame;
			displayRoomPlayersInfos(result.usersInGame);
			var notif = '';
			var textAction = (result.joinGame) ? "rejoint" : "quitté";
			if(result.usersNames != -1) {
				var nbUsers = result.usersNames.length;
				if(nbUsers == 1) {
					notif = result.usersNames[0]+notif+" a "+textAction+" la partie";
				}
				else {
					for(var i=0; i<nbUsers; i++) {
						notif += " "+result.usersNames[i]+",";
					}
					notif = rtrim(notif, ",");
					notif += " ont "+textAction+" la partie";
				}
				Dixit.alert(notif, Dixit.FLASH_INFOS);
			}
		});
	}, 2000);

	function displayRoomPlayersInfos(usersInGame) {
		text = '';
		console.log(usersInGame);
		$("#players_room").html('<img id="label_joueurs_room" src="'+IMG_DIR+'joueurs.png">');

		$.each(usersInGame, function(key, player) {
			console.log(player);
			$("#players_room").append('<div class="joueur">'
										+'<img class="profil_joueur" src="'+IMG_DIR+player.us_avatar+'">'
										+'<p class="infos_joueur">'+player.us_pseudo+((player.us_id == hostID) ? ' : hôte' : '')+'</p>'
									+'</div>');
		});
	}

</script>
