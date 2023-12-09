<?php
/**
 * Repeater control.
 *
 * @package   Genesis\CustomBlocksPro
 * @copyright Copyright(c) 2022, Genesis Custom Blocks Pro
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace Genesis\CustomBlocksPro\Blocks\Controls;

use stdClass;
use Genesis\CustomBlocks\Blocks\Controls\ControlAbstract;
use Genesis\CustomBlocks\Blocks\Controls\ControlSetting;

/**
 * Class Repeater
 */
class Repeater extends ControlAbstract {

	/**
	 * Control name.
	 *
	 * @var string
	 */
	public $name = 'repeater';

	/**
	 * Field variable type.
	 *
	 * The Repeater control is an array of objects, with each row being an object.
	 * For example, a repeater with one row might be [ { 'example-text': 'Foo', 'example-image': 4232 } ].
	 *
	 * @var string
	 */
	public $type = 'object';

	/**
	 * Repeater constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->label = __( 'Repeater', 'genesis-custom-blocks-pro' );
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		$this->settings[] = new ControlSetting( $this->settings_config['help'] );
		$this->settings[] = new ControlSetting(
			[
				'name'  => 'min',
				'label' => __( 'Minimum Rows', 'genesis-custom-blocks-pro' ),
				'type'  => 'number_non_negative',
			]
		);
		$this->settings[] = new ControlSetting(
			[
				'name'  => 'max',
				'label' => __( 'Maximum Rows', 'genesis-custom-blocks-pro' ),
				'type'  => 'number_non_negative',
			]
		);
		$this->settings[] = new ControlSetting(
			[
				'name'    => 'sub_fields',
				'type'    => 'sub_fields', // Doesn't appear in the <FieldSettings> React component.
				'default' => new stdClass(), // So this appears as {} in JavaScript.
			]
		);

		$this->locations = [ 'editor' => __( 'Editor', 'genesis-custom-blocks-pro' ) ];
	}

	/**
	 * Remove empty placeholder rows.
	 *
	 * @param mixed $value The value to either make available as a variable or echoed on the front-end template.
	 * @param bool  $is_echo Whether this will be echoed.
	 * @return mixed $value The value to be made available or echoed on the front-end template.
	 */
	public function validate( $value, $is_echo ) {
		if ( isset( $value['rows'] ) ) {
			foreach ( $value['rows'] as $key => $row ) {
				unset( $value['rows'][ $key ][''] );
				unset( $value['rows'][ $key ][0] );
			}
		}

		if ( $is_echo && defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			return __( "⚠️ Please use Genesis Custom Blocks's repeater functions to display repeater fields in your template.", 'genesis-custom-blocks-pro' );
		}

		return $value;
	}
}
