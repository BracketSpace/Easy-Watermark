(function($){
	if($('#easy-watermark-autoadd').attr('checked'))
		$('.auto-add-options').show();

	checkedAllPostTypes();

	$('#easy-watermark-autoadd').change(function(){
		var checked = $(this).attr('checked');
		if(checked)
			$('.auto-add-options').show();
		else 
			$('.auto-add-options').hide();
	});

	$('#ew-select-all-post-types').change(function(){
		var elm = $(this);
		var types = $('.ew-post-type');

		if(elm.prop('checked')){
			types.each(function(){
				var type = $(this);
				type.data('checked-before', type.prop('checked'));
			});
			types.prop('checked', true).prop('readonly', true);
		}
		else {
			types.each(function(){
				var type = $(this);
				type.prop('checked', type.data('checked-before'));
			});
			types.prop('readonly', false);
		}
	});

	$('.ew-post-type').change(function(){
		var elm = $(this), all;

		if(elm.prop('readonly')){
			elm.prop('checked', true);
		}
		else {
			all = checkedAllPostTypes();

			if(all){
				elm.data('checked-before', false);
			}
		}
	});

	$('.remove-image').click(function(e){
		e.preventDefault();

		$('#easy-watermark-url').val('');
		$('#easy-watermark-id').val('');
		$('#easy-watermark-mime').val('');
		$('#easy-watermark-settings-form').submit();
	});

	var picker = $('#colorselector');
	var input = $('#ew-color');
	var chenged = false;
	/**
	 * Using built-in Iris
	 */
	input.iris({
		palettes: true,
		hide: false,
		change: function(e, ui){
			var color = ui.color.toString();
			input.css('background-color', color);
			if(ui.color.l() < 50){
				input.css('color', 'white');
			}
			else {
				input.css('color', 'black');
			}

			refreshImage(color);
		}
	});


/** Old colour picker
	picker.ColorPicker({
		onSubmit: function(hsb, hex, rgb, el){
			picker.children('div').css('backgroundColor', '#' + hex);
			input.val(hex);
			refreshImage();
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(input.val());
		}
	});
*/
	
	$('#alignmentbox input').css('visibility', 'hidden');
	$('#alignmentbox input:checked').each(function(){
		$(this).parent().addClass('current');
	});

	$('#alignmentbox :radio').click(function(){
		if($(this).is(":checked")){
			$('#alignmentbox label').removeClass('current');
			$(this).parent().addClass('current');
		}
	});

	if($('input[name=option_page]').val() == 'easy-watermark-settings-text'){

		var refreshImage = function(color){
			var row = $('#ew-preview-row');
			var text = $('#title').val();

			if(text){
				row.removeClass('hidden');
				var font = $('#ew-font').val();
				var size = $('#ew-size').val();
				var angle = $('#ew-angle').val();
				var opacity = $('#ew-opacity').val();

				if(typeof color != 'string'){
					color = $('#ew-color').val();
				}

				var params = '&tp=1&text='+encodeURIComponent(text)+'&font='+font+'&color='+encodeURIComponent(color)+'&size='+size+'&angle='+angle+'&opacity='+opacity;
				var url = window.location + params;

				$('#ew-text-preview').attr('src', url);
			}
			else {
				row.addClass('hidden');
			}
		}

		$('#easy-watermark-settings-form input').focusout(refreshImage)
		$('#easy-watermark-settings-form select').change(refreshImage);
	}

	if($('input[name=option_page]').val() == 'easy-watermark-settings-image'){
		var row = $('#ew-scale-row');
		var select = $('#ew-scale-mode');
		var value = select.val();
		if(value == 'fit' || value == 'fill'){
			row.hide();
		}
		select.change(function(){
			value = $(this).val();
			if(value == 'fit_to_width' || value == 'fit_to_height'){
				row.fadeIn(200);
			}
			else {
				row.hide();
			}
		});
	}

	function checkedAllPostTypes(){
		var allTypes = $('.ew-post-type'),
		all = true;

		for(var i = 0; i < allTypes.length; i++){
			var type = $(allTypes[i]);
			if(type.prop('checked') == false){
				all = false;
			}
		}

		if(all){
			$('#ew-select-all-post-types').prop('checked', true);
			allTypes.prop('readonly', true);
		}

		return all;
	}
}(jQuery))
