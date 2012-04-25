<p>Vous êtes connecté en tant que : <?php echo $_SESSION[USER_MODEL]['us_pseudo'];?></p>
<h1>Phase actuelle : <?php echo $turn['phase']['title'];?></h1>
<h2><?php echo $turn['phase']['infos'];?></h2>

<h3>Conteur : <?php echo $turn['storyteller']['us_pseudo'];?></h3>
<p><?php echo $turn['tu_comment'];?></p>

<table>
	<caption>Joueurs</caption>
	<tr>
		<th>Pseudo : </th>
		<th>Points : </th>
		<th>Statut : </th>
	</tr>
	<?php foreach($turn['players'] as $player): ?>
	<tr>
		<td><?php echo $player['us_pseudo'] . (($player['role'] == 'conteur') ? ' : conteur' : '');?></td>
		<td><?php echo ($player['points'] != null) ? $player['points'] : '0';?></td>
		<td><?php _displayStatus($player['status'], $turn['phase']['id'], $player['role']);?></td>
	</tr>
	<?php endforeach; ?>
</table>
<h3>Table</h3>

<?php
_displayBoard($turn['phase']['id'], $turn['game']['ga_id'], $turn, $storyteller, $actionStatus);
?>

<h3>Votre main</h3>
<?php
_displayHand($turn['phase']['id'], $_SESSION[USER_MODEL][USER_PK], $turn['game']['ga_id'], $turn['tu_id'], $storyteller, $actionStatus);
?>