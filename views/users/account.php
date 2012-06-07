<div id="profil">

<img class="image_link" id="1" src="<?php echo IMG_DIR;?>compte_accueil.png" onclick="changeOnglet('1');">
<img class="lower_textimg image_link" id="2" src="<?php echo IMG_DIR;?>compte_compte.png" onclick="changeOnglet('2');">
<img class="image_link" id="3" src="<?php echo IMG_DIR;?>compte_amis.png" onclick="changeOnglet('3');">
<img class="image_link" id="4" src="<?php echo IMG_DIR;?>compte_cartes.png" onclick="changeOnglet('4');">

	<div id="account1">
		<img class="avatar" src="<?php echo IMG_DIR.$user['us_avatar'];?>"  alt=""/>
		<p> Bienvenue <?php echo $user['us_name'].' '.$user['us_lastname'];
		echo "<br/>";
		echo createLink('Déconnexion', 'users', 'logout');?> </p>
		<p><?php if($user['classement'] != '') {
				echo 'Vous êtes à la '.$user['classement'].(($user['classement'] == 1) ? 'ère ' : 'ème'). ' place du '.l('classement général', 'games', 'classement', null, array('class' => 'inside_link', 'title' => 'Voir le classement général')).' avec '.$user['xp'].' points d\'expérience.';
			}
			else
				echo 'Vous n\'êtes pas encore dans le '.l('classement général', 'games', 'classement', null, array('title' => 'Voir le classement général')).' du site';
			?></p>
		<br />
		<?php if(!empty($gamesInProgress)): ?>
			<p>Vous avez <?php echo count($gamesInProgress);?> parties en cours !</p>
				<?php foreach($gamesInProgress as $games): 
					echo l($games['ga_name'], 'games', 'play', array($games['ga_id']), array('title' => 'Jouer')).'<br />';
				endforeach;?>
		<?php endif; ?>
		<ul>
			<?php foreach($invitations as $invitation): ?>
			<li> 
				<img class= "message" src="<?php echo IMG_DIR;?>message.png" alt=""/><p>Vous avez reçu une invitation</p>
			</li>
			<?php endforeach; ?>
		</ul><br />
		<?php echo createLink('<img src='.IMG_DIR.'jouer_account.png alt=""/>', 'games', 'index', array(), array('id' => 'jouer'));?>
		<p><br /><br /></p>
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
		<img src="<?php echo IMG_DIR.$user['us_avatar'];?>" alt=""/>
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
					<li id="friendClick" > <div class="hidden"><?php echo $reelFriend['us_pseudo'];?></br></br>
					<?php echo createLink('Voir', 'users', 'visitFriend', array($reelFriend['us_pseudo']));?></div></li>
				<?php endforeach; ?>
			</ul>
			</br>
			<h3>Gérez vos Amis</h3>
			<div id="subFriends">
				<ul class="title">
					<ol class="title1" onclick="changeOnglet2('1');">Vos demandes</ol>
					<ol class="title2" onclick="changeOnglet2('2');">Vos invitations</ol>
					<ol class="title3" onclick="changeOnglet2('3');">Rechercher un ami</ol>
				</ul>
				<div class="subFriends1">
					<ul>
						<?php if(empty($askedFriends)){echo('<p><font size="2">Recherchez vos amis avant de leur envoyer une demande</font></p>');}foreach($askedFriends as $askedFriend): ?>
						<li> <div class="hidden"><?php echo $askedFriend['us_pseudo'];?></br></br><?php echo createLink('Voir', 'users', 'visitFriend', array($askedFriend['us_pseudo']));?></div></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="subFriends2">
					<ul id="invitations">
						<?php if(empty($invitation)){echo('<p><font size="2">Vous n\'avez aucune invitation pour le moment.</font></p>');}foreach($invitations as $invitation): ?>
						<li> 
							<div class="invitation">
								<?php echo $invitation['us_pseudo'];?></br>
								<?php echo createLink('Voir', 'users', 'visitFriend', array($invitation['us_pseudo']));?></br>
								<?php echo $invitation['accept'];?></br>
								<?php echo $invitation['refuse'];?>
							</div>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="subFriends3">
					<form class="research" method="post">
							<p>
								<label for="loginSearch"><font size="2">Rechercher un ami</font> </label>
								<input name="loginSearch" id="loginSearch" type="text" value="Login" onFocus="javascript:this.value=''"/>
							</p></br>
					</form>
					<div id="results">
						
					</div>
				</div>
			</div>
	</div>
	
	<div id="account4">
			<img  id="fleche-gauche-vide" onclick="displaySide('gauche');" src="<?php echo IMG_DIR;?>fleche-gauche-vide.png" />
			<img  id="fleche-droite-vide" onclick="displaySide('droite');" src="<?php echo IMG_DIR;?>fleche-droite-vide.png" />
		<div id="sides">
			<div id="side1">
				<h3>Creez un nouveau deck</h3>
				<div id="warning">
					<img src="<?php echo IMG_DIR;?>warning2.png" alt="#"/>
					<p>Par défaut les decks sont <strong>privés</strong>, c'est-à-dire que vous seul pouvez créer une partie utilisant un de vos decks privés.</p>
				</div>
				<form method="POST">
					<label for="deck_name" >Nom : </label><input type="text" name="deck_name" onFocus="javascript:this.value=''"></input>
					<input type="checkbox" name='public'><font size="1">Public</font>
					<input type="hidden" name="deck"></input>
					<input type="submit" value="Creer">
				</form>
				</br></br>
				<h3>Ajouter une carte</h3>
				<div id="warning">
					<img src="<?php echo IMG_DIR;?>warning.png" alt="#"/>
					<p>Pour éviter les problèmes dus au déformations d'images, la taille de votre image doit faire exactement <strong>329px</strong> de large et <strong>500px</strong> de hauteur. Le poids est limité à <strong>150Ko</strong>.
					</br>Les formats pris en charge sont <strong>'.png', '.jpeg', '.jpg' et '.gif'</strong>. </p>
				</div>
				<form class="card" enctype="multipart/form-data" name="card" method="POST">
					 <label for="card_name" >Nom : </label><input type="text" name="card_name" onFocus="javascript:this.value=''"/>
					<input type="hidden" name="MAX_FILE_SIZE" value="153600" />
					 <label for="userfile" > </label><input name="userfile" type="file" onchange="preview();" />
					<input type="hidden" name="card"/>
					<input class="submit" type="submit" value="Ajouter">
				</form>
				<div id="survey"> </div>
			</div>
			<div id="side2">
				<h3>Vos decks</h3>
				<table class="deck">
					<head>
							<th>Nom</th>
							<th>Nb cartes</th>
							<th>Statut</th>
					</head>
					<body>
						<?php  foreach($userDecks as &$deck): ?>
							<tr>
								<td><?php echo $deck['de_name'];?></td>
								<td><?php echo $deck['nbCards'];?></td>
								<td><?php echo $deck['de_status'];?></td>
							</tr>
							<?php endforeach;?>
					</body>
				</table>
				<table>
					<body>
						<?php  foreach($userDecks as &$deck): ?>
						<tr>
							<td><img class="pen" id="pen" src="<?php echo IMG_DIR;?>pen.png" alt="#" title="<?php echo($deck['de_id']);?>"/></td>
						</tr>
						<?php endforeach;?>
					</body>
					
				</table>
				<div id="changeDeck">
					<form method="POST">
						<legend>Modifier le deck</legend>
							<input type="text" name="de_name" ></br>
							<input type="checkbox" name='public'><font size="1">Public</font><br>
							<input type="hidden" name="changeDeck"/>
							<input class="submit" type="submit" value="Enregistrer">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input border='0' class="submit" src="<?php echo IMG_DIR;?>poubelle.png" type=image Value=submit align="middle" >
					</form>
				</div>
				</br></br>
				<h3>Voir les decks de vos amis</h3>
			</div>
			<div id="side3">
				<p>Afficher le groupe de carte :</p> 
				<select name="deck">
					<option value="-1">Toutes</option>
								<?php foreach($userDecks as $deck): ?>
									<?php echo $deck['de_id'];?>
									<option value='<?php echo $deck['de_id'];?>'><?php echo $deck['de_name'];?></option>
								<?php endforeach; ?>
				</select>
				<div id="gallery_conteneur">
					<div id="gallery" class="flexcroll">
						<?php if(isset($deck)) { ?>
							<?php foreach($deck['cardsInDeckInfo'] as $card): ?>
								<div class="carte">
									<img class="image_carte"  src="<?php echo IMG_DIR;?>cards/<?php echo $card['ca_image'];?>" alt="<?php echo $card['ca_name'];?>"/>
									<div id="selectable" >
										<img  class="bouton" src="<?php echo IMG_DIR;?>bouton_dore.png" />
										<img class="ui-state-default" id="btnCardID<?php echo $card['ca_id'] ;?>" src="<?php echo IMG_DIR;?>bouton.png" />
									</div>
								</div>
							<?php endforeach;?>
						<?php } else { ?>
							<p>Aucune carte à afficher</p>
						<?php } ?>
				</div>
				
			</div>
			<p class="link"><?php echo createLink('supprimer', 'users', ''); ?></p>
			<p>Ajouter dans le groupe :</p> 
				<form method="POST">
					<select name="deck">
						<option value="-1">Toutes</option>
									<?php foreach($userDecks as $deck): ?>
										<option value='
										<?php echo $deck['de_id'];?>
										'><?php echo $deck['de_name'];?></option>
										<?php endforeach; ?>
					</select>
					<input type="hidden" name="moveCards" />
					<input class="submit" type="submit" value="ok">
				</form>
			</div>
		</div>
	</div>
</div>