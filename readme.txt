=== Easy Watermark ===
Contributors: szaleq, bracketspace
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=wojtek%40szalkiewicz%2epl&lc=GB&item_name=Easy%20Watermark%20Wordpress%20Plugin&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted
Tags: watermark, image, picture, photo, media, gallery, signature, transparent, upload, admin
Requires at least: 4.6
Requires PHP: 5.6
Tested up to: 5.8
Stable tag: 1.0.11
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: easy-watermark

Allows to add watermark to images automatically on upload or manually.

== Description ==

Easy Watermark can automatically add watermark to images as they are uploaded to wordpress media library. You can also watermark existing images manually (all at once or an every single image). Watermark can be an image, text or both.

= See the demo =

[youtube https://www.youtube.com/watch?v=xM_0Y0oX4o0]

= Plugin features =

On one image you can have two watermarks! One of them can be text watermark and the other image watermark. You can control their position and size and apply them to your media independently.

* Image watermark can be a JPG, PNG or GIF
* Full support for transparency and alpha chanel in PNG and GIF files
* JPG and GIF files and text can have opacity set (from 0 to 100%)
* Text watermark is created using ttf fonts
* Text color, size and rotation can be set
* All built-in image sizes can be watermarked (thumbnail, medium, large and fullsize) as well as all additional sizes registered by themes or plugins
* Plugin can create image backups and allows to easily restore images

= Image watermark =

Easy Watermark supports three most popular image formats for watermark: JPG, PNG and GIF. For JPG watermarks you can control the opacity as well.

Watermark can be applied in on of the 9 positions on the image and you can controll the exact sizing of it.

= Text watermark =

Text watermark have a powerful feature of placeholders, which can be dynamically applied to the image. Ie. you can put the name of user who uploaded the image as well as the upload date. Watermark text will be automatically generated and applied.

The plugin supports a few fonts:

* Arial
* Arial Black
* Comic Sans MS
* Courier New
* Georgia
* Impact
* Tahoma
* Times New Roman
* Trebuchet MS
* Verdana

You can also place the text watermark in one of the 9 positions on the image, control the angle, color, opacity and size.

= Custom development =

BracketSpace - the company behind this plugin provides [custom WordPress plugin development services](https://bracketspace.com/custom-development/). We can create any custom plugin for you.

== Installation ==

Note: Easy Watermark requires GD extension installed and enabled on a server to work.

1. Install via Plugin installation screen in WordPress dashboard or download and unpack plugin zip and upload its content to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Plugin is working. Go to "Tools > Easy Watermark" to set up your watermarks.

== Frequently asked questions ==

= Can I remove watermark after it was added? =
Yes, since version 0.6.0 there is an option to "remove" watermark by restoring the original image. Backup feature is enabled by default, you can disable it on the "Tools > Easy Watermark" screen. Note: this will use more space on your server due to the fact that the images will be stored twice.
Only the fullsize image is stored in backup, thumbnails are regenerated during the restoring process.
To restore the original image just go to the attachment edit page. In the "Easy Watermark" meta box you can find the "Restore original image" button. You can also do this via Media page and bulk actions.

= How can I restore original images after the plugin was uninstalled? =
The plugin doesn't restore your images on deactivation/removal. Please consider to install the plugin again and restore your images before uninstalling.
If you don't have a possibility to do this, you can manually restore your images. Just go to wp-content/ew-backup in your wordpress main directory. You will see there are folders in the same order like in uploads, images are stored as /year/month/imagename.jpg|png. What you need is to copy all the files from ew-backup to uploads dir (it will ask you if you want to override the existing files, click YES). As mensioned before, this will restore only the fullsize images so you need to use some other plugin to generate the thumbnails again (see Force Regenerate Thumbnails by Pedro Elsner).

= How can I add watermark to pictures that were uploaded before the plugin was installed? =
You can go to "Tools > Easy Watermark > Tools" screen and use bulk action options.

= How can I adjust watermark image position? =
Watermark position can be adjusted vertically and horizontally by selecting alignment (left, center, right, top, middle, bottom). You can also define horizontal and vertical offset.

= Can I add both, text and image watermark at once? =
Yes, there is a posibility to add only image, only text or both.

= How Can I adjust text watermark? =
You can choose text font from the list of ten fonts included to this plugin. You can also set font size, color, angle and opacity. Position of text watermark can be adjusted exactly like the image watermark position.

= Can I use my font for text watermark? =
Unfortunately no. In PRO version you'll be able to use more fonts.

= How the scaling of the watermark image works? =
On the watermark image settings page you can se 'Scaling Mode' selection which has 5 options:
* 'None' - watermark scaling is off
* 'Cover' - watermark will fill the entire image
* 'Contain' - watermark width or height will be adjusted to image width or height in such a way that it will be all visible
* 'Fit to Width' - watermark width will always be adjusted to image width
* 'Fit to Height' - watermark height will always be adjusted to image height
If 'Scale down only' checkbox is checked, any scaling will be done only for images smaller than watermark image.
Watermark ratio is always preserved, so it can go beyond the image when the 'Scaling Mode' is set to 'Fill'.
With 'Fit to Width' or 'Fit to Height' options watermark dimensions can be set as a percentage in relation to the image dimensions.

= What placeholders can I use in text watermark? =
All available placeholders are listed in a box titled 'Placeholders' displayed while creating the Text Watermark.

= Does the plugin work without the GD library? =
Unfortunately, no. It is planned though for one of the releases in the undefined future.

= Can you create a plugin for me? =

Yes! We're offering a [custom plugin development](https://bracketspace.com/custom-development/) services. Feel free to contact us to find out how we can help you.

== Screenshots ==

1. Image watermark settings
2. Text watermark settings
3. All watermarks
4. Easy Watermark settings
5. Easy Watermark permissions
6. Watermark control while uploading images
7. Bulk watermark or restore images on Media screen
8. Bulk watermark and restore all images

== Changelog ==

= 1.0.11 =
* [Fixed] Watermark preview url fixed to work with non-standard WordPress installations
* [Fixed] Error in Cache Busting feature.

= 1.0.10 =
* [Added] Role existence checks during plugin activation.
* [Added] Filter to prevent applying certain watermark.

= 1.0.9 =
* [Fixed] Translated view file names causing `missing view` errors.
* [Fixed] Errors occuring on the first plugin activation.

= 1.0.8 =
* [Added] New form styles compatible with new WordPress form styles.
* [Added] Option to disable cache buster responsible for adding version param to image urls.
* [Fixed] Watermark preview.

= 1.0.7 =
* [Removed] Freemius library.
* [Fixed] Potential PHP 8 issue.

= 1.0.6 =
* [Fixed] Compatibility with other media-related plugins
* [Fixed] Backup file paths on Windows
* [Fixed] Bulk actions in media library list view

= 1.0.5 =
* Images will now hold information about applied watermarks which has been removed
* [Fixed] Missing FileBird dependency
* [Fixed] Database query error in bulk actions

= 1.0.4 =
* [Fixed] Improved error handling
* [Fixed] "Enhanced Media Library" plugin compatibility (and possibly some other plugins which replace media library components)
* [Fixed] Watermark delete error fixed
* [Fixed] Source set filtering improved

= 1.0.3 =
* [Fixed] FileBird compatibility
* [Fixed] Frontend content builders compatibility

= 1.0.2 =
* [Fixed] Media library not working with ACF plugin enabled

= 1.0.1 =
* [Fixed] Freemius screen displayed incorrectly after activation
* [Fixed] Permission settings now actualy works
* [Fixed] Config is now saved in unicode so you can use other character sets, like cyrylic
* [Fixed] Incompatibilities with caching plugins
* [Fixed] Srcset fatal error edge case
* [Fixed] Watermark deleting

= 1.0.0 =
* The plugin has been rewrote from ground up.

= 0.7.0 =
* Freemius integration

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
