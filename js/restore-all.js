(function($){

	$(document).ready(function(){

		$('#restore-all-proceed').on('click', function(e){
			e.preventDefault();
			$.ewAjax.init('restore_all');
		});
	});

})(jQuery)

