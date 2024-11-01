=== WP Media Replace ===
Contributors: prakashrao
Tags: attachment, media, replace media
Requires at least: 4.0
Tested up to: 5.1
Stable tag: 5.1
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP Replace Media is a useful and smooth plugin to replace an image to some other existing or new media image. It automatically replaces the old image with new one, so website owners do not have to change the URL manually all over the website.

== Description ==

Many of the WordPress website users fetches the media image in featured image section or content editor to be displayed at front-end. Now, the big headache is if we want to change an image to some new image then we will have to change it in all the posts types where this image has been used. 

WP Media Replace solves this headache by managing everything smoothly.

A brief about the logics:

* Goto to edit more section of a media item and in bottom you will find "Replace Media" section.
* Upload and new image which will replace the exisiting image in this media item.
* Suppose you are having an image named as "existing.png" and you want to replace it with "new.png". 
* Upload "new.png" in "Replace Media" section and click on "Update".
* "existing.png" will be moved to "del" folder and "new.png" will be copied and renamed as "existing.png".
* Now, you will have to same images with different names "exising.png" and "new.png" to overcome this issue check the box to delete the "new.png" at the time of uploading it.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `wp-media-replace` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Check the Edit Media page to see the "Replace Media" section working.

== Frequently Asked Questions ==

= What happens to original image? =
We keep a backup of original image, original image is moved to "del" folder.

= What about the the other sizes of the image, are they getting replaced? =
Yes, if an image "existing.png" have 2 sizes or more as "existing-150X150.png" and "existing-300X300.png" then these images will also be replaced with the new image and names of the images will be kept same.

= What about the database changes, any modifications are done in synchronized image metadatas? =
No, overall logic is we are replacing an exsiting image with a new media image, we take the name of original image (all sizes) -> moves the original images to "del" folder -> take the name of new image -> create a copy of new image and name it as original image.

= What if my original image is having a "large" size and new image do not have "large" size? =
As wordpress default maintains 4 sizes of media images "thumbnail", "medium", "medium_large" & "large". Depending on the aspect ratio of image we uplad the different thumbnail versions are created. If original image is having "large" size but new image have only "thumbnail", "medium", "medium_large" then "large" size for the new image will be created by plugin and it will use the fulll size of the image.
