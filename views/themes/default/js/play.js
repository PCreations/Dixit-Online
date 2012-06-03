function swapWindow(showedWindow) {
	if(showedWindow == 'room') {
		$(".arrowRoom").hide();
		$(".arrowGame").show();
		$("#contentRight").css('right', '-2000');
		$("#contentLeft").css('left', '0');
	}
	if(showedWindow == 'play') {
		$(".arrowGame").hide();
		$(".arrowRoom").show();
		$("#contentRight").css('right', '0');
		$("#contentLeft").css('left', '-2000');
	}

	$(".arrowGame").click(function(){
		$("#contentRight").animate({
			right: '0',
		}, 500);
		$("#contentLeft").animate({
			left: '-2000',
		}, 500, function() {
			$(".arrowRoom").show();
			$(".arrowGame").hide();
		});
	});
	$(".arrowRoom").click(function(){
		$("#contentRight").animate({
			right: '-2000',
		}, 500);
		$("#contentLeft").animate({
			left: '0',
		}, 500, function() {
			$(".arrowRoom").hide();
			$(".arrowGame").show();
		});
	});
}