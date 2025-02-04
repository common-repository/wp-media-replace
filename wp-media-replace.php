<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.linkedin.com/in/prakash-rao-9643398a/
 * @since             1.0.0
 * @package           Wp_Media_Replace
 *
 * @wordpress-plugin
 * Plugin Name:       WP Media Replace
 * Plugin URI:        https://wordpress.org
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Prakash Rao
 * Author URI:        https://www.linkedin.com/in/prakash-rao-9643398a/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-media-replace
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_MEDIA_REPLACE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-media-replace-activator.php
 */
function activate_wp_media_replace() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-media-replace-activator.php';
	Wp_Media_Replace_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-media-replace-deactivator.php
 */
function deactivate_wp_media_replace() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-media-replace-deactivator.php';
	Wp_Media_Replace_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_media_replace' );
register_deactivation_hook( __FILE__, 'deactivate_wp_media_replace' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-media-replace.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_media_replace() {

	$plugin = new Wp_Media_Replace();
	$plugin->run();

}
run_wp_media_replace();
