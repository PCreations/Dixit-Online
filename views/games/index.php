<?php //debug($_SERVER);?>
<h1>Parties</h1>

<form method="POST">
	<input type="text" name="name">
	<input type="text" name="nbplayers">
	<select name="deck">
<?php foreach($deckInfos as $deck): ?>
		<option value='<?php echo $deck['de_id'];?>'><?php echo $deck['de_name'];?></option>
<?php endforeach; ?>
	</select>
	<input type='submit' value='Trier'>
</form>

<table>
	<caption>Parties en attente</caption>
	<tr>
		<th>Nom</th>
		<th>Deck</th>
		<th>Joueurs</th>
		<th>Actions</th>
	</tr>
<?php foreach($partiesEnAttente as $partie): ?>
	<tr>
		<td><?php echo $partie['ga_name'];?></td>
		<td><?php echo $partie['de_name'];?></td>
		<td><?php echo $partie['nbPlayersInGame'] . '/' . $partie['ga_nb_players'];?></td>
		<td><?php echo $partie['action'];?></td>
	</tr>
<?php endforeach; ?>
</table>
