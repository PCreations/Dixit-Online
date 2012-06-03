function swapWindow(showedWindow) {
	if(showedWindow == 'room') {
		$(".arrowRoom").hide();
		$(".arrowGame").show();
		$("#contentRight").css('right', '-5000');
		$("#contentRight").hide();
		$("#contentLeft").show();
		$("#contentLeft").css('left', '0');
		var chat = $("#chat");
		$('#chat').remove();
		$("#sidebar_room").append(chat);
	}
	if(showedWindow == 'play') {
		$(".arrowGame").hide();
		$(".arrowRoom").show();
		$("#contentRight").show();
		$("#contentRight").css('right', '0');
		$("#contentLeft").css('left', '-5000');
		$("#contentLeft").hide();
		var chat = $("#chat");
		$('#chat').remove();
		$("#sidebar").append(chat);
	}

	$(".arrowGame").click(function(){
		$("#contentRight").show();
		$("#contentRight").css('bottom', '755px');
		var chat = $("#chat");
		$('#chat').remove();
		$("#sidebar").append(chat);
		$("#contentRight").animate({
			right: '0',
		}, 500);
		$("#contentLeft").animate({
			left: '-5000',
		}, 500, function() {
			$(".arrowRoom").show();
			$(".arrowGame").hide();
		});
	});
	$(".arrowRoom").click(function(){
		$("#contentLeft").show();
		var chat = $("#chat");
		$('#chat').remove();
		$("#sidebar_room").append(chat);
		$("#contentRight").animate({
			right: '-5000',
		}, 500, function() {
			$('#contentRight').hide();
		});
		$("#contentLeft").animate({
			left: '0',
		}, 500, function() {
			$(".arrowRoom").hide();
			$(".arrowGame").show();
			//$("#contentRight").hide();
		});
	});
}