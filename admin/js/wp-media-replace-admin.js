(function( $ ) {
	'use strict';

	// When the DOM is loaded
	$(function() {
		$('#upload_image_button').click(function() {

	        var formfield = $('#upload_replace_image').attr('name');
	        tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
	        window.send_to_editor = function(html) {

	        var imgurl = $(html).attr('src');
	           $('#upload_replace_image_view').show();
	           $('#upload_replace_image_view').attr('src',imgurl);
	           $('#upload_image_button').val("Change Image")
	           $('#upload_replace_image').val(imgurl);
	           tb_remove();
	        }

	        return false;
	    });
	});
	// When the window is loaded:
	$( window ).load(function() {
	});
	 

})( jQuery );
