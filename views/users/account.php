<div id="profil">
	<h1 id="1" onclick="changeOnglet('1');">Acceuil</h1>
	<h1 id="2" onclick="changeOnglet('2');">Votre Compte</h1>
	<h1 id="3" onclick="changeOnglet('3');">Vos Amis</h1>
	<h1 id="4" onclick="changeOnglet('4');">Vos Cartes</h1>
	
	<div id="account1">
		<img class="avatar" src="<?php echo IMG_DIR;?>" alt=""/>
		<p> Bienvenue <?php echo $user['us_name'] ; ?> <?php echo $user['us_lastname'] ; ?> </p>
		</br>
			<ul>
				<?php foreach($invitations as $invitation): ?>
				<li> 
					<img class= "message" src="<?php echo IMG_DIR;?>message.png" alt=""/><p>Vous avez reçu une invitation</p>
				</li>
				<?php endforeach; ?>
			</ul></br>
		<?php echo createLink('Jouer', 'games', 'index');?>
		<?php echo createLink('Déconnexion', 'users', 'logout');?>
		<p></br></br></p>
	</div>
	
	<div id="account2">
		
		<div class="left">
			<p><span><b>Nom :</b></span> 	<?php echo $user['us_name'] ; ?> </p>
			<p><span><b>Prénom : </b></span> 	<?php echo $user['us_lastname'] ; ?> </p>
			<p><span><b>Pseudo : </b></span> 	<?php echo $user['us_pseudo'] ; ?> </p>
			<p><span><b>Adresse mail: 	</b></span> <?php echo $user['us_mail'] ; ?> </p>
			<p><span><b>Date de naissance: </b></span> 	<?php echo $user['us_birthdate'] ; ?> </p>
			<div id="popupButton"><input type="submit" value="Modifier vos informations"/></div>
			<div id="popupButton"><input type="submit" value="Changer de Mot de Passe"/></div>
		</div>
		<img src="<?php echo IMG_DIR;?>" alt=""/>
		<div id="popup">
			<a id="popupClose">x</a>
			<form method="POST">
				<fieldset>
					<legend>Modifier vos informations personnelles</legend>
						<p><label for="us_name">Prénom: </label><input name="name" type="text" value="<?php echo $user['us_name'] ; ?>"/></p>
						<p><label for="us_lastname">Nom: </label><input name="lastname" type="text" value="<?php echo $user['us_lastname'] ; ?>"/></p>
						<p><label for="us_name">Pseudo: </label><input name="name" type="text" value="<?php echo $user['us_pseudo'] ; ?>"/></p>
						<p><label for="us_mail">Mail: </label><input name="mail" type="text" value="<?php echo $user['us_mail'] ; ?>"/></p>
						<p><label for="us_name">Date de naissance: </label><input name="name" type="text" value="<?php echo $user['us_birthdate'] ; ?>"/></p>
						<input type="hidden" name="update" />
						<input  type="submit" value="Enregistrer"/>
				</fieldset>
			</form>
		</div>
		<div id="backgroundPopup"></div> 
	</div>
	
	<div id="account3">
		
			<p> Vous avez <b><?php echo $nbFriends['nbFriends']?> </b>amis.</p>
			<ul class="friends">
				<?php foreach($reelFriends as $reelFriend): ?>
				<li> <div class="hidden"><?php echo $reelFriend['us_pseudo'];?></div></li>
				<?php endforeach; ?>
			</ul>
			</br>
			<p><b>Vous avez envoyé des invitations:</b></p>
			<ul>
				<?php foreach($askedFriends as $askedFriend): ?>
				<li> <div class="hidden"><?php echo $askedFriend['us_pseudo'];?></div></li>
				<?php endforeach; ?>
			</ul>
			<p><b>Vous avez reçus des invitations :</b></p>
			<ul id="invitations">
				<?php foreach($invitations as $invitation): ?>
				<li> 
					<div class="invitation">
						<?php echo $invitation['us_pseudo'];?></br>
						<?php echo $invitation['accept'];?></br>
						<?php echo $invitation['refuse'];?>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
			</br>
			<form class="research" method="POST">
					<legend>Ajouter un ami</legend>
					<p>
						<input name="login" type="text" value="Login"/>
						<input type="hidden" name="research" />
						<input class="popup" border=0 src="<?php echo IMG_DIR;?>search_icone.png" type=image value=submit align="top" > 
					</p>
			</form>
	</div>
	
	<div id="account4">
		<p>En construction</p>
	</div>
	
</div>