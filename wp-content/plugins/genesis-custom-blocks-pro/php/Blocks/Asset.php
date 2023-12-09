<?php
/**
 * Handles plugin assets.
 *
 * @package Genesis\CustomBlocksPro
 */

namespace Genesis\CustomBlocksPro\Blocks;

use Genesis\CustomBlocks\ComponentAbstract;

/**
 * Class Asset
 */
class Asset extends ComponentAbstract {

	/**
	 * The slug for the block editor script.
	 *
	 * @var string
	 */
	const BLOCK_EDITOR_SCRIPT_SLUG = 'genesis-custom-blocks-block-editor-script';

	/**
	 * The slug for the block editor stylesheet.
	 *
	 * @var string
	 */
	const BLOCK_EDITOR_STYLE_SLUG = 'genesis-custom-blocks-pro-block-editor-css';

	/**
	 * The slug for the 'Edit Block' UI script.
	 *
	 * @var string
	 */
	const EDIT_BLOCK_SCRIPT_SLUG = 'genesis-custom-blocks-pro-edit-block-script';

	/**
	 * Asset paths and urls for blocks.
	 *
	 * @var array
	 */
	protected $assets = [];

	/**
	 * An associative array of block config data for the blocks that will be registered.
	 *
	 * The key of each item in the array is the block name.
	 *
	 * @var array
	 */
	protected $blocks = [];

	/**
	 * Registers all the hooks.
	 */
	public function register_hooks() {
		add_action( 'enqueue_block_editor_assets', [ $this, 'editor_assets' ] );
		add_action( 'admin_footer', [ $this, 'enqueue_edit_block_script' ] );
	}

	/**
	 * Launches the blocks inside Gutenberg.
	 */
	public function editor_assets() {
		$js_config = require $this->plugin->get_path( 'js/dist/block-editor-pro.asset.php' );
		wp_enqueue_script(
			self::BLOCK_EDITOR_SCRIPT_SLUG,
			$this->plugin->get_url( 'js/dist/block-editor-pro.js' ),
			array_merge(
				$js_config['dependencies'],
				[ 'wp-tinymce' ]
			),
			$js_config['version'],
			true
		);

		// So the Classic Text field works.
		wp_enqueue_script( 'wp-block-library' );
		wp_tinymce_inline_scripts();

		// Enqueue editor only styles.
		$css_file = 'css/dist/blocks-editor-pro.css';
		wp_enqueue_style(
			self::BLOCK_EDITOR_STYLE_SLUG,
			$this->plugin->get_url( $css_file ),
			[],
			filemtime( $this->plugin->get_path( $css_file ) )
		);
	}

	/**
	 * Enqueues the 'Edit Block' UI script.
	 */
	public function enqueue_edit_block_script() {
		$screen = get_current_screen();

		if (
			! is_object( $screen ) ||
			genesis_custom_blocks()->get_post_type_slug() !== $screen->post_type ||
			'post' !== $screen->base
		) {
			return;
		}

		$this->editor_assets();

		$js_config = require $this->plugin->get_path( 'js/dist/edit-block-pro.asset.php' );
		wp_enqueue_script(
			self::EDIT_BLOCK_SCRIPT_SLUG,
			$this->plugin->get_url( 'js/dist/edit-block-pro.js' ),
			$js_config['dependencies'],
			$js_config['version'],
			true
		);
	}
}
