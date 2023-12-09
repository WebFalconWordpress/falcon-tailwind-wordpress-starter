<?php
/**
 * Taxonomy control.
 *
 * @package   Genesis\CustomBlocksPro
 * @copyright Copyright(c) 2022, Genesis Custom Blocks Pro
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace Genesis\CustomBlocksPro\Blocks\Controls;

use Genesis\CustomBlocks\Blocks\Controls\ControlAbstract;
use Genesis\CustomBlocks\Blocks\Controls\ControlSetting;

/**
 * Class Taxonomy
 */
class Taxonomy extends ControlAbstract {

	/**
	 * Control name.
	 *
	 * @var string
	 */
	public $name = 'taxonomy';

	/**
	 * Field variable type.
	 *
	 * @var string
	 */
	public $type = 'object';

	/**
	 * Taxonomy constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->label = __( 'Taxonomy', 'genesis-custom-blocks-pro' );
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		$this->settings[] = new ControlSetting( $this->settings_config['location'] );
		$this->settings[] = new ControlSetting( $this->settings_config['width'] );
		$this->settings[] = new ControlSetting( $this->settings_config['help'] );
		$this->settings[] = new ControlSetting(
			[
				'name'    => 'post_type_rest_slug',
				'label'   => __( 'Taxonomy Type', 'genesis-custom-blocks-pro' ),
				'type'    => 'taxonomy_type_rest_slug',
				'default' => 'categories',
			]
		);
	}

	/**
	 * Validates the value to be made available to the front-end template.
	 *
	 * @param mixed $value The value to either make available as a variable or echoed on the front-end template.
	 * @param bool  $is_echo  Whether this will be echoed.
	 * @return string|WP_Term|null $value The value to be made available or echoed on the front-end template.
	 */
	public function validate( $value, $is_echo ) {
		$term = isset( $value['id'] ) ? get_term( $value['id'] ) : null;

		if ( $is_echo ) {
			return $term ? $term->name : '';
		} else {
			return $term;
		}
	}
}
