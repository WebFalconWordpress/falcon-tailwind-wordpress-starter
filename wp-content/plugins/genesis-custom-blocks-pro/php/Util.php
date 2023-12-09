<?php
/**
 * Helper functions for the Genesis Custom Blocks plugin.
 *
 * These are publicly accessible via a magic method, like genesis_custom_blocks()->get_template_locations().
 * So these methods should generally be 'getter' functions, and should not affect the global state.
 *
 * @package Genesis\CustomBlocksPro
 */

namespace Genesis\CustomBlocksPro;

use Genesis\CustomBlocks\ComponentAbstract;
use Genesis\CustomBlocksPro\Blocks\Loop;

/**
 * Class Util
 */
class Util extends ComponentAbstract {

	/**
	 * Not implemented, as this class only has utility methods.
	 */
	public function register_hooks() {}

	/**
	 * Get the loop handler.
	 *
	 * @return Loop
	 */
	public function loop() {
		static $instance;

		if ( null === $instance ) {
			$instance = new Loop();
			return $instance;
		}

		return $instance;
	}
}
