function postAjax(url) {
	$.post(url,
    	function(data) {
     		console.log(data);
    	}
    );
}