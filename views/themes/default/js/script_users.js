//Système d'onglet pour le profil Joueur
function changeOnglet(i){
	var j;
	for (j=1; j<5; j++){
		document.getElementById('account'+j).style.display = 'none';
	}
	document.getElementById('account'+i).style.display = 'block';
}

//Pop-up  
var popupStatus = 0;

function loadPopup(){   
	if(popupStatus==0){  
		
		$("#backgroundPopup").css({  
				"opacity": "0.7"  
		});  
		$("#backgroundPopup").fadeIn("slow");  
		$("#popup").fadeIn("slow");  
		popupStatus = 1;  
	}  
} 

function disablePopup(){  
	if(popupStatus==1){  
		$("#backgroundPopup").fadeOut("slow");  
		$("#popup").fadeOut("slow");  
		popupStatus = 0;  
	}  
} 

function centerPopup(){ 
	
	var windowWidth = document.documentElement.clientWidth;  
	var windowHeight = document.documentElement.clientHeight;  
	var popupHeight = $("#popup").height();  
	var popupWidth = $("#popup").width();  

	$("#popup").css({  
			"position": "absolute",  
			"top": windowHeight/2-popupHeight/2,  
			"left": windowWidth/2-popupWidth/2  
	});   
	 
	$("#backgroundPopup").css({  	/*IE6*/
			"height": windowHeight  
	});  
} 
 
$(document).ready(function(){ 
	changeOnglet('1');
});
