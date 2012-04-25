<table>
	<caption>Joueurs</caption>
	<tr>
		<th>Pseudo : </th>
		<th>Points : </th>
		<th>Statut : </th>
	</tr>
	<?php foreach($players as $player): ?>
	<tr>
		<td><?php echo $player['us_pseudo'] . (($player['role'] == 'conteur') ? ' : conteur' : '');?></td>
		<td><?php echo ($player['points'] != null) ? $player['points'] : '0';?></td>
		<td><?php _displayStatus($player['status'], $turn['phase']['id'], $player['role']);?></td>
	</tr>
	<?php endforeach; ?>
</table>