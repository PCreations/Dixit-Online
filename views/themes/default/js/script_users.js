//Système d'onglet pour le profil Joueur
function changeOnglet(i){
	var j;
	for (j=1; j<5; j++){
		document.getElementById('account'+j).style.display = 'none';
	}
	document.getElementById('account'+i).style.display = 'block';
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

function displaySide(i){
	if(i=='droite'){
		j = document.getElementById('sides').offsetLeft;
		if(j > '-800'){
			jleft = j-1080;
			document.getElementById('sides').style.left = jleft+'px';
			 $('sides').toggleClass('sides_change');  
		}
	}
	if(i=='gauche'){
		j = document.getElementById('sides').offsetLeft;
		if(j < '300'){
			jleft = j+280;
			document.getElementById('sides').style.left = jleft+'px';
		}
	}
	displayArrow();
	
}

function displayArrow(){
		j = document.getElementById('sides').offsetLeft;
		if(j > '-1000' && j < '-900'){
			document.getElementById('fleche-droite-vide').style.visibility = 'hidden';
		}else{
			document.getElementById('fleche-droite-vide').style.visibility = 'visible';
		}
		if(j < '400' && j > '300'){
			document.getElementById('fleche-gauche-vide').style.visibility = 'hidden';
		}else{
			document.getElementById('fleche-gauche-vide').style.visibility = 'visible';
		}
	}

function preview(){ //censée afficher un aperçu de l'image selectionnée pour être téléchargée
	alert('plop');
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
	document.getElementById("pen").onclick = function(){
		changeDeck('0');
		document.getElementById("changeDeck").onsubmit = function(){
			$.post(Dixit.BASE_URL+"users/changeDeck", {'de_id': $(this).title()}, function(data) {
				$('#changeDeck').html(data);
				changeDeck('1');
			});
		};
	};
	
	document.getElementById("friendClick").onclick = function(){
		var span = document.getElementById("friendClick").childNodes;
		for(i=0; i<4; i++){
			alert(children[i].data);
			span.show('slow');
			span.css('display', 'inline-block');
		}
	}
	
	/*Sélection des cartes dans le profil*/
	$( ".carte #selectable" ).selectable();
	
	displayArrow();

});


           

