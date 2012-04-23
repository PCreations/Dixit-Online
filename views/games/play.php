<h1>Phase actuelle : <?php echo $phase;?></h1>

<?php
switch($phase) {
	case STORYTELLER_PHASE:
		if($storyteller) {
			echo "Choissisez une carte";
		}
		else {
			echo "Attendez que le conteur ait choisi";
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
?>
<h2>Vos cartes : </h2>
<?php 
foreach($cards as $card) {
	//echo '<img onclick="postAjax(\''. l(false, 'cards', 'chooseCard', array($card['ca_id'], $gameID, $_SESSION[USER_MODEL][USER_PK])) . '\');" src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="' . $card['ca_name'] . '" />';
	echo l('<img src="' . IMG_DIR . 'cards/' . $card['ca_image'] . '" alt="' . $card['ca_name'] . '" title="' . $card['ca_name'] . '" />', 'cards', 'addCard', array($gameID, $turnID, $card['ca_id']));
}
?>