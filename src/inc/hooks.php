<?php
/**
 * Hooks compatibilty file.
 *
 * Automatically generated with bin/dump-hooks.php file.
 *
 * @package easy-watermark
 */

// phpcs:disable

add_action( 'plugins_loaded', [ $this->objects['EasyWatermark\Core\Plugin']['instance'], 'setup' ], 10, 0 );
add_action( 'init', [ $this->objects['EasyWatermark\Core\Plugin']['instance'], 'init' ], 10, 0 );
add_action( 'parse_request', [ $this->objects['EasyWatermark\Core\Plugin']['instance'], 'parse_request' ], 10, 1 );
add_action( 'easy-watermark/settings/register', [ $this->objects['EasyWatermark\Backup\Manager']['instance'], 'register_settings_section' ], 10, 1 );
add_action( 'easy-watermark/settings/register/backup', [ $this->objects['EasyWatermark\Backup\Manager']['instance'], 'register_settings_fields' ], 10, 1 );
add_action( 'easy-watermark/placeholders/load', [ $this->objects['EasyWatermark\Placeholders\Defaults']['instance'], 'load_default_placeholders' ], 10, 1 );
add_action( 'init', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'register' ], 10, 0 );
add_filter( 'parent_file', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'parent_file' ], 10, 1 );
add_action( 'current_screen', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'current_screen' ], 10, 0 );
add_filter( 'post_updated_messages', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'post_updated_messages' ], 10, 1 );
add_filter( 'bulk_post_updated_messages', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'bulk_post_updated_messages' ], 10, 2 );
add_action( 'untrashed_post', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'untrashed_post' ], 10, 1 );
add_action( 'delete_post', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'delete_post' ], 10, 1 );
add_action( 'wp_redirect', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'redirect' ], 10, 1 );
add_action( 'admin_notices', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'admin_notices' ], 10, 0 );
add_filter( 'post_row_actions', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'post_row_actions' ], 10, 2 );
add_filter( 'bulk_actions-edit-watermark', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'bulk_actions' ], 10, 1 );
add_filter( 'screen_options_show_screen', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'screen_options_show_screen' ], 10, 2 );
add_action( 'edit_form_top', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'edit_form_top' ], 10, 1 );
add_action( 'edit_form_after_title', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'edit_form_after_title' ], 10, 1 );
add_filter( 'get_user_option_screen_layout_watermark', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'setup_columns' ], 10, 1 );
add_action( 'edit_form_top', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'change_title_support' ], 10, 0 );
add_filter( 'pre_untrash_post', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'pre_untrash_post' ], 10, 2 );
add_filter( 'wp_insert_post_data', [ $this->objects['EasyWatermark\Watermark\PostType']['instance'], 'wp_insert_post_data' ], 10, 2 );
add_action( 'pre-plupload-upload-ui', [ $this->objects['EasyWatermark\Features\AutoWatermarkSwitch']['instance'], 'pre_plupload_upload_ui' ], 10, 0 );
add_action( 'admin_notices', [ $this->objects['EasyWatermark\Features\AutoWatermarkSwitch']['instance'], 'admin_notices' ], 10, 0 );
add_action( 'admin_menu', [ $this->objects['EasyWatermark\Dashboard\Dashboard']['instance'], 'add_options_page' ], 10, 0 );
add_action( 'admin_notices', [ $this->objects['EasyWatermark\Dashboard\Dashboard']['instance'], 'admin_notices' ], 10, 0 );
add_action( 'easy-watermark/dashboard/watermarks/notices', [ $this->objects['EasyWatermark\Dashboard\Watermarks']['instance'], 'admin_notices' ], 10, 0 );
add_filter( 'easy-watermark/dashboard/watermarks/view-args', [ $this->objects['EasyWatermark\Dashboard\Watermarks']['instance'], 'view_args' ], 10, 1 );
add_filter( 'easy-watermark/dashboard/tabs', [ $this->objects['EasyWatermark\Dashboard\Watermarks']['instance'], 'add_tab' ], 10, 1 );
add_action( 'easy-watermark/dashboard/settings/notices', [ $this->objects['EasyWatermark\Dashboard\Settings']['instance'], 'admin_notices' ], 10, 0 );
add_filter( 'easy-watermark/dashboard/settings/view-args', [ $this->objects['EasyWatermark\Dashboard\Settings']['instance'], 'view_args' ], 10, 1 );
add_filter( 'easy-watermark/dashboard/tabs', [ $this->objects['EasyWatermark\Dashboard\Settings']['instance'], 'add_tab' ], 10, 1 );
add_action( 'admin_init', [ $this->objects['EasyWatermark\Dashboard\Permissions']['instance'], 'setup_permissions' ], 10, 0 );
add_action( 'easy-watermark/dashboard/permissions/notices', [ $this->objects['EasyWatermark\Dashboard\Permissions']['instance'], 'admin_notices' ], 10, 0 );
add_filter( 'easy-watermark/dashboard/permissions/view-args', [ $this->objects['EasyWatermark\Dashboard\Permissions']['instance'], 'view_args' ], 10, 1 );
add_filter( 'easy-watermark/dashboard/tabs', [ $this->objects['EasyWatermark\Dashboard\Permissions']['instance'], 'add_tab' ], 10, 1 );
add_action( 'easy-watermark/dashboard/settings/notices', [ $this->objects['EasyWatermark\Dashboard\Tools']['instance'], 'admin_notices' ], 10, 0 );
add_filter( 'easy-watermark/dashboard/tools/view-args', [ $this->objects['EasyWatermark\Dashboard\Tools']['instance'], 'view_args' ], 10, 1 );
add_action( 'wp_ajax_easy-watermark/tools/get-attachments', [ $this->objects['EasyWatermark\Dashboard\Tools']['instance'], 'ajax_get_attachments' ], 10, 0 );
add_filter( 'easy-watermark/dashboard/tabs', [ $this->objects['EasyWatermark\Dashboard\Tools']['instance'], 'add_tab' ], 10, 1 );
add_action( 'easy-watermark/settings/register/general', [ $this->objects['EasyWatermark\Features\SrcsetFilter']['instance'], 'register_settings' ], 10, 1 );
add_filter( 'wp_calculate_image_srcset_meta', [ $this->objects['EasyWatermark\Features\SrcsetFilter']['instance'], 'wp_calculate_image_srcset_meta' ], 1000, 4 );
add_filter( 'wp_calculate_image_srcset', [ $this->objects['EasyWatermark\Features\SrcsetFilter']['instance'], 'wp_calculate_image_srcset' ], 10, 5 );
add_action( 'wp_ajax_easy-watermark/apply_single', [ $this->objects['EasyWatermark\Watermark\Ajax']['instance'], 'apply_single_watermark' ], 10, 0 );
add_action( 'wp_ajax_easy-watermark/apply_all', [ $this->objects['EasyWatermark\Watermark\Ajax']['instance'], 'apply_all_watermarks' ], 10, 0 );
add_action( 'wp_ajax_easy-watermark/restore_backup', [ $this->objects['EasyWatermark\Watermark\Ajax']['instance'], 'restore_backup' ], 10, 0 );
add_action( 'wp_ajax_easy-watermark/autosave', [ $this->objects['EasyWatermark\Watermark\Ajax']['instance'], 'autosave' ], 10, 0 );
add_action( 'wp_ajax_easy-watermark/attachments-info', [ $this->objects['EasyWatermark\Watermark\Ajax']['instance'], 'get_attachments_info' ], 10, 0 );
add_action( 'delete_attachment', [ $this->objects['EasyWatermark\Watermark\Hooks']['instance'], 'delete_attachment' ], 10, 1 );
add_filter( 'wp_get_attachment_image_src', [ $this->objects['EasyWatermark\Watermark\Hooks']['instance'], 'wp_get_attachment_image_src' ], 10, 4 );
add_filter( 'wp_generate_attachment_metadata', [ $this->objects['EasyWatermark\Watermark\Hooks']['instance'], 'wp_generate_attachment_metadata' ], 10, 2 );
add_filter( 'wp_prepare_attachment_for_js', [ $this->objects['EasyWatermark\Watermark\Hooks']['instance'], 'wp_prepare_attachment_for_js' ], 10, 3 );
add_filter( 'bulk_actions-upload', [ $this->objects['EasyWatermark\Watermark\Hooks']['instance'], 'bulk_actions' ], 10, 1 );
add_action( 'easy-watermark/settings/register/general', [ $this->objects['EasyWatermark\Settings\Settings']['instance'], 'register_fields' ], 5, 1 );
add_action( 'admin_init', [ $this->objects['EasyWatermark\Settings\Settings']['instance'], 'register_settings' ], 10, 0 );
add_filter( 'plugin_action_links_easy-watermark/easy-watermark.php', [ $this->objects['EasyWatermark\Settings\Settings']['instance'], 'plugin_action_links' ], 10, 2 );
add_action( 'admin_enqueue_scripts', [ $this->objects['EasyWatermark\Core\Assets']['instance'], 'register_admin_scripts' ], 20, 0 );
add_action( 'admin_enqueue_scripts', [ $this->objects['EasyWatermark\Core\Assets']['instance'], 'enqueue_admin_scripts' ], 30, 0 );
add_action( 'wp_enqueue_media', [ $this->objects['EasyWatermark\Core\Assets']['instance'], 'wp_enqueue_media' ], 10, 0 );
add_action( 'do_meta_boxes', [ $this->objects['EasyWatermark\Metaboxes\Watermark\Submitdiv']['instance'], 'setup' ], 10, 0 );
add_filter( 'hidden_meta_boxes', [ $this->objects['EasyWatermark\Metaboxes\Watermark\Submitdiv']['instance'], 'hide' ], 10, 2 );
add_action( 'do_meta_boxes', [ $this->objects['EasyWatermark\Metaboxes\Watermark\WatermarkContent']['instance'], 'setup' ], 10, 0 );
add_filter( 'hidden_meta_boxes', [ $this->objects['EasyWatermark\Metaboxes\Watermark\WatermarkContent']['instance'], 'hide' ], 10, 2 );
add_action( 'do_meta_boxes', [ $this->objects['EasyWatermark\Metaboxes\Watermark\TextOptions']['instance'], 'setup' ], 10, 0 );
add_filter( 'hidden_meta_boxes', [ $this->objects['EasyWatermark\Metaboxes\Watermark\TextOptions']['instance'], 'hide' ], 10, 2 );
add_action( 'do_meta_boxes', [ $this->objects['EasyWatermark\Metaboxes\Watermark\Alignment']['instance'], 'setup' ], 10, 0 );
add_filter( 'hidden_meta_boxes', [ $this->objects['EasyWatermark\Metaboxes\Watermark\Alignment']['instance'], 'hide' ], 10, 2 );
add_action( 'do_meta_boxes', [ $this->objects['EasyWatermark\Metaboxes\Watermark\Scaling']['instance'], 'setup' ], 10, 0 );
add_filter( 'hidden_meta_boxes', [ $this->objects['EasyWatermark\Metaboxes\Watermark\Scaling']['instance'], 'hide' ], 10, 2 );
add_action( 'do_meta_boxes', [ $this->objects['EasyWatermark\Metaboxes\Watermark\ApplyingRules']['instance'], 'setup' ], 10, 0 );
add_filter( 'hidden_meta_boxes', [ $this->objects['EasyWatermark\Metaboxes\Watermark\ApplyingRules']['instance'], 'hide' ], 10, 2 );
add_action( 'wp_ajax_easy-watermark/preview_image', [ $this->objects['EasyWatermark\Metaboxes\Watermark\Preview']['instance'], 'ajax_preview_image' ], 10, 0 );
add_action( 'do_meta_boxes', [ $this->objects['EasyWatermark\Metaboxes\Watermark\Preview']['instance'], 'setup' ], 10, 0 );
add_filter( 'hidden_meta_boxes', [ $this->objects['EasyWatermark\Metaboxes\Watermark\Preview']['instance'], 'hide' ], 10, 2 );
add_action( 'do_meta_boxes', [ $this->objects['EasyWatermark\Metaboxes\Watermark\Placeholders']['instance'], 'setup' ], 10, 0 );
add_filter( 'hidden_meta_boxes', [ $this->objects['EasyWatermark\Metaboxes\Watermark\Placeholders']['instance'], 'hide' ], 10, 2 );
