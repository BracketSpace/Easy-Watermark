(function($){
	var frame;

	$('#select-image-button, #watermark-preview').click( function( event ) {
		var $el = $(this);
		event.preventDefault();

		tb_show("", "media-upload.php?type=image&amp;TB_iframe=1");
	});
	window.send_to_editor = function(html) {
		var imgurl = $('img',html).attr('src');
		$('#easy-watermark-url').val(imgurl);
		tb_remove();
		$('#easy-watermark-settings-form').append('<input type="hidden" name="easy-watermark-settings[old-manager]" value="1" />').submit();
	}
}(jQuery))
