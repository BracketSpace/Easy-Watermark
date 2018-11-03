(function($){

	$(document).ready(function(){

		$('#watermark-all-proceed').on('click', function(e){
			e.preventDefault();
			$.ewAjax.init('watermark_all');
		});
	});

})(jQuery)

