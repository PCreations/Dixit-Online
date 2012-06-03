<div id="content">
	<div id="left">
		<div id="conteur">
			<img id="profil_conteur" src="<?php echo IMG_DIR;?>profil.png"/>
			<div class="conteur_info">
				<h1>Salon d'attente pour la partie : <?php echo $gameInfos['ga_name'];?></h1>
			</div>
		</div>
	</div>
	<div id="sidebar">
		<div id="players">
			<img id="label_joueurs" src="<?php echo IMG_DIR;?>joueurs.png">
			<?php foreach($usersInGame as $player): ?>
			<div class="joueur">
				<img class="profil_joueur" src="<?php echo IMG_DIR;?>profil.png">
				<p class="infos_joueur"><?php echo $player['us_pseudo'] . (($player['us_id'] == $gameInfos['us_id']) ? ' : Hôte' : '');?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	gameID = <?php echo $gameInfos['ga_id'];?>;
	usersInGame = <?php echo $usersInGame;?>;

	setInterval(function() {
		$.post(Dixit.BASE_URL+"games/_roomAjax",{gameID: gameID, usersInGame: usersInGame}, function(json) {
			var result = $.parseJSON(json);
			usersInGame = result.usersInGame;
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
			else {
				//alert('null');
			}
		});
	}, 2000);
</script>