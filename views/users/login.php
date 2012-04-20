<h1>Se connecter</h1>

<form method="post">
	<p><label for="pseudo">Pseudo : </label><input value="<?php isset($pseudo) ? $pseudo : '';?>" type="text" name="pseudo" id="pseudo" /></p>
	<p><label for="password">Mot de passe : </label><input value="<?php isset($password) ? $password : '';?>" type="password" name="password" id="password" /></p>
	<input type="hidden" name="login" />
	<p><input type="submit" value="se connecter" /></p>
</form>