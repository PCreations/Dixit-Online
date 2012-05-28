function postAjax(url) {
	$.post(url,
    	function(data) {
     		console.log(data);
    	}
    );
}

$(document).ready(function(){ 
	
	//Affichage des messages Flash dans la bannière rouge en haut de l'écran
	$('#flash').slideDown(800);
		setTimeout(function(){
			$('#flash').slideUp(800);
		}, 5000);
	
	//Pop-up
	$("#popupButton").click(function(){  /*Show popup*/
		centerPopup();    
		loadPopup();   
	});
	$("#popupClose").click(function(){  /*Click the x*/
		disablePopup();  
	});  
	$("#backgroundPopup").click(function(){  /*Click out event*/
		disablePopup();  
	});  
	$(document).keypress(function(e){  
		if(e.keyCode==27 && popupStatus==1){  /* Press escape event*/
			disablePopup();  
		}  
	});
});
