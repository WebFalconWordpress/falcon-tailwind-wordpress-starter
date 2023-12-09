<?php
/**
 * Block Post Type.
 *
 * @package   Genesis\CustomBlocksPro
 * @copyright Copyright(c) 2022, Genesis Custom Blocks Pro
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace Genesis\CustomBlocksPro\PostTypes;

use Genesis\CustomBlocks\ComponentAbstract;
use Genesis\CustomBlocks\Blocks\Block;
use Genesis\CustomBlocks\Blocks\Controls;

/**
 * Class Block
 */
class BlockPost extends ComponentAbstract {

	/**
	 * Slug used for the custom post type.
	 *
	 * @var string
	 */
	private $slug;

	/**
	 * Registered controls.
	 *
	 * @var Controls\ControlAbstract[]
	 */
	public $controls = [];

	/**
	 * Block Post constructor.
	 */
	public function __construct() {
		$this->slug = genesis_custom_blocks()->get_post_type_slug();
	}

	/**
	 * Register any hooks that this component needs.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_filter( 'genesis_custom_blocks_controls', [ $this, 'add_pro_controls' ] );
		add_action( 'admin_init', [ $this, 'row_export' ] );
		add_action( 'post_submitbox_misc_actions', [ $this, 'post_type_condition' ] );
		add_filter( 'page_row_actions', [ $this, 'page_row_actions' ] );
		add_filter( 'bulk_actions-edit-' . $this->slug, [ $this, 'bulk_actions' ] );
		add_filter( 'handle_bulk_actions-edit-' . $this->slug, [ $this, 'bulk_export' ], 10, 3 );
	}

	/**
	 * Adds the Pro controls.
	 *
	 * @param array $controls An associative array of controls.
	 * @return array The controls, now with Pro controls.
	 */
	public function add_pro_controls( $controls ) {
		$pro_controls = [
			'repeater',
			'post',
			'rich_text',
			'classic_text',
			'taxonomy',
			'user',
		];

		foreach ( $pro_controls as $control_name ) {
			$control                    = $this->get_control( $control_name );
			$controls[ $control->name ] = $control;
		}

		return $controls;
	}

	/**
	 * Gets an instantiated control.
	 *
	 * @param string $control_name The name of the control.
	 * @return object The instantiated control.
	 */
	public function get_control( $control_name ) {
		$separator     = '_';
		$class_name    = str_replace( $separator, '', ucwords( $control_name, $separator ) );
		$control_class = 'Genesis\\CustomBlocksPro\\Blocks\\Controls\\' . $class_name;
		return new $control_class();
	}

	/**
	 * Displays an option for editing the post type that this block appears on.
	 */
	public function post_type_condition() {
		$screen = get_current_screen();

		if ( ! is_object( $screen ) || $this->slug !== $screen->post_type ) {
			return;
		}

		$post_types = get_post_types(
			[
				'show_in_rest' => true,
				'show_in_menu' => true,
			],
			'objects'
		);

		$post_types = array_filter(
			$post_types,
			function( $post_type ) {
				return post_type_supports( $post_type->name, 'editor' );
			}
		);

		$block = new Block( get_the_ID() );
		?>
		<div class="genesis-custom-blocks-pub-section hide-if-no-js">
			<?php esc_html_e( 'Post Types:', 'genesis-custom-blocks-pro' ); ?> <span class="post-types-display"></span>
			<a href="#post-types-select" class="edit-post-types" role="button">
				<span aria-hidden="true"><?php esc_html_e( 'Edit', 'genesis-custom-blocks-pro' ); ?></span>
			</a>
			<input type="hidden" value="<?php echo esc_attr( implode( ',', $block->excluded ) ); ?>" name="block-excluded-post-types" id="block-excluded-post-types" />
			<div class="post-types-select">
				<div class="post-types-select-items">
					<?php
					foreach ( $post_types as $post_type ) {
						?>
						<input type="checkbox" id="block-post-type-<?php echo esc_attr( $post_type->name ); ?>" value="<?php echo esc_attr( $post_type->name ); ?>">
						<label for="block-post-type-<?php echo esc_attr( $post_type->name ); ?>"><?php echo esc_html( $post_type->label ); ?></label>
						<br />
						<?php
					}
					?>
				</div>
				<a href="#post-types" class="save-post-types button"><?php esc_html_e( 'OK', 'genesis-custom-blocks-pro' ); ?></a>
				<a href="#post-types" class="button-cancel"><?php esc_html_e( 'Cancel', 'genesis-custom-blocks-pro' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Hide the Quick Edit row action.
	 *
	 * @param array $actions An array of row action links.
	 *
	 * @return array
	 */
	public function page_row_actions( $actions = [] ) {
		$post = get_post();

		// Abort if the post type is incorrect.
		if ( genesis_custom_blocks()->get_post_type_slug() !== $post->post_type ) {
			return $actions;
		}

		// Remove the Quick Edit link.
		if ( isset( $actions['inline hide-if-no-js'] ) ) {
			unset( $actions['inline hide-if-no-js'] );
		}

		// Add the Export link.
		$export = [
			'export' => sprintf(
				'<a href="%1$s" aria-label="%2$s">%3$s</a>',
				add_query_arg( [ 'export' => $post->ID ] ),
				sprintf(
					// translators: Placeholder is a post title.
					__( 'Export %1$s', 'genesis-custom-blocks-pro' ),
					get_the_title( $post->ID )
				),
				__( 'Export', 'genesis-custom-blocks-pro' )
			),
		];

		$actions = array_merge(
			array_slice( $actions, 0, 1 ),
			$export,
			array_slice( $actions, 1 )
		);

		// Return the set of links without Quick Edit.
		return $actions;
	}

	/**
	 * Remove Edit from the Bulk Actions menu
	 *
	 * @param array $actions An array of bulk actions.
	 *
	 * @return array
	 */
	public function bulk_actions( $actions ) {
		unset( $actions['edit'] );
		$actions['export'] = __( 'Export', 'genesis-custom-blocks-pro' );

		return $actions;
	}

	/**
	 * Handle the Export of a single block.
	 */
	public function row_export() {
		$post_id = filter_input( INPUT_GET, 'export', FILTER_SANITIZE_NUMBER_INT );

		// Check if the export has been requested, and the user has permission.
		if ( $post_id <= 0 || ! current_user_can( "{$this->slug}_read_block", $post_id ) ) {
			return;
		}

		$this->export( [ $post_id ] );
	}

	/**
	 * Handle Exporting blocks via Bulk Actions
	 *
	 * @param string $redirect Location to redirect to after the bulk action is completed.
	 * @param string $action The action to handle.
	 * @param array  $post_ids The IDs to handle.
	 *
	 * @return string
	 */
	public function bulk_export( $redirect, $action, $post_ids ) {
		if ( 'export' !== $action ) {
			return $redirect;
		}

		$this->export( $post_ids );

		$redirect = add_query_arg( 'bulk_export', count( $post_ids ), $redirect );
		return $redirect;
	}

	/**
	 * Export Blocks
	 *
	 * @param int[] $post_ids The post IDs to export.
	 */
	private function export( $post_ids ) {
		$blocks = [];

		foreach ( $post_ids as $post_id ) {
			$post = get_post( $post_id );

			if ( ! $post ) {
				break;
			}

			// Check that the post content is valid JSON.
			$block = json_decode( $post->post_content, true );

			if ( JSON_ERROR_NONE !== json_last_error() ) {
				break;
			}

			$blocks = array_merge( $blocks, $block );
		}

		// If only one block is being exported, use the block's slug as the filename.
		$filename = 'blocks.json';
		if ( 1 === count( $post_ids ) ) {
			$post     = get_post( $post_ids[0] );
			$filename = $post->post_name . '.json';
		}

		// Output the JSON file.
		header( 'Content-disposition: attachment; filename=' . $filename );
		header( 'Content-type:application/json;charset=utf-8' );
		echo wp_json_encode( $blocks );
		die();
	}
}
