<div id="profil">
	<h1 id="1" onclick="changeOnglet('1');">Acceuil</h1>
	<h1 id="2" onclick="changeOnglet('2');">Votre Compte</h1>
	<h1 id="3" onclick="changeOnglet('3');">Vos Amis</h1>
	<h1 id="4" onclick="changeOnglet('4');">Vos Cartes</h1>
	
	<div id="account1">
		<img src="<?php echo IMG_DIR;?>" alt=""/>
		<p> Bienvenue <?php echo $user['us_name'] ; ?> <?php echo $user['us_lastname'] ; ?> </p>
		</br></br>
		<?php echo createLink('Jouer', 'games', 'index');?>
		<?php echo createLink('Déconnexion', 'users', 'logout');?>
		<p></br></br></p>
	</div>
	
	<div id="account2">
		<div id="contact-form">
			<p><span><b>Nom :</b></span> 	<?php echo $user['us_name'] ; ?> </p>
			<p><span><b>Prénom : </b></span> 	<?php echo $user['us_lastname'] ; ?> </p>
			<p><span><b>Pseudo : </b></span> 	<?php echo $user['us_pseudo'] ; ?> </p>
			<p><span><b>Adresse mail: 	</b></span> <?php echo $user['us_mail'] ; ?> </p>
			<p><span><b>Date de naissance: </b></span> 	<?php echo $user['us_birthdate'] ; ?> </p>
			<form method='POST'><input type="hidden" name="popup" />
			<input class="submit" type="submit" value="Modifier"/></form>
		</div>
			<form method="POST">
				<fieldset>
					<legend>Modifier vos informations personnelles</legend>
						<p><label for="us_name">Prénom: </label><input name="name" type="text" value="<?php echo $user['us_name'] ; ?>"/></p>
						<p><label for="us_lastname">Nom: </label><input name="lastname" type="text" value="<?php echo $user['us_lastname'] ; ?>"/></p>
						<p><label for="us_mail">Mail: </label><input name="mail" type="text" value="<?php echo $user['us_mail'] ; ?>"/></p>
						<input type="hidden" name="update" />
						<input class="submit" type="submit" value="Enregistrer"/>
				</fieldset>
			</form>
	</div>
	
	<div id="account3">
		
			<p> Vous avez <?php echo $nbFriends['nbFriends']?> amis.</p>
			<ul>
				<?php foreach($friends as $friend): ?>
				<li> <div class="hidden"><?php echo $friend['us_pseudo'];?></div></li>
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
		<p>Je sais pas trop</p>
	</div>
	
</div>