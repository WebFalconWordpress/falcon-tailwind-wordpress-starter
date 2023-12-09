<?php
/**
 * WP Admin resources.
 *
 * @package   Genesis\CustomBlocksPro
 * @copyright Copyright(c) 2022, Genesis Custom Blocks Pro
 * @license   http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace Genesis\CustomBlocksPro\Admin;

use Genesis\CustomBlocks\ComponentAbstract;

/**
 * Class Admin
 */
class Admin extends ComponentAbstract {

	/**
	 * Plugin settings.
	 *
	 * @var Settings
	 */
	public $settings;

	/**
	 * Genesis Pro subscription.
	 *
	 * @var Subscription
	 */
	public $subscription;

	/**
	 * Initialise the Admin component.
	 */
	public function init() {
		$this->settings = new Settings();
		genesis_custom_blocks_pro()->register_component( $this->settings );

		$this->subscription = new Subscription();
		genesis_custom_blocks_pro()->register_component( $this->subscription );
	}

	/**
	 * Register any hooks that this component needs.
	 */
	public function register_hooks() {
		add_filter( 'genesis_custom_blocks_show_pro_nag', '__return_false' );
	}
}
