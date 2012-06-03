	<div id="games">
		<div id="left_side">
			<div id="creer">
				<img id="label_creer" src="<?php echo IMG_DIR;?>creer.png">
				<!-- Création de partie -->
				<form action="<?php echo BASE_URL;?>games/newGame" method="POST">
					<figure><label for="nom">Nom</label> 			<input type="text" name="nom" size="18" required/> </figure>
					<figure><label for="pwd">Mot de Passe</label> 	<input type="password" name="pwd"size="15" > </figure>
					<figure><label for="joueurs">Joueurs</label>
						<select name="joueurs">
							<?php
							for($i=3; $i<=10; $i++) {
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
							?>
						</select>
					</figure>
					<figure><label for="points">Points</label> 		<input type="text" name="points" size="5"required/> </figure>
					<figure><label for="deck">Cartes</label><select name="deck">
					<?php foreach($deckInfos as $deck): ?>
						<option value='<?php echo $deck['de_id'];?>'>
						<?php echo $deck['de_name'];?></option>
					<?php endforeach; ?>
				</select></figure>
					<figure class="button1"><input type="submit" value="Lancer"/> </figure>
				</form>
			</div>
			<div id="parties">
				<img src="<?php echo IMG_DIR;?>attente.png">
					<ul>
						<li></li>
						<li><img class="head_nom" src="<?php echo IMG_DIR;?>nom.png"></li>
						<li><img class="head_createur" src="<?php echo IMG_DIR;?>createur.png"></li>
						<li><img class="head_joueurs" src="<?php echo IMG_DIR;?>joueurs.png"></li>
						<li><img class="head_points" src="<?php echo IMG_DIR;?>points.png"></li>
						<li><img class="head_cartes" src="<?php echo IMG_DIR;?>cartes.png"></li>
					</ul>
				 <div class="scroll">
					<table  id="waitingGames" cellspacing="0">
					   <tbody>
							<?php foreach($partiesEnAttente as $partie): ?>
							 <tr>
								<td class="cadenas">
								<?php if (!empty($partie['ga_password'])){
									echo ('<img src="'.IMG_DIR.'cadenas.png">');
								}?>
								</td>
								<td class="nom"><?php echo createLink($partie['ga_name'], 'games', 'play', array($partie['ga_id']), array('title' => 'Accéder à la room de cette partie'));?></td>
								<td class="createur"><?php echo $partie['us_name'];?></td>
								<td class="joueurs"><?php echo $partie['nbPlayersInGame'] . '/' . $partie['ga_nb_players'];?></td>
								<td class="points"><?php echo $partie['ga_points_limit'];?></td>
								<td class="cartes"><?php echo $partie['de_name'];?></td>
						   </tr></a>
							<?php endforeach; ?>
					   </tbody>   
					</table>
				</div>
			</div>
			<div id="filtrer">
			<!--Filtrage-->
				<form method="POST">
					<figure><label for="name">Nom</label><input type="text" name="name" size="10" value='<?php echo $vars_filtrage['name'];?>'></figure>
					<figure><label for="nbplayers">Nombre de joueurs</label><input type="text" name="nbplayers" size="15" value='<?php echo $vars_filtrage['nbplayers'];?>'></figure>
					<figure><label for="nbpoints">Points max.</label><input type="text" name="nbpoints" size="10" value='<?php echo $vars_filtrage['nbpoints'];?>'></figure>
					<figure><label for="deck">Cartes</label><select name="deck">
							<option value="-1">Toutes</option>
						<?php foreach($deckInfos as $deck): ?>
							<option value='
							<?php
								echo $deck['de_id'];
								if ($deck['de_id']==$vars_filtrage['deck']){
									echo(" 'selected='selected");
								}
							?>
							'><?php echo $deck['de_name'];?></option>
						<?php endforeach; ?>
					</select></figure>
					<input type='checkbox' name='public' <?php
															if (isset($vars_filtrage['public'])){
																echo(" checked='checked'");
															}
					?>
					><font size="1">Publiques seulement</font>
					<input type='submit' value='Trier'>
				</form>
			</div>
			<div id="nouvelles">
				<img src="<?php echo IMG_DIR;?>dernieres_cartes.png">
				<div id="cartes">
					<div class="carte">
						<img src="<?php echo IMG_DIR;?>carte.png">
					</div>
					<div class="carte">
						<img src="<?php echo IMG_DIR;?>carte.png">
					</div>
					<div class="carte">
						<img src="<?php echo IMG_DIR;?>carte.png">
					</div>
					<div class="carte">
						<img src="<?php echo IMG_DIR;?>carte.png">
					</div>
					<div class="carte">
						<img src="<?php echo IMG_DIR;?>carte.png">
					</div>
					<div class="carte">
						<img src="<?php echo IMG_DIR;?>carte.png">
					</div>
				</div>
				<img src="<?php echo IMG_DIR;?>participer.png">
				<form>
					<figure class="button2"><input type="button" name="voter" value="Votez pour les cartes des internautes" size="35"/> </figure>
					<figure class="button2"><input type="button" name="proposer"value="Proposer une carte"/> </figure>
				</form>
			</div>
		</div>
		<div id = "right_side">
			<img id="label_compte" src="<?php echo IMG_DIR;?>compte.png">
				<?php
				if(isLogged()) {
					echo '<figure class="button2">';
						echo createLink('Accéder à mon compte', 'users', 'account',  array($_SESSION[USER_MODEL][USER_PK]));
					echo '</figure>';
				}
				else {
					echo "<p>".createLink('Se connecter', 'users', 'login')."</p>";
					echo "<p>".createLink('S\'enregister', 'users', 'register')."</p>";
				}
				?>
			<div id="en_ligne">
				<img src="<?php echo IMG_DIR;?>amis.png">
				<div class="ami">
					<img  src="<?php echo IMG_DIR;?>thomas.png">
					<p>Thomas Demenat</br>
					En ligne</p>
				</div>
				<div class="ami">
					<img src="<?php echo IMG_DIR;?>julie.png">
					<p>Julie Chupee</br>
					Partie en cours</p>
				</div>
				<div class="ami">
					<img src="<?php echo IMG_DIR;?>graouh.png">
					<p>Graouh Mraouh</br>
					Salle d'attente</p>
				</div>
				<div class="conversation">
					<h1>Moi</h1>
					<p>Hello je peux te rejoindre ?</p>
					<h1>Graouh Mraouh</h1>
					<p>Ouep ! La partie c'est DixIMAC et le password c'est "leadership".</p>
					<h2>Nice !!</h2>
				</div>
				<div class="ami">
					<img src="<?php echo IMG_DIR;?>storm.png">
					<p>Stormtrooper n°9</br>
					Partie en cours</p>
				</div>
		</div>
	</div>
</div>
