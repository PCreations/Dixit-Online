var IMG_DIR = 'http://localhost/dixit/views/themes/default/img/';
var FLASH_SUCCESS = 0;
var FLASH_ERROR = 1;
var FLASH_INFOS = 2;
var FLASH_MESSAGE = 3;

function postAjax(url) {
	$.post(url,
    	function(data) {
     		console.log(data);
    	}
    );
}

function setMessage(message, type) {
	var flash;
	$('#flash').remove();
	console.log("setMessage");
	switch(type) {
		case FLASH_SUCCESS:
			flash = '<div id="flash">'
						+'<p><img src="'+IMG_DIR+'notif_success.png"/>'+message+'</p>'
					+'</div>';
			break;
		case FLASH_ERROR:
			flash = '<div id="flash">'
						+'<p><img src="'+IMG_DIR+'notif_erreur.png"/>'+message+'</p>'
					+'</div>';
			break;
		case FLASH_INFOS:
			flash = '<div id="flash">'
						+'<p><img src="'+IMG_DIR+'notif_info.png"/>'+message+'</p>'
					+'</div>';
			break;
		case FLASH_MESSAGE:
		default:
			flash = '<div id="flash" class="message">'
						+'<p><img src="'+IMG_DIR+'notif_info.png"/>'+message+'</p>'
					+'</div>';
			break;
	}
	$("body").append(flash);
	slideNotifications();
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

/*window.alert = function(alertMessage) {
	window.focus();
    setMessage(alertMessage, FLASH_SUCCESS);
}*/