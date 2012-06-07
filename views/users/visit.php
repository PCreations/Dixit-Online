<div id="visit">
	
		<img class="avatar" src="<?php echo IMG_DIR;?>" alt=""/>
		<div id="friendTitle"><?php echo $friend['us_pseudo'];?></div>
		<div id="friendMenu">
			<img class="lower_textimg image_link" id="1" src="<?php echo IMG_DIR;?>son_compte.png" onclick="changeOnglet('1');">
			<img class="image_link" id="2" src="<?php echo IMG_DIR;?>ses_amis.png" onclick="changeOnglet('2');">
			<img class="image_link" id="3" src="<?php echo IMG_DIR;?>ses_cartes.png" onclick="changeOnglet('3');">
		</div>

		<div id="account1">
			<div class="infos">
				<h3>Informations Personnelles</h3>
				<p><span><b>Nom : </b></span> 	<?php echo $friend['us_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><b>Prénom : </b></span> <?php echo $friend['us_lastname'];?> </p>
				<p><span><b>Né le  </b></span> <?php echo $friend['us_birthdate'];?> </p>
			</div>
		</div>	
		<div id="account2">
				<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Vous avez <b><?php echo $nbCommuns;?></b> amis en commun.</p>
				<ul class="friends">
					<?php if(!empty($communs))foreach($communs as $commun): ?>
						<li id="friendClick" > <div class="hidden"><?php echo $commun['us_pseudo'];?></br>
						<?php echo createLink('Voir', 'users', 'visitFriend', array($commun['us_pseudo']));?></div></li>
					<?php endforeach; ?>
				</ul>
				<h3>Ses Amis</h3>
				<ul class="friends">
						<?php foreach($reelfriends as $reelfriend): ?>
						<li id="friendClick" > <div class="hidden"><?php echo $reelfriend['us_pseudo'];?></br>
						<?php echo createLink('Voir', 'users', 'visitFriend', array($reelfriend['us_pseudo']));?></div></li>
						<?php endforeach; ?>
				</ul>
		</div>
		<div id="account3">
				<div class="deck">
					<div id="subdeck">
						<ul>
							<?php if(empty($decks)){echo('<p>Cet utilisateur n\'a pas de deck</p>');}if(!empty($decks)){foreach($decks as $deck): ?>
								<li class="deckList" name="de_id" value="<?php echo $deck['de_id'];?>"><?php echo $deck['de_name'];?></li>
							<?php endforeach;}?>
						</ul>
					</div>
					<div id="gallery_conteneur">
					<div id="gallery" class="flexcroll">
							
						</div>
					</div>
				</div>
		</div>
</div>
<div id="aside">
	<div class="monCompte">
		<h3><?php echo $user['us_pseudo'];?></h3>
		<?php echo $result;?>
</div>