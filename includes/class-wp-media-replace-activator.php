<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.linkedin.com/in/prakash-rao-9643398a/
 * @since      1.0.0
 *
 * @package    Wp_Media_Replace
 * @subpackage Wp_Media_Replace/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Media_Replace
 * @subpackage Wp_Media_Replace/includes
 * @author     Prakash Rao <prakash122014@gmail.com>
 */
class Wp_Media_Replace_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'media_replace';
		$sql = "CREATE TABLE $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  media_id mediumint(9) NOT NULL,
		  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  dump_path LONGTEXT DEFAULT '' NOT NULL,
		  file_name LONGTEXT DEFAULT '' NOT NULL,
		  version tinytext NOT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}
