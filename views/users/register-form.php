<h1>S'enregistrer</h1>
<form method="post">
<p><label for="us_name">Prénom : <label><input value="<?php echo isset($us_name) ? $us_name : '';?>" type="text" name="us_name" id="us_name" /></p>
<p><label for="us_lastname">Nom: <label><input value="<?php echo isset($us_lastname) ? $us_lastname : '';?>" type="text" name="us_lastname" id="us_lastname" /></p>
<p><label for="us_pseudo">Pseudo : <label><input value="<?php echo isset($us_pseudo) ? $us_pseudo : '';?>" type="text" name="us_pseudo" id="us_pseudo" /></p>
<p><label for="us_password">Mot de passe : <label><input value="<?php echo isset($us_password) ? $us_password : '';?>" type="password" name="us_password" id="us_password" /></p>
<p><label for="passConfirm">Confirmer le mot de passe : <label><input value="<?php echo isset($passConfirm) ? $passConfirm : '';?>" type="password" name="passConfirm" id="passConfirm" /></p>
<p><label for="us_mail">Adresse e-mail : <label><input value="<?php echo isset($us_mail) ? $us_mail : '';?>" type="text" name="us_mail" id="us_mail" /></p>
<p><label for="us_birthdate">Date de naissance : <label><input value="<?php echo isset($us_birthdate) ? $us_birthdate : '';?>" type="text" name="us_birthdate" id="us_birthdate" /></p>
<input type="hidden" name="register" />
<p><input type="submit" value="s'enregistrer" /></p>
</form>