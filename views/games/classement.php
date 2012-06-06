<ul>
<?php foreach($classement as $joueur): ?>
	<ol><?php echo $joueur['us_pseudo'].' : '.$joueur['xp'];?></ol>
<?php endforeach; ?>
</ul>