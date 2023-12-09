<?php
/**
 * Genesis Custom Blocks Settings.
 *
 * @package   Genesis\CustomBlocksPro
 * @copyright Copyright(c) 2022, Genesis Custom Blocks Pro
 * @license   http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace Genesis\CustomBlocksPro\Admin;

use Genesis\CustomBlocks\ComponentAbstract;
use Genesis\CustomBlocks\Admin\Settings as GcbFreeSettings;

/**
 * Class Settings
 */
class Settings extends ComponentAbstract {

	/**
	 * Option name for the notices.
	 *
	 * @var string
	 */
	const NOTICES_OPTION_NAME = 'genesis_custom_blocks_notices';

	/**
	 * Page slug.
	 *
	 * @var string
	 */
	public $slug = 'genesis-custom-blocks-settings';

	/**
	 * Register any hooks that this component needs.
	 */
	public function register_hooks() {
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'genesis_custom_blocks_render_settings_page', [ $this, 'render_subscription_key' ] );
		add_action( 'admin_notices', [ $this, 'show_notices' ] );
	}

	/**
	 * Register Genesis Custom Blocks settings.
	 */
	public function register_settings() {
		register_setting( GcbFreeSettings::SETTINGS_GROUP, Subscription::SUBSCRIPTION_KEY_OPTION_NAME );
	}

	/**
	 * Render the Settings page subscription key markup.
	 */
	public function render_subscription_key() {
		include genesis_custom_blocks_pro()->get_path() . 'php/Views/Subscription.php';
	}

	/**
	 * Prepare notices to be displayed after saving the settings.
	 *
	 * @param string $notice The notice text to display.
	 */
	public function prepare_notice( $notice ) {
		$notices   = get_option( self::NOTICES_OPTION_NAME, [] );
		$notices[] = $notice;
		update_option( self::NOTICES_OPTION_NAME, $notices );
	}

	/**
	 * Show any admin notices after saving the settings.
	 */
	public function show_notices() {
		if (
			! is_admin() ||
			filter_input( INPUT_GET, 'page' ) !== $this->slug
		) {
			return;
		}

		$notices = get_option( self::NOTICES_OPTION_NAME, [] );

		if ( empty( $notices ) || ! is_array( $notices ) ) {
			if ( ! genesis_custom_blocks_pro()->admin->subscription->get_subscription()->is_valid() ) {
				// Only show this notice if there's no other notice.
				// That way, they won't get multiple notices about the subscription.
				include genesis_custom_blocks_pro()->get_path() . 'php/Views/SubscriptionNotice.php';
			}

			return;
		}

		foreach ( $notices as $notice ) {
			echo wp_kses_post( $notice );
		}

		delete_option( self::NOTICES_OPTION_NAME );
	}
}
