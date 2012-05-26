function postAjax(url) {
	$.post(url,
    	function(data) {
     		console.log(data);
    	}
    );
}

//Système d'onglet pour le profil Joueur
function changeOnglet(i){
	var j;
	for (j=1; j<5; j++){
		document.getElementById('account'+j).style.display = 'none';
	}
	document.getElementById('account'+i).style.display = 'block';
}
 

$(document).ready(function(){ 
	changeOnglet('1');

	//Affichage des messages Flash dans la bannière rouge en haut de l'écran
	$('#flash').slideDown(800);
	setTimeout(function(){
		$('#flash').slideUp(800);
	}, 5000);


});

