$(document).ready(function() {
	$(".arrowRoom").hide();
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
})