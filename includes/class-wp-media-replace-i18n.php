<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.linkedin.com/in/prakash-rao-9643398a/
 * @since      1.0.0
 *
 * @package    Wp_Media_Replace
 * @subpackage Wp_Media_Replace/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Media_Replace
 * @subpackage Wp_Media_Replace/includes
 * @author     Prakash Rao <prakash122014@gmail.com>
 */
class Wp_Media_Replace_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-media-replace',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
