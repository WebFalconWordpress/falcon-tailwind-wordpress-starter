<?php
/**
 * Rich Text control.
 *
 * @package   Genesis\CustomBlocksPro
 * @copyright Copyright(c) 2022, Genesis Custom Blocks Pro
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace Genesis\CustomBlocksPro\Blocks\Controls;

use Genesis\CustomBlocks\Blocks\Controls\ControlAbstract;
use Genesis\CustomBlocks\Blocks\Controls\ControlSetting;

/**
 * Class RichText
 */
class RichText extends ControlAbstract {

	/**
	 * Control name.
	 *
	 * @var string
	 */
	public $name = 'rich_text';

	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->label = __( 'Rich Text', 'genesis-custom-blocks-pro' );
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		foreach ( [ 'help', 'default', 'placeholder' ] as $setting ) {
			$this->settings[] = new ControlSetting( $this->settings_config[ $setting ] );
		}

		$this->locations = [ 'editor' => __( 'Editor', 'genesis-custom-blocks-pro' ) ];
	}

	/**
	 * Validates the value to be made available to the front-end template.
	 *
	 * @param mixed $value The value to either make available as a variable or echoed on the front-end template.
	 * @param bool  $is_echo Whether this will be echoed.
	 * @return mixed $value The value to be made available or echoed on the front-end template.
	 */
	public function validate( $value, $is_echo ) {
		unset( $is_echo );

		// If there's no text entered, Rich Text saves '<p></p>', so instead return ''.
		if ( '<p></p>' === $value ) {
			return '';
		}

		return wpautop( $value );
	}
}
