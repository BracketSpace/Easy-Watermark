<div class="watermark-content-metabox">
	<div class="image-content">
		<input class="watermark-id" name="watermark[attachment_id]" type="hidden" value="<?php echo $attachment_id; ?>" />
		<input class="watermark-url" name="watermark[url]" type="hidden" value="<?php echo $url; ?>" />
		<input class="watermark-mime-type" name="watermark[mime_type]" type="hidden" value="<?php echo $mime_type; ?>" />

		<div class="select-image-button">
			<a class="button-secondary" data-choose="<?php _e('Choose Watermark Image', 'easy-watermark'); ?>" data-button-label="<?php _e('Set as Watermark Image', 'easy-watermark'); ?>" href="#"><?php _e('Select/Upload Image', 'easy-watermark'); ?></a>
		</div>
		<div class="watermark-image">
			<img src="<?php echo $url; ?>" />
		</div>
	</div>
	<div class="text-content">
		<p>Text Setup</p>
	</div>
</div>
