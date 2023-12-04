<?php

/**
 * @wordpress-plugin
 * Plugin Name: Create Block Theme
 * Plugin URI: https://wordpress.org/plugins/create-block-theme
 * Description: Generates a block theme
 * Version: 1.13.7
 * Author: WordPress.org
 * Author URI: https://wordpress.org/
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: create-block-theme
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Include file with download_url() if function doesn't exist.
if ( ! function_exists( 'download_url' ) ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
}

/**
 * The core plugin class.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-create-block-theme.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.2
 */
function run_create_block_theme() {

	$plugin = new Create_Block_Theme();
	$plugin->run();

}
run_create_block_theme();
