<div id="login">
	<a href="../../dixit"><img class="retour" src="<?php echo IMG_DIR;?>retour.png"></a>
	<img class="oups" src="<?php echo IMG_DIR;?>oups.png" onclick="alert('Vous avez oublié votre login ou votre mot de passe ?');">
	<form method="post">
		<fieldset>
			<legend>Connectez-vous</legend>
				<p><label for="pseudo">Pseudo : </label><input value="<?php isset($pseudo) ? $pseudo : '';?>" type="text" name="pseudo" id="pseudo" /></p>
				<p><label for="password">Mot de passe : </label><input value="<?php isset($password) ? $password : '';?>" type="password" name="password" id="password" /></p>
				<input type="hidden" name="login" />
				<input class="submit"type="submit" value="Connexion" />
		</fieldset>
	</form>
	
</div>