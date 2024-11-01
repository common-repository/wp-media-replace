<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/prakash-rao-9643398a/
 * @since      1.0.0
 *
 * @package    Wp_Media_Replace
 * @subpackage Wp_Media_Replace/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Media_Replace
 * @subpackage Wp_Media_Replace/admin
 * @author     Prakash Rao <prakash122014@gmail.com>
 */
class Wp_Media_Replace_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Media_Replace_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Media_Replace_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style('thickbox');
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-media-replace-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Media_Replace_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Media_Replace_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script('media-upload');
    	wp_enqueue_script('thickbox');
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-media-replace-admin.js', array( 'jquery','media-upload','thickbox' ), $this->version, false );
		wp_enqueue_script($this->plugin_name);


	}

	public function add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'attachment', '' );
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'replace_image',
                __( 'Replace Media', 'textdomain' ),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
        }
    }

    public function save_meta_box($post_id) {
    	
    	// Check if our nonce is set.
        if ( ! isset( $_POST['replace_image_field_nonce'] ) ){return $post_id;}

        $nonce = $_POST['replace_image_field_nonce'];
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'replace_image_field' ) ){ return $post_id;}

        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){ return $post_id;}

        // check user permission
        if ( ! current_user_can( 'edit_post', $post_id ) ) {return $post_id;}

        if( $_REQUEST['upload_replace_image'] == "" ) { return $post_id; }

        // dumping the main files to del folder
        $dump_attach_ID = $_REQUEST['post_ID'];
        $file_dumped = $this->backup_myfiles_main( $dump_attach_ID );

        // replace original image with new one
        $new_image_url = $_REQUEST['upload_replace_image'];
        $new_img_ID = $this->get_image_id($new_image_url);
        $this->replace_with_new_file( $new_img_ID, $dump_attach_ID );

        // check if we have to delete the new replacable image
        if( isset( $_REQUEST['delete_replaced_image'] ) ){
        	if( !wp_delete_attachment( $new_img_ID, true ) ){
        		wp_die( 'Can\'t delete the attachment', 'Error' );
        	}
        } 
    }

    public function backup_myfiles_main( $fileID ) {

    	if( !$fileID ){ return false; }

    	$transfer_data = array();
    	$return_arr = array();
    	
    	$entire_path = wp_upload_dir();
    	// $entire_path = $entire_path['path'];
    	$upload_dir = $entire_path['basedir'];
    	
    	// get image metadata
    	$image_datas = wp_get_attachment_metadata( $fileID );
    	$image_name_attr = explode("/", $image_datas['file']);
    	
    	$curr_path = $upload_dir.'/'.$image_name_attr[0].'/'.$image_name_attr[1].'/';
    	$dump_path = $upload_dir.'/'.$image_name_attr[0].'/'.$image_name_attr[1].'/del/';

    	// check if del folder exists, if not create one
    	if ( !wp_mkdir_p( $dump_path ) ) {
		    wp_die( '"del" folder can not be created, $dump_path is not having writable permissions', 'Permission error' );
		}

    	// will be returned in response
    	$image_name = $image_name_attr[2];

    	// initialize array with main file
    	$transfer_data[] = array(
    						'curr_path' => $curr_path,
    						'dump_path'	=> $dump_path,
    						'file_name'	=> $image_name,
    						'media_id' 	=> $fileID,
    						);
    	$return_arr[] = $image_datas['file'];


    	// get all metadatas in transfer array
    	foreach ($image_datas['sizes'] as $key => $value) {
    		$i++;
    		$transfer_data[] = array(
    						'curr_path' => $curr_path,
    						'dump_path'	=> $dump_path,
    						'file_name'	=> $value['file'],
    						'media_id' 	=> $fileID,
    						);
    		$return_arr[$key] = $value['file'];
    	}

    	$media_replace_response = $this->make_actual_transfer($transfer_data);
    	if( $media_replace_response ){
    		return $return_arr;
    	} else{
    		wp_die( $media_replace_response, "Replace media error" );
    	}
	}

    public function make_actual_transfer( $transfer_data ) {
    	global $wpdb;
    	$table_name = $wpdb->prefix . 'media_replace';
    	
    	// p($transfer_data);
    	// die();

    	foreach ($transfer_data as $TD) {
    		
    		// insert into table
    		$data_inserted = $wpdb->insert( 
								$table_name, 
								array( 
									'time' => gmdate("Y-m-d H:i:s"), 
									'media_id' => $TD['media_id'],
									'dump_path' => $TD['dump_path'],
									'file_name' => $TD['file_name'],
									'version' => '1', 
								) 
							);

    		$files_moved = rename( 
    							$TD['curr_path'].$TD['file_name'],
    							$TD['dump_path'].$wpdb->insert_id.'-'.$TD['file_name']
    							);
    		if( !$files_moved || !$data_inserted ){
    			return $wpdb->print_error();
    		}
    	}
    	return true;
    }


    public function replace_with_new_file($newFileID, $oldFileID) {

    	$new_image_datas = wp_get_attachment_metadata( $newFileID );
    	$old_image_datas = wp_get_attachment_metadata( $oldFileID );

    	$new_file_sets = $this->get_image_sets($new_image_datas);
    	$org_file_sets = $this->get_image_sets($old_image_datas);

    	$copy_args = array(
    					'new_files' => $new_file_sets,
    					'org_files' => $org_file_sets,
    					);

    	if( $this->copy_new_files( $copy_args ) ){
    		return true;
    	} else{
    		return false;
    	}
    }

    public function copy_new_files($args) {

    	$entire_path = wp_upload_dir();
    	$upload_dir = $entire_path['basedir'];

    	$from_file_path = $upload_dir.'/'.$args['new_files']['sub_dir'];
    	$av_sizes = array_keys($args['new_files']['sizes']);
    	unset($av_sizes[0]);
    	
    	$to_file_path = $upload_dir.'/'.$args['org_files']['sub_dir'];

    	$copy_flag = 1;

    	// loop through org files and get name of all original images
    	foreach ($args['org_files']['sizes'] as $key => $value) {
    		$to_file 	= $value;

    		// check if the original image size is present in new file or not
    		if( in_array($key, $av_sizes) ){
    			$from_file 	= $args['new_files']['sizes'][$key];
    		} else{
    			$from_file 	= $args['new_files']['sizes'][0];
    		}

    		// echo $from_file_path.'/'.$from_file .' => '. $to_file_path.'/'.$to_file.'<br>';

			if( !copy( $from_file_path.'/'.$from_file, $to_file_path.'/'.$to_file ) ){
				$copy_flag = 0;
			}
    	}
		
		if( $copy_flag == 0 ){
			return false;
		} else{
			return true;
		}
    }

    public function get_image_sets($image_data) {
    	$image_arr = explode("/", $image_data['file'] );
    	$file_sets['sizes'][] = $image_arr[2];
    	$file_sets['sub_dir'] = $image_arr[0].'/'.$image_arr[1];
    	foreach ($image_data['sizes'] as $key => $value) {
    		$file_sets['sizes'][$key] = $value['file'];	
    	}
    	return $file_sets;
    }

    public function render_meta_box_content( $post ) {
 
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'replace_image_field', 'replace_image_field_nonce' );
 
        // Use get_post_meta to retrieve an existing value from the database.
        $value = get_post_meta( $post->ID, 'upload_replace_image', true );
 
        // Display the form, using the current value.
        ?>

        <p>
	        <label for="upload_replace_image">
	        	<strong><?php _e( 'Add or upload the image', 'textdomain' ); ?></strong>
	        </label>
    	</p>
    	<p>
	        <input id="upload_replace_image" type="hidden" name="upload_replace_image" value="<?php echo esc_attr( $value ); ?>" />
	        
	        <p>
	        	<img 
	        	id="upload_replace_image_view" 
	        	class="thumbnail"
	        	src="<?php echo plugin_dir_url( __FILE__ ).'/partials/product-placeholder.gif'; ?>"
	        	style="
	        		width:200px;
	        		height: auto;
	        		border: solid 1px #e7e7e7;
	        		">
	        </p>
	        <p>
				<input id="upload_image_button" type="button" value="Upload Image" class="button" />
			</p>
		</p>
		<p>
			<label for="delete_replaced_image">
			<input type="checkbox" name="delete_replaced_image" id="delete_replaced_image">
			<strong><?php _e( 'Delete the selected image after replacing it with original image?',
								'textdomain' ); ?></strong>
			</label>
		</p>
		<?php
    }

    // retrieves the attachment ID from the file URL
	public function get_image_id($image_url) {
		global $wpdb;
		$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
	    return $attachment[0]; 
	}
}
