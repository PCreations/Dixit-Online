<!DOCTYPE html>
<html>
    <head>
		<meta charset="UTF-8"/>
        <?php echo $jsList;?>
        <?php echo $cssList;?>
		<title><?php echo $pageTitle;?></title>
    </head>
	<body>
		<header>
			<a href="<?php
				if (isLogged()==0){
					echo BASE_URL;
				}
				else{
					echo BASE_URL."games/index";
				}
				?>">
				
				<img src="<?php echo IMG_DIR;?>titre.png"/>
			</a>
		</header>
		<section>
			<?php echo $contentForLayout;?>
		</section>
		<footer>
			Dixit | Copyright 2012 |Tous droits réservés
		</footer>
	</body>
</html>