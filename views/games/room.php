<h1>Salon d'attente pour la partie : <?php echo $gameInfos['ga_name'];?></h1>

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