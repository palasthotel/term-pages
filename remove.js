(function($){
	$(document).ready(function() {


$("#remove").click(function(){
	$(".or-page-id").val("");
	
	var $form = $("#edittag");
	
 $.ajax({
        url     : $form.attr('action'),
        type    : $form.attr('method'),
        dataType: 'json',
        data    : $form.serialize()
    });    
		
})
 
       
    });
    
    })(jQuery);