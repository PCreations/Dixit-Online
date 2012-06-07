//Système d'onglet pour le profil Joueur
function changeOnglet(i){
	var j;
	for (j=1; j<4; j++){
		document.getElementById('account'+j).style.display = 'none';
	}
	document.getElementById('account'+i).style.display = 'block';
	
	/* Cas de l'onglet de gestion des cartes */
	console.log(i);
	if(i == '4') {
		$('#ongletsTitres').css('margin-bottom', '5px');
		var position = '-750px';
		$('#sides').css('left', position);
		$('#fleche-gauche-vide').show();
		$('#fleche-gauche-vide').click(function() {
			/* Si on est sur l'onglet du milieu */
			if(position == '-750px') {
				/* Alors on se dirige vers l'onglet de gauche */
				position = '0px';
				$("#sides").animate({
					left: position,
				}, 200, function() {
					$("#fleche-gauche-vide").hide();
					$("#fleche-droite-vide").show();
					$("#fleche-droite-vide").css('left', '700px');
				});
			}
			/* Si on est sur l'onglet de droite */
			if(position == '-1500px') {
				/* Alors on se dirige vers l'onglet du milieu */
				position = '-750px';
				$("#sides").animate({
					left: position,
				}, 200, function() {
					$("#fleche-gauche-vide").show();
					$("#fleche-droite-vide").show();
				});
			}
		});
		$('#fleche-droite-vide').show();
		$('#fleche-droite-vide').click(function() {
			/* Si on est sur l'onglet du milieu */
			if(position == '-750px') {
				/* Alors on se dirige vers l'onglet de droite */
				position = '-1500px';
				$("#sides").animate({
					left: position,
				}, 200, function() {
					$("#fleche-gauche-vide").show();
					$("#fleche-droite-vide").hide();
				});
			}
			/* Si on est sur l'onglet de gauche */
			if(position == '0px') {
				/* Alors on se dirige vers l'onglet du milieu */
				position = '-750px';
				$("#sides").animate({
					left: position,
				}, 200, function() {
					$("#fleche-gauche-vide").show();
					$("#fleche-droite-vide").show();
					$("#fleche-droite-vide").css('left', '660px');
				});
			}
		});
	}
	else {
		$('#fleche-gauche-vide').hide();
		$('#fleche-droite-vide').hide();
	}
}
function changeOnglet2(i){
	var j;
	for (j=1; j<4; j++){
		$(".subFriends"+j).css('display','none');
	}
	$(".subFriends"+i).show(900);
	$(".subFriends"+i).css('display', 'inline-block');
	$(".title"+i).css('background-color', 'rgba(255, 255, 255, 0.5);');
}

function preview(){ //censée afficher un aperçu de l'image selectionnée pour être téléchargée
	imgCalque = document.getElementById("survey") ;
	imgCalque.innerHTML = "<p>hello</p><img id='imgPrev' src='C:/Users/Cécilia/Dropbox/Dixit/medias/cartes/carte_2.png'/>";
}

function changeDeck(open){ //ouvre un formulaire de modification des decks
	if(open==0){
		$("#changeDeck").show('slow');
		$("#changeDeck").css('display', 'inline-block');
		open = 1;
	}else{
		$("#changeDeck").hide('slow');
		$("#changeDeck").css('display', 'none');
		open = 0;
	}
}
$(document).ready(function(){ 
	changeOnglet('1');
	changeOnglet2('1');
	open = 0;

	//recherche d'ami
	$('#loginSearch').keyup( function(){
		$field = $(this);
		$('#results').html(''); // on vide les resultats
		// on commence à traiter à partir du 2ème caractère saisie
		if( $field.val().length > 1 )
		{
			$.post(Dixit.BASE_URL+"users/research", {'loginSearch': $(this).val()}, function(data) {
				$('#results').html(data);
			})
		}
	});
	
	$(".deckList").click( function(){
		
		$field = $(this);
		$('#gallery').html('');
		$.post(Dixit.BASE_URL+"users/displayDeck", {'de_id': $(this).val()}, function(data) {
				$('#gallery').html(data);
		});
	});
	
	
	/*Sélection des cartes dans le profil*/
	$( ".carte #selectable" ).selectable();
	
	//displayArrow();

	//ouverture du formulaire de modif des decks
	$("#pen").click(function(){
		changeDeck('0');
		document.getElementById("changeDeck").onsubmit = function(){
			$.post(Dixit.BASE_URL+"users/changeDeck", {'de_id': $(this).title()}, function(data) {
				$('#changeDeck').html(data);
				changeDeck('1');
			});
		};
	});
});
