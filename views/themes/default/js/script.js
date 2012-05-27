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

//Pop-up  
var i;
var popupStatus = new Array();

function loadPopup(i){   
	if(popupStatus[i]==0){  
		
		$("#backgroundPopup"+i).css({  
				"opacity": "0.7"  
		});  
		$("#backgroundPopup"+i).fadeIn("slow");  
		$("#popup"+i).fadeIn("slow");  
		popupStatus[i] = 1;  
	}  
} 

function disablePopup(i){  
	if(popupStatus[i]==1){  
		$("#backgroundPopup"+i).fadeOut("slow");  
		$("#popup"+i).fadeOut("slow");  
		popupStatus[i] = 0;  
	}  
} 

function centerPopup(i){ 
	
	var windowWidth = document.documentElement.clientWidth;  
	var windowHeight = document.documentElement.clientHeight;  
	var popupHeight = $("#popup"+i).height();  
	var popupWidth = $("#popup"+i).width();  

	$("#popup"+i).css({  
			"position": "absolute",  
			"top": windowHeight/2-popupHeight/2,  
			"left": windowWidth/2-popupWidth/2  
	});   
	 
	$("#backgroundPopup"+i).css({  	/*IE6*/
			"height": windowHeight  
	});  
} 

$(document).ready(function(){ 
	
	//Affichage des messages Flash dans la bannière rouge en haut de l'écran
	$('#flash').slideDown(800);
		setTimeout(function(){
			$('#flash').slideUp(800);
		}, 5000);
	
	//Pop-up
	if(i=1){
	$("#popupButton"+i).click(function(){  /*Show popup*/
		alert('plop!');
		centerPopup(i);    
		loadPopup(i);   
	});
};
	$("#popupClose"+i).click(function(){  /*Click the x*/
		disablePopup(i);  
	});  
	$("#backgroundPopup"+i).click(function(){  /*Click out event*/
		disablePopup(i);  
	});  
	$(document).keypress(function(e){  
		if(e.keyCode==27 && popupStatus[i]==1){  /* Press escape event*/
			disablePopup(i);  
		}  
	});
	//A laisser en tout dernier parce qu'empeche la suite du script je sais pas encore pourquoi --'
	changeOnglet('1');
});


