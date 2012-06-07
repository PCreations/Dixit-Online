<div id="register">
	<form method="post" enctype="multipart/form-data">
		<fieldset>
		<legend>S'inscrire sur Dixit</legend>
				 <p><label for="avatar">Avatar :</label>
     				<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
     				<input type="file" name="avatar" id="avatar"/>
     			</p>
				<p><label for="us_name">Prénom : </label>
					<input value="<?php echo isset($us_name) ? $us_name : '';?>" type="text" name="us_name" id="us_name"/></p>
				<p><label for="us_lastname">Nom: </label>
					<input value="<?php echo isset($us_lastname) ? $us_lastname : '';?>" type="text" name="us_lastname" id="us_lastname" required/></p>
				<p><label for="us_pseudo">Pseudo : </label>
					<input value="<?php echo isset($us_pseudo) ? $us_pseudo : '';?>" type="text" name="us_pseudo" id="us_pseudo" required/></p>
				<p><label for="us_password">Mot de passe : </label>
					<input value="<?php echo isset($us_password) ? $us_password : '';?>" type="password" name="us_password" id="us_password" required/></p>
				<p><label for="passConfirm">Confirmer le mot de passe : </label>
					<input value="<?php echo isset($passConfirm) ? $passConfirm : '';?>" type="password" name="passConfirm" id="passConfirm" required/></p>
				<p><label for="us_mail">Adresse e-mail : </label>
					<input value="<?php echo isset($us_mail) ? $us_mail : '';?>" type="text" name="us_mail" id="us_mail" required/></p>
				
				<p><label for="us_birthdate">Date de naissance : </label>
					<select name='day'>
					<?php for($i=1;$i<=31;$i++){
							echo "<option value=".$i.">".$i."</option>";
						}
					?>
					</select>
					<select name='month'>
						<option value=1>Janvier</option>
						<option value=2>Février</option>
						<option value=3>Mars</option>
						<option value=4>Avril</option>
						<option value=5>Mai</option>
						<option value=6>Juin</option>
						<option value=7>Juillet</option>
						<option value=8>Août</option>
						<option value=9>Septembre</option>
						<option value=10>Octobre</option>
						<option value=11>Novembre</option>
						<option value=12>Décembre</option>
					</select>
					<select name='year'>
					<?php for($i=2012;$i>=1900;$i--){
							echo "<option value=".$i.">".$i."</option>";
						}
					?>
					</select>
					</p>

				<input type="hidden" name="register" />
				<input class="submit" type="submit" value="Inscription" />
		</fieldset>
	</form>
</div>