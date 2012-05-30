//Système d'onglet pour le profil Joueur
function changeOnglet(i){
	var j;
	for (j=1; j<5; j++){
		document.getElementById('account'+j).style.display = 'none';
	}
	document.getElementById('account'+i).style.display = 'block';
}

function displaySide(i){
	j = document.getElementById('sides').offsetLeft;
	jleft = j+200;
	jright = j-200;
	if(i=='gauche'){
		document.getElementById('sides').style.left = jleft+'px';
	}
	if(i=='droite'){
		alert('yo');
		document.getElementById('sides').style.left = jrigth+'px';
	}
}

$(document).ready(function(){ 
	changeOnglet('1');


	$( ".carte #selectable" ).selectable();

});


           

