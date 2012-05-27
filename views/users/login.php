<div id="login">
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