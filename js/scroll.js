$(document).ready(function() {

	$(window).scroll(function(){
			if  ($(window).scrollTop() >= $(document).height() - $(window).height()-300){
				$.ajaxSetup({async: false});
				$.post("ajax?do=zoek")
				.done(function(data) {
					$("#zoekresultaten").append(data);
				});
			}
	});
});