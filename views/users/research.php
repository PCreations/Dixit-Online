
			<div id="results">
				<p> Résultats pour : <?php echo $login;?>  </p>
				<table>
					<head>
							<th>Pseudo</th>
							<th>Nom</th>
							<th>Prénom</th>
							<th> Action</th>
					</head>
					<body>
						<?php foreach($results as $result): ?>
						<tr>
							<td><?php echo $result['us_pseudo'];?></td>
							<td><?php echo $result['us_name'];?></td>
							<td><?php echo $result['us_lastname'];?></td>
							<td class="action"><?php echo $result['action'];?></td>
						</tr>
						<?php endforeach; ?>
					</body>
				</table>
				<div class="link" ><?php echo createLink('Retour', 'users', 'account', array($_SESSION[USER_MODEL][USER_PK]));?></div>
			</div>
		</div>
