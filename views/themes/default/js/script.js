var Dixit = function() {
}

Dixit.BASE_URL = 'http://localhost/dixit/';
Dixit.IMG_DIR = 'http://localhost/dixit/views/themes/default/img/';
Dixit.FLASH_SUCCESS = 0;
Dixit.FLASH_INFOS = 2;
Dixit.FLASH_MESSAGE = 3;
Dixit.alert = function(message, type) {
	var flash;
	$('#flash').remove();
	console.log("setMessage");
	switch(type) {
		case Dixit.FLASH_SUCCESS:
			flash = '<div id="flash">'
						+'<p><img src="'+Dixit.IMG_DIR+'notif_success.png"/>'+message+'</p>'
					+'</div>';
			break;
		case Dixit.FLASH_ERROR:
			flash = '<div id="flash">'
						+'<p><img src="'+Dixit.IMG_DIR+'notif_erreur.png"/>'+message+'</p>'
					+'</div>';
			break;
		case Dixit.FLASH_INFOS:
			flash = '<div id="flash">'
						+'<p><img src="'+Dixit.IMG_DIR+'notif_info.png"/>'+message+'</p>'
					+'</div>';
			break;
		case Dixit.FLASH_MESSAGE:
		default:
			flash = '<div id="flash" class="message">'
						+'<p><img src="'+Dixit.IMG_DIR+'notif_info.png"/>'+message+'</p>'
					+'</div>';
			break;
	}
	$("#ping").html("<audio autoplay='autoplay'><source src='<?php echo BASE_URL;?>views/themes/default/ping.mp3' /><source src='<?php echo BASE_URL;?>views/themes/default/ping.ogg' /></audio>");
	alert("test");

	
	$("body").append(flash);
	slideNotifications();
};



function postAjax(url) {
	$.post(url,
    	function(data) {
     		console.log(data);
    	}
    );
}

$(document).ready(function(){ 
	
	//Affichage des messages Flash dans la bannière rouge en haut de l'écran
	slideNotifications();
	
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

function slideNotifications() {
	$('#flash').slideDown(800);
	setTimeout(function(){
		$('#flash').slideUp(800);
	}, 5000);
}

function rtrim(str, charlist) {
    charlist = !charlist ? ' \\s\u00A0' : (charlist + '').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\\$1');
    var re = new RegExp('[' + charlist + ']+$', 'g');
    return (str + '').replace(re, '');
}

/*window.alert = function(alertMessage) {
	window.focus();
    setMessage(alertMessage, FLASH_SUCCESS);
}*/