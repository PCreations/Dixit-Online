<div id="home_content">
	<article>
		<div id="welcome">
			<img src="<?php echo IMG_DIR;?>bienvenue.png"/>
			<p>Vous voici sur la page de Dixit Online, le site web inspiré du célèbre jeu de cartes français Dixit. 
			Jouez en lignes et chattez avec vos amis, créez vos propres cartes et soumettez-les au vote de la communauté Dixit !</p>
			<img class="cartes" src="<?php echo IMG_DIR;?>welcome.png" alt="#"/>
		</div>
		<div id="home_subcontent">
			<div id="info">
				<img src="<?php echo IMG_DIR;?>dixit_interrogation.png"/>
				<p>Comment ! Diantre ! Vous ne savez pas ce qu'est Dixit ! Et bien faites demi-tour de ce pas vous n'en apprendrez pas plus ici :D</p>
				<a href="#" alt="#">Découvrir Dixit</a>
			</div>
			<div class="inscription">
			<img class="ligne" src="<?php echo IMG_DIR;?>ligne.png"/>
				<img src="<?php echo IMG_DIR;?>sinscrire.png"/>
				<p></p>
				<?php echo createLink('S\'inscrire', 'users', 'register');?>
			</div>
		</div>
	</article>
	<aside>
		<div id="home_aside">
			<img src="<?php echo IMG_DIR;?>compte.png"/>
			<img class="ligne" src="<?php echo IMG_DIR;?>ligne.png"/>
			<?php echo createLink('Connexion', 'users', 'login');?>
		</div>
	</aside>
</div>