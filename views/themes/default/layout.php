<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
		<meta charset="UTF-8"/>
        <?php echo $jsList;?>
        <?php echo $cssList;?>
		<title><?php echo $pageTitle;?></title>
    </head>
	
	<body>
		<header>
			<img src="<?php echo IMG_DIR;?>titre.png">
		</header>
		<section>
			<?php echo $contentForLayout;?>
		</section>
		<footer>
			Dixit | Copyright 2012 |Tous droits réservés
		</footer>
	</body>
</html>