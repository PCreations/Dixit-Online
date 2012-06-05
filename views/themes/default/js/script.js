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
	}, 3000);
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

function isset () {
    // !No description available for isset. @php.js developers: Please update the function summary text file.
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/isset
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: FremyCompany
    // +   improved by: Onno Marsman
    // +   improved by: Rafał Kukawski
    // *     example 1: isset( undefined, true);
    // *     returns 1: false
    // *     example 2: isset( 'Kevin van Zonneveld' );
    // *     returns 2: true
    var a = arguments,
        l = a.length,
        i = 0,
        undef;
 
    if (l === 0) {
        throw new Error('Empty isset');
    }
 
    while (i !== l) {
        if (a[i] === undef || a[i] === null) {
            return false;
        }
        i++;
    }
    return true;
}
