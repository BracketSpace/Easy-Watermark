(function($){
	var frame;

	$('#select-image-button, #watermark-preview').click( function( event ) {
		var $el = $(this);
		event.preventDefault();

		if ( frame ) {
			frame.open();
			return;
		}

		frame = wp.media.frames.customHeader = wp.media({
			title: $el.data('choose'),

			library: {
				type: 'image'
			},

			button: {
				text: $el.data('buttonLabel'),
				close: true
			}
		});

		frame.on( 'select', function() {
			// Grab the selected attachment.
			var attachment = frame.state().get('selection').first();
			$('#easy-watermark-url').val(attachment.attributes.url);
			$('#easy-watermark-mime').val(attachment.attributes.mime);
			$('#easy-watermark-id').val(attachment.id);
			$('#easy-watermark-settings-form').submit();
		});

		frame.open();
	});
}(jQuery))
