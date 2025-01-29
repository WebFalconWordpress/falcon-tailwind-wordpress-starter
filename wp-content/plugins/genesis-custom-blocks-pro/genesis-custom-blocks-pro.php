<?php
/**
 * Genesis Custom Blocks Pro
 *
 * @package   Genesis\CustomBlocksPro
 * @copyright Copyright(c) 2022, Genesis Custom Blocks Pro
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 *
 * Plugin Name: Genesis Custom Blocks Pro
 * Plugin URI: https://www.studiopress.com/genesis-pro/
 * Description: The easy way to build custom blocks for Gutenberg.
 * Version: 1.7.0
 * Author: Genesis Custom Blocks Pro
 * Author URI: https://studiopress.com
 * License: GPL2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: genesis-custom-blocks-pro
 * Domain Path: languages
 */

use Genesis\CustomBlocksPro\Plugin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Deactivates the GCB free plugin if it's active.
 *
 * This Pro plugin includes that plugin as a Composer dependency.
 * So it can't also be present as an installed plugin.
 */
function genesis_custom_blocks_pro_activation() {
	$free_plugin = 'genesis-custom-blocks/genesis-custom-blocks.php';
	if ( is_plugin_active( $free_plugin ) ) {
		deactivate_plugins( $free_plugin );
	}
}

register_activation_hook(
	__FILE__,
	'genesis_custom_blocks_pro_activation'
);

// This function usually won't exist.
// But in case GCB free is also active, this conditional allows the activation hook above to
// deactivate the free plugin without a PHP error.
if ( ! function_exists( 'genesis_custom_blocks' ) ) {
	require_once __DIR__ . '/vendor/studiopress/genesis-custom-blocks/genesis-custom-blocks.php';
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Get the plugin object.
 *
 * @return Plugin
 */
function genesis_custom_blocks_pro() {
	static $instance;

	if ( null === $instance ) {
		$instance = new Plugin();
	}

	return $instance;
}

/**
 * Sets up the plugin instance.
 */
genesis_custom_blocks_pro()
	->set_basename( plugin_basename( __FILE__ ) )
	->set_directory( plugin_dir_path( __FILE__ ) )
	->set_file( __FILE__ )
	->set_slug( 'genesis-custom-blocks-pro' )
	->set_url( plugin_dir_url( __FILE__ ) )
	->set_version( __FILE__ )
	->init();

// Sometimes we need to do things after the plugin is loaded.
add_action( 'plugins_loaded', [ genesis_custom_blocks_pro(), 'plugin_loaded' ] );
