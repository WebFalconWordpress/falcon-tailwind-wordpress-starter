<?php
/**
 * Post control.
 *
 * @package   Genesis\CustomBlocksPro
 * @copyright Copyright(c) 2022, Genesis Custom Blocks Pro
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace Genesis\CustomBlocksPro\Blocks\Controls;

use Genesis\CustomBlocks\Blocks\Controls\ControlAbstract;
use Genesis\CustomBlocks\Blocks\Controls\ControlSetting;

/**
 * Class Post
 */
class Post extends ControlAbstract {

	/**
	 * Control name.
	 *
	 * @var string
	 */
	public $name = 'post';

	/**
	 * Field variable type.
	 *
	 * @var string
	 */
	public $type = 'object';

	/**
	 * Post constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->label = __( 'Post', 'genesis-custom-blocks-pro' );
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
				'label'   => __( 'Post Type', 'genesis-custom-blocks-pro' ),
				'type'    => 'post_type_rest_slug',
				'default' => 'posts',
			]
		);
	}

	/**
	 * Validates the value to be made available to the front-end template.
	 *
	 * @param mixed $value The value to either make available as a variable or echoed on the front-end template.
	 * @param bool  $is_echo  Whether this will be echoed.
	 * @return string|WP_Post|null $value The value to be made available or echoed on the front-end template.
	 */
	public function validate( $value, $is_echo ) {
		$post = isset( $value['id'] ) ? get_post( $value['id'] ) : null;
		if ( $is_echo ) {
			return $post ? get_the_title( $post ) : '';
		} else {
			return $post;
		}
	}
}
