<h1>Parties</h1>
<table>
	<caption>Parties en attente</caption>
	<tr>
		<th>Nom</th>
		<th>Type</th>
		<th>Joueurs</th>
	</tr>
<?php foreach($partiesEnAttente as $partie): ?>
	<tr>
		<td><?php echo $partie['ga_name'];?></td>
		<td><?php echo $partie['gt_name'];?></td>
		<td><?php echo $partie['nbPlayersInGame'] . '/' . $partie['gt_nb_players'];?></td>
	</tr>
<?php endforeach; ?>
</table>
