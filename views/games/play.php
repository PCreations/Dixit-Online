<h1>Phase actuelle : <?php echo $turn['phase']['title'];?></h1>
<h2><?php echo $turn['phase']['infos'];?></h2>

<h3>Conteur : <?php echo $turn['storyteller']['us_pseudo'];?></h3>
<p><?php echo $turn['tu_comment'];?></p>


<h3>Table</h3>
<?php
_displayBoard($turn['phase']['id'], $turn['tu_id']);
?>

<h3>Votre main</h3>
<?php
_displayHand($turn['phase']['id'], $_SESSION[USER_MODEL][USER_PK], $turn['game']['ga_id'], $turn['tu_id'], $storyteller);
?>
<?php
/*
switch($phase) {
	case STORYTELLER_PHASE:
		if($storyteller) {
			echo "Choissisez une carte";
			?>
			<form method="post" action="<?php echo BASE_URL;?>cards/addStorytellerCard">
				<input type="text" name="comment" />
				<input type="hidden" name="gameID" value="<?php echo $gameID;?>" />
				<input type="hidden" name="turnID" value="<?php echo $turnID;?>" />
			<h2>Vos cartes : </h2>
			<?php 
			foreach($cards as $card) {
				//echo '<img onclick="postAjax(\''. l(false, 'cards', 'chooseCard', array($card['ca_id'], $gameID, $_SESSION[USER_MODEL][USER_PK])) . '\');" src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="' . $card['ca_name'] . '" />';
				echo '<label for="' . $card['ca_id'] . '"><img src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="' . $card['ca_name'] . '" /></label><input type="radio" id="' . $card['ca_id'] .'" name="cardID" value="' . $card['ca_id'] . '" />';
			}
			?>
			<input type="submit" value="valider" />
			</form>
			<?php
		}
		else {
			echo "Attendez que le conteur ait choisi";
			?>
			<h2>Vos cartes : </h2>
			<?php 
			foreach($cards as $card) {
				//echo '<img onclick="postAjax(\''. l(false, 'cards', 'chooseCard', array($card['ca_id'], $gameID, $_SESSION[USER_MODEL][USER_PK])) . '\');" src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="' . $card['ca_name'] . '" />';
				echo l('<img src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="' . $card['ca_name'] . '" />', 'cards', 'addCard', array($gameID, $turnID, $card['ca_id']));
			}
		}
		break;
	case BOARD_PHASE:
		if(!$storyteller) {
			echo "Choissisez une carte correspondante";
		}
		else
			echo "Attendez que les joueurs choisissent";
		break;
}
*/
?>
