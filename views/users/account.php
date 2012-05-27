<div id="profil">
	<h1 id="1" onclick="changeOnglet('1');">Acceuil</h1>
	<h1 id="2" onclick="changeOnglet('2');">Votre Compte</h1>
	<h1 id="3" onclick="changeOnglet('3');">Vos Amis</h1>
	<h1 id="4" onclick="changeOnglet('4');">Vos Cartes</h1>
	
	<div id="account1">
		<img class="avatar" src="<?php echo IMG_DIR;?>" alt=""/>
		<p> Bienvenue <?php echo $user['us_lastname'] ; ?> <?php echo $user['us_name'] ; ?> </p>
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
			<div id="popupButton1" class="popupButton"><input type="submit" value="Modifier vos informations"/></div>
			<div id="popupButton2" class="popupButton"><input type="submit" value="Changer de Mot de Passe"/></div>
		</div>
		<img src="<?php echo IMG_DIR;?>" alt=""/>
		<div id="popup1" class="popup">
			<a id="popupClose1" class="popupClose">x</a>
			<form method="POST">
				<fieldset>
					<legend>Modifier vos informations personnelles</legend>
						<p><label for="lastname">Prénom: </label><input name="lastname" type="text" value="<?php echo $user['us_lastname'] ; ?>"/></p>
						<p><label for="name">Nom: </label><input name="name" type="text" value="<?php echo $user['us_name'] ; ?>"/></p>
						<p><label for="birthdate">Date de naissance: </label><input name="birthdate" type="text" value="<?php echo $user['us_birthdate'] ; ?>"/></p>
						<p><label for="mail">Mail: </label><input name="mail" type="text" value="<?php echo $user['us_mail'] ; ?>"/></p>
						<input type="hidden" name="update" />
						<input  type="submit" value="Enregistrer"/>
				</fieldset>
			</form>
		</div>
		<div id="backgroundPopup1" class="backgroundPopup"></div> 
		<div id="popup2" class="popup">
			<a id="popupClose2" class="popupClose">x</a>
			<form method="POST">
					<fieldset>
						<legend>Modifier votre mot de passe</legend>
							<p><label for="oldPass">Ancien mot de passe: </label><input name="oldPass" type="password" required/></p>
							<p><label for="password">Nouveau mot de passe: </label><input name="password" type="password" required/></p>
							<p><label for="passConfirm">Confirmer: </label><input name="passConfirm" type="password" required/></p>
							<input type="hidden" name="pseudo" value="<?php echo $user['us_pseudo'] ; ?>" />
							<input type="hidden" name="updatePwd" />
							<input  type="submit" value="Enregistrer"/>
					</fieldset>
			</form>
		</div>
		<div id="backgroundPopup2" class="backgroundPopup"></div> 
	</div>
	
	<div id="account3">
		
			<p> Vous avez <b><?php echo $nbFriends['nbFriends']?> </b>amis.</p>
			<ul class="friends">
				<?php foreach($reelFriends as $reelFriend): ?>
				<li> <div class="hidden"><?php echo $reelFriend['us_pseudo'];?></br><?php echo createLink('Supprimer', 'users', 'newFriend', array($reelFriend['us_pseudo'], '0'));?></div></li>
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