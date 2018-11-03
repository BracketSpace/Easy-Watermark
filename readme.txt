=== Easy Watermark ===
Contributors: szaleq
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=wojtek%40szalkiewicz%2epl&lc=GB&item_name=Easy%20Watermark%20Wordpress%20Plugin&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted
Tags: watermark, image, picture, photo, media, gallery, signature, transparent, upload, admin
Requires at least: 3.8
Tested up to: 4.9.7
Stable tag: 0.6.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: easy-watermark

Allows to add watermark to images automatically on upload or manually.

== Description ==

Easy Watermark can automatically add watermark to images as they are uploaded to wordpress media library. You can also watermark existing images manually (all at once or an every single image). Watermark can be an image, text or both.

= Plugin features =
* image watermark can be a jpg, png or gif
* full support for transparency and alpha chanel in png and gif files
* jpg files, gif files and text can have opacity set (from 0 to 100%)
* text watermark is created using ttf fonts
* text color, size and rotation can be set
* all built-in image sizes can be watermarked (thumbnail, medium, large and fullsize) as well as all additional sizes registered by themes or plugins (since 0.4.3)
* since 0.6 there is a possibility to remove watermark by restoring the original image
* fully translatable

= Translations included =
* Polish
* French (by Regis Brisard)
* Spanish ([http://abitworld.com/](http://abitworld.com/ "Translator's home page"))
* Russian
* Persian

If you have made a translation and want to contribute with it to Easy Watermark, please e-mail me.

== Installation ==

Note: Easy Watermark requires GD extension installed and enabled on a server to work.

1. Unpack easy-watermark.zip and upload its content to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Plugin is working. Go to "Settings > Easy Watermark" to set up your watermark.

== Frequently asked questions ==

= Can I remove watermark after it was added? =
Yes, since version 0.6.0 there is an option to "remove" watermark by restorin the original image. Backup feature is disabled by default, you can enable it on the "Settings > Easy Watermark". Note: this will use more space on your server due to the fact that the images will be stored twice.
Only the fullsize image is stored in backup, thumbnails are regenerated during the restoring process.
To restore the original image just go to the attachment edit page. In the "Easy Watermark" meta box you can find the "Restore original image" button. Just click it.

= How can I restore original images after the plugin was uninstalled? =
The plugin doesn't restore your images on deactivation/removal. Please consider to install the plugin again and restore your images before uninstalling.
If you don't have a possibility to do this, you can manually restore your images. Just go to wp-content/ew_backup in your wordpress main directory. You will se there are folders in the same order like in uploads, images are stored as /year/month/imagename.jpg|png|whatever. What you need is to copy all the files from ew_backup to uploads dir (it will ask you if you want to override the existing files, click YES). As mensioned before, this will restore only the fullsize images so you need to use some other plugin to generate the thumbnails again (see Force Regenerate Thumbnails by Pedro Elsner).

= How can I add watermark to pictures that were uploaded before the plugin was installed? =
You can go to "Media >> Easy Watermark" and click "Add watermark to all images" button. If you want to add watermark to single images, you can find links titled "Add watermark" in the media library (see screenshots) or "Add watermark" button on image edit page.

= How can I adjust watermark image position? =
Watermark position can be adjusted vertically and horizontally by selecting alignment (left, center, right, top, middle, bottom). You can also define horizontal and vertical offset.

= Can I add both, text and image watermark at once? =
Yes, there is a posibility to add only image, only text or both.

= How Can I adjust text watermark? =
You can choose text font from the list of ten fonts included to this plugin. In future releases you will be able to upload your own font file. You can also set font size, color, angel and opacity. Position of text watermark can be adjusted exactly like image position.

= Can I use my font for text watermark? =
There is no user-friendly way to do this, however if you know what you do, you can upload your truetype font file to the %plugin_dir%/fonts. Then edit %plugin_dir%/lib/EasyWatermarkSettings.php and add your font file name to $fonts array.

= How the scaling of the watermark image works? =
On the watermark image settings page you can se 'Scaling Mode' selection which has 5 options:
* 'None' - watermark scaling is off
* 'Fill' - watermark will fill the entire image
* 'Fit' - watermark width or height will be adjusted to image width or height in such a way that it will be all visible
* 'Fit to Width' - watermark width will always be adjusted to image width
* 'Fit to Height' - watermark height will always be adjusted to image height
If 'Scale to Smaller' checkbox is checked, any scaling will be done only for images smaller than watermark image.
Watermark ratio is always preserved, so it can go beyond the image when the 'Scaling Mode' is set to 'Fill'.
With 'Fit to Width' or 'Fit to Height' options watermark dimensions can be set as a percentage in relation to the image dimensions.

= What placeholders can I use in text watermark? =
All available placeholders are listed in a box titled 'Placeholders' on the text watermark settings page, under the 'About' box.

== Screenshots ==

1. General settings page
2. Image settings page
3. Text settings page
4. Easy Watermark Tool
5. Easy Watermark metabox on attachment page

== Changelog ==

= 0.6.1 =
* Minor adjustments for newest WordPress version

= 0.6.0 =
* Added: backup option for watermarked images - allows to restore original image
* Added: button to restore all original images on the plugins page
* Changed method of watermarking all images
	* it now uses ajax and watermarks 10 images at once so it needs less time and memory per request

= 0.5.2 =
* Fix: watermarking class optimised to avoid out-of-memory errors
* Fix: settings are now kept after deactivation
* Fix: many small bugs in a code
* Added: bunch of new text placeholders
* small changes in UI

= 0.5.1 =
* fixed cooperation with front-end upload plugins (like BuddyPress Media)
* added possibility to define which post type attachments should be watermarked on upload

= 0.5 =
* fixed issue with watermarking not selected image types on upload
* png transparency in watermarked images is now preserved
* added possibility to disable watermarking feature for particular roles
* user which can only add posts (like 'author') can only add watermark to the images uploaded by him
* introduced placeholders in text watermark
* color picker changed to Iris (integrated with wordpress)
* added information about image status (watermarked or not)
* added 'Easy Watermark' column in media table and metabox on an image editing page
* a lot of 'invisible' changes in the code

= 0.4.4 =
* repaired issue with auto-watermark option

= 0.4.3 =
* added support for additional image sizes registered by some templates or plugins (e.g. 'post-thumbnail')

= 0.4.2 =
* added possibility to define jpeg quality

= 0.4.1 =
* added an option to scale watermark only for smaller images
* added confirmation button for 'Add watermark to all images' action
* some changes in the code to make the plugin more compatible

= 0.4 =
* introduced watermark image scaling option

= 0.3 =
* added support for all image sizes (thumbnail, medium, large), not only the fullsize image
* plugin now checks if the GD library is available, if not, it'll inform you about it instead of throw errors

= 0.2.3 =
* added bulk action on media library page

= 0.2.2 =
* added live text preview on the settings page

= 0.2.1 =
* some changes on settings page

= 0.2 =
* added text watermark handling
* corrected issue with auto adding watermark on upload

= 0.1.1 =
* offset can be now also a percentage, not only pixel value
* changed code structure
	* separete class responsible only for watermarking (can be used alone)

= 0.1 =
* Initial release
