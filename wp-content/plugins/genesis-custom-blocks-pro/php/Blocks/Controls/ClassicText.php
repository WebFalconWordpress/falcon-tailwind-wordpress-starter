<?php
/**
 * Classic Text control.
 *
 * @package   Genesis\CustomBlocksPro
 * @copyright Copyright(c) 2022, Genesis Custom Blocks Pro
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace Genesis\CustomBlocksPro\Blocks\Controls;

use Genesis\CustomBlocks\Blocks\Controls\ControlAbstract;
use Genesis\CustomBlocks\Blocks\Controls\ControlSetting;

/**
 * Class ClassicText
 */
class ClassicText extends ControlAbstract {

	/**
	 * Control name.
	 *
	 * @var string
	 */
	public $name = 'classic_text';

	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->label = __( 'Classic Text', 'genesis-custom-blocks-pro' );
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		foreach ( [ 'help', 'default' ] as $setting ) {
			$this->settings[] = new ControlSetting( $this->settings_config[ $setting ] );
		}

		$this->locations = [ 'editor' => __( 'Editor', 'genesis-custom-blocks-pro' ) ];
	}
}
