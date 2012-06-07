<div id="classement">	
	<div class="scroll">
		<table id="usersInfos" >
			<tr id="firstTR">
				<th>Joueur</th>
				<th>Points d'expérience</th>
			</tr>
			<?php foreach($classement as $user): ?>
			<tr>
				<td><?php echo $user['us_pseudo'];?></td>
				<td><?php echo $user['xp'];?></td>
			</tr>
			<?php endforeach ?>
		</table>
	</div>
	
</div>