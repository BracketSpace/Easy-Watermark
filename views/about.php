		<div class="postbox">
			<h3><?php _e('About', 'easy-watermark'); ?></h3>
			<div class="inside">
				<p><?php _e('Plugin Version', 'easy-watermark'); ?>: <strong><?php echo EW_Plugin::getVersion();?></strong></p>
				<p><?php _e('Plugin Author', 'easy-watermark'); ?>: <a href="mailto:wojtek@szalkiewicz.pl">Wojtek Szałkiewicz</a></p>
				<p><?php if(EW_Plugin::isGDEnabled()) : _e('GD library is enabled.', 'easy-watermark'); else : ?><span style="color:red;"><?php _e('GD library is not available! Easy Watermark can\'t work without it.', 'easy-watermark'); ?></span><?php endif; ?></p>
				<?php if(!EW_Plugin::isFreeTypeEnabled()) : ?><p><span style="color:red;"><?php _e('FreeType extension is not available! You will not be able to use text watermark.', 'easy-watermark'); ?></span></p><?php endif; ?>
				<a href="http://wordpress.org/extend/plugins/easy-watermark" target="_blank">
				<?php _e('Plugin page in WP repository', 'easy-watermark'); ?></a><br/>
				<a href="http://wordpress.org/extend/plugins/easy-watermark/faq" target="_blank">
				<?php _e('FAQ', 'easy-watermark'); ?></a><br/>
				<a href="http://wordpress.org/support/plugin/easy-watermark" target="_blank">
				<?php _e('Support', 'easy-watermark'); ?></a><br/><br/>
				<strong><?php _e('Want to buy me a coffee?', 'easy-watermark'); ?></strong>
				<?php include dirname(__FILE__) . '/donation.php'; ?>
			</div><!-- .inside -->
		</div>
