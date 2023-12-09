<?php
/**
 * Primary plugin file.
 *
 * @package   Genesis\CustomBlocksPro
 * @copyright Copyright(c) 2022, Genesis Custom Blocks Pro
 * @license   http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace Genesis\CustomBlocksPro;

use Genesis\CustomBlocks\Admin\Onboarding;
use Genesis\CustomBlocks\PluginAbstract;

use Genesis\CustomBlocksPro\Admin\Admin;
use Genesis\CustomBlocksPro\Blocks\Asset;
use Genesis\CustomBlocksPro\Blocks\RepeaterFilter;
use Genesis\CustomBlocksPro\PostTypes\BlockPost;

/**
 * Class Plugin
 */
class Plugin extends PluginAbstract {

	/**
	 * Utility methods.
	 *
	 * @var Util
	 */
	protected $util;

	/**
	 * Plugin assets.
	 *
	 * @var Asset
	 */
	public $asset;

	/**
	 * WP Admin resources.
	 *
	 * @var Admin
	 */
	public $admin;

	/**
	 * Execute this as early as possible.
	 */
	public function init() {
		$this->util  = new Util();
		$this->asset = new Asset();
		$this->register_component( $this->asset );

		$this->register_component( new BlockPost() );
		$this->register_component( new RepeaterFilter() );

		register_activation_hook(
			$this->get_file(),
			function() {
				$onboarding = new Onboarding();
				$onboarding->plugin_activation();
			}
		);
	}

	/**
	 * Execute this once plugins are loaded. (not the best place for all hooks)
	 */
	public function plugin_loaded() {
		$this->admin = new Admin();
		$this->register_component( $this->admin );
		$this->require_helpers();
	}

	/**
	 * Requires helper functions.
	 */
	private function require_helpers() {
		if ( ! function_exists( 'block_row' ) ) {
			require_once __DIR__ . '/Helpers.php';
		}
	}
}
