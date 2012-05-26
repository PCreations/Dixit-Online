<?php debug($_SERVER);?>
<h1>Parties</h1>

<form action="">
	<input type="text" name="name">
	<input type="text" name="nbplayers">
	<select>
<?php foreach($deckInfos as $deck): ?>
		<option name='<?php echo $deck['de_id'];?>'><?php echo $deck['de_name'];?></option>
<?php endforeach; ?>
	</select>
	<submit value='Trier'>
</form>

<table>
	<caption>Parties en attente</caption>
	<tr>
		<th>Nom</th>
		<th>Type</th>
		<th>Joueurs</th>
		<th>Actions</th>
	</tr>
<?php foreach($partiesEnAttente as $partie): ?>
	<tr>
		<td><?php echo $partie['ga_name'];?></td>
		<td><?php echo $partie['gt_name'];?></td>
		<td><?php echo $partie['nbPlayersInGame'] . '/' . $partie['gt_nb_players'];?></td>
		<td><?php echo $partie['action'];?></td>
	</tr>
<?php endforeach; ?>
</table>
