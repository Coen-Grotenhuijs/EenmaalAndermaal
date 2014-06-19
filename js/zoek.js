$(document).ready(function() {

        
        var end = false;
	$(window).scroll(function(){
			if  ($(window).scrollTop() >= $(document).height() - $(window).height()-300 && !end){
				$.ajaxSetup({async: false});
				$.post("ajax.php?do=zoek")
				.done(function(data) {
					$("#zoekresultaten").append(data);
                                        if(data.split(/\r\n|\r|\n/).length==8)
                                        {
                                                end = true;
                                        }
				});
			}
	});
});